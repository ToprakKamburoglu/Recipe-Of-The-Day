<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if (!isLoggedIn()) {
    header("Location: " . BASE_URL . "/public/login.php");
    exit;
}

$userId = currentUserId(); 

$stmt = $pdo->prepare("
    SELECT r.*, c.name as category_name, f.added_at as fav_date
    FROM favorites f
    JOIN recipes r ON r.id = f.recipe_id
    LEFT JOIN categories c ON c.id = r.category_id
    WHERE f.user_id = ?
    ORDER BY f.added_at DESC
");
$stmt->execute([$userId]);
$myRecipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cookbook - Recipe of the Day</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .cookbook-hero {
        background: linear-gradient(135deg, #79a206 0%, #5e7f04 100%);
        padding: 60px 20px;
        text-align: center;
        color: white;
        margin-bottom: 40px;
    }

    .cookbook-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .cookbook-hero p {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .cookbook-content {
        padding: 0 0 60px;
    }

    .cookbook-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    .recipe-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }

    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .card-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .recipe-card:hover .card-image img {
        transform: scale(1.1);
    }

    .card-cat {
        position: absolute;
        left: 12px;
        background: rgba(121, 162, 6, 0.95);
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .card-body {
        padding: 20px;
    }

    .card-body h3 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        min-height: 50px;
    }

    .card-body h3 a {
        color: #2c3e50;
        text-decoration: none;
        font-weight: 700;
    }

    .card-body h3 a:hover {
        color: #79a206;
    }

    .card-meta {
        display: flex;
        gap: 15px;
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 12px;
    }

    .card-meta i {
        color: #79a206;
    }

    .card-desc {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        min-height: 40px;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-view {
        flex: 1;
        background: #79a206;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-view:hover {
        background: #5e7f04;
        color: white;
    }

    .btn-remove {
        background: #e74c3c;
        color: white;
        border: none;
        width: 40px;
        height: 36px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-remove:hover {
        background: #c0392b;
        transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 80px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 1.1rem;
        color: #7f8c8d;
        margin-bottom: 25px;
    }

    .btn-green {
        background: #79a206;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-green:hover {
        background: #5e7f04;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 1200px) {
        .cookbook-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .cookbook-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .cookbook-hero h1 {
            font-size: 2rem;
        }
        
        .card-image {
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .cookbook-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../public/partials/header.php'; ?>

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
                                <img src="<?= BASE_URL ?>/<?= $recipe['image'] ?>">
                            <?php else: ?>
                                <img src="https://placehold.co/400x200?text=No+Image" alt="No Image">
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

</body>
</html>