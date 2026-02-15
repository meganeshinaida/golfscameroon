<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../config/helpers.php';

$model = new Member();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$member = $id ? $model->find($id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $error = 'Invalid CSRF token'; }
    else {
        $name = $_POST['name'] ?? '';
        $role = $_POST['role'] ?? '';
        $bio = $_POST['bio'] ?? '';

        $imageName = $member['image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $res = upload_image($_FILES['image'], __DIR__ . '/../uploads');
            if ($res['success']) $imageName = $res['filename']; else $error = $res['error'];
        }

        if (empty($name)) $error = 'Name required';
        if (empty($error)) {
          $data = ['name'=>$name,'role'=>$role,'bio'=>$bio,'image'=>$imageName];
          if (!empty($_POST['id'])) { $model->update((int)$_POST['id'], $data); $success = 'Updated'; $member = $model->find((int)$_POST['id']); }
          else { $model->create($data); $success = 'Created'; }
        }
    }
}
?>

<div class="max-w-2xl bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4"><?php echo $member ? 'Edit Member' : 'New Member'; ?></h2>
  <?php if (!empty($error)): ?><div class="bg-red-100 text-red-800 p-2 rounded mb-3"><?php echo e($error); ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-3"><?php echo e($success); ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id" value="<?php echo e($member['id'] ?? ''); ?>">
    <label class="block mb-2">Name
      <input name="name" class="w-full p-2 border rounded" required value="<?php echo e($member['name'] ?? ''); ?>">
    </label>
    <label class="block mb-2">Role
      <input name="role" class="w-full p-2 border rounded" value="<?php echo e($member['role'] ?? ''); ?>">
    </label>
    <label class="block mb-2">Bio
      <textarea name="bio" class="w-full p-2 border rounded" rows="6"><?php echo e($member['bio'] ?? ''); ?></textarea>
    </label>
    <label class="block mb-2">Profile Image
      <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
    </label>
    <?php if (!empty($member['image'])): ?>
      <div class="mb-3"><img src="<?php echo base_url('uploads/' . $member['image']); ?>" class="h-32 object-cover rounded"></div>
    <?php endif; ?>
    <button class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium"><i class="bi bi-check-circle"></i> <?php echo $member ? 'Update' : 'Create'; ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
