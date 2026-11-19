<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

$id = valid_id($_GET['id'] ?? null);
if ($id === null) {
    set_flash('error', 'Рецепт табылмады.');
    header('Location: dashboard.php');
    exit;
}

// Иелік тексерісі: рецепт тек автор болса ғана табылады
$stmt = $pdo->prepare('SELECT * FROM recipes WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$recipe = $stmt->fetch();
if (!$recipe) {
    set_flash('error', 'Рецепт табылмады немесе оны өңдеуге рұқсатыңыз жоқ.');
    header('Location: dashboard.php');
    exit;
}

$categories = get_categories($pdo);
$errors = [];
$data = recipe_input([
    'title'        => $recipe['title'],
    'category_id'  => $recipe['category_id'],
    'description'  => $recipe['description'] ?? '',
    'ingredients'  => $recipe['ingredients'],
    'instructions' => $recipe['instructions'],
    'cooking_time' => $recipe['cooking_time'] ?? '',
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = recipe_input($_POST);
    if (!csrf_check()) {
        $errors[] = 'Қауіпсіздік токені жарамсыз. Бетті жаңартып қайталаңыз.';
    }
    $errors = array_merge($errors, validate_recipe($data, $categories));

    if (!$errors) {
        $upd = $pdo->prepare(
            'UPDATE recipes
             SET category_id = ?, title = ?, description = ?, ingredients = ?, instructions = ?, cooking_time = ?
             WHERE id = ? AND user_id = ?'
        );
        $upd->execute([
            (int)$data['category_id'],
            $data['title'],
            $data['description'] !== '' ? $data['description'] : null,
            $data['ingredients'],
            $data['instructions'],
            $data['cooking_time'] !== '' ? (int)$data['cooking_time'] : null,
            $id,
            $_SESSION['user_id'],
        ]);
        set_flash('success', 'Рецепт жаңартылды ✨');
        header('Location: recipe-detail.php?id=' . $id);
        exit;
    }
}

$pageTitle = 'Рецептті өңдеу';
$containerClass = 'wide';
$action = 'edit-recipe.php?id=' . $id;
$submitLabel = 'Өзгерістерді сақтау';
require 'includes/header.php';
?>
        <h1>Рецептті өңдеу</h1>
<?php require 'includes/recipe_form.php'; ?>
<?php require 'includes/footer.php'; ?>
