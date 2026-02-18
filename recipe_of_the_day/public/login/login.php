<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id, username, email, password, role, is_banned
        FROM users
        WHERE email = ?
        LIMIT 1
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
        exit;
    }

    if ((int)$user['is_banned'] === 1) {
        echo json_encode([
            'success' => false,
            'message' => 'Your account has been banned'
        ]);
        exit;
    }


    $_SESSION['user'] = [
        'id'       => (int)$user['id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'role'     => (int)$user['role'],
    ];

    $redirect = match ((int)$user['role']) {
        2 => '/gr2025-015.com/recipe_of_the_day/admin/dashboard.php',
        1 => '/gr2025-015.com/recipe_of_the_day/admin/dashboard.php',
        default => '/gr2025-015.com/recipe_of_the_day/public/index.php',
    };

    echo json_encode([
        'success'  => true,
        'redirect' => $redirect
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Recipe of the Day</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-container position-relative">

        <div class="spinner-overlay" id="spinnerOverlay">
            <div class="text-center">
                <div class="spinner-border text-success mb-3"></div>
                <div class="fw-semibold">Signing you in...</div>
            </div>
        </div>

        <div class="login-card">

            <div class="text-center mb-4">
                <div class="logo-text">
                    Recipe<span>OfTheDay</span>
                </div>
                <div class="text-muted small mt-1">
                    Welcome back 👋
                </div>
            </div>

            <div id="errorBox" class="alert alert-danger d-none"></div>

            <form id="loginForm">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input name="password" type="password" class="form-control" id="passwordInput" required>
                        <button class="btn btn-outline-success" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Sign In
                </button>

                <div class="text-center mt-3">
                    <a href="register.php" class="small text-success text-decoration-none">
                        Sign Up
                    </a>
                </div>

                <div class="text-center mt-2">
                    <a href="/gr2025-015.com/recipe_of_the_day/public/index.php" class="small text-secondary text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Back to Site
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const toggleBtn = document.getElementById('togglePassword');
const passwordInput = document.getElementById('passwordInput');
const form = document.getElementById('loginForm');
const spinner = document.getElementById('spinnerOverlay');
const errorBox = document.getElementById('errorBox');

toggleBtn.addEventListener('click', () => {
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    toggleBtn.innerHTML = isPassword
        ? '<i class="bi bi-eye-slash"></i>'
        : '<i class="bi bi-eye"></i>';
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    errorBox.classList.add('d-none');
    spinner.style.display = 'flex';

    const formData = new FormData(form);

    const res = await fetch('login.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    spinner.style.display = 'none';

    if (!data.success) {
        errorBox.textContent = data.message;
        errorBox.classList.remove('d-none');
        return;
    }

    window.location.href = data.redirect;
});
</script>

</body>
</html>