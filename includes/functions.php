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

// ===== 12-апта: рецепттер CRUD көмекші функциялары =====

function set_flash(string $type, string $text): void {
    $_SESSION['flash'] = ['type' => $type, 'text' => $text];
}

// GET/POST ішіндегі id-ді тексеру: оң бүтін сан болмаса null
function valid_id($value): ?int {
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

function get_categories(PDO $pdo): array {
    return $pdo->query('SELECT id, name FROM categories ORDER BY id')->fetchAll();
}

// Формадан келген деректерді тазалап, массив етіп қайтарады
function recipe_input(array $src): array {
    return [
        'title'        => trim($src['title'] ?? ''),
        'category_id'  => trim($src['category_id'] ?? ''),
        'description'  => trim($src['description'] ?? ''),
        'ingredients'  => trim($src['ingredients'] ?? ''),
        'instructions' => trim($src['instructions'] ?? ''),
        'cooking_time' => trim($src['cooking_time'] ?? ''),
    ];
}

// Серверлік валидация: қателер тізімін қайтарады (бос болса — дұрыс)
function validate_recipe(array $d, array $categories): array {
    $errors = [];
    $len = fn(string $s): int => mb_strlen($s, 'UTF-8');

    if ($len($d['title']) < 3 || $len($d['title']) > 150) {
        $errors[] = 'Атауы 3 пен 150 таңба аралығында болуы тиіс.';
    }
    $ids = array_column($categories, 'id');
    if (valid_id($d['category_id']) === null || !in_array((int)$d['category_id'], array_map('intval', $ids), true)) {
        $errors[] = 'Санатты тізімнен таңдаңыз.';
    }
    if ($len($d['description']) > 1000) {
        $errors[] = 'Сипаттама 1000 таңбадан аспауы керек.';
    }
    if ($len($d['ingredients']) < 5 || $len($d['ingredients']) > 5000) {
        $errors[] = 'Ингредиенттер 5 пен 5000 таңба аралығында болуы тиіс.';
    }
    if ($len($d['instructions']) < 10 || $len($d['instructions']) > 10000) {
        $errors[] = 'Дайындалу нұсқаулығы 10 бен 10000 таңба аралығында болуы тиіс.';
    }
    if ($d['cooking_time'] !== '') {
        $t = filter_var($d['cooking_time'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 1440]]);
        if ($t === false) {
            $errors[] = 'Дайындалу уақыты 1 мен 1440 минут аралығындағы бүтін сан болуы керек.';
        }
    }
    return $errors;
}
