<?php

class LeadsController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $filters = [
            'search' => $_GET['search'] ?? null,
            'origin' => $_GET['origin'] ?? null,
            'payment_type' => $_GET['payment_type'] ?? null,
            'service' => $_GET['service'] ?? null,
        ];
        $page = max(1, safe_int($_GET['page'] ?? 1, 1));
        $perPage = 10;
        $filters['limit'] = $perPage;
        $filters['offset'] = ($page - 1) * $perPage;
        $total = Lead::count($filters);
        $leads = Lead::list($filters);
        $this->render('leads/index', [
            'leads' => $leads,
            'filters' => $filters,
            'page' => $page,
            'total_pages' => max(1, (int)ceil($total / $perPage)),
        ]);
    }

    public function create(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $users = User::all();
        $this->render('leads/create', [
            'users' => $users,
        ]);
    }

    public function store(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $data = $this->sanitizeLeadData();
        $services = $_POST['services'] ?? [];

        if ($data['entry_value'] <= 0) {
            $_SESSION['flash_error'] = 'Valor de entrada obrigatorio.';
            redirect('/leads/create');
        }

        $leadId = Lead::create($data, $services);

        FinancialEvent::create([
            'event_date' => date('Y-m-d'),
            'direction' => 'IN',
            'type' => 'CONTRACT_ENTRY',
            'amount' => $data['entry_value'],
            'description' => 'Entrada de contrato: ' . $data['name'],
            'details' => null,
            'related_entity_type' => 'lead',
            'related_entity_id' => $leadId,
            'created_by' => Auth::user()['id'],
        ]);

        if ($data['payment_type'] === 'RECURRING') {
            RecurringContract::createFromLead($leadId, $data);
        }

        $_SESSION['flash_success'] = 'Lead cadastrado.';
        redirect('/leads');
    }

    public function edit(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $lead = Lead::find((int)$id);
        if (!$lead) {
            redirect('/leads');
        }
        $users = User::all();
        $this->render('leads/edit', [
            'lead' => $lead,
            'users' => $users,
        ]);
    }

    public function update(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $lead = Lead::find((int)$id);
        if (!$lead) {
            redirect('/leads');
        }

        $data = $this->sanitizeLeadData();
        $services = $_POST['services'] ?? [];

        if ($data['entry_value'] <= 0) {
            $_SESSION['flash_error'] = 'Valor de entrada obrigatorio.';
            redirect('/leads/' . $id . '/edit');
        }

        if (is_admin()) {
            Lead::update((int)$id, $data, $services);
            $existingContract = RecurringContract::findByLeadId((int)$id);
            if ($data['payment_type'] === 'RECURRING') {
                if ($existingContract) {
                    RecurringContract::updateFromLead((int)$id, $data);
                } else {
                    RecurringContract::createFromLead((int)$id, $data);
                }
            } else {
                if ($existingContract) {
                    RecurringContract::updateStatus((int)$existingContract['id'], 'INACTIVE');
                }
            }
            Approval::expirePending('lead', (int)$id);
            $_SESSION['flash_success'] = 'Lead atualizado.';
            redirect('/leads');
        }

        $before = $lead;
        $after = $data;
        $after['services'] = $services;

        Approval::createOrUpdatePending([
            'entity_type' => 'lead',
            'entity_id' => (int)$id,
            'requested_by' => Auth::user()['id'],
            'summary' => 'Solicitacao de alteracao em lead: ' . $lead['name'],
            'before_json' => json_encode($before),
            'after_json' => json_encode($after),
        ]);

        Notification::create([
            'type' => 'APPROVAL_REQUEST',
            'title' => 'Nova solicitacao de alteracao',
            'body' => 'Lead ' . $lead['name'] . ' aguardando aprovacao.',
            'reference_type' => 'lead',
            'reference_id' => (int)$id,
            'notify_date' => date('Y-m-d'),
            'unique_key' => 'approval_lead_' . $id . '_' . date('Ymd'),
        ]);

        $_SESSION['flash_success'] = 'Solicitacao enviada para aprovacao.';
        redirect('/leads');
    }

    private function sanitizeLeadData(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'contact_name' => trim($_POST['contact_name'] ?? ''),
            'business_gmn' => trim($_POST['business_gmn'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'service_location' => trim($_POST['service_location'] ?? ''),
            'origin' => $_POST['origin'] ?? 'OUTROS',
            'converted_by' => ($converted = safe_int($_POST['converted_by'] ?? 0)) > 0 ? $converted : null,
            'payment_type' => $_POST['payment_type'] ?? 'ONE_TIME',
            'entry_value' => safe_float($_POST['entry_value'] ?? 0),
            'monthly_value' => safe_float($_POST['monthly_value'] ?? 0),
            'months' => safe_int($_POST['months'] ?? 0),
            'pay_day' => safe_int($_POST['pay_day'] ?? 1),
            'contract_start' => $_POST['contract_start'] ?? date('Y-m-d'),
            'contract_end' => empty($_POST['contract_end']) ? null : $_POST['contract_end'],
            'created_by' => Auth::user()['id'],
        ];
    }
}
