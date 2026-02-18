<?php
require_once __DIR__ . '/config.php';

define('DB_HOST', 'localhost');
define('DB_NAME', 'recipe_of_the_day');
define('DB_USER', 'recipeoftheday');
define('DB_PASS', 'Recipeoftheday_MasterProject');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ]
    );
} catch (PDOException $e) {
    if (ENV === 'development') {
        die("DB ERROR: " . $e->getMessage());
    } else {
        die("Database connection error.");
    }
}
