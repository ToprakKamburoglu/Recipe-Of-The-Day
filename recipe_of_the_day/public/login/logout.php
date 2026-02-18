<?php
session_start();
session_destroy();

header("Location: /gr2025-015.com/recipe_of_the_day/public/login/login.php");
exit;
