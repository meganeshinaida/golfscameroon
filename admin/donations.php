<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Donation.php';
require_once __DIR__ . '/../models/Project.php';

$donationModel = new Donation();
$projectModel = new Project();

// filters
$filters = [];
if (!empty($_GET['project_id'])) $filters['project_id'] = (int)$_GET['project_id'];
if (!empty($_GET['from'])) $filters['from'] = $_GET['from'];
if (!empty($_GET['to'])) $filters['to'] = $_GET['to'];

$allDonations = $donationModel->allWithDetails($filters);
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$total = count($allDonations);
$pages = (int)ceil($total / $perPage);
$donations = array_slice($allDonations, ($page-1)*$perPage, $perPage);
$projects = $projectModel->all();

// handle export
if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="donations.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id','donor_name','donor_email','project','amount','date']);
    foreach ($donations as $d) {
        fputcsv($out, [$d['id'],$d['donor_name'],$d['donor_email'],$d['project_name'],$d['amount'],$d['created_at']]);
    }
    fclose($out);
    exit;
}

?>
<div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold">Donations</h2>
  <div>
    <a href="?export=csv" class="bg-gray-800 text-white px-3 py-2 rounded">Export CSV</a>
  </div>
</div>

<form method="get" class="mb-4 flex gap-2">
  <select name="project_id" class="p-2 border rounded">
    <option value="">All projects</option>
    <?php foreach ($projects as $p): ?>
      <option value="<?php echo e($p['id']); ?>" <?php if(!empty($filters['project_id']) && $filters['project_id']==$p['id']) echo 'selected'; ?>><?php echo e($p['name']); ?></option>
    <?php endforeach; ?>
  </select>
  <input type="date" name="from" class="p-2 border rounded" value="<?php echo e($filters['from'] ?? ''); ?>">
  <input type="date" name="to" class="p-2 border rounded" value="<?php echo e($filters['to'] ?? ''); ?>">
  <button class="bg-blue-600 text-white px-3 py-2 rounded">Filter</button>
</form>

<div class="bg-white rounded shadow">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="p-3 text-left">#</th>
        <th class="p-3 text-left">Donor</th>
        <th class="p-3 text-left">Project</th>
        <th class="p-3 text-left">Amount</th>
        <th class="p-3 text-left">Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($donations as $d): ?>
        <tr class="border-t">
          <td class="p-3"><?php echo e($d['id']); ?></td>
          <td class="p-3"><?php echo e($d['donor_name']); ?><br><span class="text-xs text-gray-600"><?php echo e($d['donor_email']); ?></span></td>
          <td class="p-3"><?php echo e($d['project_name']); ?></td>
          <td class="p-3"><?php echo number_format($d['amount'],2); ?></td>
          <td class="p-3"><?php echo e($d['created_at']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="p-4">
  <?php if ($pages > 1): ?>
    <div class="flex gap-2">
      <?php for ($i=1;$i<=$pages;$i++): ?>
        <a href="?page=<?php echo $i; ?>" class="px-3 py-1 rounded <?php echo $i==$page? 'bg-gray-800 text-white':'bg-gray-200'; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
