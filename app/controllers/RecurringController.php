<?php

class RecurringController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $contracts = RecurringContract::list();
        $currentMonth = date('Y-m');

        $pdo = Database::getConnection();
        $totals = [
            'total' => (int)$pdo->query('SELECT COUNT(*) FROM recurring_contracts')->fetchColumn(),
            'active' => (int)$pdo->query('SELECT COUNT(*) FROM recurring_contracts WHERE status = "ACTIVE"')->fetchColumn(),
            'monthly_revenue' => (float)$pdo->query('SELECT COALESCE(SUM(monthly_value),0) FROM recurring_contracts WHERE status = "ACTIVE"')->fetchColumn(),
            'entry_revenue' => (float)$pdo->query('SELECT COALESCE(SUM(entry_value),0) FROM recurring_contracts')->fetchColumn(),
        ];

        foreach ($contracts as &$contract) {
            $contract['current_status'] = RecurringContract::getMonthlyStatus((int)$contract['id'], $currentMonth);
            $services = Lead::services((int)$contract['lead_id']);
            $contract['services'] = $services ? implode(', ', $services) : '-';
        }

        $this->render('recurring/index', [
            'contracts' => $contracts,
            'totals' => $totals,
            'current_month' => $currentMonth,
        ]);
    }

    public function show(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $contract = RecurringContract::find((int)$id);
        if (!$contract) {
            redirect('/recurring');
        }
        $currentMonth = date('Y-m');
        $contract['current_status'] = RecurringContract::getMonthlyStatus((int)$id, $currentMonth);
        $this->render('recurring/show', [
            'contract' => $contract,
            'current_month' => $currentMonth,
        ]);
    }

    public function markPaid(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $contract = RecurringContract::find((int)$id);
        if (!$contract) {
            redirect('/recurring');
        }

        $month = $_POST['reference_month'] ?? date('Y-m');
        $currentStatus = RecurringContract::getMonthlyStatus((int)$id, $month);
        RecurringContract::markPayment((int)$id, $month, 'PAID', date('Y-m-d'));

        if ($currentStatus !== 'PAID') {
            FinancialEvent::create([
                'event_date' => date('Y-m-d'),
                'direction' => 'IN',
                'type' => 'RECURRING_PAYMENT',
                'amount' => $contract['monthly_value'],
                'description' => 'Recorrencia: ' . $contract['name'],
                'details' => json_encode(['reference_month' => $month]),
                'related_entity_type' => 'recurring_contract',
                'related_entity_id' => (int)$id,
                'created_by' => Auth::user()['id'],
            ]);
        }

        $_SESSION['flash_success'] = 'Pagamento registrado.';
        redirect('/recurring');
    }

    public function markInad(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $contract = RecurringContract::find((int)$id);
        if (!$contract) {
            redirect('/recurring');
        }

        $month = $_POST['reference_month'] ?? date('Y-m');
        RecurringContract::markPayment((int)$id, $month, 'INADIMPLENT', null);
        $_SESSION['flash_success'] = 'Contrato marcado como inadimplente.';
        redirect('/recurring');
    }

    public function toggleStatus(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $status = $_POST['status'] ?? 'ACTIVE';
        RecurringContract::updateStatus((int)$id, $status);
        $_SESSION['flash_success'] = 'Status atualizado.';
        redirect('/recurring');
    }
}
