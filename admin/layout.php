<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/database.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
// role-based access enforcement: only admin/editor allowed in admin area
$role = $_SESSION['role'] ?? null;
if (!in_array($role, ['admin','editor'])) {
  // destroy session and redirect
  session_unset(); session_destroy();
  header('Location: login.php'); exit;
}

// Determine current admin page for active nav state
function get_current_admin_page() {
  $script = basename($_SERVER['SCRIPT_NAME']) ?? '';
  $script = preg_replace('/\.php$/', '', $script);
  return $script ?: 'dashboard';
}

function admin_nav_class($page) {
  $current = get_current_admin_page();
  $isActive = ($current === $page) || ($page === 'dashboard' && $current === '');
  return $isActive ? 'block py-2 px-3 rounded bg-green-600 transition duration-300 ease-in-out font-semibold transform hover:translate-x-1' : 'block py-2 px-3 rounded hover:bg-green-600 transition duration-200 ease-in-out transform hover:translate-x-1';
}

// Load logo and recent activity notifications
$site_logo = get_setting('site_logo', '');
$notifications = [];
try {
  $db = (new Database())->getConnection();
  $sources = [
    [
      'sql' => "SELECT d.created_at, CONCAT('Donation from ', donors.name, ' to ', projects.name) AS text FROM donations d JOIN donors ON d.donor_id=donors.id JOIN projects ON d.project_id=projects.id ORDER BY d.created_at DESC LIMIT 3",
    ],
    [
      'sql' => "SELECT created_at, CONCAT('New message from ', name) AS text FROM messages ORDER BY created_at DESC LIMIT 3",
    ],
    [
      'sql' => "SELECT created_at, CONCAT('New gallery image: ', COALESCE(title, '(untitled)')) AS text FROM gallery_images ORDER BY created_at DESC LIMIT 3",
    ],
    [
      'sql' => "SELECT created_at, CONCAT('New blog post: ', title) AS text FROM blogs ORDER BY created_at DESC LIMIT 3",
    ],
    [
      'sql' => "SELECT created_at, CONCAT('New member: ', name) AS text FROM members ORDER BY created_at DESC LIMIT 3",
    ],
    [
      'sql' => "SELECT created_at, CONCAT('New project: ', name) AS text FROM projects ORDER BY created_at DESC LIMIT 3",
    ],
  ];

  foreach ($sources as $src) {
    try {
      $stmt = $db->query($src['sql']);
      foreach ($stmt->fetchAll() as $row) {
        if (!empty($row['created_at']) && !empty($row['text'])) {
          $notifications[] = [
            'text' => $row['text'],
            'created_at' => $row['created_at'],
          ];
        }
      }
    } catch (Exception $e) {
      // ignore missing tables
    }
  }

  usort($notifications, function($a, $b) {
    return strtotime($b['created_at']) <=> strtotime($a['created_at']);
  });
  $notifications = array_slice($notifications, 0, 8);
} catch (Exception $e) {
  $notifications = [];
}
?>
<!doctype html>
<html lang="en" class="bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo asset_url('assets/animations.css'); ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?php echo asset_url('assets/app.js'); ?>" defer></script>
  <title>Admin - Golfs Cameroon</title>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 admin-page-animation">
  <style>
    @keyframes adminFadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .admin-page-animation {
      animation: adminFadeIn 0.6s ease-in-out;
    }
    main {
      animation: adminFadeIn 0.6s ease-in-out 0.1s both;
    }
    .admin-card {
      transition: all 0.3s ease-in-out;
    }
    .admin-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    button, a {
      transition: all 0.3s ease-in-out;
    }
    input, textarea, select {
      transition: all 0.2s ease-in-out;
    }
    input:focus, textarea:focus, select:focus {
      transform: scale(1.01);
    }
  </style>
  <div class="flex min-h-screen flex-col md:flex-row">
    <aside class="w-full md:w-64 bg-green-700 text-white border-r border-green-800 md:fixed md:top-0 md:left-0 md:h-screen">
      <div class="p-4 border-b border-green-600 bg-white flex items-center justify-center">
        <?php if (!empty($site_logo)): ?>
          <img src="<?php echo e(base_url($site_logo)); ?>" alt="Logo" class="h-20 w-auto">
        <?php else: ?>
          <span class="text-lg font-bold">Admin</span>
        <?php endif; ?>
      </div>
      <nav class="p-4 space-y-1">
        <a class="<?php echo admin_nav_class('dashboard'); ?>" href="<?php echo base_url('admin/dashboard'); ?>"><i class="bi bi-graph-up"></i> Dashboard</a>
        <a class="<?php echo admin_nav_class('projects'); ?>" href="<?php echo base_url('admin/projects'); ?>"><i class="bi bi-bullseye"></i> Projects</a>
        <a class="<?php echo admin_nav_class('members'); ?>" href="<?php echo base_url('admin/members'); ?>"><i class="bi bi-people"></i> Members</a>
        <a class="<?php echo admin_nav_class('donations'); ?>" href="<?php echo base_url('admin/donations'); ?>"><i class="bi bi-currency-dollar"></i> Donations</a>
        <a class="<?php echo admin_nav_class('blogs'); ?>" href="<?php echo base_url('admin/blogs'); ?>"><i class="bi bi-file-text"></i> Blog Manager</a>
        <a class="<?php echo admin_nav_class('gallery'); ?>" href="<?php echo base_url('admin/gallery'); ?>"><i class="bi bi-images"></i> Gallery</a>
        <a class="<?php echo admin_nav_class('contacts'); ?>" href="<?php echo base_url('admin/contacts'); ?>"><i class="bi bi-envelope"></i> Contacts</a>
        <a class="<?php echo admin_nav_class('settings'); ?>" href="<?php echo base_url('admin/settings'); ?>"><i class="bi bi-gear"></i> Settings</a>
        <hr class="border-green-600 my-3">
        <a class="block py-2 px-3 rounded hover:bg-red-600 transition text-red-100" href="<?php echo base_url('admin/logout'); ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        <div class="pt-4 text-xs text-green-100">
          <?php echo e(get_setting('contact_email', '')); ?>
        </div>
      </nav>
    </aside>
    <div class="flex-1 flex flex-col md:ml-64">
      <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4">
          <div class="flex items-center gap-4">
            <div class="hidden md:block">
              <input type="text" placeholder="Search..." class="w-80 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition duration-200 hover:border-green-400">
            </div>
          </div>
          <div class="flex items-center gap-4">
            <div class="relative">
              <button id="admin-notify-btn" class="relative px-3 py-2 rounded-lg hover:bg-gray-100 transition duration-300 transform hover:scale-110">
                <i class="bi bi-bell text-xl transition duration-300"></i>
                <?php if (count($notifications) > 0): ?>
                  <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5">
                    <?php echo count($notifications); ?>
                  </span>
                <?php endif; ?>
              </button>
              <div id="admin-notify-menu" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                <div class="p-3 border-b border-gray-100 font-semibold text-gray-700">Notifications</div>
                <div class="max-h-80 overflow-y-auto">
                  <?php if (empty($notifications)): ?>
                    <div class="p-4 text-sm text-gray-500">No recent activity.</div>
                  <?php else: ?>
                    <?php foreach ($notifications as $n): ?>
                      <div class="p-3 border-b border-gray-100 text-sm">
                        <div class="text-gray-800"><?php echo e($n['text']); ?></div>
                        <div class="text-xs text-gray-500 mt-1"><?php echo e(date('M d, Y H:i', strtotime($n['created_at']))); ?></div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <main class="flex-1 p-6">
