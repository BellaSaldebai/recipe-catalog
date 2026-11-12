<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
redirect_if_logged_in();

$errors = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!csrf_check()) {
        $errors[] = 'Қауіпсіздік токені жарамсыз. Бетті жаңартып қайталаңыз.';
    }
    // Серверлік валидация
    if (mb_strlen($username) < 3 || mb_strlen($username) > 50) {
        $errors[] = 'Аты-жөні 3 пен 50 таңба аралығында болуы тиіс.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email дұрыс емес.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Құпиясөз кемінде 8 таңбадан тұруы керек.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Құпиясөздер сәйкес емес.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $check->execute([$username, $email]);
        if ($check->fetch()) {
            $errors[] = 'Мұндай аты-жөні немесе email тіркелген.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $ins->execute([$username, $email, $hash]);
            $_SESSION['flash'] = ['type' => 'success', 'text' => 'Тіркелу сәтті өтті! Енді жүйеге кіріңіз.'];
            header('Location: login.php');
            exit;
        }
    }
}

$pageTitle = 'Тіркелу';
require 'includes/header.php';
?>
        <h1>Жаңа аккаунт құру</h1>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= e($err) ?></div>
        <?php endforeach; ?>
        <form action="register.php" method="POST" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="form-group">
                <label for="username">Аты-жөні:</label>
                <input type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="Атыңызды енгізіңіз" required>
            </div>
            <div class="form-group">
                <label for="email">Электрондық почта:</label>
                <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="name@example.com" required>
            </div>
            <div class="form-group">
                <label for="password">Құпиясөз:</label>
                <input type="password" id="password" name="password" minlength="8" placeholder="Кемінде 8 таңба" required>
            </div>
            <div class="form-group">
                <label for="confirm">Құпиясөзді қайталаңыз:</label>
                <input type="password" id="confirm" name="confirm" minlength="8" required>
            </div>
            <button type="submit" class="btn">Тіркелу</button>
        </form>
        <p style="margin-top:20px;">Аккаунтыңыз бар ма? <a class="link" href="login.php">Кіру</a></p>
<?php require 'includes/footer.php'; ?>
