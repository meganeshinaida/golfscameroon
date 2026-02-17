<?php 
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../config/database.php';

$dbProjects = (new Project())->all();
$members = (new Member())->all();

// compute simple progress for projects using donations table if present
$database = new Database();
$db = $database->getConnection();
function project_progress($db, $project_id, $target) {
    try {
        $stmt = $db->prepare('SELECT SUM(amount) as s FROM donations WHERE project_id = :id');
        $stmt->execute([':id'=>$project_id]);
        $row = $stmt->fetch();
        $sum = $row['s'] ?? 0;
        $pct = $target > 0 ? min(100, round(($sum / $target) * 100)) : 0;
        return [$sum, $pct];
    } catch (Exception $e) { return [0,0]; }
}

$page_title = 'Home';
include __DIR__ . '/header.php';
?>
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto p-8 md:p-16 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1 text-center" data-reveal>
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight text-green-700">Empowering Cameroon&apos;s Youth through Education & Opportunity</h1>
                <p class="mt-4 text-lg md:text-xl text-gray-700">We create mentorship, skill-building, and scholarship programs to unlock potential and build resilient communities aligned with the UN SDGs.</p>
                <div class="mt-6 flex gap-3 justify-center">
                    <button onclick="openDonateModal(0, 'General Donation')" class="bg-green-600 text-white px-5 py-3 rounded-lg shadow hover:bg-green-700 transition font-medium">
                        <i class="bi bi-heart"></i> Donate Now
                    </button>
                    <a href="<?php echo base_url('members'); ?>" class="border border-green-600 text-green-700 px-5 py-3 rounded-lg hover:bg-green-50 transition font-medium">Join Us</a>
                </div>
            </div>
<<<<<<< HEAD
            <div class="w-full md:w-1/2" data-reveal>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="<?php echo asset_url('assets/hero-placeholder.jpg'); ?>" alt="Youth program" class="w-full h-64 object-cover">
=======
            <div class="w-full md:w-1/2 rotate-y-90" data-reveal>
                <div class="bg-white  overflow-hidden shadow-lg w-[250px] h-[200px] p-4 ">
                    <img src="<?php echo asset_url('uploads/heroImage.png'); ?>" alt="Youth program" class="w-full object-center ">
>>>>>>> master
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 space-y-12">
<<<<<<< HEAD
        <section id="services" class="grid md:grid-cols-3 gap-6" data-reveal>
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="font-semibold text-lg">Community Assistance</h3>
                <p class="mt-2 text-sm text-gray-600">Direct support, food drives and essential services for vulnerable communities.</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="font-semibold text-lg">Youth Mentorship</h3>
                <p class="mt-2 text-sm text-gray-600">Mentorship connecting youth to professionals and role models.</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="font-semibold text-lg">Educational Outreach</h3>
                <p class="mt-2 text-sm text-gray-600">Workshops, scholarships and learning resources for improved outcomes.</p>
            </div>
        </section>

=======
        <section id="services" class="py-8" data-reveal>
            <h1 class="text-2xl md:text-3xl font-bold text-center mx-8">The Future Begins With Our Youth</h1>
            <div class="grid md:grid-cols-3 gap-6 my-12" >
                <div class="text-left">
                <h3 class="font-bold text-2xl px-2">Who?</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
                <p class="mt-2 text-sm text-gray-600">The Golfs Cameroon is a youth-focused organization committed to raising future leaders, professionals, and entrepreneurs. We align our work with the United Nations Sustainable Development Goals to drive meaningful, lasting change.</p>
            </div>
            <div class="text-left">
                <h3 class="font-bold text-2xl px-2">What?</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
                <p class="mt-2 text-sm text-gray-600">We empower young people through education support, mentorship, leadership engagement, and community outreach. From school visits to community support initiatives, we inspire responsibility, hard work, and positive impact.</p>
            </div>
            <div class="text-left">
                <h3 class="font-bold text-2xl px-2">Where?</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
                <p class="mt-2 text-sm text-gray-600">Registered in Cameroon, we operate locally and internationally. Our work extends across Cameroon, Liberia, and Equatorial Guinea as we build a growing network of changemakers.</p>
            </div>
            </div>
        </section>
       <section id="initiative" class="py-8 bg-brand text-white grid md:grid-cols-2 gap-6 mb-12 py-8 " data-reveal>
            
            <div class="bg-white w-[710px] h-[544px] shadow-lg p-4" data-reveal>
                <img src="<?php echo asset_url('uploads/initiative.jpg'); ?>" alt="initiative" class="w-full object-cover ">
            </div>
            
            <article class="text-left">
                <h2 class="text-2xl md:text-3xl font-semibold text-green-700 mb-4">Cameroon Youth Leadership Initiative</h2>
                <p class="mt-4 text-lg text-gray-700  max-w-2xl mx-auto mb-2">Young people across Cameroon need opportunities to grow as future leaders, entrepreneurs, and changemakers.
               Through mentorship programs, school awards, and community workshops, we equip youth with the skills, confidence, and guidance to drive positive change in their communities.</p>
            <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
             Join the Movement!
            </a></button>
           </article>
            
        </section>
        <section  class="py-8" data-reveal>
            <h1 class="text-2xl md:text-3xl font-bold text-center mx-8">We're not stopping here</h1>
            <div class="grid md:grid-cols-3 gap-10 my-12" >
                <article>
                    <h2 class="font-bold text-green-700 text-center text-4xl">51%</h2>
               <p class="text-sm text-gray-600 mt-2 text-center">
                of young people in underserved communities still lack equal access to quality educational and leadership opportunities. When youth are held back, communities fall behind. 
               </p>
                </article>
                <article>
                    <h2 class="font-bold text-green-700 text-center text-4xl">1 in 7</h2>
               <p class="text-sm text-gray-600 mt-2 text-center">
                youths lacks the support system needed to develop their talents and skills. Our mission is to bridge that gap and prepare young people to shape the future.
               </p>
                </article>
                 <article>
                    <h2 class="font-bold text-green-700 text-center text-4xl">50%</h2>
               <p class="text-sm text-gray-600 mt-2 text-center">
               of young people complete school each year without access to career guidance or practical leadership training. education alone is not enough without direction and opportunity. 
               </p>
                </article>
               
            </div>
        </section>
>>>>>>> master
        <section id="top-projects" data-reveal>
            <div class="flex flex-col items-center justify-center mb-4 text-center">
                <h2 class="text-2xl font-semibold">Featured Projects</h2>
                <a href="<?php echo base_url('donations'); ?>" class="text-sm text-green-700 mt-1">See all projects →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach (array_slice($dbProjects,0,6) as $proj):
                    list($raised,$pct) = project_progress($db, $proj['id'], $proj['target_amount']);
                ?>
                <article class="bg-white rounded shadow p-4">
                    <h3 class="font-semibold"><?php echo e($proj['name']); ?></h3>
                    <p class="text-sm text-gray-600 mt-2"><?php echo e(substr($proj['description'],0,120)); ?>...</p>
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded h-3 overflow-hidden">
                            <div class="bg-green-500 h-3" style="width: <?php echo $pct; ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600 mt-1">
                            <span><?php echo $pct; ?>% funded</span>
                            <span><?php echo format_currency($raised); ?> raised</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="openDonateModal(<?php echo e($proj['id']); ?>, '<?php echo e($proj['name']); ?>')" class="inline-block bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                            <i class="bi bi-heart"></i> Donate
                        </button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="members" data-reveal>
            <h2 class="text-2xl font-semibold mb-4 text-center">Our Members</h2>
            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach (array_slice($members,0,8) as $m): ?>
                    <div class="bg-white p-4 rounded shadow text-center">
                        <div class="h-36 w-36 mx-auto mb-3 bg-gray-100 rounded-full overflow-hidden">
                            <?php if (!empty($m['image'])): ?>
                                <img src="<?php echo base_url('uploads/' . $m['image']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-gray-400">No image</div>
                            <?php endif; ?>
                        </div>
                        <h4 class="font-semibold"><?php echo e($m['name']); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e($m['role']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="impact" data-reveal>
            <h2 class="text-2xl font-semibold mb-4 text-center">How We Change The World</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow text-center">
                    <h4 class="font-semibold">Education</h4>
                    <p class="text-sm text-gray-600 mt-2">Scholarships and training programs that increase opportunities for youth.</p>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <h4 class="font-semibold">Mentorship</h4>
                    <p class="text-sm text-gray-600 mt-2">Long-term mentoring relationships providing guidance and networks.</p>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <h4 class="font-semibold">Community</h4>
                    <p class="text-sm text-gray-600 mt-2">Local initiatives that strengthen community resilience and wellbeing.</p>
                </div>
            </div>
        </section>

        <section id="testimonials" data-reveal>
            <h2 class="text-2xl font-semibold mb-4 text-center">Testimonials</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow text-center">"This program helped me get a scholarship." — A Beneficiary</div>
                <div class="bg-white p-6 rounded shadow text-center">"Volunteering changed how I see community service." — Volunteer</div>
                <div class="bg-white p-6 rounded shadow text-center">"We partnered with Golfs Cameroon for local outreach." — Partner Org</div>
            </div>
        </section>
<<<<<<< HEAD
=======
    
    
>>>>>>> master
    </main>

  <!-- Donation Modal -->
  <div id="donateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
    <div class="bg-green-700 text-white p-6 flex justify-between items-center">
        <h2 class="text-xl font-bold">Make a Donation</h2>
        <button onclick="closeDonateModal()" class="text-white hover:text-gray-200 text-2xl">&times;</button>
      </div>
      
      <form id="donateForm" method="post" action="<?php echo base_url('api/process_donation.php'); ?>" class="p-6 space-y-4">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="project_id" id="modal_project_id" value="">
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
          <p id="modal_project_name" class="font-semibold text-gray-900"></p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
          <input type="text" name="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
          <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Amount (USD) *</label>
          <input type="number" name="amount" step="0.01" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
        </div>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
          Continue to Payment
        </button>
      </form>
    </div>
  </div>

<?php include __DIR__ . '/footer.php'; ?>
    <script>
        function openDonateModal(projectId, projectName) {
          document.getElementById('modal_project_id').value = projectId;
          document.getElementById('modal_project_name').textContent = projectName;
          document.getElementById('donateModal').classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        }

        function closeDonateModal() {
          document.getElementById('donateModal').classList.add('hidden');
          document.body.style.overflow = 'auto';
          document.getElementById('donateForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('donateModal').addEventListener('click', function(e) {
          if (e.target === this) {
            closeDonateModal();
          }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            closeDonateModal();
          }
        });

        // initialize mobile nav and scroll reveal
        document.addEventListener('DOMContentLoaded', function(){
            initMobileMenu('#mobile-menu-btn', '#mobile-nav');
            initScrollReveal();
        });
    </script>
