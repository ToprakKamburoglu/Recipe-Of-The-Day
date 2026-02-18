<?php 

require_once __DIR__ . '/../../config/auth.php';
requireRole([1, 2]);

require_once "../header.php"; 

// --- AYARLAR ---
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'DESC';

// Whitelist: SQL'de kullandığımız takma adları (alias) veya gerçek sütun adlarını buraya yazıyoruz
$allowed_columns = ['id', 'title', 'category_name', 'prep_time', 'difficulty'];
if (!in_array($sort, $allowed_columns)) {
    $sort = 'id';
}
$order = ($order === 'ASC') ? 'ASC' : 'DESC';
$next_order = ($order === 'ASC') ? 'DESC' : 'ASC';

// --- VERİ ÇEKME ---
$total_stmt = $pdo->query("SELECT COUNT(*) FROM recipes");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Sıralama sorgusunda c.name'i 'category_name' olarak alias yaptık
$sql = "SELECT r.*, c.name AS category_name
        FROM recipes r
        JOIN categories c ON c.id = r.category_id
        ORDER BY $sort $order 
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recipes = $stmt->fetchAll();

function sortIcon($column, $current_sort, $current_order) {
    if ($column !== $current_sort) return '<i class="fas fa-sort text-muted opacity-25 ms-1"></i>';
    return $current_order === 'ASC' ? '<i class="fas fa-sort-up text-primary ms-1"></i>' : '<i class="fas fa-sort-down text-primary ms-1"></i>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
    <i class="fas fa-utensils me-2 primary-text"></i> Recipes
</h1>
    <a href="create.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Add Recipe</a>
</div>

<div class="bg-white p-4 rounded shadow-sm border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th scope="col" class="py-3 ps-3">
                        <a href="?page=<?= $page ?>&sort=id&order=<?= $next_order ?>" class="text-decoration-none text-dark">ID <?= sortIcon('id', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=title&order=<?= $next_order ?>" class="text-decoration-none text-dark">Title <?= sortIcon('title', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=category_name&order=<?= $next_order ?>" class="text-decoration-none text-dark">Category <?= sortIcon('category_name', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=prep_time&order=<?= $next_order ?>" class="text-decoration-none text-dark">Prep Time <?= sortIcon('prep_time', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=difficulty&order=<?= $next_order ?>" class="text-decoration-none text-dark">Difficulty <?= sortIcon('difficulty', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3 text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($recipes) == 0): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No recipes found.</td></tr>
                <?php else: foreach ($recipes as $r): 
                    $diffBadge = 'bg-secondary'; $diffText = $r['difficulty'];
                    if($r['difficulty'] == 1) { $diffBadge = 'bg-success'; $diffText = 'Easy'; }
                    elseif($r['difficulty'] == 2) { $diffBadge = 'bg-warning text-dark'; $diffText = 'Medium'; }
                    elseif($r['difficulty'] >= 3) { $diffBadge = 'bg-danger'; $diffText = 'Hard'; }
                ?>
                <tr>
                    <td class="ps-3 fw-bold text-muted">#<?= $r['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($r['title']) ?></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill"><?= htmlspecialchars($r['category_name']) ?></span></td>
                    <td class="text-muted"><i class="far fa-clock me-1"></i> <?= $r['prep_time'] ?> min</td>
                    <td><span class="badge <?= $diffBadge ?> rounded-pill"><?= $diffText ?></span></td>
                    <td class="text-end pe-3">
                        <a href="update.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-success me-1"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete recipe?');"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>">Next</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php require_once "../footer.php"; ?>