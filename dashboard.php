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

$pageTitle = 'Жеке кабинет';
require 'includes/header.php';
?>
        <h1>Қош келдіңіз, <?= e($me['username']) ?>!</h1>
        <p>Бұл сіздің жеке кабинет парақшаңыз. Мұнда жобаның негізгі элементтері мен статистикасы көрсетіледі.</p>

        <div class="note">
            <p>• Email: <?= e($me['email']) ?></p>
            <p>• Рөлі: <?= e($me['role']) ?></p>
            <p>• Тіркелген күні: <?= e(date('d.m.Y', strtotime($me['created_at']))) ?></p>
            <p>• Менің рецепттерім: <?= $recipeCount ?></p>
            <p>• Жоба статусы: Сәтті орындалуда (11-апта)</p>
        </div>

        <a href="logout.php" class="btn" style="background:#e8a5b8;">Шығу</a>
<?php require 'includes/footer.php'; ?>
