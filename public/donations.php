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

// handle donation form post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) {
        $error = 'Invalid CSRF token';
    } else {
        $donorModel = new Donor($db);
        $donationModel = new Donation($db);

        $donorData = [
            'name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'location' => $_POST['location'] ?? null,
            'phone' => $_POST['phone'] ?? null,
        ];
        $donorModel->create($donorData);
        $donor_id = $donorModel->findLastInsertId();

        $project_id = (int) ($_POST['project_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);
        $donationModel->create($donor_id, $project_id, $amount);

        // fetch payment link
        $ps = $db->prepare('SELECT payment_link FROM projects WHERE id = :id');
        $ps->execute([':id'=>$project_id]);
        $p = $ps->fetch();
        $payment_link = $p['payment_link'] ?? '/';

        // redirect to payment link
        header('Location: ' . $payment_link);
        exit;
    }
}
$page_title = 'Donate';
include __DIR__ . '/header.php';
?>
  <header class="bg-gradient-to-r from-green-700 to-red-600 p-6 text-white">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold">Support Our Projects</h1>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-6">
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-800 p-3 rounded mb-4"><?php echo e($error); ?></div>
    <?php endif; ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-xl font-semibold mb-4">Active Projects</h2>
        <?php foreach ($projects as $proj):
            $progress = ($proj['target_amount'] > 0) ? rand(5,90) : 0; // placeholder for real calc
        ?>
          <div class="bg-white p-4 rounded shadow mb-4">
            <h3 class="font-semibold"><?php echo e($proj['name']); ?></h3>
            <p class="text-sm text-gray-700"><?php echo e(substr($proj['description'],0,160)); ?>...</p>
            <div class="mt-3">
              <div class="w-full bg-gray-200 rounded h-3 overflow-hidden">
                <div class="bg-green-500 h-3" style="width: <?php echo $progress; ?>%"></div>
              </div>
              <p class="text-sm text-gray-600 mt-1"><?php echo $progress; ?>% of <?php echo number_format($proj['target_amount'],2); ?></p>
            </div>
            <a href="#donate" data-project="<?php echo e($proj['id']); ?>" class="mt-3 inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Donate</a>
          </div>
        <?php endforeach; ?>
      </div>
      <div>
        <h2 class="text-xl font-semibold mb-4">Donate Now</h2>
        <form method="post" id="donateForm" action="">
          <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="project_id" id="project_id" value="<?php echo e($projects[0]['id'] ?? 0); ?>">
          <label class="block mb-2">Full name
            <input name="full_name" class="w-full p-2 border rounded" required>
          </label>
          <label class="block mb-2">Email
            <input type="email" name="email" class="w-full p-2 border rounded" required>
          </label>
          <label class="block mb-2">Location
            <input name="location" class="w-full p-2 border rounded">
          </label>
          <label class="block mb-2">Phone
            <input name="phone" class="w-full p-2 border rounded">
          </label>
          <label class="block mb-2">Amount (USD)
            <input name="amount" type="number" step="0.01" class="w-full p-2 border rounded" required>
          </label>
          <button class="bg-green-600 text-white px-4 py-2 rounded">Continue to Payment</button>
        </form>
      </div>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
  <script>
    // basic behavior: clicking Donate on a project sets the project id in the donate form
    document.querySelectorAll('a[data-project]').forEach(function(el){
      el.addEventListener('click', function(e){
        e.preventDefault();
        var pid = this.getAttribute('data-project');
        document.getElementById('project_id').value = pid;
        window.scrollTo({top: document.getElementById('donateForm').offsetTop - 20, behavior: 'smooth'});
      });
    });
  </script>
