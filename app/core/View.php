<?php

class View
{
    public static function render(string $view, array $data = [], string $layout = 'app'): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'View not found';
            return;
        }

        extract($data);

        if ($layout === 'auth') {
            include __DIR__ . '/../views/layout/header.php';
            include $viewPath;
            include __DIR__ . '/../views/layout/footer.php';
            return;
        }

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/layout/topbar.php';
        echo '<main class="ml-0 lg:ml-64 pt-20 px-6 pb-10">';
        include $viewPath;
        echo '</main>';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
