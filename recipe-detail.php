<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$id = valid_id($_GET['id'] ?? null);
$recipe = false;
if ($id !== null) {
    $stmt = $pdo->prepare(
        'SELECT r.*, c.name AS category, u.username
         FROM recipes r
         JOIN categories c ON c.id = r.category_id
         JOIN users u ON u.id = r.user_id
         WHERE r.id = ?'
    );
    $stmt->execute([$id]);
    $recipe = $stmt->fetch();
}

if (!$recipe) {
    http_response_code(404);
    $pageTitle = 'Табылмады';
    require 'includes/header.php';
    ?>
        <div class="empty">
            <div class="big-emoji">🔍</div>
            <p>Мұндай рецепт табылмады.</p>
            <a href="index.php" class="btn">Басты бетке</a>
        </div>
<?php
    require 'includes/footer.php';
    exit;
}

$isOwner = is_logged_in() && (int)$_SESSION['user_id'] === (int)$recipe['user_id'];
$ingredients = array_filter(array_map('trim', preg_split('/\R/u', $recipe['ingredients'])), 'strlen');

$pageTitle = $recipe['title'];
$containerClass = 'wide';
require 'includes/header.php';
?>
        <span class="tag"><?= e($recipe['category']) ?></span>
        <h1 style="margin-top:10px;"><?= e($recipe['title']) ?></h1>
        <p class="small">⏱ <?= e($recipe['cooking_time'] ?? '–') ?> мин · Автор: <?= e($recipe['username']) ?> · <?= e(date('d.m.Y', strtotime($recipe['created_at']))) ?></p>

        <?php if ($recipe['description']): ?>
            <p><?= nl2br(e($recipe['description'])) ?></p>
        <?php endif; ?>

        <h2>Ингредиенттер</h2>
        <ul class="ingredients">
            <?php foreach ($ingredients as $line): ?>
                <li><?= e($line) ?></li>
            <?php endforeach; ?>
        </ul>

        <h2 style="margin-top:25px;">Дайындалу тәсілі</h2>
        <p><?= nl2br(e($recipe['instructions'])) ?></p>

        <div class="actions">
            <a href="index.php" class="btn btn-light">← Басты бет</a>
            <?php if ($isOwner): ?>
                <a href="edit-recipe.php?id=<?= (int)$recipe['id'] ?>" class="btn">Өңдеу</a>
                <form action="delete-recipe.php" method="POST" class="inline-form" data-confirm="Рецептті шынымен жою керек пе?">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$recipe['id'] ?>">
                    <button type="submit" class="btn btn-danger">Жою</button>
                </form>
            <?php endif; ?>
        </div>
<?php require 'includes/footer.php'; ?>
