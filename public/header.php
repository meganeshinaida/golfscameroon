<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/visitor-tracker.php';
$site_name = get_setting('site_name', 'Golfs Cameroon');
$site_logo = get_setting('site_logo', '');
$languages = supported_languages();
$current_lang = current_lang();

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
        ? 'block px-4 py-2 bg-red-600 text-white font-semibold transition duration-300 ease-in-out'
        : 'block px-4 py-2 text-green-800 hover:bg-green-50 transition duration-200 ease-in-out';
    }

    return $is_active
      ? 'text-red-600 font-semibold border-b-2 border-red-600 pb-1 transition duration-300 ease-in-out'
      : 'text-green-700 hover:text-red-600 transition duration-200 ease-in-out';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="<?php echo e(get_setting('site_description', 'Empowering youth through education and community projects')); ?>">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo asset_url('assets/animations.css'); ?>">
  <script src="<?php echo asset_url('assets/app.js'); ?>" defer></script>
  <title><?php echo isset($page_title) ? e($page_title) . ' - ' . e($site_name) : e($site_name); ?></title>
</head>
<body class="bg-white text-gray-800 animation-fade-in">
  <style>
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animation-fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    a, button {
      transition: all 0.3s ease-in-out;
    }
    nav a:hover {
      text-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }
  </style>
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="flex items-center justify-between py-4">
        <div class="flex items-center gap-4">
          <a href="<?php echo base_url(''); ?>" class="text-xl font-bold text-green-700 flex items-center gap-2">
            <?php if (!empty($site_logo)): ?>
              <img src="<?php echo e(base_url($site_logo)); ?>" alt="Logo" class="h-20 w-auto">
            <?php else: ?>
              <span class="sr-only"><?php echo e($site_name); ?></span>
            <?php endif; ?>
          </a>
          <div class="hidden md:flex items-center space-x-4 text-sm">
            <a href="<?php echo base_url(''); ?>" class="<?php echo nav_link_class('home'); ?>"><?php echo e(t('nav.home')); ?></a>
            <a href="<?php echo base_url('about'); ?>" class="<?php echo nav_link_class('about'); ?>"><?php echo e(t('nav.about')); ?></a>
            <a href="<?php echo base_url('services'); ?>" class="<?php echo nav_link_class('services'); ?>"><?php echo e(t('nav.services')); ?></a>
            <a href="<?php echo base_url('members'); ?>" class="<?php echo nav_link_class('members'); ?>"><?php echo e(t('nav.members')); ?></a>
            <a href="<?php echo base_url('gallery'); ?>" class="<?php echo nav_link_class('gallery'); ?>"><?php echo e(t('nav.gallery')); ?></a>
            <a href="<?php echo base_url('blog'); ?>" class="<?php echo nav_link_class('blog'); ?>"><?php echo e(t('nav.blog')); ?></a>
            <a href="<?php echo base_url('donations'); ?>" class="<?php echo nav_link_class('donations'); ?>"><?php echo e(t('nav.donate')); ?></a>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <form method="get" class="hidden sm:flex items-center gap-2 transition duration-300">
            <label for="lang" class="text-sm text-gray-600">Language</label>
            <select id="lang" name="lang" class="border border-gray-300 text-sm rounded px-2 py-1 transition duration-200 hover:border-green-500 focus:border-green-600" onchange="this.form.submit()">
              <?php foreach ($languages as $code => $label): ?>
                <option value="<?php echo e($code); ?>" <?php echo $current_lang === $code ? 'selected' : ''; ?>><?php echo e($label); ?></option>
              <?php endforeach; ?>
            </select>
          </form>
          <a href="<?php echo base_url('contact'); ?>" class="hidden sm:inline bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 transform hover:scale-105"><?php echo e(t('nav.contact_us')); ?></a>
          <button id="mobile-menu-btn" class="md:hidden px-3 py-2 text-green-700 hover:text-red-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
        </div>
      </div>
    </div>
    <div id="mobile-nav" class="md:hidden hidden bg-white border-t border-gray-200">
      <a class="<?php echo nav_link_class('home', true); ?>" href="<?php echo base_url(''); ?>"><?php echo e(t('nav.home')); ?></a>
      <a class="<?php echo nav_link_class('about', true); ?>" href="<?php echo base_url('about'); ?>"><?php echo e(t('nav.about')); ?></a>
      <a class="<?php echo nav_link_class('services', true); ?>" href="<?php echo base_url('services'); ?>"><?php echo e(t('nav.services')); ?></a>
      <a class="<?php echo nav_link_class('members', true); ?>" href="<?php echo base_url('members'); ?>"><?php echo e(t('nav.members')); ?></a>
      <a class="<?php echo nav_link_class('gallery', true); ?>" href="<?php echo base_url('gallery'); ?>"><?php echo e(t('nav.gallery')); ?></a>
      <a class="<?php echo nav_link_class('blog', true); ?>" href="<?php echo base_url('blog'); ?>"><?php echo e(t('nav.blog')); ?></a>
      <a class="<?php echo nav_link_class('donations', true); ?>" href="<?php echo base_url('donations'); ?>"><?php echo e(t('nav.donate')); ?></a>
      <a class="<?php echo nav_link_class('contact', true); ?>" href="<?php echo base_url('contact'); ?>"><?php echo e(t('nav.contact')); ?></a>
      <div class="px-4 py-2 border-t border-gray-200">
        <form method="get" class="flex items-center gap-2">
          <label for="lang-mobile" class="text-sm text-gray-600"><?php echo e(t('nav.language')); ?></label>
          <select id="lang-mobile" name="lang" class="border border-gray-300 text-sm rounded px-2 py-1" onchange="this.form.submit()">
            <?php foreach ($languages as $code => $label): ?>
              <option value="<?php echo e($code); ?>" <?php echo $current_lang === $code ? 'selected' : ''; ?>><?php echo e($label); ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>
    </div>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      var btn = document.querySelector('#mobile-menu-btn');
      var nav = document.querySelector('#mobile-nav');
      if (btn && nav) {
        btn.addEventListener('click', function(e){
          e.preventDefault();
          nav.classList.toggle('hidden');
        });
      }
      if (typeof initScrollReveal === 'function') {
        initScrollReveal();
      }
    });
  </script>
</body>
</html>
