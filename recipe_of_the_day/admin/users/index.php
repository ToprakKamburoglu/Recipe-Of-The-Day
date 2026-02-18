<?php 

require_once __DIR__ . '/../../config/auth.php';
requireRole([1, 2]);

require_once "../header.php"; 

$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'DESC';

$allowed_columns = ['id', 'username', 'email', 'role', 'is_banned'];
if (!in_array($sort, $allowed_columns)) {
    $sort = 'id';
}
$order = ($order === 'ASC') ? 'ASC' : 'DESC';

$next_order = ($order === 'ASC') ? 'DESC' : 'ASC';

$total_stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT * FROM users ORDER BY $sort $order LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

function sortIcon($column, $current_sort, $current_order) {
    if ($column !== $current_sort) {
        return '<i class="fas fa-sort text-muted opacity-25 ms-1"></i>';
    }
    return $current_order === 'ASC' 
        ? '<i class="fas fa-sort-up ms-1" style="color: var(--main-text-color);"></i>' 
        : '<i class="fas fa-sort-down ms-1" style="color: var(--main-text-color);"></i>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
    <i class="fas fa-users me-2 primary-text"></i> User Management</h1>
    <a href="create.php" class="btn btn-primary shadow-sm">
        <i class="fas fa-users-cog me-2"></i>Add New User
    </a>
</div>

<div class="bg-white p-4 rounded shadow-sm border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th scope="col" class="py-3 ps-3">
                        <a href="?page=<?= $page ?>&sort=id&order=<?= $next_order ?>" class="text-decoration-none text-dark">
                            ID <?= sortIcon('id', $sort, $order) ?>
                        </a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=username&order=<?= $next_order ?>" class="text-decoration-none text-dark">
                            Username <?= sortIcon('username', $sort, $order) ?>
                        </a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=email&order=<?= $next_order ?>" class="text-decoration-none text-dark">
                            Email <?= sortIcon('email', $sort, $order) ?>
                        </a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=role&order=<?= $next_order ?>" class="text-decoration-none text-dark">
                            Role <?= sortIcon('role', $sort, $order) ?>
                        </a>
                    </th>
                    <th scope="col" class="py-3">
                        <a href="?page=<?= $page ?>&sort=is_banned&order=<?= $next_order ?>" class="text-decoration-none text-dark">
                            Status <?= sortIcon('is_banned', $sort, $order) ?>
                        </a>
                    </th>
                    <th scope="col" class="py-3 text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) == 0): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                <?php else: 
                    foreach ($users as $user): ?>
                    <tr>
                        <td class="ps-3 fw-bold">#<?= $user['id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user text-secondary"></i>
                                </div>
                                <span><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php if($user['role'] === 1): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    Admin
                                </span>

                            <?php elseif($user['role'] === 2): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                    Super Admin
                                </span>

                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                    User
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($user['is_banned']): ?>
                                <span class="badge bg-danger">Banned</span>
                            <?php else: ?>
                                <span class="badge bg-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-3" style="white-space: nowrap;">
                            <a href="update.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete user?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
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