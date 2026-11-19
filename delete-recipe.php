<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

// Жою тек POST + CSRF арқылы жасалады (сілтеме арқылы жоюға болмайды)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    set_flash('error', 'Қауіпсіздік токені жарамсыз немесе сұраныс қате.');
    header('Location: dashboard.php');
    exit;
}

$id = valid_id($_POST['id'] ?? null);
if ($id === null) {
    set_flash('error', 'Рецепт табылмады.');
    header('Location: dashboard.php');
    exit;
}

// WHERE user_id = ? — тек өз рецептін жоя алады
$stmt = $pdo->prepare('DELETE FROM recipes WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);

if ($stmt->rowCount() > 0) {
    set_flash('success', 'Рецепт жойылды.');
} else {
    set_flash('error', 'Рецепт табылмады немесе оны жоюға рұқсатыңыз жоқ.');
}
header('Location: dashboard.php');
exit;
