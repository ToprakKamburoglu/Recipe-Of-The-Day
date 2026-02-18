<?php
$searchQuery = trim($_GET['q'] ?? '');
$categoryId  = $_GET['category'] ?? '';

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$categoryNames = array_map(function($c) { return $c['name']; }, $categories);
$categoryString = implode(' , ', array_slice($categoryNames, 0, 8));
?>

<section class="search-bar-section">
    <div class="container">
        <div class="search-container">
            
            <form method="GET" class="main-search-form">
                <input type="text" name="q" placeholder="Search for recipe..." value="<?= htmlspecialchars($searchQuery) ?>" class="search-input">
                
                <button type="submit" class="search-btn" aria-label="Search">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <div class="search-tags">
                <span class="tags-label">Recipe Types:</span>
                <span class="tags-list"><?= $categoryString ?>...</span>
            </div>

        </div>
    </div>
</section>