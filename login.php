<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
redirect_if_logged_in();

$error = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_check()) {
        $error = 'Қауіпсіздік токені жарамсыз. Бетті жаңартып қайталаңыз.';
    } elseif ($login === '' || $password === '') {
        $error = 'Барлық өрістерді толтырыңыз.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // session fixation қорғанысы
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['flash']    = ['type' => 'success', 'text' => 'Сәлем, ' . $user['username'] . '! Жүйеге сәтті кірдіңіз 🎉'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Аты-жөні/email немесе құпиясөз қате.';
    }
}

$pageTitle = 'Кіру';
require 'includes/header.php';
?>
        <h1>Жүйеге кіру</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="form-group">
                <label for="login">Аты-жөні немесе email:</label>
                <input type="text" id="login" name="login" value="<?= e($login) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Құпиясөз:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Кіру</button>
        </form>
        <p style="margin-top:20px;">Аккаунт жоқ па? <a class="link" href="register.php">Тіркелу</a></p>
<?php require 'includes/footer.php'; ?>
