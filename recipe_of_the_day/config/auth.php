<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function currentUserId(): ?int
{
    return $_SESSION['user']['id'] ?? null;
}

function currentUserRole(): ?int
{
    return $_SESSION['user']['role'] ?? null;
}

function isUser(): bool
{
    return currentUserRole() === 0;
}

function isAdmin(): bool
{
    return currentUserRole() === 1;
}

function isSuperAdmin(): bool
{
    return currentUserRole() === 2;
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: recipe_of_the_day/public/login/login.php');
        exit;
    }
}

function requireRole(array $allowedRoles)
{
    requireLogin();

    if (!in_array(currentUserRole(), $allowedRoles, true)) {
        http_response_code(403);
        echo "403 - Access Denied";
        exit;
    }
}
