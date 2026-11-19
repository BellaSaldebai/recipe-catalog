<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login(); // жабық бет: тек кірген пайдаланушыларға

$stmt = $pdo->prepare('SELECT username, email, role, created_at FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$me = $stmt->fetch();

$cnt = $pdo->prepare('SELECT COUNT(*) FROM recipes WHERE user_id = ?');
$cnt->execute([$_SESSION['user_id']]);
$recipeCount = (int)$cnt->fetchColumn();

$mine = $pdo->prepare(
    'SELECT r.id, r.title, r.cooking_time, r.created_at, c.name AS category
     FROM recipes r
     JOIN categories c ON c.id = r.category_id
     WHERE r.user_id = ?
     ORDER BY r.created_at DESC'
);
$mine->execute([$_SESSION['user_id']]);
$myRecipes = $mine->fetchAll();

$pageTitle = 'Жеке кабинет';
$containerClass = 'wide';
require 'includes/header.php';
?>
        <h1>Қош келдіңіз, <?= e($me['username']) ?>! <span class="wave">👋</span></h1>
        <p>Бұл сіздің жеке кабинет парақшаңыз. Мұнда жобаның негізгі элементтері мен статистикасы көрсетіледі.</p>

        <div class="stats">
            <div class="stat"><span>📖</span><b><?= $recipeCount ?></b><small>Менің рецепттерім</small></div>
            <div class="stat"><span>🎀</span><b><?= e($me['role']) ?></b><small>Рөлі</small></div>
            <div class="stat"><span>📅</span><b><?= e(date('d.m.Y', strtotime($me['created_at']))) ?></b><small>Тіркелген күні</small></div>
        </div>

        <div class="note">
            <p>• Email: <?= e($me['email']) ?></p>
            <p>• Жоба статусы: Сәтті орындалуда (12-апта)</p>
        </div>

        <h2>Менің рецепттерім</h2>
        <a href="add-recipe.php" class="btn">+ Рецепт қосу</a>

        <?php if (!$myRecipes): ?>
            <div class="empty">
                <div class="big-emoji">🍽️</div>
                <p>Сізде әзірге рецепттер жоқ. Алғашқысын қосыңыз!</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Атауы</th><th>Санаты</th><th>Уақыты</th><th>Күні</th><th>Әрекеттер</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($myRecipes as $r): ?>
                        <tr>
                            <td><a class="link" href="recipe-detail.php?id=<?= (int)$r['id'] ?>"><?= e($r['title']) ?></a></td>
                            <td><?= e($r['category']) ?></td>
                            <td><?= e($r['cooking_time'] ?? '–') ?> мин</td>
                            <td><?= e(date('d.m.Y', strtotime($r['created_at']))) ?></td>
                            <td class="row-actions">
                                <a href="edit-recipe.php?id=<?= (int)$r['id'] ?>" class="btn btn-small">Өңдеу</a>
                                <form action="delete-recipe.php" method="POST" class="inline-form" data-confirm="Рецептті шынымен жою керек пе?">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Жою</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <p style="margin-top:25px;"><a href="logout.php" class="btn btn-light">Шығу</a></p>
<?php require 'includes/footer.php'; ?>
