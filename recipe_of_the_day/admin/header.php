<?php
require_once __DIR__ . '/../config/database.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/recipe_of_the_day'); 
}

$current_uri = $_SERVER['REQUEST_URI'];

$is_dashboard = (strpos($current_uri, '/admin/dashboard.php') !== false);
$is_users = (strpos($current_uri, '/admin/users/') !== false);
$is_recipes = (strpos($current_uri, '/admin/recipes/') !== false);
$is_categories = (strpos($current_uri, '/admin/categories/') !== false);
$is_messages = (strpos($current_uri, '/admin/messages.php') !== false); 
$is_public = (strpos($current_uri, '/public/index.php') !== false); 

?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css">
</head>
<body>

<div class="d-flex" id="wrapper">

    <div class="sidebar-wrapper bg-white">
        <div class="sidebar-heading text-center primary-text fs-4 fw-bold text-uppercase border-bottom">
            <i class="fas fa-user-secret me-2"></i>Admin
        </div>
        
        <div class="list-group list-group-flush my-3">
            <a href="<?= BASE_URL ?>/admin/dashboard.php" 
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_dashboard ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            
            <a href="<?= BASE_URL ?>/admin/users/index.php" 
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_users ? 'active' : '' ?>">
                <i class="fas fa-users me-2"></i>Users
            </a>
            
            <a href="<?= BASE_URL ?>/admin/recipes/index.php" 
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_recipes ? 'active' : '' ?>">
                <i class="fas fa-utensils me-2"></i>Recipes
            </a>
            
            <a href="<?= BASE_URL ?>/admin/categories/index.php" 
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_categories ? 'active' : '' ?>">
                <i class="fas fa-tags me-2"></i>Categories
            </a>

            <a href="<?= BASE_URL ?>/admin/messages.php" 
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_messages ? 'active' : '' ?>">
                <i class="fas fa-envelope me-2"></i>Messages
            </a>

            <a href="<?= BASE_URL ?>/public/index.php" target="_blank"
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold <?= $is_public ? 'active' : '' ?>">
                <i class="fas fa-globe me-2"></i>Go to Website
            </a>

            <a href="<?= BASE_URL ?>/public/login/logout.php" 
            class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                <i class="fas fa-power-off me-2"></i>Logout
            </a>
        </div>
    </div>
    <div id="page-content-wrapper">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle" style="cursor: pointer;"></i>
            </div>
        </nav>

        <div class="container-fluid px-4 main-content">