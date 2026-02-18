<?php
$stmt = $pdo->prepare("
    SELECT r.*, c.name as category_name,
    (SELECT COUNT(*) FROM favorites f WHERE f.recipe_id = r.id) as fav_count
    FROM daily_recipe d
    JOIN recipes r ON r.id = d.recipe_id
    LEFT JOIN categories c ON c.id = r.category_id
    WHERE d.date = CURDATE()
    LIMIT 1
");
$stmt->execute();
$dailyRecipe = $stmt->fetch();
?>

<style>
.featured-section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 30px;
    align-items: stretch;
}

.featured-content,
.explore-recipes {
    height: 100%;
}

.explore-recipes {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: #fff;
    border: 1px solid #eee;
    padding: 30px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.explore-recipes h3 {
    font-family: 'Lobster', cursive;
    font-size: 28px;
    color: #444;
    margin-bottom: 15px;
}

.explore-recipes .highlight {
    color: #79a206;
}

.explore-recipes p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
    max-width: 350px;
}

.explore-icon {
    font-size: 60px;
    color: #79a206;
    margin-bottom: 20px;
}

@media (max-width: 992px) {
    .featured-section-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}
</style>

<section class="featured-section">
    <div class="container">
        
        <div class="featured-header">
            <h2 class="featured-title">Today's <span class="highlight">recipe</span></h2>
            <p class="featured-subtitle">Sliding recipes are much more tasty as food than sliding images.</p>
        </div>

        <div class="featured-section-grid">
            
            <div>
                <?php if ($dailyRecipe): ?>
                    <div class="featured-content">
                        <div class="featured-image">
                            <?php if(!empty($dailyRecipe['image'])): ?>
                                 <img src="<?= BASE_URL ?>/<?= $dailyRecipe['image'] ?>" alt="<?= htmlspecialchars($dailyRecipe['title']) ?>">
                            <?php else: ?>
                                <img src="https://placehold.co/600x400?text=No+Image" alt="No Image">
                            <?php endif; ?>
                        </div>

                        <div class="featured-info">
                            <h3 class="daily-title">
                                <a href="<?= BASE_URL ?>/public/recipe.php?id=<?= $dailyRecipe['id'] ?>">
                                    <?= htmlspecialchars($dailyRecipe['title']) ?>
                                </a>
                            </h3>
                            
                            <div class="rating-box">
                                <span class="stars">
                                    <?php 
                                    $stars = min(5, max(1, round($dailyRecipe['fav_count'] / 2))); 
                                    for($i=0; $i<5; $i++) {
                                        echo $i < $stars ? '<i class="fa fa-heart"></i>' : '<i class="fa fa-heart-o"></i>';
                                    }
                                    ?>
                                </span>
                                <span class="rating-text">Favorite Count: <?= $dailyRecipe['fav_count'] ?></span>
                            </div>

                            <div class="daily-desc">
                                <?= mb_strimwidth(htmlspecialchars($dailyRecipe['description']), 0, 250, '...') ?>
                            </div>

                            <a href="<?= BASE_URL ?>/public/recipe.php?id=<?= $dailyRecipe['id'] ?>" class="btn-green">Read more</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-recipe">
                        <p>No recipe selected for today.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="explore-recipes">
                <i class="fa fa-book explore-icon"></i>
                <h3>Explore All <span class="highlight">Recipes</span></h3>
                <p>
                    Discover our complete collection of delicious recipes. Browse through hundreds of dishes and find your next favorite meal!
                </p>
                <a href="<?= BASE_URL ?>/public/all_recipes.php" class="btn-green">View All Recipes</a>
            </div>

        </div>
    </div>
</section>