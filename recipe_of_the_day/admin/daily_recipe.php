<?php
require_once __DIR__ . '/../config/database.php';

$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM daily_recipe 
    WHERE date = ?
");
$stmt->execute([$today]);

if ($stmt->fetchColumn() > 0) {
    exit;
}

$stmt = $pdo->query("
    SELECT id 
    FROM recipes 
    ORDER BY RAND() 
    LIMIT 1
");

$recipe = $stmt->fetch();

if (!$recipe) {
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO daily_recipe (recipe_id, date)
    VALUES (?, ?)
");
$stmt->execute([
    $recipe['id'],
    $today
]);
