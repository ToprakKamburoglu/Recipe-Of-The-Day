<head>
    <link rel="stylesheet" href="style.css"> 
</head>
<?php require_once "partials/header.php"; ?>

<?php
$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    echo "<div class='container' style='padding:50px; text-align:center;'>Recipe not found.</div>";
    require_once "partials/footer.php";
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.*, c.name AS category_name 
    FROM recipes r 
    LEFT JOIN categories c ON c.id = r.category_id 
    WHERE r.id = ?
");
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    echo "<div class='container' style='padding:50px; text-align:center;'>Recipe not found.</div>";
    require_once "partials/footer.php";
    exit;
}

$stmtComments = $pdo->prepare("
    SELECT cm.*, u.username 
    FROM comments cm 
    JOIN users u ON u.id = cm.user_id 
    WHERE cm.recipe_id = ? 
    ORDER BY cm.created_at DESC
");
$stmtComments->execute([$id]);
$comments = $stmtComments->fetchAll();

$ingredientsList = array_filter(explode("\n", $recipe['ingredients'])); 
$stepsList       = array_filter(explode("\n", $recipe['steps']));
?>

<?php 
$isFavorited = false;

if (isLoggedIn()) {
    $favStmt = $pdo->prepare("
        SELECT 1
        FROM favorites
        WHERE user_id = ? AND recipe_id = ?
        LIMIT 1
    ");
    $favStmt->execute([currentUserId(), $recipe['id']]);
    $isFavorited = (bool)$favStmt->fetch();
}
?>

<style>
.recipe-detail-section {
    padding: 40px 0;
}

.recipe-detail-card {
    max-width: 1100px;
    margin: 0 auto;
}

.recipe-header {
    text-align: center;
    margin-bottom: 30px;
}

.recipe-cat-tag {
    display: inline-block;
    background: #79a206;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 15px;
}

.recipe-main-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
}

.recipe-meta {
    color: #7f8c8d;
    font-size: 14px;
}

.meta-divider {
    margin: 0 10px;
}

.recipe-hero-image {
    margin-bottom: 30px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.recipe-hero-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.recipe-description {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    border-left: 4px solid #79a206;
}

.recipe-description p {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    margin: 0;
}

.recipe-divider {
    border: 0;
    border-top: 1px solid #eee;
    margin: 30px 0;
}

.recipe-body-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.recipe-ingredients,
.recipe-steps {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.recipe-ingredients h3,
.recipe-steps h3 {
    font-size: 1.4rem;
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #79a206;
}

.ingredient-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ingredient-list li {
    padding: 10px 0;
    border-bottom: 1px solid #ecf0f1;
    font-size: 15px;
    color: #555;
}

.ingredient-list li i {
    color: #79a206;
    margin-right: 10px;
}

.step-item {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.step-number {
    flex-shrink: 0;
    width: 35px;
    height: 35px;
    background: #79a206;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}

.step-text {
    flex: 1;
    font-size: 15px;
    line-height: 1.7;
    color: #555;
    padding-top: 5px;
}

.recipe-actions {
    text-align: center;
    margin-bottom: 40px;
}

.btn-favorite-large {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 14px 35px;
    font-size: 16px;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(231,76,60,0.3);
    transition: all 0.3s;
}

.btn-favorite-large:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231,76,60,0.4);
}

.btn-danger {
    background: #95a5a6 !important;
}

.btn-danger:hover {
    background: #7f8c8d !important;
}

.login-alert {
    background: #fff3cd;
    color: #856404;
    padding: 15px 25px;
    border-radius: 8px;
    display: inline-block;
}

.login-alert a {
    color: #79a206;
    font-weight: 600;
}

.comments-section {
    max-width: 900px;
    margin: 0 auto;
    margin-top: 30px;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eee;
}

.comments-title {
    font-size: 1.6rem;
    color: #2c3e50;
    margin-bottom: 25px;
    padding-bottom: 15px;
}

.comment-form-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.comment-form-box h4 {
    font-size: 1.1rem;
    margin-bottom: 15px;
    color: #2c3e50;
}

.comment-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    min-height: 100px;
    resize: vertical;
}

.comment-textarea:focus {
    border-color: #79a206;
    outline: none;
}

.rating-select .form-select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.btn-comment {
    background: #79a206;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 10px;
}

.btn-comment:hover {
    background: #5e7f04;
}

.login-warning {
    background: #fff3cd;
    color: #856404;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.login-warning a {
    color: #79a206;
    font-weight: 600;
}

.comment-item {
    display: flex;
    gap: 15px;
    padding: 20px 0;
    border-bottom: 1px solid #ecf0f1;
}

.comment-avatar {
    flex-shrink: 0;
    width: 45px;
    height: 45px;
    background: #79a206;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.comment-user {
    font-weight: 700;
    color: #2c3e50;
    margin-right: 10px;
}

.comment-date {
    color: #95a5a6;
    font-size: 13px;
}

.comment-text {
    color: #555;
    line-height: 1.6;
    margin: 0;
}

.no-comments {
    text-align: center;
    color: #95a5a6;
    padding: 30px;
}

@media (max-width: 768px) {
    .recipe-body-grid {
        grid-template-columns: 1fr;
    }
    
    .recipe-main-title {
        font-size: 1.8rem;
    }
    
    .recipe-hero-image img {
        height: 300px;
    }
}
</style>

<div class="recipe-detail-section">
    <div class="container">
        
        <div class="recipe-detail-card">
            
            <div class="recipe-header">
                <span class="recipe-cat-tag"><?= htmlspecialchars($recipe['category_name']) ?></span>
                <h1 class="recipe-main-title"><?= htmlspecialchars($recipe['title']) ?></h1>
                <div class="recipe-meta">
                    <span><i class="fa fa-clock-o"></i> <?= $recipe['prep_time'] ?> mins</span>
                    <span class="meta-divider">|</span>
                    <span><i class="fa fa-signal"></i> 
                        <?php 
                        echo match($recipe['difficulty']) {
                            1 => 'Easy',
                            2 => 'Medium',
                            3 => 'Hard',
                            default => 'Medium'
                        }; 
                        ?>
                    </span>
                    <span class="meta-divider">|</span>
                    <span><i class="fa fa-calendar"></i> <?= date('F d, Y', strtotime($recipe['created_at'])) ?></span>
                </div>
            </div>

            <div class="recipe-hero-image">
                <?php if(!empty($recipe['image'])): ?>
                    <img src="<?= BASE_URL ?>/<?= $recipe['image'] ?>" alt="<?= htmlspecialchars($recipe['title']) ?>">
                <?php else: ?>
                    <img src="https://placehold.co/800x400?text=No+Image" alt="No Image">
                <?php endif; ?>
            </div>

            <div class="recipe-description">
                <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
            </div>

            <hr class="recipe-divider">

            <div class="recipe-body-grid">
                
                <div class="recipe-ingredients">
                    <h3><i class="fa fa-shopping-basket"></i> Ingredients</h3>
                    <ul class="ingredient-list">
                        <?php foreach ($ingredientsList as $ing): ?>
                            <li>
                                <i class="fa fa-check-circle"></i> 
                                <?= htmlspecialchars(trim($ing)) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="recipe-steps">
                    <h3><i class="fa fa-list-ol"></i> Steps</h3>
                    <div class="steps-list">
                        <?php $stepCount = 1; foreach ($stepsList as $step): ?>
                            <div class="step-item">
                                <div class="step-number"><?= $stepCount++ ?></div>
                                <div class="step-text"><?= htmlspecialchars(trim($step)) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div class="recipe-actions">
                <?php if (isLoggedIn()): ?>

                    <?php if ($isFavorited): ?>
                        <form method="POST" action="<?= BASE_URL ?>/user/favorites_remove.php">
                            <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                            <button class="btn btn-danger btn-favorite-large">
                                <i class="fa fa-heart"></i> Remove from Favorites
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>/user/favorites_add.php">
                            <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                            <button class="btn btn-favorite-large">
                                <i class="fa fa-heart"></i> Add to Favorites
                            </button>
                        </form>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="login-alert">
                        <i class="fa fa-info-circle"></i>
                        Please <a href="<?= BASE_URL ?>/public/login/login.php">login</a> to add favorites.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="comments-section">
            <h3 class="comments-title">Comments (<?= count($comments) ?>)</h3>

            <?php if (isLoggedIn()): ?>
                <div class="comment-form-box">
                    <h4>Leave a Reply</h4>
                    <form method="POST" action="<?= BASE_URL ?>/user/comment_add.php">
                        <textarea
                            name="comment"
                            class="comment-textarea"
                            placeholder="Write your thought about this recipe..."
                            required></textarea>

                        <div class="rating-select" style="margin: 15px 0;">
                            <label style="font-size:14px; margin-bottom:8px; display:block; font-weight:600;">Your Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Select rating</option>
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★☆ (4)</option>
                                <option value="3">★★★☆☆ (3)</option>
                                <option value="2">★★☆☆☆ (2)</option>
                                <option value="1">★☆☆☆☆ (1)</option>
                            </select>
                        </div>

                        <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">

                        <button class="btn btn-comment">Post Comment</button>
                    </form>

                </div>
            <?php else: ?>
                <p class="login-warning">Please <a href="<?= BASE_URL ?>/public/login.php">login</a> to leave a comment.</p>
            <?php endif; ?>

            <div class="comments-list">
                <?php if ($comments): ?>
                    <?php foreach ($comments as $cm): ?>
                        <div class="comment-item">
                            <div class="comment-avatar">
                                <i class="fa fa-user"></i>
                            </div>
                            
                            <div class="comment-content">
                                <div class="comment-header">
                                    <div class="user-meta">
                                        <span class="comment-user"><?= htmlspecialchars($cm['username']) ?></span>
                                        <span class="comment-date"><?= date('d M Y, H:i', strtotime($cm['created_at'])) ?></span>
                                    </div>

                                    <?php if (!empty($cm['rating'])): ?>
                                        <div class="user-rating" style="color: #ffc107; font-size: 13px;">
                                            <?php 
                                            for($i=0; $i<5; $i++) {
                                                echo $i < $cm['rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php 
                                    $commentBody = $cm['TEXT'] ?? $cm['text'] ?? '';
                                ?>
                                <p class="comment-text"><?= nl2br(htmlspecialchars($commentBody)) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-comments">No comments yet. Be the first!</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once "partials/footer.php"; ?>