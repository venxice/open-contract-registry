<?php

// Router for PHP built-in server
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);

// Serve static files directly
$staticFile = __DIR__ . '/public' . $path;
if ($path !== '/' && file_exists($staticFile) && is_file($staticFile)) {
    return false;
}

// Route everything else through CodeIgniter
$_SERVER['SCRIPT_NAME'] = '/index.php';
chdir(__DIR__ . '/public');
require __DIR__ . '/public/index.php';
