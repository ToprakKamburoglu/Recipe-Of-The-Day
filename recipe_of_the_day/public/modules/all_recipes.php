<?php
$isSearch = !empty($_GET['q']) || !empty($_GET['category']);
$recipes = [];

if ($isSearch) {
    $sql = "SELECT r.id, r.title, r.created_at, r.image, r.description, c.name AS category 
            FROM recipes r JOIN categories c ON c.id = r.category_id WHERE 1=1";
    $params = [];
    
    if (!empty($_GET['q'])) { 
        $sql .= " AND (r.title LIKE ? OR c.name LIKE ?)"; 
        $params[] = '%'.$_GET['q'].'%';
        $params[] = '%'.$_GET['q'].'%';
    }
    
    if (!empty($_GET['category'])) { 
        $sql .= " AND r.category_id = ?"; 
        $params[] = $_GET['category']; 
    }
    
    $sql .= " ORDER BY r.created_at DESC LIMIT 16";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll();
    $sectionTitle = "Search Results";
}

 else {
    $stmt = $pdo->query("
        SELECT r.id, r.title, r.created_at, r.image, r.description, c.name AS category
        FROM recipes r
        JOIN categories c ON c.id = r.category_id
        ORDER BY r.created_at DESC LIMIT 8
    ");
    $recipes = $stmt->fetchAll();
    $sectionTitle = "What's <span class='highlight'>Hot</span>";
}
?>

<style>
.recipe-list-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-top: 30px;
}

.recipe-list-item {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.recipe-list-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}

.list-img {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.list-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.recipe-list-item:hover .list-img img {
    transform: scale(1.1);
}

.list-info {
    padding: 20px;
}

.list-info h4 {
    margin-bottom: 10px;
    font-size: 18px;
}

.list-info h4 a {
    color: #2a6496;
    text-decoration: none;
    font-weight: 600;
}

.list-info h4 a:hover {
    color: #79a206;
}

.list-meta {
    font-size: 12px;
    color: #999;
    margin-bottom: 12px;
}

.cat-tag {
    color: #79a206;
    font-weight: bold;
}

.list-info p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

@media (max-width: 1200px) {
    .recipe-list-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .recipe-list-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .list-img {
        height: 150px;
    }
}

@media (max-width: 480px) {
    .recipe-list-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="all-recipes-section">
    <div class="container">
        <h3 class="section-heading"><?= $sectionTitle ?></h3>
        <div class="recipe-list-grid">
            <?php if ($recipes): ?>
                <?php foreach ($recipes as $r): ?>
                    <div class="recipe-list-item">
                        <div class="list-img">
                            <img src="<?= !empty($r['image']) ? BASE_URL.'/'.$r['image'] : 'https://placehold.co/150' ?>" alt="<?= htmlspecialchars($r['title']) ?>">
                        </div>
                        <div class="list-info">
                            <h4>
                                <a href="<?= BASE_URL ?>/public/recipe.php?id=<?= $r['id'] ?>">
                                    <?= htmlspecialchars($r['title']) ?>
                                </a>
                            </h4>
                            <div class="list-meta">
                                <span class="cat-tag"><?= htmlspecialchars($r['category']) ?></span> | 
                                <span class="date-tag"><?= date('F d, Y', strtotime($r['created_at'])) ?></span>
                            </div>
                            <p><?= mb_strimwidth(htmlspecialchars($r['description']), 0, 120, '...') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted py-5">No recipes found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>