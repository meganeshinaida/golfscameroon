<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/helpers.php';
$site_name = get_setting('site_name', 'Golfs Cameroon');
$site_logo = get_setting('site_logo', '');

$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
$base = trim(parse_url(base_url(''), PHP_URL_PATH) ?? '', '/');
if ($base !== '' && strpos($path, $base) === 0) {
    $path = trim(substr($path, strlen($base)), '/');
}
if ($path === 'public' || $path === 'public/index.php' || $path === 'index.php') {
    $path = '';
}
if (strpos($path, 'public/') === 0) {
    $path = substr($path, 7);
}
$parts = explode('/', $path);
$current_page = $parts[0] ?? '';
$current_page = preg_replace('/\.php$/', '', $current_page);
if ($current_page === 'index') {
    $current_page = 'home';
}
if ($current_page === 'blog_single') {
    $current_page = 'blog';
}
if ($current_page === '') {
    $current_page = 'home';
}

function nav_link_class($route, $is_mobile = false) {
    global $current_page;
    $is_active = ($current_page === $route) || ($route === 'home' && $current_page === '');

    if ($is_mobile) {
        return $is_active
            ? 'block px-4 py-2 bg-red-600 text-white font-semibold'
            : 'block px-4 py-2 text-white hover:bg-green-600';
    }

    return $is_active
        ? 'text-red-300 font-semibold border-b-2 border-red-300 pb-1'
        : 'text-white hover:text-red-200';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="<?php echo e(get_setting('site_description', 'Empowering youth through education and community projects')); ?>">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="<?php echo asset_url('assets/app.js'); ?>" defer></script>
  <title><?php echo isset($page_title) ? e($page_title) . ' - ' . e($site_name) : e($site_name); ?></title>
</head>
<body class="bg-gray-50 text-gray-800">
  <nav class="bg-green-700 shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
          <a href="<?php echo base_url(''); ?>" class="text-xl font-bold text-white flex items-center gap-2">
            <?php if (!empty($site_logo)): ?>
              <img src="<?php echo e($site_logo); ?>" alt="Logo" class="h-8 w-auto">
            <?php endif; ?>
            <?php echo e($site_name); ?>
          </a>
          <div class="hidden md:flex items-center space-x-4 text-sm">
            <a href="<?php echo base_url(''); ?>" class="<?php echo nav_link_class('home'); ?>">Home</a>
            <a href="<?php echo base_url('about'); ?>" class="<?php echo nav_link_class('about'); ?>">About</a>
            <a href="<?php echo base_url('services'); ?>" class="<?php echo nav_link_class('services'); ?>">Services</a>
            <a href="<?php echo base_url('members'); ?>" class="<?php echo nav_link_class('members'); ?>">Members</a>
            <a href="<?php echo base_url('blog'); ?>" class="<?php echo nav_link_class('blog'); ?>">Blog</a>
            <a href="<?php echo base_url('donations'); ?>" class="<?php echo nav_link_class('donations'); ?>">Donate</a>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <button onclick="toggleTheme()" class="hidden sm:inline px-3 py-1 border border-white text-white rounded hover:bg-green-600">Toggle</button>
          <button id="mobile-menu-btn" class="md:hidden px-3 py-2 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
        </div>
      </div>
    </div>
    <div id="mobile-nav" class="md:hidden hidden bg-green-700 border-t border-green-600">
      <a class="<?php echo nav_link_class('home', true); ?>" href="<?php echo base_url(''); ?>">Home</a>
      <a class="<?php echo nav_link_class('about', true); ?>" href="<?php echo base_url('about'); ?>">About</a>
      <a class="<?php echo nav_link_class('services', true); ?>" href="<?php echo base_url('services'); ?>">Services</a>
      <a class="<?php echo nav_link_class('members', true); ?>" href="<?php echo base_url('members'); ?>">Members</a>
      <a class="<?php echo nav_link_class('blog', true); ?>" href="<?php echo base_url('blog'); ?>">Blog</a>
      <a class="<?php echo nav_link_class('donations', true); ?>" href="<?php echo base_url('donations'); ?>">Donate</a>
    </div>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      initMobileMenu('#mobile-menu-btn', '#mobile-nav');
      initScrollReveal();
    });
  </script>
</body>
</html>
