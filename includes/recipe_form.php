<?php
// Рецепт қосу және өңдеу беттеріне ортақ форма.
// Күтілетін айнымалылар: $data, $categories, $errors, $action, $submitLabel
?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= e($err) ?></div>
        <?php endforeach; ?>
        <form action="<?= e($action) ?>" method="POST" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="form-group">
                <label for="title">Атауы:</label>
                <input type="text" id="title" name="title" maxlength="150" value="<?= e($data['title']) ?>" placeholder="Мысалы: Бауырсақ" required>
            </div>
            <div class="form-group">
                <label for="category_id">Санат:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">— Санатты таңдаңыз —</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= (string)$data['category_id'] === (string)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Қысқаша сипаттама:</label>
                <textarea id="description" name="description" rows="3" maxlength="1000"><?= e($data['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="ingredients">Ингредиенттер (әр жолға біреуден):</label>
                <textarea id="ingredients" name="ingredients" rows="6" required><?= e($data['ingredients']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="instructions">Дайындалу нұсқаулығы:</label>
                <textarea id="instructions" name="instructions" rows="8" required><?= e($data['instructions']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="cooking_time">Дайындалу уақыты (минут):</label>
                <input type="number" id="cooking_time" name="cooking_time" min="1" max="1440" value="<?= e($data['cooking_time']) ?>">
            </div>
            <button type="submit" class="btn"><?= e($submitLabel) ?></button>
            <a href="dashboard.php" class="btn btn-light">Болдырмау</a>
        </form>
