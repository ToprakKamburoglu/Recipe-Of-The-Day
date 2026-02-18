<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe of the Day</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header class="site-header">
    
    <div class="header-top">
        <div class="container">
            <h1 class="site-logo">
                <a href="<?= BASE_URL ?>/public/index.php">
                    <span class="logo-text-1">Recipe</span> <span class="logo-text-2">of the Day</span>
                </a>
            </h1>
        </div>
    </div>

    <nav class="site-nav">
        <div class="container nav-container">

            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-links" id="navLinks">
                <a href="<?= BASE_URL ?>/public/index.php" class="nav-link">
                    <i class="fa fa-home"></i> Home
                </a>

                <a href="<?= BASE_URL ?>/public/all_recipes.php" class="nav-link">
                    <i class="fa fa-cutlery"></i> All Recipes
                </a>

                <a href="<?= BASE_URL ?>/public/about_us.php" class="nav-link">
                    <i class="fa fa-info-circle"></i> About Us
                </a>

                <a href="<?= BASE_URL ?>/public/contact.php" class="nav-link">
                    <i class="fa fa-envelope"></i> Contact
                </a>

                <?php if (isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/user/my_cookbook.php" class="nav-link">
                        <i class="fa fa-heart"></i> My Cookbook
                    </a>

                    <a href="<?= BASE_URL ?>/public/login/logout.php" class="nav-link">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/login/login.php" class="nav-link">
                        <i class="fa fa-sign-in"></i> Login
                    </a>

                    <a href="<?= BASE_URL ?>/public/login/register.php" class="nav-link">
                        <i class="fa fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="site-content">

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');

        if (!hamburger || !navLinks) {
            console.log("Hamburger veya navLinks bulunamadı!");
            return;
        }

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            console.log("Hamburger clicked");
        });
    });
</script>