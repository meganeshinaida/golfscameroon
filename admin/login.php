<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $token = $_POST['_csrf'] ?? '';

    if (!verify_csrf($token)) {
        $error = 'Invalid CSRF token';
    } else {
        $userModel = new User();
        $user = $userModel->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
          // role-based allow only admin or editor
          $allowed = ['admin','editor'];
          $role = $user['role'] ?? 'admin';
          if (!in_array($role, $allowed)) {
            $error = 'Insufficient privileges';
          } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;
            redirect(base_url('admin/dashboard'));
          }
        } else {
          $error = 'Invalid credentials';
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>Admin Login - Golfs Cameroon</title>
</head>
<body class="bg-white flex items-center justify-center min-h-screen p-4">
  <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-green-700 mb-2">Admin Portal</h1>
      <p class="text-gray-600 text-sm">Golfs Cameroon Management</p>
    </div>
    <?php if (!empty($error)): ?>
      <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded mb-4 text-sm"><strong>Error:</strong> <?php echo e($error); ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
        <input name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
      </div>
      <button class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">Login</button>
    </form>
  </div>
</body>
</html>
