<?php
require_once __DIR__ . '/../config/helpers.php';

// Simple router to handle clean URLs
$path = trim($_GET['path'] ?? '', '/');

// Map clean URLs to files
$routes = [
    '' => 'home',
    'home' => 'home',
    'about' => 'about',
    'services' => 'services',
    'members' => 'members',
    'blog' => 'blog',
    'donations' => 'donations',
    'terms' => 'terms',
];

// Handle blog single post (blog/{id})
if (preg_match('/^blog\/(\d+)/', $path, $m)) {
    $_GET['id'] = $m[1];
    include __DIR__ . '/blog_single.php';
    exit;
}

// Check if path matches a route
if (isset($routes[$path])) {
    $file = $routes[$path];
    include __DIR__ . '/' . $file . '.php';
    exit;
}

// Default to home if no route found
include __DIR__ . '/home.php';
?>
