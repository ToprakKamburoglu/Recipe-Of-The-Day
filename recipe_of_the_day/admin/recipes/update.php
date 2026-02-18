<?php require_once "../header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
    <i class="fas fa-pen me-2 primary-text"></i> Edit Recipe</h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<?php
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id=?");
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo '<div class="alert alert-danger">Recipe not found.</div>';
    require_once "../footer.php";
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $image = $recipe['image'];

   if (!empty($_FILES['image']['name'])) {
    $imageName = time() . "_" . $_FILES['image']['name'];
    
    // ROOT_PATH kontrolü (create.php'deki gibi güvenli yol)
    $targetPath = defined('ROOT_PATH') ? ROOT_PATH . "/uploads/recipes/" . $imageName : __DIR__ . "/../../uploads/recipes/" . $imageName;
    
    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    $image = "uploads/recipes/" . $imageName; // BURAYI DEĞİŞTİRDİK
}

    $stmt = $pdo->prepare("
        UPDATE recipes SET
        title=?, description=?, ingredients=?, steps=?, image=?,
        prep_time=?, difficulty=?, category_id=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['title'], $_POST['description'],
        $_POST['ingredients'], $_POST['steps'],
        $image, $_POST['prep_time'], $_POST['difficulty'],
        $_POST['category_id'], $id
    ]);

    echo "<script>window.location.href='index.php';</script>";
    exit;
}
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            
            <!-- RECIPE IMAGE - SOL DAR, SAĞ GENİŞ -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-3">
                       Recipe Image
                    </label>
                    
                    <!-- Görsel Preview -->
                    <?php if(!empty($recipe['image'])): ?>
                        <div class="position-relative">
                            <img id="imagePreview" 
                                 src="<?= BASE_URL ?>/<?= $recipe['image'] ?>" 
                                 class="rounded shadow-sm w-100" 
                                 style="height: 370px; object-fit: cover;" 
                                 alt="Recipe Image">
                        </div>
                    <?php else: ?>
                        <img id="imagePreview" 
                             src="#" 
                             class="rounded shadow-sm w-100 d-none" 
                             style="height: 220px; object-fit: cover;" 
                             alt="Preview">
                        <div id="noImageText" class="border rounded text-center d-flex flex-column justify-content-center align-items-center bg-light" 
                             style="height: 220px;">
                            <i class="fas fa-camera fs-1 text-muted mb-2"></i>
                            <p class="text-muted mb-0 small">No image</p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Hidden File Input -->
                    <input type="file" 
                           name="image" 
                           class="d-none" 
                           accept="image/*" 
                           id="recipeImage" 
                           onchange="previewImage(event)">
                    
                    <!-- Choose Image Button -->
                    <button type="button" 
                            class="btn btn-primary shadow-sm w-100 mt-3" 
                            onclick="document.getElementById('recipeImage').click()">
                        <i class="fas fa-upload me-2"></i>Choose Image
                    </button>
                </div>

                <!-- FORM FIELDS - SAĞ GENİŞ (ALT ALTA) -->
                <div class="col-md-8 ps-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select">
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $recipe['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Difficulty</label>
                        <select name="difficulty" class="form-select">
                            <option value="1" <?= $recipe['difficulty'] == 1 ? 'selected' : '' ?>>Easy</option>
                            <option value="2" <?= $recipe['difficulty'] == 2 ? 'selected' : '' ?>>Medium</option>
                            <option value="3" <?= $recipe['difficulty'] == 3 ? 'selected' : '' ?>>Hard</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prep Time (minutes)</label>
                        <input type="number" name="prep_time" value="<?= $recipe['prep_time'] ?>" class="form-control">
                    </div>
                    
                    <!-- DESCRIPTION -->
                    <div class="mb-0">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($recipe['description']) ?></textarea>
                    </div>
                </div>

            <hr class="my-4">

            <!-- INGREDIENTS & STEPS -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Ingredients</label>
                    <textarea name="ingredients" class="form-control" rows="8"><?= htmlspecialchars($recipe['ingredients']) ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Preparation Steps</label>
                    <textarea name="steps" class="form-control" rows="8"><?= htmlspecialchars($recipe['steps']) ?></textarea>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary shadow-sm px-5 py-2">
                    <i class="fas fa-save me-2"></i>Update Recipe
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const noImageText = document.getElementById('noImageText');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if(noImageText) {
                noImageText.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once "../footer.php"; ?>