<?php

class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'app'): void
    {
        View::render($view, $data, $layout);
    }

    protected function requireCsrf(): void
    {
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            exit;
        }
    }
}
