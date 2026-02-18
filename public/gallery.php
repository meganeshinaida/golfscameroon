<?php
require_once __DIR__ . '/../models/Gallery.php';

$page_title = 'Gallery';
$model = new Gallery();
$items = $model->all();

include __DIR__ . '/header.php';
?>
  <header class="bg-white border-b border-gray-200 p-6" style="background-image: linear-gradient(rgba(64, 74, 63, 0.7), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') ">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-700">Gallery</h1>
      <p class="mt-2 text-white">Moments from our community programs, workshops, and events.</p>
    </div>
  </header>

  <main class="max-w-6xl mx-auto p-6">
    <?php if (empty($items)): ?>
      <div class="text-center text-gray-600">No gallery images yet.</div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($items as $g): ?>
          <?php
            $thumb = 'uploads/gallery/thumbs/' . pathinfo($g['image'], PATHINFO_FILENAME) . '.webp';
            $thumbPath = __DIR__ . '/../' . $thumb;
            $imgSrc = file_exists($thumbPath) ? $thumb : ('uploads/gallery/' . $g['image']);
          ?>
          <div class="bg-white rounded shadow overflow-hidden">
            <img src="<?php echo base_url($imgSrc); ?>" alt="<?php echo e($g['title'] ?? 'Gallery image'); ?>" class="w-full h-56 object-cover">
            <?php if (!empty($g['title'])): ?>
              <div class="p-3 text-center text-sm font-medium text-gray-700"><?php echo e($g['title']); ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
