<?php require_once "../header.php"; ?>

<?php
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: /recipe_of_the_day/admin/categories/index.php");
    exit;
}

if (isset($_POST['confirm'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);
    header("Location: /recipe_of_the_day/admin/categories/index.php");
    exit;
}
?>

<h1 class="page-title">Delete Category</h1>

<p class="warning-text">
    Delete category <strong><?= htmlspecialchars($category['name']) ?></strong>?
</p>

<form method="POST" class="delete-form">
    <button type="submit" name="confirm" class="btn btn-delete">Yes, Delete</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php require_once "../footer.php"; ?>
