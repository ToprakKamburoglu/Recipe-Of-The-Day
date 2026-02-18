<?php require_once "../header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
        <i class="fas fa-plus me-2 primary-text"></i> Create New Category
    </h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<?php
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        $check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
        $check->execute([$name]);
        
        if ($check->fetchColumn() > 0) {
            $error = "This category already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, created_at) VALUES (?, NOW())");
            $stmt->execute([$name]);
            
            $success = "Category created successfully!";
            echo "<script>setTimeout(function(){ window.location.href='/recipe_of_the_day/admin/categories/index.php'; }, 1500);</script>";
        }
    }
}
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0">
            Category Information
        </h5>
    </div>
    <div class="card-body p-4">
        
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg" 
                               placeholder="e.g. Main Course, Desserts, Soup..." 
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               required 
                               autofocus>
                        <small class="text-muted">Enter a unique category name for organizing recipes.</small>
                    </div>
                </div>
                
                
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary shadow-sm px-5">
                    <i class="fas fa-plus-circle me-2"></i>Create Category
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once "../footer.php"; ?>