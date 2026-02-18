<?php require_once "../header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
        <i class="fas fa-plus me-2 primary-text"></i> Create New Recipe
    </h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<?php
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $desc  = $_POST['description'];
    $ing   = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $time  = (int)$_POST['prep_time'];
    $diff  = (int)$_POST['difficulty'];
    $cat   = (int)$_POST['category_id'];

    $imageName = "";

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        $targetPath = defined('ROOT_PATH') ? ROOT_PATH . "/uploads/recipes/" . $imageName : __DIR__ . "/../../uploads/recipes/" . $imageName;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $imageName = "uploads/recipes/" . $imageName;
    }

    $stmt = $pdo->prepare("
        INSERT INTO recipes 
        (title, description, ingredients, steps, image, prep_time, difficulty, category_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $title, $desc, $ing, $steps,
        $imageName, $time, $diff, $cat
    ]);

    echo "<script>window.location.href='index.php';</script>";
    exit;
}
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-3">
                       Recipe Image
                    </label>
                    
                    <div class="border rounded bg-light" style="height: 370px; overflow: hidden;">
                        <img id="imagePreview" 
                            src="#" 
                            class="w-100 h-100 d-none" 
                            style="object-fit: cover;" 
                            alt="Preview">
                        <div id="noImageText" class="text-center d-flex flex-column justify-content-center align-items-center h-100">
                            <i class="fas fa-camera fs-1 text-muted mb-2"></i>
                            <p class="text-muted mb-0 small">No image selected</p>
                        </div>
                    </div>
                    
                    <input type="file" 
                           name="image" 
                           class="d-none" 
                           accept="image/*" 
                           id="recipeImage" 
                           onchange="previewImage(event)">
                    
                    <button type="button" 
                            class="btn btn-primary shadow-sm w-100 mt-3" 
                            onclick="document.getElementById('recipeImage').click()">
                        <i class="fas fa-upload me-2"></i>Choose Image
                    </button>
                </div>

                <div class="col-md-8 ps-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Spaghetti Bolognese" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="" disabled selected>Select Category</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Difficulty</label>
                        <select name="difficulty" class="form-select">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prep Time (minutes)</label>
                        <input type="number" name="prep_time" class="form-control" placeholder="30">
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief summary of the dish..."></textarea>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Ingredients</label>
                    <textarea name="ingredients" class="form-control" rows="8" placeholder="- 2 Eggs&#10;- 200g Flour..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Preparation Steps</label>
                    <textarea name="steps" class="form-control" rows="8" placeholder="1. Boil the water...&#10;2. Add pasta..."></textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary shadow-sm px-5 py-2">
                    <i class="fas fa-save me-2"></i>Create Recipe
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
            preview.classList.add('rounded', 'shadow-sm');
            if(noImageText) {
                noImageText.classList.add('d-none');
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once "../footer.php"; ?>