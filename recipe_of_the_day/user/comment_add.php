<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$userId   = currentUserId();
$recipeId = (int)($_POST['recipe_id'] ?? 0);
$comment  = trim($_POST['comment'] ?? '');
$rating   = (int)($_POST['rating'] ?? 0);

if ($recipeId <= 0 || $comment === '' || $rating < 1 || $rating > 5) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO comments (user_id, recipe_id, text, rating, created_at)
    VALUES (?, ?, ?, ?, NOW())
");

$stmt->execute([
    $userId,
    $recipeId,
    $comment,
    $rating
]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
