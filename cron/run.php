<?php

require_once __DIR__ . '/../app/config/env.php';
load_env(__DIR__ . '/../.env');

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

$token = $_GET['token'] ?? '';
if ($token === '' || $token !== env('CRON_TOKEN')) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$today = new DateTimeImmutable('today');
$notifyDates = [
    $today,
    $today->modify('+1 day'),
    $today->modify('+2 days'),
];

$pdo = Database::getConnection();

$tools = Tool::all();
foreach ($tools as $tool) {
    if (empty($tool['next_due_date'])) {
        continue;
    }
    $dueDate = new DateTimeImmutable($tool['next_due_date']);
    foreach ($notifyDates as $date) {
        if ($dueDate->format('Y-m-d') === $date->format('Y-m-d')) {
            $key = 'tool_due_' . $tool['id'] . '_' . $date->format('Ymd');
            Notification::create([
                'type' => 'TOOL_DUE',
                'title' => 'Vencimento de ferramenta',
                'body' => $tool['name'] . ' vence em ' . $date->format('d/m/Y') . '.',
                'reference_type' => 'tool',
                'reference_id' => (int)$tool['id'],
                'notify_date' => $date->format('Y-m-d'),
                'unique_key' => $key,
            ]);
        }
    }
}

$contracts = RecurringContract::list(['status' => 'ACTIVE']);
$currentMonth = $today->format('Y-m');
foreach ($contracts as $contract) {
    $day = (int)$contract['pay_day'];
    $monthDate = new DateTimeImmutable($today->format('Y-m-01'));
    $lastDay = (int)$monthDate->format('t');
    if ($day > $lastDay) {
        $day = $lastDay;
    }
    $dueDate = new DateTimeImmutable($today->format('Y-m-') . str_pad((string)$day, 2, '0', STR_PAD_LEFT));
    $status = RecurringContract::getMonthlyStatus((int)$contract['id'], $currentMonth);

    foreach ($notifyDates as $date) {
        if ($dueDate->format('Y-m-d') === $date->format('Y-m-d') && $status !== 'PAID') {
            $key = 'recurring_due_' . $contract['id'] . '_' . $date->format('Ymd');
            Notification::create([
                'type' => 'RECURRING_DUE',
                'title' => 'Recorrencia a vencer',
                'body' => 'Contrato ' . $contract['name'] . ' vence em ' . $date->format('d/m/Y') . '.',
                'reference_type' => 'recurring_contract',
                'reference_id' => (int)$contract['id'],
                'notify_date' => $date->format('Y-m-d'),
                'unique_key' => $key,
            ]);
        }
    }

    if ($today > $dueDate && $status !== 'PAID') {
        $key = 'recurring_overdue_' . $contract['id'] . '_' . $today->format('Ymd');
        Notification::create([
            'type' => 'RECURRING_OVERDUE',
            'title' => 'Contrato em atraso',
            'body' => 'Contrato ' . $contract['name'] . ' esta em atraso.',
            'reference_type' => 'recurring_contract',
            'reference_id' => (int)$contract['id'],
            'notify_date' => $today->format('Y-m-d'),
            'unique_key' => $key,
        ]);
    }
}

echo 'Cron executed.';
