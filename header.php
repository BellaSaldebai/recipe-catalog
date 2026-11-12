<?php require_once __DIR__ . '/functions.php'; $flash = flash(); ?>
<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Рецепттер каталогы') ?> - Рецепттер каталогы</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <header>
        <a class="logo" href="index.php">🍰 Рецепттер каталогы</a>
        <nav>
            <a href="index.php">Басты бет</a>
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php">Кабинет</a>
                <a href="logout.php">Шығу</a>
            <?php else: ?>
                <a href="login.php">Кіру</a>
                <a href="register.php">Тіркелу</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container <?= e($containerClass ?? '') ?>">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['text']) ?></div>
        <?php endif; ?>
