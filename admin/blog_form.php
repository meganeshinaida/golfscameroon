<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../models/Blog.php';
require_once __DIR__ . '/../config/helpers.php';

$model = new Blog();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $id ? $model->find($id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? '')) { $error = 'Invalid CSRF token'; }
    else {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $thumbnailName = $post['thumbnail'] ?? null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $res = upload_image($_FILES['thumbnail'], __DIR__ . '/../uploads');
            if ($res['success']) $thumbnailName = $res['filename']; else $error = $res['error'];
        }

        $imageName = $post['image'] ?? null;
        if (!empty($_FILES['image']['name'])) {
            $res2 = upload_image($_FILES['image'], __DIR__ . '/../uploads');
            if ($res2['success']) $imageName = $res2['filename']; else $error = $res2['error'];
        }

        if (empty($title)) $error = 'Title required';
        if (empty($content)) $error = 'Content required';
        if (empty($error)) {
          $data = ['title'=>$title,'content'=>$content,'author_id'=>$_SESSION['user_id'] ?? null,'thumbnail'=>$thumbnailName,'image'=>$imageName];
          if (!empty($_POST['id'])) { $model->update((int)$_POST['id'], $data); $success = 'Updated'; $post = $model->find((int)$_POST['id']); }
          else { $model->create($data); $success = 'Created'; }
        }
    }
}
?>

<div class="max-w-3xl bg-white p-6 rounded shadow">
  <h2 class="text-xl font-semibold mb-4"><?php echo $post ? 'Edit Post' : 'New Post'; ?></h2>
  <?php if (!empty($error)): ?><div class="bg-red-100 text-red-800 p-2 rounded mb-3"><?php echo e($error); ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="bg-green-100 text-green-800 p-2 rounded mb-3"><?php echo e($success); ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id" value="<?php echo e($post['id'] ?? ''); ?>">
    <label class="block mb-2">Title
      <input name="title" class="w-full p-2 border rounded" required value="<?php echo e($post['title'] ?? ''); ?>">
    </label>
    <label class="block mb-2">Thumbnail (will generate webp thumb)
      <input type="file" name="thumbnail" accept="image/*" class="w-full p-2 border rounded">
    </label>
    <?php if (!empty($post['thumbnail'])): ?>
      <div class="mb-3"><img src="<?php echo base_url('uploads/thumbs/' . pathinfo($post['thumbnail'], PATHINFO_FILENAME) . '.webp'); ?>" class="h-24 object-cover rounded"></div>
    <?php endif; ?>

    <label class="block mb-2">Header Image
      <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded">
    </label>
    <?php if (!empty($post['image'])): ?>
      <div class="mb-3"><img src="<?php echo base_url('uploads/' . $post['image']); ?>" class="h-36 object-cover rounded"></div>
    <?php endif; ?>

    <label class="block mb-2">Content
      <textarea id="editor" name="content" class="w-full p-2 border rounded" rows="12"><?php echo e($post['content'] ?? ''); ?></textarea>
    </label>

    <button class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition font-medium"><i class="bi bi-check-circle"></i> Save</button>
  </form>
  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({ selector:'#editor', height:400, menubar:false, plugins:['link','image','lists','table','autolink','code'], toolbar:'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code' });
  </script>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
