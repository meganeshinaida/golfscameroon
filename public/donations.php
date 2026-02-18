<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Donor.php';
require_once __DIR__ . '/../models/Donation.php';

$database = new Database();
$db = $database->getConnection();

// fetch projects to display
$projStmt = $db->query('SELECT * FROM projects ORDER BY created_at DESC');
$projects = $projStmt->fetchAll();

// compute project progress helper
function project_progress($db, $project_id, $target) {
  try {
    $stmt = $db->prepare('SELECT SUM(amount) as s FROM donations WHERE project_id = :id');
    $stmt->execute([':id'=>$project_id]);
    $row = $stmt->fetch();
    $sum = $row['s'] ?? 0;
    $pct = $target > 0 ? min(100, round(($sum / $target) * 100)) : 0;
    return [$sum, $pct];
  } catch (Exception $e) { return [0,0]; }
}

$page_title = 'Donate';
include __DIR__ . '/header.php';
?>
  <header class="bg-white border-b border-gray-200 p-6" style="background-image: linear-gradient(rgba(64, 74, 63, 0.7), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') ">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-500">Support Our Projects</h1>
      <p class="text-white mt-2">Help us make a difference by supporting any of our active projects.</p>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6 text-center">Active Projects</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($projects as $proj):
        list($raised,$progress) = project_progress($db, $proj['id'], $proj['target_amount']);
      ?>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <h3 class="text-lg font-semibold mb-2"><?php echo e($proj['name']); ?></h3>
          <p class="text-sm text-gray-700 mb-4"><?php echo e(substr($proj['description'],0,150)); ?>...</p>
          <div class="mb-4">
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
              <div class="bg-green-500 h-2" style="width: <?php echo $progress; ?>%"></div>
            </div>
            <p class="text-xs text-gray-600 mt-2"><?php echo $progress; ?>% funded • Goal: <?php echo format_currency($proj['target_amount']); ?></p>
          </div>
          <button class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium"
                  onclick="openDonateModal(<?php echo e($proj['id']); ?>, '<?php echo e($proj['name']); ?>')">
            <i class="bi bi-heart"></i> Donate Now
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Donation Modal -->
  <div id="donateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
      <div class="bg-green-700 text-white p-6 flex justify-between items-center">
        <h2 class="text-xl font-bold">Make a Donation</h2>
        <button onclick="closeDonateModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
      </div>
      
      <form id="donateForm" method="post" action="<?php echo base_url('api/process_donation.php'); ?>" class="p-6 space-y-4">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="project_id" id="modal_project_id" value="">
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
          <p id="modal_project_name" class="font-semibold text-gray-900"></p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
          <input type="text" name="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
          <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Amount (USD) *</label>
          <input type="number" name="amount" step="0.01" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
          Continue to Payment
        </button>
      </form>
    </div>
  </div>

<?php include __DIR__ . '/footer.php'; ?>

  <script>
    function openDonateModal(projectId, projectName) {
      document.getElementById('modal_project_id').value = projectId;
      document.getElementById('modal_project_name').textContent = projectName;
      document.getElementById('donateModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDonateModal() {
      document.getElementById('donateModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
      document.getElementById('donateForm').reset();
    }

    // Close modal when clicking outside
    document.getElementById('donateModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDonateModal();
      }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDonateModal();
      }
    });
  </script>

