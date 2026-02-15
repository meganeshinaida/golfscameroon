<?php
require_once __DIR__ . '/../config/helpers.php';
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
?>
<!doctype html>
<html lang="en" class="bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?php echo asset_url('assets/app.js'); ?>" defer></script>
  <title>Admin - Golfs Cameroon</title>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
  <div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-green-700 to-green-800 text-white border-r border-green-900">
      <div class="p-4 font-bold text-lg border-b border-green-600">Golfs Cameroon</div>
      <nav class="p-4 space-y-1">
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="dashboard.php"><i class="bi bi-graph-up"></i> Dashboard</a>
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="projects.php"><i class="bi bi-bullseye"></i> Projects</a>
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="members.php"><i class="bi bi-people"></i> Members</a>
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="donations.php"><i class="bi bi-currency-dollar"></i> Donations</a>
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="blogs.php"><i class="bi bi-file-text"></i> Blog Manager</a>
        <a class="block py-2 px-3 rounded hover:bg-green-600 transition" href="settings.php"><i class="bi bi-gear"></i> Settings</a>
        <hr class="border-green-600 my-3">
        <a class="block py-2 px-3 rounded hover:bg-red-600 transition text-red-100" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </nav>
    </aside>
    <div class="flex-1 flex flex-col">
      <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between p-6">
          <div>
            <h1 class="text-2xl font-semibold text-gray-800">Admin Dashboard</h1>
            <p class="text-sm text-gray-600">Welcome, <?php echo e($_SESSION['username'] ?? 'Admin'); ?> (<?php echo e($_SESSION['role'] ?? 'admin'); ?>)</p>
          </div>
          <div>
            <button onclick="toggleTheme()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-medium"><i class="bi bi-moon"></i> Toggle Theme</button>
          </div>
        </div>
      </header>

      <main class="flex-1 p-6">
