<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Project.php';

$projectModel = new Project();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = $id ? $projectModel->find($id) : null;

// handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $error = 'Invalid CSRF token'; }
    else {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $target = $_POST['target_amount'] ?? 0;
        $payment_link = $_POST['payment_link'] ?? '';

        // handle file upload
        $imageName = $project['image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $f = $_FILES['image'];
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) { $error = 'Invalid image type'; }
            elseif ($f['size'] > 2*1024*1024) { $error = 'Image too large'; }
            else {
                $newName = uniqid('proj_') . '.' . $ext;
                $dest = __DIR__ . '/../uploads/' . $newName;
                if (!move_uploaded_file($f['tmp_name'], $dest)) { $error = 'Failed to save image'; }
                else { $imageName = $newName; }
            }
        }

        // basic validation
        if (empty($name)) $error = 'Name is required';
        elseif (!is_numeric($target) || $target < 0) $error = 'Target amount must be a positive number';

        if (empty($error)) {
          $data = ['name'=>$name,'description'=>$description,'target_amount'=>$target,'payment_link'=>$payment_link,'image'=>$imageName];
          if (!empty($_POST['id'])) {
            $projectModel->update((int)$_POST['id'], $data);
            $success = 'Project updated';
            $project = $projectModel->find((int)$_POST['id']);
          } else {
            $projectModel->create($data);
            $success = 'Project created';
            $project = null; // clear
          }
        }
    }
}
?>

<div class="max-w-2xl bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4"><?php echo $project ? 'Edit Project' : 'New Project'; ?></h2>
  <?php if (!empty($error)): ?><div class="bg-red-100 text-red-800 p-2 rounded mb-3"><?php echo e($error); ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-3"><?php echo e($success); ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id" value="<?php echo e($project['id'] ?? ''); ?>">
    <label class="block mb-2">Name
      <input name="name" class="w-full p-2 border rounded" required value="<?php echo e($project['name'] ?? ''); ?>">
    </label>
    <label class="block mb-2">Description
      <textarea name="description" class="w-full p-2 border rounded" rows="6"><?php echo e($project['description'] ?? ''); ?></textarea>
    </label>
    <label class="block mb-2">Target Amount
      <input name="target_amount" type="number" step="0.01" class="w-full p-2 border rounded" value="<?php echo e($project['target_amount'] ?? '0'); ?>">
    </label>
    <label class="block mb-2">Payment Link
      <input name="payment_link" class="w-full p-2 border rounded" value="<?php echo e($project['payment_link'] ?? ''); ?>">
    </label>
    <label class="block mb-2">Image
      <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
    </label>
    <?php if (!empty($project['image'])): ?>
      <div class="mb-3"><img src="<?php echo base_url('uploads/' . $project['image']); ?>" class="h-32 object-cover rounded"></div>
    <?php endif; ?>
    <button class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium"><i class="bi bi-check-circle"></i> <?php echo $project ? 'Update' : 'Create'; ?></button>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
