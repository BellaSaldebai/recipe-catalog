# 🗄 Дерекқор құрылымы (Database Structure)

Жоба MySQL дерекқорындағы 3 негізгі кестеден тұрады:

### 1. `users` (Пайдаланушылар кестесі)
* `id` (INT, AUTO_INCREMENT, PRIMARY KEY) — Пайдаланушының бірегей идентификаторы.
* `username` (VARCHAR(50), UNIQUE) — Пайдаланушы аты.
* `email` (VARCHAR(100), UNIQUE) — Электрондық пошта.
* `password` (VARCHAR(255)) — Хэштелген құпиясөз (`password_hash()`).
* `role` (ENUM('user', 'admin')) — Пайдаланушы рөлі.
* `created_at` (TIMESTAMP) — Тіркелген уақыты.

---

### 2. `categories` (Санаттар кестесі)
* `id` (INT, AUTO_INCREMENT, PRIMARY KEY) — Санаттың бірегей идентификаторы.
* `name` (VARCHAR(50)) — Санат атауы (мысалы: Таңғы ас, Сорпалар, Дезерттер).
* `slug` (VARCHAR(50)) — URL үшін ыңғайлы атау.

---

### 3. `recipes` (Рецепттер кестесі)
* `id` (INT, AUTO_INCREMENT, PRIMARY KEY) — Рецепттің бірегей идентификаторы.
* `user_id` (INT, FOREIGN KEY -> `users.id`) — Рецепт авторы.
* `category_id` (INT, FOREIGN KEY -> `categories.id`) — Тағам санаты.
* `title` (VARCHAR(150)) — Рецепттің атауы.
* `description` (TEXT) — Қысқаша сипаттамасы.
* `ingredients` (TEXT) — Ингредиенттер тізімі.
* `instructions` (TEXT) — Дайындалу жолы.
* `cooking_time` (INT) — Дайындалу уақыты (минутпен).
* `image` (VARCHAR(255)) — Суреттің файлына өту сілтемесі.
* `created_at` (TIMESTAMP) — Жарияланған уақыты.
