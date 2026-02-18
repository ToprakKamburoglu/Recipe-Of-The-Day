<?php
require_once __DIR__ . '/../config/database.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/gr2025-015.com/recipe_of_the_day'); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$name, $email, $subject, $message]);

            if ($result) {
                header("Location: " . BASE_URL . "/public/contact.php?status=success");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: " . BASE_URL . "/public/contact.php?status=error");
            exit;
        }
    } else {
        header("Location: " . BASE_URL . "/public/contact.php?status=error");
        exit;
    }
} else {
    header("Location: " . BASE_URL . "/public/index.php");
    exit;
}