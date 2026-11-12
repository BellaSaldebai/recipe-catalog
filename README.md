# Recipe Catalog Web Application (Рецепттер каталогы)

PHP және MySQL технологияларын қолдану арқылы әзірленген веб-қосымша.

## 📌 Жоба туралы
Бұл жоба пайдаланушыларға тағам рецепттерін іздеуге, санаттар бойынша сүзуге және өз рецепттерін жариялауға мүмкіндік береді.

## 🚀 Негізгі функциялар
- Пайдаланушыларды тіркеу және кіру жүйесі (Authentication) ✅ 11-апта
- Рецепттерді қосу, өңдеу, жою және қарау (CRUD)
- Іздеу және санаттар бойынша сүзу (Search & Filter)

## 🛠 Технологиялар
- Backend: PHP (PDO)
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript

## 📅 Орындалған апталар
- 8-апта: жоспарлау, ER-диаграмма (`database.md`), беттер тізімі (`pages.md`)
- 9-апта: HTML/CSS/JS интерфейсі (пастельді дизайн)
- 10-апта: PHP беттері, include (header/footer), формалар
- **11-апта: MySQL + PDO, тіркелу, кіру, шығу, жабық беттер**

## ⚙️ Іске қосу
1. XAMPP-та Apache және MySQL қосыңыз.
2. phpMyAdmin-де `database/schema.sql` файлын импорттаңыз.
3. `config/db.php` ішіндегі логин/парольді тексеріңіз.
4. Жобаны `htdocs/recipe-catalog` қалтасына салып, `http://localhost/recipe-catalog/` ашыңыз.

## 🔒 Қауіпсіздік
`password_hash()` / `password_verify()`, PDO prepared statements, `htmlspecialchars()`, CSRF-токен, `session_regenerate_id()`.
