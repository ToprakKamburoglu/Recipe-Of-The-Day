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

$checkStmt = $pdo->prepare("
    SELECT 1 FROM favorites
    WHERE user_id = ? AND recipe_id = ?
    LIMIT 1
");
$checkStmt->execute([$userId, $recipeId]);

if ($checkStmt->fetch()) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$insertStmt = $pdo->prepare("
    INSERT INTO favorites (user_id, recipe_id, added_at)
    VALUES (?, ?, NOW())
");
$insertStmt->execute([$userId, $recipeId]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
