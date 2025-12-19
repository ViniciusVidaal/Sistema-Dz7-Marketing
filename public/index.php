<?php

require_once __DIR__ . '/../app/config/env.php';
load_env(__DIR__ . '/../.env');

session_name(env('SESSION_NAME', 'dz7_session'));
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../app/config/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require_once __DIR__ . '/../app/core/Helpers.php';

$router = new Router();

$router->get('/', function () {
    if (Auth::check()) {
        redirect('/dashboard');
    }
    redirect('/login');
});

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/forgot', [AuthController::class, 'forgot']);
$router->post('/forgot', [AuthController::class, 'forgotSubmit']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/finance', [FinanceController::class, 'index']);
$router->post('/finance/adjustment', [FinanceController::class, 'storeAdjustment']);
$router->post('/finance/traffic', [FinanceController::class, 'storeTraffic']);
$router->post('/finance/misc', [FinanceController::class, 'storeMisc']);

$router->get('/leads', [LeadsController::class, 'index']);
$router->get('/leads/create', [LeadsController::class, 'create']);
$router->post('/leads', [LeadsController::class, 'store']);
$router->get('/leads/{id}/edit', [LeadsController::class, 'edit']);
$router->post('/leads/{id}/update', [LeadsController::class, 'update']);

$router->get('/recurring', [RecurringController::class, 'index']);
$router->get('/recurring/{id}', [RecurringController::class, 'show']);
$router->post('/recurring/{id}/paid', [RecurringController::class, 'markPaid']);
$router->post('/recurring/{id}/inad', [RecurringController::class, 'markInad']);
$router->post('/recurring/{id}/status', [RecurringController::class, 'toggleStatus']);

$router->get('/tools', [ToolsController::class, 'index']);
$router->get('/tools/create', [ToolsController::class, 'create']);
$router->post('/tools', [ToolsController::class, 'store']);
$router->get('/tools/{id}/edit', [ToolsController::class, 'edit']);
$router->post('/tools/{id}/update', [ToolsController::class, 'update']);
$router->post('/tools/{id}/delete', [ToolsController::class, 'delete']);

$router->get('/investments', [InvestmentsController::class, 'index']);
$router->get('/investments/create', [InvestmentsController::class, 'create']);
$router->post('/investments', [InvestmentsController::class, 'store']);
$router->get('/investments/{id}/edit', [InvestmentsController::class, 'edit']);
$router->post('/investments/{id}/update', [InvestmentsController::class, 'update']);

$router->get('/goals/company', [GoalsController::class, 'company']);
$router->get('/goals/company/{id}/edit', [GoalsController::class, 'editCompany']);
$router->post('/goals/company', [GoalsController::class, 'storeCompany']);
$router->post('/goals/company/{id}/update', [GoalsController::class, 'updateCompany']);
$router->post('/goals/settings', [GoalsController::class, 'updateSettings']);
$router->get('/goals/personal', [GoalsController::class, 'personal']);
$router->get('/goals/personal/{id}/edit', [GoalsController::class, 'editPersonal']);
$router->post('/goals/personal', [GoalsController::class, 'storePersonal']);
$router->post('/goals/personal/{id}/update', [GoalsController::class, 'updatePersonal']);
$router->post('/goals/personal/{id}/delete', [GoalsController::class, 'deletePersonal']);

$router->get('/users', [UsersController::class, 'index']);
$router->get('/users/create', [UsersController::class, 'create']);
$router->post('/users', [UsersController::class, 'store']);
$router->get('/users/{id}/edit', [UsersController::class, 'edit']);
$router->post('/users/{id}/update', [UsersController::class, 'update']);
$router->post('/users/{id}/reset', [UsersController::class, 'resetPassword']);

$router->get('/approvals', [ApprovalsController::class, 'index']);
$router->get('/approvals/{id}', [ApprovalsController::class, 'show']);
$router->post('/approvals/{id}/approve', [ApprovalsController::class, 'approve']);
$router->post('/approvals/{id}/reject', [ApprovalsController::class, 'reject']);

$router->get('/notifications', [NotificationsController::class, 'index']);
$router->post('/notifications/{id}/read', [NotificationsController::class, 'read']);

$router->get('/profile/password', [ProfileController::class, 'password']);
$router->post('/profile/password', [ProfileController::class, 'updatePassword']);
$router->get('/profile/toggle-view', [ProfileController::class, 'toggleView']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
