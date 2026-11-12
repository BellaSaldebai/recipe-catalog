<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// XSS-тен қорғау: шығарылатын мәтінді экранирлеу
function e($s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

// Жабық беттерді қорғау
function require_login(): void {
    if (!is_logged_in()) {
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Бұл бетті көру үшін алдымен жүйеге кіріңіз.'];
        header('Location: login.php');
        exit;
    }
}

// Кірген пайдаланушыны login/register беттерінен бағыттау
function redirect_if_logged_in(): void {
    if (is_logged_in()) {
        header('Location: dashboard.php');
        exit;
    }
}

function flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// CSRF қорғанысы
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}
