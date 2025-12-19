<?php

class ProfileController extends Controller
{
    public function password(): void
    {
        Middleware::requireAuth();
        $this->render('profile/password');
    }

    public function updatePassword(): void
    {
        Middleware::requireAuth();
        $this->requireCsrf();

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new === '' || $new !== $confirm) {
            $_SESSION['flash_error'] = 'Nova senha invalida.';
            redirect('/profile/password');
        }

        $user = User::find(Auth::user()['id']);
        if (!$user || !password_verify($current, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Senha atual incorreta.';
            redirect('/profile/password');
        }

        $hash = password_hash($new, PASSWORD_BCRYPT);
        User::setPassword((int)$user['id'], $hash, 0);
        Auth::refreshUser((int)$user['id']);

        $_SESSION['flash_success'] = 'Senha atualizada.';
        redirect('/dashboard');
    }

    public function toggleView(): void
    {
        Middleware::requireAdmin();
        $_SESSION['view_as_employee'] = empty($_SESSION['view_as_employee']);
        redirect('/dashboard');
    }
}
