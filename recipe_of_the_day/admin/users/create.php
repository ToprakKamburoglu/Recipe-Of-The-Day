<?php require_once "../header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-2 m-0 primary-text"><i class="fas fa-user-friends me-2 primary text"></i> Create User</h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role']; 

    if ($username && $email && $password) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, role, is_banned, created_at)
            VALUES (?, ?, ?, ?, 0, NOW())
        ");
        $stmt->execute([$username, $email, $hashedPassword, $role]);

        echo "<script>window.location.href='/recipe_of_the_day/admin/users/index.php';</script>";
        exit;
    }
}
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="johndoe" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="******" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary shadow-sm ">
                    <i class="fas fa-save me-2"></i>Create User
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once "../footer.php"; ?>