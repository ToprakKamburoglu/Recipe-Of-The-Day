<head>
    <link rel="stylesheet" href="style.css"> 
</head>
<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if (!isLoggedIn()) {
    header("Location: " . BASE_URL . "/public/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT r.*, c.name as category_name, f.created_at as fav_date
    FROM favorites f
    JOIN recipes r ON r.id = f.recipe_id
    LEFT JOIN categories c ON c.id = r.category_id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
$stmt->execute([$userId]);
$myRecipes = $stmt->fetchAll();

require_once __DIR__ . '/../public/partials/header.php';
?>

<section class="cookbook-hero">
    <div class="container">
        <h1><i class="fa fa-book"></i> My Personal Cookbook</h1>
        <p>Here are the recipes you love and saved for later cooking!</p>
    </div>
</section>

<section class="cookbook-content">
    <div class="container">
        
        <?php if ($myRecipes): ?>
            <div class="cookbook-grid">
                <?php foreach ($myRecipes as $recipe): ?>
                    <div class="recipe-card">
                        
                        <div class="card-image">
                            <?php if(!empty($recipe['image'])): ?>
                                <img src="<?= BASE_URL ?>/uploads/recipes/<?= $recipe['image'] ?>" alt="<?= htmlspecialchars($recipe['title']) ?>">
                            <?php else: ?>
                                <img src="https://placehold.co/400x300?text=No+Image" alt="No Image">
                            <?php endif; ?>
                            <span class="card-cat"><?= htmlspecialchars($recipe['category_name']) ?></span>
                        </div>

                        <div class="card-body">
                            <h3>
                                <a href="<?= BASE_URL ?>/public/recipe.php?id=<?= $recipe['id'] ?>">
                                    <?= htmlspecialchars($recipe['title']) ?>
                                </a>
                            </h3>
                            <div class="card-meta">
                                <span><i class="fa fa-clock-o"></i> <?= $recipe['prep_time'] ?> min</span>
                                <span><i class="fa fa-signal"></i> 
                                    <?= match($recipe['difficulty']){ 1=>'Easy', 2=>'Medium', 3=>'Hard', default=>'-' } ?>
                                </span>
                            </div>
                            <p class="card-desc">
                                <?= mb_strimwidth(htmlspecialchars($recipe['description']), 0, 80, '...') ?>
                            </p>
                            
                            <div class="card-actions">
                                <a href="<?= BASE_URL ?>/public/recipe.php?id=<?= $recipe['id'] ?>" class="btn-view">
                                    View Recipe
                                </a>
                                
                                <form action="favorites_remove.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this recipe?');">
                                    <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                                    <button type="submit" class="btn-remove" title="Remove from favorites">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa fa-folder-open-o"></i>
                <h3>Your cookbook is empty!</h3>
                <p>You haven't saved any recipes yet. Explore our delicious collection.</p>
                <a href="<?= BASE_URL ?>/public/all_recipes.php" class="btn btn-green">Browse Recipes</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../public/partials/footer.php'; ?>