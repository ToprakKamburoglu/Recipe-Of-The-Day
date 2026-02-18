<?php
require_once "../header.php";
require_once "../../config/auth.php";

requireRole([1, 2]);

$currentUserId   = currentUserId();
$currentUserRole = currentUserRole();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT id, username, role
    FROM users
    WHERE id = ?
");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: /recipe_of_the_day/admin/users/index.php");
    exit;
}

if ($id === $currentUserId) {
    echo '<div class="alert alert-danger">You cannot delete your own account.</div>';
    require_once "../footer.php";
    exit;
}

if ($currentUserRole === 1 && (int)$user['role'] >= 1) {
    echo '<div class="alert alert-danger">You are not allowed to delete this user.</div>';
    require_once "../footer.php";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}
?>

<h1 class="page-title">Delete User</h1>

<p class="warning-text">
    Are you sure you want to delete user
    <strong><?= htmlspecialchars($user['username']) ?></strong>?
</p>

<form method="POST" class="delete-form">
    <button type="submit" name="confirm" class="btn btn-delete">
        Yes, Delete
    </button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once "../footer.php"; ?>
