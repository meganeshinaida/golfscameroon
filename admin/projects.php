<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Project.php';

if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$projectModel = new Project();

// Handle CSV export BEFORE layout (before any output)
if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
    $all = $projectModel->all() ?? [];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="projects.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id','name','target_amount','payment_link','created_at']);
    foreach ($all as $r) fputcsv($out, [$r['id'],$r['name'],$r['target_amount'],$r['payment_link'],$r['created_at']]);
    fclose($out); exit;
}

// Now include layout after export check
require_once __DIR__ . '/layout.php';

// Individual delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_action']) && $_POST['_action'] === 'delete') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
    else {
        $id = (int) ($_POST['id'] ?? 0);
        if ($projectModel->delete($id)) { $msg = 'Project deleted'; }
        else { $msg = 'Delete failed'; }
    }
}

// Bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_action']) && $_POST['_action'] === 'bulk') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $msg = 'Invalid CSRF token'; }
    else {
        $ids = $_POST['ids'] ?? [];
        if (is_array($ids)) {
            foreach ($ids as $id) { $projectModel->delete((int)$id); }
            $msg = 'Selected projects deleted';
        }
    }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$allProjects = $projectModel->all() ?? [];
$total = count($allProjects);
$projects = array_slice($allProjects, ($page-1)*$perPage, $perPage);
$pages = (int)ceil($total / $perPage);
?>
<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold text-gray-800">Projects</h2>
  <div class="flex gap-2">
    <a href="<?php echo base_url('admin/project-form'); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium"><i class="bi bi-plus-circle"></i> New Project</a>
    <a href="?export=csv" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition font-medium"><i class="bi bi-download"></i> Export CSV</a>
  </div>
</div>

<?php if (!empty($msg)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-4"><?php echo e($msg); ?></div><?php endif; ?>

<div class="bg-white rounded shadow overflow-hidden">
  <form method="post" id="projects-bulk-form">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="_action" value="bulk">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-3 text-left"><input id="proj-select-all" type="checkbox"></th>
          <th class="p-3 text-left">#</th>
          <th class="p-3 text-left">Name</th>
          <th class="p-3 text-left">Target</th>
          <th class="p-3 text-left">Created</th>
          <th class="p-3 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
          <tr class="border-t">
            <td class="p-3"><input type="checkbox" name="ids[]" value="<?php echo e($p['id']); ?>"></td>
            <td class="p-3"><?php echo e($p['id']); ?></td>
            <td class="p-3"><?php echo e($p['name']); ?></td>
            <td class="p-3"><?php echo number_format($p['target_amount'],2); ?></td>
            <td class="p-3"><?php echo e($p['created_at']); ?></td>
            <td class="p-3">
              <a class="text-green-600 hover:underline font-medium mr-3" href="<?php echo base_url('admin/project-form') . '?id=' . e($p['id']); ?>"><i class="bi bi-pencil-square"></i> Edit</a>
              <form method="post" style="display:inline" onsubmit="return confirm('Delete project?')">
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
      <button id="proj-delete-selected" type="button" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium"><i class="bi bi-trash"></i> Delete Selected</button>
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
</div>

<script>
  // init select-all
  selectAll('#proj-select-all', 'input[name="ids[]"]');
  document.getElementById('proj-delete-selected').addEventListener('click', function(){
    showConfirm('Delete selected projects? This action is permanent.', function(){ document.getElementById('projects-bulk-form').submit(); });
  });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
