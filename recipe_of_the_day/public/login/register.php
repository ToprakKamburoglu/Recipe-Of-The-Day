<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!$username || !$email || !$password || !$password2) {
        echo json_encode([
            'success' => false,
            'message' => 'All fields are required'
        ]);
        exit;
    }

    if ($password !== $password2) {
        echo json_encode([
            'success' => false,
            'message' => 'Passwords do not match'
        ]);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must be at least 6 characters'
        ]);
        exit;
    }

    $checkStmt = $pdo->prepare("
        SELECT id FROM users
        WHERE email = ? OR username = ?
        LIMIT 1
    ");
    $checkStmt->execute([$email, $username]);

    if ($checkStmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Email or username already exists'
        ]);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, role, is_banned, created_at)
        VALUES (?, ?, ?, 0, 0, NOW())
    ");

    $stmt->execute([
        $username,
        $email,
        $passwordHash
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully'
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Recipe of the Day</title>

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
                <div class="fw-semibold">Creating your account...</div>
            </div>
        </div>

        <div class="login-card">

            <div class="text-center mb-4">
                <div class="logo-text">
                    Recipe<span>OfTheDay</span>
                </div>
                <div class="text-muted small mt-1">
                    Create your account 🌱
                </div>
            </div>

            <div id="errorBox" class="alert alert-danger d-none"></div>
            <div id="successBox" class="alert alert-success d-none"></div>

            <form id="registerForm">

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input name="username" type="text" class="form-control" placeholder="yourusername" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" placeholder="you@example.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input name="password" type="password" class="form-control" id="passwordInput" required>
                        <button class="btn btn-outline-success toggle-password" type="button"
                                data-target="passwordInput">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input name="password2" type="password" class="form-control" id="confirmPasswordInput" required>
                        <button class="btn btn-outline-success toggle-password" type="button"
                                data-target="confirmPasswordInput">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Create Account
                </button>

                <div class="text-center mt-4">
                    <span class="small text-muted">Already have an account?</span>
                    <a href="login.php" class="small text-success text-decoration-none fw-semibold">
                        Sign in
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        btn.innerHTML = isPassword
            ? '<i class="bi bi-eye-slash"></i>'
            : '<i class="bi bi-eye"></i>';
    });
});

const form = document.getElementById('registerForm');
const spinner = document.getElementById('spinnerOverlay');
const errorBox = document.getElementById('errorBox');
const successBox = document.getElementById('successBox');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    errorBox.classList.add('d-none');
    successBox.classList.add('d-none');
    spinner.style.display = 'flex';

    const formData = new FormData(form);

    const res = await fetch('register.php', {
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

    successBox.textContent = data.message + ' Redirecting to login...';
    successBox.classList.remove('d-none');

    setTimeout(() => {
        window.location.href = 'login.php';
    }, 1500);
});
</script>

</body>
</html>
