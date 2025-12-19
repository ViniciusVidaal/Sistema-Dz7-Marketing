<?php

class InvestmentsController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $investments = Investment::all();
        $this->render('investments/index', [
            'investments' => $investments,
        ]);
    }

    public function create(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->render('investments/create');
    }

    public function store(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $data = $this->sanitize();
        $data['created_by'] = Auth::user()['id'];
        Investment::create($data);

        $_SESSION['flash_success'] = 'Investimento cadastrado.';
        redirect('/investments');
    }

    public function edit(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $investment = Investment::find((int)$id);
        if (!$investment) {
            redirect('/investments');
        }
        $this->render('investments/edit', [
            'investment' => $investment,
        ]);
    }

    public function update(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $investment = Investment::find((int)$id);
        if (!$investment) {
            redirect('/investments');
        }

        $data = $this->sanitize();

        if (is_admin()) {
            Investment::update((int)$id, $data);
            Approval::expirePending('investment', (int)$id);
            $_SESSION['flash_success'] = 'Investimento atualizado.';
            redirect('/investments');
        }

        Approval::createOrUpdatePending([
            'entity_type' => 'investment',
            'entity_id' => (int)$id,
            'requested_by' => Auth::user()['id'],
            'summary' => 'Solicitacao de alteracao em investimento: ' . $investment['name'],
            'before_json' => json_encode($investment),
            'after_json' => json_encode($data),
        ]);

        Notification::create([
            'type' => 'APPROVAL_REQUEST',
            'title' => 'Nova solicitacao de alteracao',
            'body' => 'Investimento ' . $investment['name'] . ' aguardando aprovacao.',
            'reference_type' => 'investment',
            'reference_id' => (int)$id,
            'notify_date' => date('Y-m-d'),
            'unique_key' => 'approval_investment_' . $id . '_' . date('Ymd'),
        ]);

        $_SESSION['flash_success'] = 'Solicitacao enviada para aprovacao.';
        redirect('/investments');
    }

    private function sanitize(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'value' => safe_float($_POST['value'] ?? 0),
            'priority' => $_POST['priority'] ?? 'MEDIA',
            'due_date' => $_POST['due_date'] ?? date('Y-m-d'),
            'status' => $_POST['status'] ?? 'PENDENTE',
        ];
    }
}
