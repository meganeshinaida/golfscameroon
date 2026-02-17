<?php
/**
 * Admin Setup Script - Create initial admin user(s)
 * 
 * Usage:
 * 1. Copy this file path: c:/xampp/htdocs/ngo/admin/setup_admin.php
 * 2. Visit: http://localhost/ngo/admin/setup_admin.php
 * 3. Fill in username and password, click "Create Admin"
 * 4. Delete this file after creating your first admin (for security)
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'admin';
    
    $errors = [];
    if (empty($username)) $errors[] = 'Username is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    
    if (empty($errors)) {
        try {
            $userModel = new User();
            $userModel->createAdmin($username, $password, $role);
            $success = "Admin user '$username' created successfully!";
        } catch (Exception $e) {
            $errors[] = 'Error creating user: ' . $e->getMessage();
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
    <title>Admin Setup - Golfs Cameroon</title>
</head>
<body class="bg-white flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">
        <h1 class="text-3xl font-bold text-center mb-2 text-green-700">Admin Setup</h1>
        <p class="text-center text-gray-600 text-sm mb-6">Create your first admin user</p>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-6">
                <strong>✓ Success!</strong> <?php echo e($success); ?>
                <p class="text-sm mt-2">You can now <a href="<?php echo base_url('admin/login'); ?>" class="underline font-semibold">login here</a>.</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6">
                <?php foreach ($errors as $err): ?>
                    <p class="text-sm">• <?php echo e($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    value="<?php echo e($_POST['username'] ?? ''); ?>"
                    required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Enter a strong password"
                    required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="admin">Admin (full access)</option>
                    <option value="editor">Editor (content only)</option>
                </select>
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition">
                Create Admin User
            </button>
        </form>
        
        <p class="text-xs text-gray-500 text-center mt-6 border-t pt-4">
            ⚠️ <strong>IMPORTANT:</strong> Delete this file after creating your admin account for security.
        </p>
    </div>
</body>
</html>
