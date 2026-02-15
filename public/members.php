<?php
require_once __DIR__ . '/../models/Member.php';
$page_title = 'Members';
$memberModel = new Member();
$members = $memberModel->all();
include __DIR__ . '/header.php';
?>
  <header class="bg-gradient-to-r from-green-700 to-red-600 p-6 text-white">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold">Our Members</h1>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php foreach ($members as $m): ?>
        <div class="bg-white rounded shadow p-4">
          <div class="h-40 bg-gray-100 rounded mb-4 flex items-center justify-center overflow-hidden">
              <?php if (!empty($m['image'])): ?>
              <img src="<?php echo base_url('uploads/' . $m['image']); ?>" alt="<?php echo e($m['name']); ?>" class="object-cover h-full w-full">
            <?php else: ?>
              <span class="text-gray-400">No image</span>
            <?php endif; ?>
          </div>
          <h3 class="font-semibold text-lg"><?php echo e($m['name']); ?></h3>
          <p class="text-sm text-gray-600"><?php echo e($m['role']); ?></p>
          <p class="mt-2 text-sm text-gray-700"><?php echo e(substr($m['bio'],0,120)); ?>...</p>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
