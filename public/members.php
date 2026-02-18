<?php
require_once __DIR__ . '/../models/Member.php';
$page_title = 'Members';
$memberModel = new Member();
$members = $memberModel->all();
include __DIR__ . '/header.php';
?>
  <header class="bg-white border-b border-gray-200 p-6" style="background-image: linear-gradient(rgba(64, 74, 63, 0.7), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') ">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-700">Our Members</h1>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php foreach ($members as $m): ?>
        <div class="bg-white rounded shadow text-center">
          <div class="h-40 bg-gray-100 rounded mb-4 flex items-center justify-center overflow-hidden h-2/3  ">
              <?php if (!empty($m['image'])): ?>
              <img src="<?php echo base_url('uploads/' . $m['image']); ?>" alt="<?php echo e($m['name']); ?>" class="object-cover bg-cover bg-center h-full w-full">
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