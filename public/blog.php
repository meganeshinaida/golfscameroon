<?php
require_once __DIR__ . '/../models/Blog.php';
$page_title = 'Blog';
$blogModel = new Blog();
$posts = $blogModel->all();
include __DIR__ . '/header.php';
?>
  <header class="bg-gradient-to-r from-green-700 to-red-600 p-6 text-white">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold">News & Blog</h1>
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
            <a href="<?php echo base_url('public/blog_single.php?id=' . $p['id']); ?>" class="inline-block mt-3 text-green-700 hover:text-red-600">Read more</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
