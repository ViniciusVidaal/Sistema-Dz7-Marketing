<?php

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function url(string $path): string
{
    $base = rtrim(env('APP_URL', ''), '/');
    if ($base === '') {
        return $path;
    }
    return $base . $path;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user && $user['role'] === 'ADMIN';
}

function is_employee(): bool
{
    $user = current_user();
    return $user && $user['role'] === 'EMPLOYEE';
}

function view_as_employee(): bool
{
    return !empty($_SESSION['view_as_employee']);
}

function format_money($value): string
{
    return 'R$ ' . number_format((float)$value, 2, ',', '.');
}

function today(): string
{
    return date('Y-m-d');
}

function current_month(): string
{
    return date('Y-m');
}

function safe_int($value, int $default = 0): int
{
    $filtered = filter_var($value, FILTER_VALIDATE_INT);
    return $filtered === false ? $default : (int)$filtered;
}

function safe_float($value, float $default = 0.0): float
{
    if ($value === null || $value === '') {
        return $default;
    }
    $clean = str_replace(['.', ','], ['', '.'], (string)$value);
    if (!is_numeric($clean)) {
        return $default;
    }
    return (float)$clean;
}
