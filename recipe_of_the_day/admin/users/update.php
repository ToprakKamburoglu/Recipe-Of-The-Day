<?php
require_once "../header.php";
require_once "../../config/auth.php";

requireRole([1, 2]);

$currentUserId   = currentUserId();
$currentUserRole = currentUserRole();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo '<div class="alert alert-danger">User not found.</div>';
    require_once "../footer.php";
    exit;
}

if ($currentUserRole === 1 && (int)$user['role'] >= 1) {
    echo '<div class="alert alert-danger">You are not allowed to edit this user.</div>';
    require_once "../footer.php";
    exit;
}

if ($id === $currentUserId && $currentUserRole !== 2) {
    echo '<div class="alert alert-danger">You cannot modify your own account.</div>';
    require_once "../footer.php";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $is_banned = (int)$_POST['is_banned'];

    if ($currentUserRole === 2) {
        $role = (int)$_POST['role'];
    } else {
        $role = (int)$user['role'];
    }

    if ($currentUserRole === 2 && !empty($_POST['password'])) {

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE users
            SET username=?, email=?, password=?, role=?, is_banned=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->execute([
            $username,
            $email,
            $password,
            $role,
            $is_banned,
            $id
        ]);

    } else {

        $stmt = $pdo->prepare("
            UPDATE users
            SET username=?, email=?, role=?, is_banned=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->execute([
            $username,
            $email,
            $role,
            $is_banned,
            $id
        ]);
    }

    header("Location: /recipe_of_the_day/admin/users/index.php");
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text">
        <i class="fas fa-user-edit me-2"></i>Edit User
    </h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username"
                           value="<?= htmlspecialchars($user['username']) ?>"
                           class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($user['email']) ?>"
                           class="form-control" required>
                </div>
            </div>

            <?php if ($currentUserRole === 2): ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        New Password <small class="text-muted">(Super Admin only)</small>
                    </label>
                    <input type="password" name="password" class="form-control">
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select" <?= $currentUserRole !== 2 ? 'disabled' : '' ?>>
                        <option value="0" <?= (int)$user['role'] === 0 ? 'selected' : '' ?>>User</option>
                        <option value="1" <?= (int)$user['role'] === 1 ? 'selected' : '' ?>>Admin</option>
                        <option value="2" <?= (int)$user['role'] === 2 ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="is_banned" class="form-select">
                        <option value="0" <?= $user['is_banned'] == 0 ? 'selected' : '' ?>>Active</option>
                        <option value="1" <?= $user['is_banned'] == 1 ? 'selected' : '' ?>>Banned</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save me-2"></i>Update User
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once "../footer.php"; ?>
