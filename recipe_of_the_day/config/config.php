<?php
define('ENV', 'development');

define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('PUBLIC_PATH', ROOT_PATH . '/public');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseDir = '/recipe_of_the_day';

define('BASE_URL', $protocol . '://' . $host . $baseDir);

date_default_timezone_set('Europe/Istanbul');
ini_set('default_charset', 'UTF-8');

if (ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
}
