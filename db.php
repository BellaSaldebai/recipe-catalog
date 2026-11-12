<?php
// PDO арқылы MySQL дерекқорына қосылу (XAMPP-тың әдепкі параметрлері)
$host   = 'localhost';
$dbname = 'recipe_catalog';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Дерекқорға қосылу қатесі. config/db.php файлын тексеріңіз.');
}
