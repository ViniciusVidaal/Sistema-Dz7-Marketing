<?php

class UsersController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();
        $users = User::all();
        $resetRequests = User::pendingResetRequests();
        $this->render('users/index', [
            'users' => $users,
            'reset_requests' => $resetRequests,
        ]);
    }

    public function create(): void
    {
        Middleware::requireAdmin();
        $this->render('users/create');
    }

    public function store(): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'EMPLOYEE';
        $position = trim($_POST['position'] ?? '');
        $defaultPassword = $_POST['default_password'] ?? env('DEFAULT_PASSWORD', 'ChangeMe123!');

        $passwordHash = password_hash($defaultPassword, PASSWORD_BCRYPT);

        User::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => $role,
            'position' => $position,
            'must_change_password' => 1,
        ]);

        $_SESSION['flash_success'] = 'Usuario cadastrado.';
        redirect('/users');
    }

    public function edit(string $id): void
    {
        Middleware::requireAdmin();
        $user = User::find((int)$id);
        if (!$user) {
            redirect('/users');
        }
        $this->render('users/edit', [
            'user' => $user,
        ]);
    }

    public function update(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'EMPLOYEE',
            'position' => trim($_POST['position'] ?? ''),
            'active' => safe_int($_POST['active'] ?? 1),
        ];
        User::update((int)$id, $data);
        $_SESSION['flash_success'] = 'Usuario atualizado.';
        redirect('/users');
    }

    public function resetPassword(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();

        $defaultPassword = env('DEFAULT_PASSWORD', 'ChangeMe123!');
        $passwordHash = password_hash($defaultPassword, PASSWORD_BCRYPT);
        User::setPassword((int)$id, $passwordHash, 1);
        User::markPasswordResetDone((int)$id);

        $_SESSION['flash_success'] = 'Senha resetada para a senha padrao.';
        redirect('/users');
    }
}
