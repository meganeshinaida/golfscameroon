  <footer class="bg-green-800 text-white p-6 mt-12">
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-6">
      <div>
        <h4 class="font-semibold"><?php echo e(get_setting('site_name', 'Golfs Cameroon')); ?></h4>
        <p class="text-sm text-gray-300 mt-2"><?php echo e(get_setting('site_description', 'Empowering youth through education and community support.')); ?></p>
      </div>
      <div>
        <h4 class="font-semibold">Contact</h4>
        <p class="text-sm text-gray-300 mt-2">
          <?php $email = get_setting('contact_email', 'info@golfs-cameroon.org'); ?>
          <?php $address = get_setting('address', 'Yaounde, Cameroon'); ?>
          <?php echo e($email); ?><br/><?php echo e($address); ?>
        </p>
      </div>
      <div>
        <h4 class="font-semibold">Follow</h4>
        <div class="flex gap-2 mt-2 text-sm">
          <?php $twitter = get_setting('social_twitter', ''); ?>
          <?php if (!empty($twitter)): ?>
            <a href="<?php echo e($twitter); ?>" class="text-gray-100 hover:text-red-300">Twitter</a>
          <?php endif; ?>
          <?php $facebook = get_setting('social_facebook', ''); ?>
          <?php if (!empty($facebook)): ?>
            <a href="<?php echo e($facebook); ?>" class="text-gray-100 hover:text-red-300">Facebook</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
