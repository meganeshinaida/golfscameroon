<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../models/User.php';

$settingModel = new Setting();
$userModel = new User();

$msg = '';
$error = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'settings') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { 
        $error = 'Invalid CSRF token'; 
    } else {
        $keys = ['site_name', 'site_description', 'site_logo', 'about_text', 'contact_email', 'contact_phone', 'address'];
        foreach ($keys as $key) {
            $value = $_POST[$key] ?? '';
            $settingModel->set($key, $value);
        }
        $msg = 'Settings updated successfully!';
    }
}

// Handle admin user create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'create_admin') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { 
        $error = 'Invalid CSRF token'; 
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'editor';
        
        if (empty($username)) {
            $error = 'Username is required';
        } elseif (empty($password)) {
            $error = 'Password is required';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            try {
                $userModel->createAdmin($username, $password, $role);
                $msg = "Admin user '$username' created successfully!";
            } catch (Exception $e) {
                $error = 'Error creating user: ' . $e->getMessage();
            }
        }
    }
}

// Handle admin user delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'delete_admin') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { 
        $error = 'Invalid CSRF token'; 
    } else {
        $user_id = (int)($_POST['user_id'] ?? 0);
        if ($user_id === (int)$_SESSION['user_id']) {
            $error = 'You cannot delete your own account';
        } else {
            try {
                $db = $database->getConnection();
                $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
                $stmt->execute([':id' => $user_id]);
                $msg = 'Admin user deleted successfully!';
            } catch (Exception $e) {
                $error = 'Error deleting user: ' . $e->getMessage();
            }
        }
    }
}

// Get all settings
$settings = $settingModel->all();
$settingsMap = [];
foreach ($settings as $s) {
    $settingsMap[$s['key']] = $s['value'];
}

// Get all admin users
$allUsers = $userModel->all();
?>

<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Settings & Admin Management</h2>

    <?php if (!empty($msg)): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
            <i class="bi bi-check-circle"></i> <?php echo e($msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
            <i class="bi bi-exclamation-circle"></i> <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="bi bi-sliders"></i> Global Website Settings</h3>
                <form method="post">
                    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="_action" value="settings">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" name="site_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                               value="<?php echo e($settingsMap['site_name'] ?? 'Golfs Cameroon'); ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea name="site_description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" rows="3"><?php echo e($settingsMap['site_description'] ?? 'Empowering youth through education and community support'); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo URL</label>
                        <input type="text" name="site_logo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                               placeholder="https://example.com/logo.png" value="<?php echo e($settingsMap['site_logo'] ?? ''); ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">About Text</label>
                        <textarea name="about_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" rows="4"><?php echo e($settingsMap['about_text'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                               value="<?php echo e($settingsMap['contact_email'] ?? 'info@golfs-cameroon.org'); ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="tel" name="contact_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                               value="<?php echo e($settingsMap['contact_phone'] ?? ''); ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" 
                               value="<?php echo e($settingsMap['address'] ?? 'Yaounde, Cameroon'); ?>">
                    </div>

                    <button class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                        <i class="bi bi-check-circle"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Admin Users Card -->
        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="bi bi-shield-lock"></i> Admin Users (<?php echo count($allUsers); ?>)</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <?php foreach ($allUsers as $u): ?>
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800"><?php echo e($u['username']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($u['role']); ?></p>
                                </div>
                                <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                    <form method="post" style="display:inline" onsubmit="return confirm('Delete admin user?')">
                                        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="_action" value="delete_admin">
                                        <input type="hidden" name="user_id" value="<?php echo e($u['id']); ?>">
                                        <button class="text-red-600 hover:text-red-800"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-xs text-green-600 font-semibold">Current</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin User Form -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="bi bi-person-plus"></i> Create New Admin User</h3>
        <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_action" value="create_admin">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                </select>
            </div>

            <div class="flex items-end">
                <button class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium">
                    <i class="bi bi-plus-circle"></i> Create
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
