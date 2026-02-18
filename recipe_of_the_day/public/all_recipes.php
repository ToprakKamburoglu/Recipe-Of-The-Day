<head>
    <link rel="stylesheet" href="style.css">
    <style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 30px 0;
        gap: 5px;
    }

    .page-item {
        display: inline-block;
    }

    .page-link {
        display: block;
        padding: 8px 14px;
        background: white;
        border: 1px solid #ddd;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .page-link:hover {
        background: #79a206;
        color: white;
        border-color: #79a206;
    }

    .page-item.active .page-link {
        background: #79a206;
        color: white;
        border-color: #79a206;
    }

    .page-item.disabled .page-link {
        background: #f8f9fa;
        color: #999;
        cursor: not-allowed;
        pointer-events: none;
    }

    .recipe-search-section,
    .search-results-section {
        max-width: 100% !important;
    }
    </style>
</head>
<?php 
require_once "partials/header.php"; 

$searchQuery  = trim($_GET['q'] ?? '');
$ingredient   = trim($_GET['ingredient'] ?? '');
$categoryId   = $_GET['category'] ?? '';
$difficulty   = $_GET['difficulty'] ?? '';
$minStars     = $_GET['stars'] ?? '';

$limit = 4;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$countSql = "
    SELECT COUNT(DISTINCT r.id) as total
    FROM recipes r
    JOIN categories c ON c.id = r.category_id
    LEFT JOIN favorites f ON f.recipe_id = r.id
    WHERE 1=1
";

$countParams = [];

if ($searchQuery !== '') {
    $countSql .= " AND r.title LIKE ? ";
    $countParams[] = '%' . $searchQuery . '%';
}

if ($ingredient !== '') {
    $countSql .= " AND r.ingredients LIKE ? ";
    $countParams[] = '%' . $ingredient . '%';
}

if ($categoryId !== '') {
    $countSql .= " AND r.category_id = ? ";
    $countParams[] = $categoryId;
}

if ($difficulty !== '') {
    $countSql .= " AND r.difficulty = ? ";
    $countParams[] = $difficulty;
}

if ($minStars !== '') {
    $countSql .= " GROUP BY r.id HAVING COUNT(f.recipe_id) >= ? ";
    $countParams[] = $minStars;
}

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);

if ($minStars !== '') {
    $total_rows = $countStmt->rowCount();
} else {
    $total_rows = $countStmt->fetch()['total'];
}

$total_pages = ceil($total_rows / $limit);

$sql = "
    SELECT
        r.id,
        r.title,
        r.ingredients,
        r.difficulty,
        r.created_at,
        c.name AS category,
        COUNT(f.recipe_id) AS favorite_count
    FROM recipes r
    JOIN categories c ON c.id = r.category_id
    LEFT JOIN favorites f ON f.recipe_id = r.id
    WHERE 1=1
";

$params = [];

if ($searchQuery !== '') {
    $sql .= " AND r.title LIKE ? ";
    $params[] = '%' . $searchQuery . '%';
}

if ($ingredient !== '') {
    $sql .= " AND r.ingredients LIKE ? ";
    $params[] = '%' . $ingredient . '%';
}

if ($categoryId !== '') {
    $sql .= " AND r.category_id = ? ";
    $params[] = $categoryId;
}

if ($difficulty !== '') {
    $sql .= " AND r.difficulty = ? ";
    $params[] = $difficulty;
}

$sql .= " GROUP BY r.id ";

if ($minStars !== '') {
    $sql .= " HAVING favorite_count >= ? ";
    $params[] = $minStars;
}

$sql .= " ORDER BY favorite_count DESC, r.created_at DESC LIMIT ? OFFSET ? ";
$params[] = $limit;
$params[] = $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll();

function difficultyLabel($level) {
    return match ((int)$level) {
        1 => 'Easy',
        2 => 'Medium',
        3 => 'Hard',
        default => '-'
    };
}

$queryParams = http_build_query([
    'q' => $searchQuery,
    'ingredient' => $ingredient,
    'category' => $categoryId,
    'difficulty' => $difficulty,
    'stars' => $minStars
]);
?>

<div class="container">
    <section class="recipe-search-section">
        <h2 class="section-title">Filter Recipes</h2>

        <form method="GET" class="search-form">
            
            <div class="form-group">
                <label>Recipe Name</label>
                <input type="text" name="q" 
                       value="<?= htmlspecialchars($searchQuery) ?>" 
                       class="form-input" 
                       placeholder="e.g. Pasta">
            </div>

            <div class="form-group">
                <label>Ingredient</label>
                <input type="text" name="ingredient" 
                       value="<?= htmlspecialchars($ingredient) ?>" 
                       class="form-input" 
                       placeholder="e.g. Chicken">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $categoryId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Difficulty</label>
                <select name="difficulty" class="form-select">
                    <option value="">Any Difficulty</option>
                    <option value="1" <?= $difficulty=='1'?'selected':'' ?>>Easy</option>
                    <option value="2" <?= $difficulty=='2'?'selected':'' ?>>Medium</option>
                    <option value="3" <?= $difficulty=='3'?'selected':'' ?>>Hard</option>
                </select>
            </div>

            <div class="form-group">
                <label>Min Favorites</label>
                <select name="stars" class="form-select">
                    <option value="">Any Rating</option>
                    <option value="1" <?= $minStars=='1'?'selected':'' ?>>1+ Favorites</option>
                    <option value="2" <?= $minStars=='2'?'selected':'' ?>>2+ Favorites</option>
                    <option value="5" <?= $minStars=='5'?'selected':'' ?>>5+ Favorites</option>
                    <option value="10" <?= $minStars=='10'?'selected':'' ?>>10+ Favorites</option>
                </select>
            </div>

            <div class="form-group" style="justify-content: flex-end;">
                <button type="submit" class="btn btn-secondary">
                    <i class="fa fa-filter"></i> Filter
                </button>
            </div>

        </form>
    </section>

    <section class="search-results-section">
        <h2 class="section-title">Results: <?= $total_rows ?> <?= $total_pages > 1 ? "(Page $page of $total_pages)" : '' ?></h2>

        <?php if ($recipes): ?>
            <div class="table-responsive">
                <table class="recipes-table">
                    <thead>
                        <tr>
                            <th width="35%">Recipe Name</th>
                            <th width="20%">Category</th>
                            <th width="15%">Difficulty</th>
                            <th width="15%">Favorites</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recipes as $r): ?>
                            <tr>
                                <td style="font-weight: bold; color: #333;">
                                    <?= htmlspecialchars($r['title']) ?>
                                </td>
                                
                                <td style="color: #79a206; font-weight: 600;">
                                    <?= htmlspecialchars($r['category']) ?>
                                </td>
                                
                                <td>
                                    <?php 
                                        $diffColor = match($r['difficulty']) {
                                            1 => '#02e00e',
                                            2 => '#00c20a',
                                            3 => '#004f04',
                                            default => '#666'
                                        };
                                    ?>
                                    <span style="color: <?= $diffColor ?>; font-weight: bold;">
                                        <?= difficultyLabel($r['difficulty']) ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <i class="fa fa-heart" style="color: #ff4400;"></i> <?= $r['favorite_count'] ?>
                                </td>
                                
                                <td>
                                    <a href="recipe.php?id=<?= $r['id'] ?>" class="btn-small">
                                        View Recipe
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= $queryParams ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= $queryParams ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= $queryParams ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="info-text">
                <i class="fa fa-search" style="font-size: 40px; color: #ccc; margin-bottom: 10px; display: block;"></i>
                No recipes found matching your criteria.
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once "partials/footer.php"; ?>