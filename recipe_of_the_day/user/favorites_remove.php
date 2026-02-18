<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$userId   = currentUserId();
$recipeId = (int)($_POST['recipe_id'] ?? 0);

if ($recipeId <= 0) {
    header("Location: " . BASE_URL);
    exit;
}

$deleteStmt = $pdo->prepare("
    DELETE FROM favorites
    WHERE user_id = ? AND recipe_id = ?
");
$deleteStmt->execute([$userId, $recipeId]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
