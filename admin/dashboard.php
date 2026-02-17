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

// Prepare monthly donations chart data (last 12 months)
$months = [];
$amounts = [];
for ($i = 11; $i >= 0; $i--) {
    $date = new DateTime("first day of -$i months");
    $months[] = $date->format('M Y');
}

$currency = get_setting('default_currency', 'USD');
$symbol = $currency === 'XAF' ? 'FCFA' : ($currency === 'EUR' ? '€' : '$');
$decimals = $currency === 'XAF' ? 0 : 2;

$stmt = $db->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as ym, 
        SUM(amount) as total 
    FROM donations 
    GROUP BY ym 
    ORDER BY ym
");
$donationsByMonth = [];
foreach ($stmt->fetchAll() as $row) {
    $donationsByMonth[$row['ym']] = (float)$row['total'];
}

foreach ($months as $month) {
    $ym = DateTime::createFromFormat('M Y', $month)->format('Y-m');
    $amounts[] = $donationsByMonth[$ym] ?? 0;
}

$convertedAmounts = array_map(function($amt) use ($currency) {
    return convert_amount($amt, 'USD', $currency);
}, $amounts);

$totalAmount = convert_amount(array_sum($amounts), 'USD', $currency);
$nonZero = array_values(array_filter($convertedAmounts, function($v){ return $v > 0; }));
$avgAmount = count($nonZero) ? (array_sum($nonZero) / count($nonZero)) : 0;
$peakAmount = count($convertedAmounts) ? max($convertedAmounts) : 0;

// Visitor stats
$todayVisitors = 0;
$totalVisitors = 0;
$topPages = [];
try {
    $todayVisitors = $db->query("SELECT COUNT(*) as c FROM visitors WHERE DATE(visited_at) = CURDATE()")->fetch()['c'] ?? 0;
    $totalVisitors = $db->query("SELECT COUNT(*) as c FROM visitors")->fetch()['c'] ?? 0;
    $stmt = $db->query("
        SELECT page, COUNT(*) as count FROM visitors 
        WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY page ORDER BY count DESC LIMIT 5
    ");
    $topPages = $stmt->fetchAll();
} catch (Exception $e) {
    // visitors table may not exist yet
}

// Mini stats for last 7 days (projects, members, donations)
$projectsLast7 = $db->query("SELECT COUNT(*) as c FROM projects WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()['c'] ?? 0;
$membersLast7 = $db->query("SELECT COUNT(*) as c FROM members WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()['c'] ?? 0;
$donationsLast7 = $db->query("SELECT COUNT(*) as c FROM donations WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()['c'] ?? 0;

// Contact submissions
$contactMessages = [];
try {
    $contactMessages = $db->query("SELECT name, email, message, created_at FROM messages ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    // messages table may not exist yet
}
?>

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6 mb-6">
    <!-- Projects Card -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-600">Projects</p>
                <p class="text-3xl font-bold text-green-700"><?php echo e($counts['projects']); ?></p>
                <p class="text-xs text-gray-500 mt-2">+<?php echo e($projectsLast7); ?> this week</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full transition duration-300">
                <i class="bi bi-bullseye text-2xl text-green-700"></i>
            </div>
        </div>
    </div>

    <!-- Members Card -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-600">Members</p>
                <p class="text-3xl font-bold text-blue-700"><?php echo e($counts['members']); ?></p>
                <p class="text-xs text-gray-500 mt-2">+<?php echo e($membersLast7); ?> this week</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full transition duration-300">
                <i class="bi bi-people text-2xl text-blue-700"></i>
            </div>
        </div>
    </div>

    <!-- Donors Card -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-600">Donors</p>
                <p class="text-3xl font-bold text-purple-700"><?php echo e($counts['donors']); ?></p>
                <p class="text-xs text-gray-500 mt-2">Active supporters</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full transition duration-300">
                <i class="bi bi-heart text-2xl text-purple-700"></i>
            </div>
        </div>
    </div>

    <!-- Donations Card -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-600">Donations</p>
                <p class="text-3xl font-bold text-orange-700"><?php echo e($counts['donations']); ?></p>
                <p class="text-xs text-gray-500 mt-2">+<?php echo e($donationsLast7); ?> this week</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full transition duration-300">
                <i class="bi bi-currency-dollar text-2xl text-orange-700"></i>
            </div>
        </div>
    </div>

    <!-- Blog Posts Card -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-600">Blog Posts</p>
                <p class="text-3xl font-bold text-red-700"><?php echo e($counts['blogs']); ?></p>
                <p class="text-xs text-gray-500 mt-2">Content published</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full transition duration-300">
                <i class="bi bi-file-text text-2xl text-red-700"></i>
            </div>
        </div>
    </div>
</section>
<section class="admin-card mt-6 bg-white p-4 sm:p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4 text-gray-800"><i class="bi bi-graph-up"></i> Monthly Donation Trend (Last 12 Months)</h2>
    <div class="relative h-64 sm:h-80 lg:h-96 mb-5">
        <canvas id="donationsChart"></canvas>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="admin-card bg-green-50 p-4 rounded border border-green-200">
            <p class="text-sm text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-green-700"><?php echo format_currency($totalAmount, $currency); ?></p>
        </div>
        <div class="admin-card bg-blue-50 p-4 rounded border border-blue-200">
            <p class="text-sm text-gray-600">Average Monthly</p>
            <p class="text-2xl font-bold text-blue-700"><?php echo format_currency($avgAmount, $currency); ?></p>
        </div>
        <div class="admin-card bg-purple-50 p-4 rounded border border-purple-200">
            <p class="text-sm text-gray-600">Peak Month</p>
            <p class="text-2xl font-bold text-purple-700"><?php echo format_currency($peakAmount, $currency); ?></p>
        </div>
    </div>
</section>

<!-- Visitor Analytics -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6 pt-8 sm:pt-12">
    <!-- Total Visitors -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <h3 class="text-sm text-gray-600 mb-2">Total Visitors (All Time)</h3>
        <p class="text-3xl font-bold text-indigo-700"><?php echo e(number_format($totalVisitors)); ?></p>
        <p class="text-xs text-gray-500 mt-2">Unique page visits tracked</p>
    </div>

    <!-- Today's Visitors -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <h3 class="text-sm text-gray-600 mb-2">Today's Visitors</h3>
        <p class="text-3xl font-bold text-teal-700"><?php echo e(number_format($todayVisitors)); ?></p>
        <p class="text-xs text-gray-500 mt-2"><?php echo e(date('M d, Y')); ?></p>
    </div>

    <!-- Top Page -->
    <div class="admin-card bg-white p-4 sm:p-6 rounded-lg shadow">
        <h3 class="text-sm text-gray-600 mb-2">Most Visited Page</h3>
        <?php if (!empty($topPages)): ?>
            <p class="text-2xl font-bold text-cyan-700 truncate"><?php echo e(ucfirst(str_replace('_', ' ', $topPages[0]['page']))); ?></p>
            <p class="text-xs text-gray-500 mt-2"><?php echo e(number_format($topPages[0]['count'])); ?> visits (30d)</p>
        <?php else: ?>
            <p class="text-lg text-gray-400">No data</p>
        <?php endif; ?>
    </div>
</section>

<!-- Top Pages List -->
<?php if (!empty($topPages)): ?>
<section class="admin-card mb-6 bg-white p-4 sm:p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4 text-gray-800"><i class="bi bi-graph-up"></i> Top Pages (Last 30 Days)</h3>
    <div class="space-y-2">
        <?php foreach ($topPages as $idx => $pg): ?>
            <div class="flex items-center justify-between pb-2 border-b border-gray-100 transition duration-200 hover:bg-gray-50 px-2 rounded">
                <div class="flex items-center gap-3">
                    <span class="inline-block bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-full w-8 h-8 flex items-center justify-center"><?php echo ($idx + 1); ?></span>
                    <span class="text-gray-700"><?php echo e(ucfirst(str_replace('_', ' ', $pg['page']))); ?></span>
                </div>
                <span class="text-gray-600 font-medium"><?php echo e(number_format($pg['count'])); ?> visits</span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Contact Submissions -->
<section class="admin-card mb-6 bg-white p-4 sm:p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4 text-gray-800"><i class="bi bi-envelope"></i> Contact Submissions</h3>
    <?php if (empty($contactMessages)): ?>
        <p class="text-gray-500">No contact submissions yet.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm">
                <thead>
                    <tr class="text-left text-gray-600 border-b">
                        <th class="py-2 pr-4">Name</th>
                        <th class="py-2 pr-4">Email</th>
                        <th class="py-2 pr-4">Message</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactMessages as $msg): ?>
                        <tr class="border-b border-gray-100 align-top transition duration-200 hover:bg-gray-50">
                            <td class="py-3 pr-4 font-medium text-gray-800"><?php echo e($msg['name']); ?></td>
                            <td class="py-3 pr-4 text-gray-700"><?php echo e($msg['email']); ?></td>
                            <td class="py-3 pr-4 text-gray-700">
                                <div class="max-w-xl whitespace-pre-line break-words"><?php echo e($msg['message']); ?></div>
                            </td>
                            <td class="py-3 text-gray-600 whitespace-nowrap">
                                <?php echo e(date('M d, Y H:i', strtotime($msg['created_at']))); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<script>
    (function(){
        var ctx = document.getElementById('donationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Monthly Donations (<?php echo e($currency); ?>)',
                    data: <?php echo json_encode($convertedAmounts); ?>,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 14 },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return '<?php echo e($symbol); ?>' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: <?php echo $decimals; ?> });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '<?php echo e($symbol); ?>' + value.toLocaleString('en-US');
                            },
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    })();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>