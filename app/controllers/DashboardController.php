<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $user = Auth::user();

        $filter = $this->buildDateFilter();

        $revenueTotal = FinancialEvent::sumByDirectionAndTypes('IN', ['CONTRACT_ENTRY', 'RECURRING_PAYMENT'], $filter['start'], $filter['end']);
        $entryRevenue = FinancialEvent::sumByDirectionAndTypes('IN', ['CONTRACT_ENTRY'], $filter['start'], $filter['end']);
        $trafficSpend = FinancialEvent::sumByDirectionAndTypes('OUT', ['TRAFFIC_SPEND'], $filter['start'], $filter['end']);
        $miscSpend = FinancialEvent::sumByDirectionAndTypes('OUT', ['MISC_SPEND'], $filter['start'], $filter['end']);
        $balance = FinancialEvent::balance();

        $pdo = Database::getConnection();
        $activeRecurring = (int)$pdo->query('SELECT COUNT(*) FROM recurring_contracts WHERE status = "ACTIVE"')->fetchColumn();
        $recurringRevenue = (float)$pdo->query('SELECT COALESCE(SUM(monthly_value),0) FROM recurring_contracts WHERE status = "ACTIVE"')->fetchColumn();

        $settings = DashboardSetting::get();
        $goalProgress = 0;
        if ((float)$settings['main_goal_value'] > 0) {
            $goalProgress = min(100, round(($revenueTotal / $settings['main_goal_value']) * 100));
        }

        $meetingsGoal = (int)$settings['meetings_goal'];
        $meetingsDone = (int)$settings['meetings_done'];
        $dealsGoal = (int)$settings['deals_goal'];
        $dealsDone = (int)$settings['deals_done'];
        $conversionRate = $meetingsDone > 0 ? round(($dealsDone / $meetingsDone) * 100, 1) : 0;

        $monthlySeries = FinancialEvent::monthlyRevenueSeries(12);
        $metaSpend = $this->sumTrafficByPlatform('META_ADS', $filter['start'], $filter['end']);
        $googleSpend = $this->sumTrafficByPlatform('GOOGLE_ADS', $filter['start'], $filter['end']);

        $roleForNotifications = ($user['role'] === 'ADMIN' && view_as_employee()) ? 'EMPLOYEE' : $user['role'];
        $notifications = Notification::listForUser($user['id'], $roleForNotifications);
        $unreadCount = Notification::unreadCount($user['id'], $roleForNotifications);

        $data = [
            'filter' => $filter,
            'revenue_total' => $revenueTotal,
            'entry_revenue' => $entryRevenue,
            'traffic_spend' => $trafficSpend,
            'misc_spend' => $miscSpend,
            'balance' => $balance,
            'active_recurring' => $activeRecurring,
            'recurring_revenue' => $recurringRevenue,
            'settings' => $settings,
            'goal_progress' => $goalProgress,
            'meetings_goal' => $meetingsGoal,
            'meetings_done' => $meetingsDone,
            'deals_goal' => $dealsGoal,
            'deals_done' => $dealsDone,
            'conversion_rate' => $conversionRate,
            'monthly_series' => $monthlySeries,
            'meta_spend' => $metaSpend,
            'google_spend' => $googleSpend,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ];

        if ($user['role'] === 'ADMIN') {
            $this->render('dashboard/admin', $data);
            return;
        }
        $this->render('dashboard/employee', $data);
    }

    private function buildDateFilter(): array
    {
        $preset = $_GET['preset'] ?? 'month';
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;

        if ($preset === 'day') {
            $start = date('Y-m-d');
            $end = date('Y-m-d');
        } elseif ($preset === 'month') {
            $start = date('Y-m-01');
            $end = date('Y-m-t');
        } elseif ($preset === 'year') {
            $start = date('Y-01-01');
            $end = date('Y-12-31');
        } elseif ($preset === 'custom') {
            $start = $start ?: date('Y-m-01');
            $end = $end ?: date('Y-m-t');
        }

        return [
            'preset' => $preset,
            'start' => $start,
            'end' => $end,
        ];
    }

    private function sumTrafficByPlatform(string $platform, ?string $start, ?string $end): float
    {
        $pdo = Database::getConnection();
        $where = 'direction = "OUT" AND type = "TRAFFIC_SPEND" AND description = ?';
        $params = [$platform];
        if ($start) {
            $where .= ' AND event_date >= ?';
            $params[] = $start;
        }
        if ($end) {
            $where .= ' AND event_date <= ?';
            $params[] = $end;
        }
        $stmt = $pdo->prepare('SELECT COALESCE(SUM(amount),0) FROM financial_events WHERE ' . $where);
        $stmt->execute($params);
        return (float)$stmt->fetchColumn();
    }
}
