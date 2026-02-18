<?php
require_once __DIR__ . '/../models/Blog.php';
$page_title = 'Blog';
$blogModel = new Blog();
$posts = $blogModel->all();
include __DIR__ . '/header.php';
?>
  <header class="bg-white border-b border-gray-200 p-6" style="background-image: linear-gradient(rgba(64, 74, 63, 0.7), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') ">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-600">News & Blog</h1>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach ($posts as $p): ?>
        <article class="bg-white rounded shadow overflow-hidden">
          <?php if (!empty($p['thumbnail'])): ?>
            <img src="<?php echo base_url('uploads/' . $p['thumbnail']); ?>" alt="<?php echo e($p['title']); ?>" class="w-full h-48 object-cover">
          <?php endif; ?>
          <div class="p-4">
            <h2 class="font-semibold text-xl"><?php echo e($p['title']); ?></h2>
            <p class="text-sm text-gray-600">By <?php echo e($p['author'] ?? 'Admin'); ?> • <?php echo e(date('M d, Y', strtotime($p['created_at']))); ?></p>
            <p class="mt-2 text-sm"><?php echo e(substr(strip_tags($p['content']),0,180)); ?>...</p>
            <a href="<?php echo base_url('blog/' . $p['id']); ?>" class="inline-block mt-3 text-green-700 hover:text-red-600">Read more</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
