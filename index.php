<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Басты бет';
$containerClass = 'wide';

$stmt = $pdo->query(
    'SELECT r.id, r.title, r.description, r.cooking_time, r.created_at,
            c.name AS category, u.username
     FROM recipes r
     JOIN categories c ON c.id = r.category_id
     JOIN users u ON u.id = r.user_id
     ORDER BY r.created_at DESC
     LIMIT 6'
);
$recipes = $stmt->fetchAll();

require 'includes/header.php';
?>
        <h1>Жобамызға қош келдіңіз!</h1>
        <p>Рецепттерді қарап шығыңыз, тіркеліп, өз рецептіңізді бөлісіңіз.</p>
        <?php if (!is_logged_in()): ?>
            <a href="register.php" class="btn">Бастау</a>
            <a href="login.php" class="btn btn-light">Кіру</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn">Жеке кабинетке өту</a>
        <?php endif; ?>

        <h2 style="margin-top:40px;">Соңғы рецепттер</h2>
        <?php if (!$recipes): ?>
            <p>Әзірге рецепттер жоқ. Рецепт қосу мүмкіндігі 12-аптада (CRUD) жасалады.</p>
        <?php endif; ?>
        <div class="grid">
        <?php foreach ($recipes as $r): ?>
            <article class="card">
                <span class="tag"><?= e($r['category']) ?></span>
                <h3><?= e($r['title']) ?></h3>
                <p class="small"><?= e(mb_strimwidth((string)$r['description'], 0, 90, '…', 'UTF-8')) ?></p>
                <p class="small">⏱ <?= e($r['cooking_time'] ?? '–') ?> мин · Автор: <?= e($r['username']) ?></p>
            </article>
        <?php endforeach; ?>
        </div>
<?php require 'includes/footer.php'; ?>
