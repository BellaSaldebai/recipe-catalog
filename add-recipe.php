<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

$categories = get_categories($pdo);
$data = recipe_input([]);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = recipe_input($_POST);
    if (!csrf_check()) {
        $errors[] = 'Қауіпсіздік токені жарамсыз. Бетті жаңартып қайталаңыз.';
    }
    $errors = array_merge($errors, validate_recipe($data, $categories));

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO recipes (user_id, category_id, title, description, ingredients, instructions, cooking_time)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $_SESSION['user_id'],
            (int)$data['category_id'],
            $data['title'],
            $data['description'] !== '' ? $data['description'] : null,
            $data['ingredients'],
            $data['instructions'],
            $data['cooking_time'] !== '' ? (int)$data['cooking_time'] : null,
        ]);
        set_flash('success', 'Рецепт сәтті қосылды! 🍰');
        header('Location: recipe-detail.php?id=' . (int)$pdo->lastInsertId());
        exit;
    }
}

$pageTitle = 'Рецепт қосу';
$containerClass = 'wide';
$action = 'add-recipe.php';
$submitLabel = 'Рецептті сақтау';
require 'includes/header.php';
?>
        <h1>Жаңа рецепт қосу</h1>
<?php require 'includes/recipe_form.php'; ?>
<?php require 'includes/footer.php'; ?>
