<?php 
$page_title = 'Services'; 
require_once __DIR__ . '/../config/components.php';
require_once __DIR__ . '/../config/data.php';
include __DIR__ . '/header.php'; 
?>
  <!-- <header class="bg-white border-b border-gray-200 p-6">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-700">Our Services</h1>
    </div>
  </header> -->
  <header class="bg-center h-screen/2  object-fit no-repeat bg-cover flex justify-center items-center" style="background-image: linear-gradient(rgba(56, 51, 51, 0.79), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') " >
        <div class="max-w-7xl mx-auto  p-8 md:p-16 flex flex-col md:flex-row items-center gap-8">
            <div class="text-center md:text-left text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Services</h1>
                <p class="text-lg md:text-xl">Empowering youth through education and community projects.</p>
                 <h1 class="text-4xl md:text-5xl font-semibold mb-4 flex justify-end" >“The Power of Positive Thinking”</h1>
            </div>
        </div>
    </header>

  <main class="max-w-6xl mx-auto p-6">
   <h1 class="text-2xl font-semibold mb-6 text-center">Our Focus Areas</h1> 
      <p class="text-center mb-6 text-gray-600">Guided by our motto, “The Power of Positive Thinking”, The Golfs Cameroon designs programs that empower young people, strengthen communities, and build meaningful partnerships across borders. Each focus area reflects our commitment to leadership, education, inclusion, and sustainable development.</p>

      <?php 
      $focus_areas = get_focus_areas();
      foreach ($focus_areas as $index => $area):
          $image_on_left = ($index % 2 == 0);
      ?>
          <?php render_focus_area($area, $image_on_left); ?>
      <?php endforeach; ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
      <?php foreach (get_service_features() as $feature): ?>
        <div class="p-6 bg-white rounded shadow">
          <h4 class="font-semibold"><?php echo e($feature['title']); ?></h4>
          <p class="mt-2 text-sm"><?php echo e($feature['description']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
