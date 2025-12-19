<?php

class GoalsController extends Controller
{
    public function company(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $goals = GoalCompany::all();
        $settings = DashboardSetting::get();
        $this->render('goals/company', [
            'goals' => $goals,
            'settings' => $settings,
        ]);
    }

    public function editCompany(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $goal = GoalCompany::find((int)$id);
        if (!$goal) {
            redirect('/goals/company');
        }
        $this->render('goals/company_edit', [
            'goal' => $goal,
        ]);
    }

    public function storeCompany(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        $data = $this->sanitizeCompany();
        GoalCompany::create($data);
        $_SESSION['flash_success'] = 'Meta da empresa cadastrada.';
        redirect('/goals/company');
    }

    public function updateCompany(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $goal = GoalCompany::find((int)$id);
        if (!$goal) {
            redirect('/goals/company');
        }
        $data = $this->sanitizeCompany();

        if (is_admin()) {
            GoalCompany::update((int)$id, $data);
            Approval::expirePending('goal_company', (int)$id);
            $_SESSION['flash_success'] = 'Meta atualizada.';
            redirect('/goals/company');
        }

        Approval::createOrUpdatePending([
            'entity_type' => 'goal_company',
            'entity_id' => (int)$id,
            'requested_by' => Auth::user()['id'],
            'summary' => 'Solicitacao de alteracao em meta da empresa: ' . $goal['name'],
            'before_json' => json_encode($goal),
            'after_json' => json_encode($data),
        ]);

        Notification::create([
            'type' => 'APPROVAL_REQUEST',
            'title' => 'Nova solicitacao de alteracao',
            'body' => 'Meta da empresa ' . $goal['name'] . ' aguardando aprovacao.',
            'reference_type' => 'goal_company',
            'reference_id' => (int)$id,
            'notify_date' => date('Y-m-d'),
            'unique_key' => 'approval_goal_company_' . $id . '_' . date('Ymd'),
        ]);

        $_SESSION['flash_success'] = 'Solicitacao enviada para aprovacao.';
        redirect('/goals/company');
    }

    public function updateSettings(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        $data = [
            'main_goal_name' => trim($_POST['main_goal_name'] ?? 'Meta Principal'),
            'main_goal_value' => safe_float($_POST['main_goal_value'] ?? 0),
            'meetings_goal' => safe_int($_POST['meetings_goal'] ?? 0),
            'meetings_done' => safe_int($_POST['meetings_done'] ?? 0),
            'deals_goal' => safe_int($_POST['deals_goal'] ?? 0),
            'deals_done' => safe_int($_POST['deals_done'] ?? 0),
        ];
        DashboardSetting::update($data);
        $_SESSION['flash_success'] = 'Configura??es atualizadas.';
        redirect('/goals/company');
    }

    public function personal(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $userId = Auth::user()['id'];
        $selectedUser = null;
        if (is_admin() && !empty($_GET['user_id'])) {
            $userId = safe_int($_GET['user_id']);
            $selectedUser = User::find($userId);
        }
        $goals = GoalPersonal::listByUser($userId);
        $users = is_admin() ? User::all() : [];
        $this->render('goals/personal', [
            'goals' => $goals,
            'users' => $users,
            'selected_user' => $selectedUser,
            'selected_user_id' => $userId,
        ]);
    }

    public function editPersonal(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $goal = null;
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM goals_personal WHERE id = ?');
        $stmt->execute([(int)$id]);
        $goal = $stmt->fetch();
        if (!$goal) {
            redirect('/goals/personal');
        }
        if (!is_admin() && (int)$goal['user_id'] !== (int)Auth::user()['id']) {
            redirect('/goals/personal');
        }
        $this->render('goals/personal_edit', [
            'goal' => $goal,
        ]);
    }

    public function storePersonal(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();
        $data = $this->sanitizePersonal();
        $data['user_id'] = Auth::user()['id'];
        GoalPersonal::create($data);
        $_SESSION['flash_success'] = 'Meta pessoal cadastrada.';
        redirect('/goals/personal');
    }

    public function updatePersonal(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT user_id FROM goals_personal WHERE id = ?');
        $stmt->execute([(int)$id]);
        $ownerId = $stmt->fetchColumn();

        if (!$ownerId) {
            redirect('/goals/personal');
        }
        if (!is_admin() && (int)$ownerId !== (int)Auth::user()['id']) {
            redirect('/goals/personal');
        }

        $data = $this->sanitizePersonal();
        GoalPersonal::update((int)$id, $data);
        $_SESSION['flash_success'] = 'Meta pessoal atualizada.';
        redirect('/goals/personal');
    }

    public function deletePersonal(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT user_id FROM goals_personal WHERE id = ?');
        $stmt->execute([(int)$id]);
        $ownerId = $stmt->fetchColumn();

        if (!$ownerId) {
            redirect('/goals/personal');
        }
        if (!is_admin() && (int)$ownerId !== (int)Auth::user()['id']) {
            redirect('/goals/personal');
        }

        GoalPersonal::delete((int)$id);
        $_SESSION['flash_success'] = 'Meta pessoal removida.';
        redirect('/goals/personal');
    }

    private function sanitizeCompany(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'target_value' => safe_float($_POST['target_value'] ?? 0),
            'current_value' => safe_float($_POST['current_value'] ?? 0),
            'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
            'end_date' => empty($_POST['end_date']) ? null : $_POST['end_date'],
        ];
    }

    private function sanitizePersonal(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'type' => $_POST['type'] ?? 'MONTHLY',
            'target_value' => safe_float($_POST['target_value'] ?? 0),
            'current_value' => safe_float($_POST['current_value'] ?? 0),
            'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
            'end_date' => empty($_POST['end_date']) ? null : $_POST['end_date'],
        ];
    }
}
