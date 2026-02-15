<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/Donor.php';
require_once __DIR__ . '/../models/Donation.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../models/Blog.php';

$database = new Database();
$db = $database->getConnection();

$counts = [];
$counts['projects'] = $db->query('SELECT COUNT(*) as c FROM projects')->fetch()['c'] ?? 0;
$counts['donors'] = $db->query('SELECT COUNT(*) as c FROM donors')->fetch()['c'] ?? 0;
$counts['donations'] = $db->query('SELECT COUNT(*) as c FROM donations')->fetch()['c'] ?? 0;
$counts['members'] = $db->query('SELECT COUNT(*) as c FROM members')->fetch()['c'] ?? 0;
$counts['blogs'] = $db->query('SELECT COUNT(*) as c FROM blogs')->fetch()['c'] ?? 0;

// Prepare monthly donations chart data (last 6 months)
$stmt = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(amount) as total FROM donations GROUP BY ym ORDER BY ym DESC LIMIT 6");
$rows = array_reverse($stmt->fetchAll());
$chart_labels = [];
$chart_data = [];
foreach ($rows as $r) { $chart_labels[] = $r['ym']; $chart_data[] = (float)$r['total']; }
?>

<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600">Projects</h3>
        <div class="text-2xl font-bold"><?php echo e($counts['projects']); ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600">Donors</h3>
        <div class="text-2xl font-bold"><?php echo e($counts['donors']); ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600">Donations</h3>
        <div class="text-2xl font-bold"><?php echo e($counts['donations']); ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600">Members</h3>
        <div class="text-2xl font-bold"><?php echo e($counts['members']); ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-600">Blog Posts</h3>
        <div class="text-2xl font-bold"><?php echo e($counts['blogs']); ?></div>
    </div>
</section>

<section class="mt-6 bg-white p-4 rounded shadow">
    <h2 class="font-semibold mb-4">Monthly Donations</h2>
    <canvas id="donationsChart" height="120"></canvas>
</section>

<script>
    (function(){
        var ctx = document.getElementById('donationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{ label: 'Donations', data: <?php echo json_encode($chart_data); ?>, borderColor: 'rgba(34,197,94,1)', backgroundColor: 'rgba(34,197,94,0.15)', fill:true }]
            },
            options: { responsive:true, maintainAspectRatio:false }
        });
    })();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>