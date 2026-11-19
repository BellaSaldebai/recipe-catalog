<?php
require_once 'includes/functions.php';

// Бұл бетке тек жаңа тіркелген пайдаланушы түседі
if (empty($_SESSION['welcome_name'])) {
    header('Location: index.php');
    exit;
}
$name = $_SESSION['welcome_name'];
unset($_SESSION['welcome_name']);

$pageTitle = 'Рақмет!';
$containerClass = 'celebrate';
require 'includes/header.php';
?>
        <div class="confetti-host" data-confetti="40"></div>
        <div class="center">
            <div class="big-emoji">🧁</div>
            <h1>Рақмет, <?= e($name) ?>! 🎉</h1>
            <p>Тіркелу сәтті аяқталды. Рецепттер әлеміне қош келдіңіз!<br>
               Енді жүйеге кіріп, өз рецептіңізді бөлісе аласыз.</p>
            <a href="login.php" class="btn">Жүйеге кіру</a>
            <a href="index.php" class="btn btn-light">Басты бетке</a>
        </div>
<?php require 'includes/footer.php'; ?>
