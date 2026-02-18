<?php require_once "../header.php"; ?>

<?php
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id=?");
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (isset($_POST['confirm'])) {
    $pdo->prepare("DELETE FROM recipes WHERE id=?")->execute([$id]);
    header("Location: index.php");
    exit;
}
?>

<h1 class="page-title">Delete Recipe</h1>

<p class="warning-text">
    Delete recipe <strong><?= htmlspecialchars($recipe['title']) ?></strong>?
</p>

<form method="POST" class="delete-form">
    <button name="confirm" class="btn btn-delete">Yes, Delete</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once "../footer.php"; ?>
