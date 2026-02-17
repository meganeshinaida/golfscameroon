<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Blog.php';

if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$model = new Blog();

// Handle CSV export BEFORE layout (before any output)
if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
    $allPosts = $model->all() ?? [];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="blogs.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $out = fopen('php://output','w');
    fputcsv($out, ['id','title','author','created_at']);
    foreach ($allPosts as $p) fputcsv($out, [$p['id'],$p['title'],$p['author'] ?? '',$p['created_at']]);
    fclose($out); exit;
}

// Now include layout after export check
require_once __DIR__ . '/layout.php';

// handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'delete') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
    else { $id = (int)($_POST['id'] ?? 0); if ($model->delete($id)) $msg = 'Deleted'; else $msg = 'Delete failed'; }
}

// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$all = $model->all();
$total = count($all);
$posts = array_slice($all, ($page-1)*$perPage, $perPage);
$pages = (int)ceil($total / $perPage);

// bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'bulk') {
  if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
  else { $ids = $_POST['ids'] ?? []; foreach ($ids as $id) $model->delete((int)$id); $msg = 'Selected deleted'; }
}
?>
<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold text-gray-800">Blog Posts</h2>
  <div class="flex gap-2">
    <a href="<?php echo base_url('admin/blog-form'); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium"><i class="bi bi-plus-circle"></i> New Post</a>
    <a href="?export=csv" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-medium"><i class="bi bi-download"></i> Export CSV</a>
  </div>
</div>

<?php if (!empty($msg)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-4"><?php echo e($msg); ?></div><?php endif; ?>

<div class="bg-white rounded shadow overflow-hidden">
  <form method="post">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="_action" value="bulk">
    <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
          <th class="p-3 text-left"><input id="blogs-select-all" type="checkbox"></th>
          <th class="p-3 text-left">Title</th>
          <th class="p-3 text-left">Author</th>
          <th class="p-3 text-left">Date</th>
          <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr class="border-t">
          <td class="p-3"><input type="checkbox" name="ids[]" value="<?php echo e($p['id']); ?>"></td>
          <td class="p-3"><?php echo e($p['title']); ?></td>
          <td class="p-3"><?php echo e($p['author'] ?? ''); ?></td>
          <td class="p-3"><?php echo e($p['created_at']); ?></td>
          <td class="p-3">
          <a class="text-green-600 hover:underline font-medium mr-3" href="<?php echo base_url('admin/blog-form') . '?id=' . e($p['id']); ?>"><i class="bi bi-pencil-square"></i> Edit</a>
          <form method="post" style="display:inline" onsubmit="return confirm('Delete post?')">
            <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_action" value="delete">
            <input type="hidden" name="id" value="<?php echo e($p['id']); ?>">
            <button class="text-red-600 hover:underline font-medium"><i class="bi bi-trash"></i> Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    </table>
    <div class="p-3">
      <button id="blogs-delete-selected" type="button" class="bg-red-600 text-white px-3 py-1 rounded">Delete Selected</button>
    </div>
  </form>
  <script>
    selectAll('#blogs-select-all','input[name="ids[]"]');
    document.getElementById('blogs-delete-selected').addEventListener('click', function(){
      showConfirm('Delete selected posts?', function(){ document.querySelector('form').submit(); });
    });
  </script>
  <div class="p-4">
    <?php if ($pages > 1): ?>
      <div class="flex gap-2">
        <?php for ($i=1;$i<=$pages;$i++): ?>
          <a href="?page=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo $i==$page? 'bg-gray-800 text-white':'bg-gray-200'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
