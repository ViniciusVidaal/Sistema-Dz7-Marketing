<?php

class ToolsController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $tools = Tool::all();
        $this->render('tools/index', [
            'tools' => $tools,
        ]);
    }

    public function create(): void
    {
        Middleware::requireAdmin();
        $this->render('tools/create');
    }

    public function store(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $startDate = $_POST['start_date'] ?? date('Y-m-d');
        $nextDueDate = $_POST['next_due_date'] ?? '';
        if ($nextDueDate === '') {
            $nextDueDate = date('Y-m-d', strtotime($startDate . ' +30 days'));
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'value' => safe_float($_POST['value'] ?? 0),
            'start_date' => $startDate,
            'next_due_date' => $nextDueDate,
            'card_last4' => trim($_POST['card_last4'] ?? ''),
        ];
        Tool::create($data);
        $_SESSION['flash_success'] = 'Ferramenta cadastrada.';
        redirect('/tools');
    }

    public function edit(string $id): void
    {
        Middleware::requireAdmin();
        $tool = Tool::find((int)$id);
        if (!$tool) {
            redirect('/tools');
        }
        $this->render('tools/edit', [
            'tool' => $tool,
        ]);
    }

    public function update(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        $startDate = $_POST['start_date'] ?? date('Y-m-d');
        $nextDueDate = $_POST['next_due_date'] ?? '';
        if ($nextDueDate === '') {
            $nextDueDate = date('Y-m-d', strtotime($startDate . ' +30 days'));
        }
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'value' => safe_float($_POST['value'] ?? 0),
            'start_date' => $startDate,
            'next_due_date' => $nextDueDate,
            'card_last4' => trim($_POST['card_last4'] ?? ''),
        ];
        Tool::update((int)$id, $data);
        $_SESSION['flash_success'] = 'Ferramenta atualizada.';
        redirect('/tools');
    }

    public function delete(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        Tool::delete((int)$id);
        $_SESSION['flash_success'] = 'Ferramenta removida.';
        redirect('/tools');
    }
}
