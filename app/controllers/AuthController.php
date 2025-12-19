<?php

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        $this->render('auth/login', ['title' => 'Login'], 'auth');
    }

    public function login(): void
    {
        $this->requireCsrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($email === '' || $password === '') {
            $_SESSION['flash_error'] = 'Informe email e senha.';
            redirect('/login');
        }
        if (!Auth::attempt($email, $password)) {
            $_SESSION['flash_error'] = 'Credenciais inv?lidas.';
            redirect('/login');
        }
        redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }

    public function forgot(): void
    {
        $this->render('auth/forgot', ['title' => 'Esqueci minha senha'], 'auth');
    }

    public function forgotSubmit(): void
    {
        $this->requireCsrf();
        $email = trim($_POST['email'] ?? '');
        if ($email === '') {
            $_SESSION['flash_error'] = 'Informe o email cadastrado.';
            redirect('/forgot');
        }
        $user = User::findByEmail($email);
        if ($user) {
            User::createPasswordResetRequest((int)$user['id']);
            Notification::create([
                'type' => 'PASSWORD_RESET',
                'title' => 'Pedido de reset de senha',
                'body' => 'Usuario ' . $user['name'] . ' solicitou reset de senha.',
                'reference_type' => 'user',
                'reference_id' => (int)$user['id'],
                'notify_date' => date('Y-m-d'),
                'unique_key' => 'pwd_reset_' . $user['id'] . '_' . date('Ymd'),
            ]);
        }
        $_SESSION['flash_success'] = 'Solicitacao enviada. Um admin ira resetar sua senha.';
        redirect('/login');
    }
}
