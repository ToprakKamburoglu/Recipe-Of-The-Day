<?php
require_once __DIR__ . '/../config/auth.php';
requireRole([1, 2]);
?>

<?php require_once "header.php"; ?>

<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 157, 99, 0.15) !important;
}

a:hover .hover-card {
    background-color: #f8f9fa !important;
}
</style>

<div class="row g-3 my-2"> 
    <div class="col-12 col-sm-6 col-md-3">
        <a href="users/index.php" class="text-decoration-none text-dark">
            <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success hover-card">
                <div>
                    <h3 class="fs-2 fw-bold">
                        <?php echo $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?>
                    </h3>
                    <p class="fs-5 text-muted mb-0">Total Users</p>
                </div>
                <i class="fas fa-users fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <a href="recipes/index.php" class="text-decoration-none text-dark">
            <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success hover-card">
                <div>
                    <h3 class="fs-2 fw-bold">
                        <?php echo $pdo->query("SELECT COUNT(*) FROM recipes")->fetchColumn(); ?>
                    </h3>
                    <p class="fs-5 text-muted mb-0">Total Recipes</p>
                </div>
                <i class="fas fa-utensils fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <a href="categories/index.php" class="text-decoration-none text-dark">
            <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success hover-card">
                <div>
                    <h3 class="fs-2 fw-bold">
                        <?php echo $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(); ?>
                    </h3>
                    <p class="fs-5 text-muted mb-0">Categories</p>
                </div>
                <i class="fas fa-tags fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success">
            <div>
                <h3 class="fs-2 fw-bold">
                    <?php echo $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn(); ?>
                </h3>
                <p class="fs-5 text-muted mb-0">Total Comments</p>
            </div>
            <i class="fas fa-comments fs-1 primary-text border rounded-full secondary-bg p-3"></i>
        </div>
    </div>

</div>

<div class="row g-3 my-2">
    <div class="col-12 col-sm-6 col-md-3">
        <a href="messages.php" class="text-decoration-none text-dark">
            <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success hover-card">
                <div>
                    <h3 class="fs-2 fw-bold">
                        <?php echo $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn(); ?>
                    </h3>
                    <p class="fs-5 text-muted mb-0">Total Messages</p>
                </div>
                <i class="fas fa-envelope fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success">
            <div>
                <h3 class="fs-2 fw-bold">
                    <?php echo $pdo->query("SELECT COUNT(*) FROM favorites")->fetchColumn(); ?>
                </h3>
                <p class="fs-5 text-muted mb-0">Total Favorites</p>
            </div>
            <i class="fas fa-heart fs-1 primary-text border rounded-full secondary-bg p-3"></i>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="p-4 bg-white shadow-sm d-flex justify-content-between align-items-center rounded h-100 border-start border-5 border-success">
            <div>
                <h3 class="fs-2 fw-bold">
                    <?php 
                    $avgRating = $pdo->query("SELECT AVG(rating) FROM comments WHERE rating IS NOT NULL")->fetchColumn();
                    echo $avgRating ? number_format($avgRating, 1) : '0.0';
                    ?>
                </h3>
                <p class="fs-5 text-muted mb-0">Avg Rating</p>
            </div>
            <i class="fas fa-star fs-1 primary-text border rounded-full secondary-bg p-3"></i>
        </div>
    </div>
    

</div>
<div class="row g-4 my-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-star me-2 primary-text"></i>Top Rated Recipes</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php
                    $topRecipes = $pdo->query("
                        SELECT r.id, r.title, AVG(c.rating) as avg_rating, COUNT(c.id) as comment_count
                        FROM recipes r
                        LEFT JOIN comments c ON r.id = c.recipe_id
                        GROUP BY r.id
                        ORDER BY avg_rating DESC
                        LIMIT 5
                    ")->fetchAll();
                    
                    foreach($topRecipes as $recipe): 
                        $rating = $recipe['avg_rating'] ? round($recipe['avg_rating'], 1) : 0;
                    ?>
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-utensils text-muted me-2"></i>
                                <?= htmlspecialchars($recipe['title']) ?>
                            </span>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> <?= $rating ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-heart me-2 text-danger"></i>Most Favorited Recipes</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php
                    $favRecipes = $pdo->query("
                        SELECT r.id, r.title, COUNT(f.user_id) as fav_count
                        FROM recipes r
                        LEFT JOIN favorites f ON r.id = f.recipe_id
                        GROUP BY r.id
                        ORDER BY fav_count DESC
                        LIMIT 5
                    ")->fetchAll();
                    
                    foreach($favRecipes as $recipe): ?>
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-utensils text-muted me-2"></i>
                                <?= htmlspecialchars($recipe['title']) ?>
                            </span>
                            <span class="badge bg-danger">
                                <i class="fas fa-heart"></i> <?= $recipe['fav_count'] ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 my-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-clock me-2 primary-text"></i>Recent Recipes</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php
                    $recent = $pdo->query("SELECT id, title, created_at FROM recipes ORDER BY created_at DESC LIMIT 5")->fetchAll();
                    foreach($recent as $r): ?>
                        <li class="mb-2">
                            <i class="fas fa-utensils text-muted me-2"></i>
                            <a href="recipes/update.php?id=<?= $r['id'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($r['title']) ?>
                            </a>
                            <small class="text-muted ms-2">(<?= date('M d, H:i', strtotime($r['created_at'])) ?>)</small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2 primary-text"></i>Recent Users</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php
                    $users = $pdo->query("SELECT id, username, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
                    foreach($users as $u): ?>
                        <li class="mb-2">
                            <i class="fas fa-user text-muted me-2"></i>
                            <a href="users/update.php?id=<?= $u['id'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($u['username']) ?>
                            </a>
                            <small class="text-muted ms-2">(<?= date('M d, H:i', strtotime($u['created_at'])) ?>)</small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-envelope me-2 primary-text"></i>Recent Messages</h5>
                <a href="messages.php" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentMsgs = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
                            
                            if (count($recentMsgs) > 0):
                                foreach($recentMsgs as $msg): ?>
                                    <tr>
                                        <td class="text-muted small" style="width: 150px;">
                                            <?= date('M d, H:i', strtotime($msg['created_at'])) ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($msg['name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($msg['email']) ?></div>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(mb_strimwidth($msg['subject'], 0, 40, '...')) ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="messages.php" class="btn btn-sm btn-light border">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; 
                            else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No messages found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 primary-text"></i>Recipes by Category</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $catStats = $pdo->query("
                        SELECT c.name, COUNT(r.id) as recipe_count
                        FROM categories c
                        LEFT JOIN recipes r ON c.id = r.category_id
                        GROUP BY c.id
                        ORDER BY recipe_count DESC
                    ")->fetchAll();
                    
                    foreach($catStats as $cat): ?>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <span class="fw-bold"><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="badge bg-success"><?= $cat['recipe_count'] ?> recipes</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>