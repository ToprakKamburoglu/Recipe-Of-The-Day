<?php 

require_once __DIR__ . '/../../config/auth.php';
requireRole([1, 2]);

require_once "../header.php"; 

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'DESC';

$allowed_columns = ['id', 'name', 'created_at'];
if (!in_array($sort, $allowed_columns)) {
    $sort = 'id';
}
$order = ($order === 'ASC') ? 'ASC' : 'DESC';
$next_order = ($order === 'ASC') ? 'DESC' : 'ASC';

$total_stmt = $pdo->query("SELECT COUNT(*) FROM categories");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT * FROM categories ORDER BY $sort $order LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll();

function sortIcon($column, $current_sort, $current_order) {
    if ($column !== $current_sort) return '<i class="fas fa-sort text-muted opacity-25 ms-1"></i>';
    return $current_order === 'ASC' ? '<i class="fas fa-sort-up primary-text ms-1"></i>' : '<i class="fas fa-sort-down primary-text ms-1"></i>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
    <i class="fas fa-tags me-2 primary-text"></i> Categories
</h1>
    <a href="create.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Add Category</a>
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
                        <a href="?page=<?= $page ?>&sort=name&order=<?= $next_order ?>" class="text-decoration-none text-dark">Category Name <?= sortIcon('name', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=created_at&order=<?= $next_order ?>" class="text-decoration-none text-dark">Created At <?= sortIcon('created_at', $sort, $order) ?></a>
                    </th>
                    <th scope="col" class="py-3 text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($categories) == 0): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No categories found.</td></tr>
                <?php else: foreach ($categories as $cat): ?>
                <tr>
                    <td class="ps-3 fw-bold text-muted">#<?= $cat['id'] ?></td>
                    <td class="fw-bold primary-text"><i class="fas fa-tag me-2 text-secondary opacity-50"></i><?= htmlspecialchars($cat['name']) ?></td>
                    <td class="text-muted small"><i class="far fa-calendar-alt me-1"></i><?= $cat['created_at'] ?></td>
                    <td class="text-end pe-3">
                        <a href="delete.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category?');"><i class="fas fa-trash-alt"></i></a>
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