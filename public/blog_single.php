<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Blog.php';

$id = $_GET['id'] ?? null;
$blogModel = new Blog();
$post = $id ? $blogModel->find($id) : null;
if (!$post) {
    http_response_code(404);
    echo 'Post not found';
    exit;
}
$page_title = $post['title'];
include __DIR__ . '/header.php';
?>
  <header class="bg-gradient-to-r from-green-700 to-red-600 p-6 text-white">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold"><?php echo e($post['title']); ?></h1>
      <p class="text-sm">By <?php echo e($post['author'] ?? 'Admin'); ?> • <?php echo e(date('M d, Y', strtotime($post['created_at']))); ?></p>
    </div>
  </header>
  <main class="max-w-4xl mx-auto p-6 bg-white mt-6 rounded shadow">
    <?php if (!empty($post['image'])): ?>
      <img src="<?php echo base_url('uploads/' . $post['image']); ?>" alt="" class="w-full h-64 object-cover rounded mb-4">
    <?php endif; ?>
    <div class="prose max-w-none">
      <?php echo $post['content']; ?>
    </div>
    <div class="mt-6">
      <a href="<?php echo base_url('public/blog.php'); ?>" class="text-green-700 hover:text-red-600">← Back to blog</a>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
