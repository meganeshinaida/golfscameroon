<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Member.php';

$model = new Member();

// handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'delete') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
    else { $id = (int)($_POST['id'] ?? 0); if ($model->delete($id)) $msg = 'Deleted'; else $msg = 'Delete failed'; }
}

// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$all = $model->all();
$total = count($all);
$members = array_slice($all, ($page-1)*$perPage, $perPage);
$pages = (int)ceil($total / $perPage);

// bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'bulk') {
  if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
  else { $ids = $_POST['ids'] ?? []; foreach ($ids as $id) $model->delete((int)$id); $msg = 'Selected deleted'; }
}
// export CSV
if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
    $allMembers = $model->all();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="members.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['id','name','role','created_at']);
    foreach ($allMembers as $m) fputcsv($out, [$m['id'],$m['name'],$m['role'],$m['created_at']]);
    fclose($out); exit;
}
?>
<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold text-gray-800">Members</h2>
  <div class="flex gap-2">
    <a href="<?php echo base_url('admin/member-form'); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium"><i class="bi bi-plus-circle"></i> New Member</a>
    <a href="?export=csv" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-medium"><i class="bi bi-download"></i> Export CSV</a>
  </div>
</div>

<div class="mb-3">
  <label class="inline-flex items-center"><input id="members-select-all" type="checkbox" class="mr-2"> Select all visible</label>
</div>

<?php if (!empty($msg)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-4"><?php echo e($msg); ?></div><?php endif; ?>

<form method="post" id="members-bulk-form">
  <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="_action" value="bulk">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    <?php foreach ($members as $m): ?>
      <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-start">
          <div class="flex gap-4">
            <div class="h-36 w-36 bg-gray-100 rounded overflow-hidden">
              <?php
                $imgUrl = '';
                if (!empty($m['image'])) {
                    $thumbPath = __DIR__ . '/../uploads/thumbs/' . pathinfo($m['image'], PATHINFO_FILENAME) . '.webp';
                    if (file_exists($thumbPath)) {
                        $imgUrl = base_url('uploads/thumbs/' . pathinfo($m['image'], PATHINFO_FILENAME) . '.webp');
                    } else {
                        $imgUrl = base_url('uploads/' . $m['image']);
                    }
                }
              ?>
              <?php if (!empty($imgUrl)): ?>
                <img src="<?php echo e($imgUrl); ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="flex items-center justify-center h-full text-gray-400">No image</div>
              <?php endif; ?>
            </div>
            <div>
              <h3 class="font-semibold"><?php echo e($m['name']); ?></h3>
              <p class="text-sm text-gray-600"><?php echo e($m['role']); ?></p>
              <p class="text-sm mt-2"><?php echo e(substr($m['bio'],0,160)); ?></p>
            </div>
          </div>
          <div>
            <input type="checkbox" name="ids[]" value="<?php echo e($m['id']); ?>">
          </div>
        </div>
        <div class="mt-3">
          <a class="text-green-600 hover:underline font-medium mr-3" href="<?php echo base_url('admin/member-form') . '?id=' . e($m['id']); ?>"><i class="bi bi-pencil-square"></i> Edit</a>
          <form method="post" style="display:inline" onsubmit="return confirm('Delete member?')">
            <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_action" value="delete">
            <input type="hidden" name="id" value="<?php echo e($m['id']); ?>">
            <button class="text-red-600 hover:underline font-medium"><i class="bi bi-trash"></i> Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="mt-4">
    <button id="members-delete-selected" type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium"><i class="bi bi-trash"></i> Delete Selected</button>
  </div>
</form>

<div class="p-4">
  <?php if ($pages > 1): ?>
    <div class="flex gap-2">
      <?php for ($i=1;$i<=$pages;$i++): ?>
        <a href="?page=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo $i==$page? 'bg-gray-800 text-white':'bg-gray-200'; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<script>
  // init simple bulk select feature by delegating to select-all if used later
  // wire delete selected
  selectAll('#members-select-all','input[name="ids[]"]');
  document.getElementById('members-delete-selected').addEventListener('click', function(){
    showConfirm('Delete selected members? This action cannot be undone.', function(){ document.getElementById('members-bulk-form').submit(); });
  });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
