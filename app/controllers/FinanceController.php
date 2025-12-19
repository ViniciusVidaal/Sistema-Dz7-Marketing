<?php

class FinanceController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();

        $filters = [
            'start' => $_GET['start'] ?? null,
            'end' => $_GET['end'] ?? null,
            'type' => $_GET['type'] ?? null,
            'direction' => $_GET['direction'] ?? null,
        ];
        $page = max(1, safe_int($_GET['page'] ?? 1, 1));
        $perPage = 15;
        $filters['limit'] = $perPage;
        $filters['offset'] = ($page - 1) * $perPage;
        $total = FinancialEvent::count($filters);
        $events = FinancialEvent::list($filters);

        $totals = [
            'in' => FinancialEvent::sumByDirectionAndTypes('IN', ['CONTRACT_ENTRY', 'RECURRING_PAYMENT', 'CASH_ADJUSTMENT'], $filters['start'], $filters['end']),
            'out' => FinancialEvent::sumByDirectionAndTypes('OUT', ['TRAFFIC_SPEND', 'MISC_SPEND', 'CASH_ADJUSTMENT'], $filters['start'], $filters['end']),
        ];

        $this->render('finance/index', [
            'events' => $events,
            'filters' => $filters,
            'totals' => $totals,
            'page' => $page,
            'total_pages' => max(1, (int)ceil($total / $perPage)),
        ]);
    }

    public function storeAdjustment(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $amount = safe_float($_POST['amount'] ?? 0);
        $direction = $_POST['direction'] ?? 'IN';
        $date = $_POST['event_date'] ?? date('Y-m-d');
        $reason = trim($_POST['reason'] ?? 'Ajuste de caixa');

        if ($amount <= 0) {
            $_SESSION['flash_error'] = 'Valor invalido.';
            redirect('/finance');
        }

        FinancialEvent::create([
            'event_date' => $date,
            'direction' => $direction,
            'type' => 'CASH_ADJUSTMENT',
            'amount' => $amount,
            'description' => $reason,
            'details' => null,
            'related_entity_type' => 'adjustment',
            'related_entity_id' => null,
            'created_by' => Auth::user()['id'],
        ]);

        $_SESSION['flash_success'] = 'Ajuste registrado.';
        redirect('/finance');
    }

    public function storeTraffic(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $amount = safe_float($_POST['amount'] ?? 0);
        $date = $_POST['event_date'] ?? date('Y-m-d');
        $platform = $_POST['platform'] ?? 'META_ADS';
        $cardLast4 = trim($_POST['card_last4'] ?? '');

        if ($amount <= 0) {
            $_SESSION['flash_error'] = 'Valor invalido.';
            redirect('/finance');
        }

        $details = $cardLast4 !== '' ? json_encode(['card_last4' => $cardLast4]) : null;

        FinancialEvent::create([
            'event_date' => $date,
            'direction' => 'OUT',
            'type' => 'TRAFFIC_SPEND',
            'amount' => $amount,
            'description' => $platform,
            'details' => $details,
            'related_entity_type' => 'traffic_spend',
            'related_entity_id' => null,
            'created_by' => Auth::user()['id'],
        ]);

        $_SESSION['flash_success'] = 'Gasto de trafego registrado.';
        redirect('/finance');
    }

    public function storeMisc(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $amount = safe_float($_POST['amount'] ?? 0);
        $date = $_POST['event_date'] ?? date('Y-m-d');
        $category = trim($_POST['category'] ?? 'Diversos');
        $description = trim($_POST['description'] ?? '');

        if ($amount <= 0) {
            $_SESSION['flash_error'] = 'Valor invalido.';
            redirect('/finance');
        }

        $details = $description !== '' ? json_encode(['description' => $description]) : null;

        FinancialEvent::create([
            'event_date' => $date,
            'direction' => 'OUT',
            'type' => 'MISC_SPEND',
            'amount' => $amount,
            'description' => $category,
            'details' => $details,
            'related_entity_type' => 'misc_spend',
            'related_entity_id' => null,
            'created_by' => Auth::user()['id'],
        ]);

        $_SESSION['flash_success'] = 'Gasto diverso registrado.';
        redirect('/finance');
    }
}
