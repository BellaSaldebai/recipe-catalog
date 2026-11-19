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
        <section class="hero">
            <span class="float f1">🍰</span>
            <span class="float f2">🍓</span>
            <span class="float f3">🧁</span>
            <span class="float f4">🍪</span>
            <span class="float f5">🍩</span>
            <h1>Жобамызға қош келдіңіз!</h1>
            <p>Дәмді рецепттерді қарап шығыңыз, тіркеліп, өз рецептіңізді бөлісіңіз.</p>
            <?php if (!is_logged_in()): ?>
                <a href="register.php" class="btn">Бастау ✨</a>
                <a href="login.php" class="btn btn-light">Кіру</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn">Жеке кабинетке өту</a>
            <?php endif; ?>
        </section>

        <div class="features">
            <div class="feature"><span>📝</span><b>Тіркел</b><small>Аккаунт ашып, жүйеге кір</small></div>
            <div class="feature"><span>👩‍🍳</span><b>Рецепт бөліс</b><small>Өз рецептіңді жарияла</small></div>
            <div class="feature"><span>🔍</span><b>Тап</b><small>Санат бойынша іздеп көр</small></div>
        </div>

        <h2 style="margin-top:40px;">Соңғы рецепттер</h2>
        <?php if (is_logged_in()): ?>
            <a href="add-recipe.php" class="btn">+ Рецепт қосу</a>
        <?php endif; ?>
        <?php if (!$recipes): ?>
            <div class="empty">
                <div class="big-emoji">🍽️</div>
                <p>Әзірге рецепттер жоқ. Алғашқы рецептті сіз қосыңыз!</p>
            </div>
        <?php endif; ?>
        <div class="grid">
        <?php foreach ($recipes as $r): ?>
            <article class="card">
                <span class="tag"><?= e($r['category']) ?></span>
                <h3><a class="link" href="recipe-detail.php?id=<?= (int)$r['id'] ?>"><?= e($r['title']) ?></a></h3>
                <p class="small"><?= e(mb_strimwidth((string)$r['description'], 0, 90, '…', 'UTF-8')) ?></p>
                <p class="small">⏱ <?= e($r['cooking_time'] ?? '–') ?> мин · Автор: <?= e($r['username']) ?></p>
            </article>
        <?php endforeach; ?>
        </div>
<?php require 'includes/footer.php'; ?>
