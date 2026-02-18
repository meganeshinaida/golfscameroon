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
    <header class="bg-white  border-gray-200">
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
            <div class="w-full md:w-1/2 rotate-y-90" data-reveal>
                <div class="bg-white  overflow-hidden shadow-lg transform rotate-x-15 w-[200px] p-4 ">
                    <img src="<?php echo asset_url('uploads/heroImage.png'); ?>" alt="Youth program" class=" object-center ">
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 ">
        <!-- section The Future Begins With Our Yout -->
        <section id="services" class="py-8 my-[2vw]" data-reveal>
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
        <!-- section Cameroon Youth Leadership Initiative -->
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
        <section id="top-projects" data-reveal>
            <h1 class="text-2xl md:text-3xl font-bold text-center mx-8">Our work</h1>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 my-12" >
            <div class="bg-white rounded shadow shadow-sm   overflow-hidden  " data-reveal>
                <img src="<?php echo asset_url('uploads/leadership.jpg'); ?>" alt="leadership" class="h-2/3 w-full object-cover">
                <div class="px-2 ">
                <h3 class="font-semibold text-xl text-green-700 flex justify-between"> <span> Leadership Development</span> <i class="bi bi-chevron-right text-green-700 "></i></h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">Mentorship, coaching, and youth leadership engagement programs designed to raise confident and purpose-driven changemakers.</p>
                </div>
                
            </div>
            <div class="bg-white rounded shadow shadow-sm   overflow-hidden" data-reveal>
                <img src="<?php echo asset_url('uploads/school_engagement.jpg'); ?>" alt="school_engagement" class="h-2/3  w-full object-cover ">
                <div class="px-2">
                <h3 class="font-semibold text-xl text-green-700 flex justify-between"> <span> Education & School Engagemen</span><i class="bi bi-chevron-right text-green-700 "></i></h3>
                <p class="text-sm text-gray-600  mt-2  mx-2 ">School visits, student recognition, and academic empowerment initiatives that promote discipline, excellence, and growth..</p>
                </div>
                
            </div>
            <div class="bg-white rounded shadow shadow-sm   overflow-hidden" data-reveal>
                <img src="<?php echo asset_url('uploads/community_outreach.png'); ?>" alt="community_outreach" class="h-2/3  w-full object-cover ">
                <div class="px-2">
                <h3 class="font-semibold text-xl text-green-700 flex justify-between"> <span> Community Outreach</span> <i class="bi bi-chevron-right text-green-700 "></i></h3>
                <p class="text-sm text-gray-600  mt-2  mx-2 ">Community support initiatives and social impact activities that strengthen underserved areas and promote collective development.</p>
                </div>
                
            </div>
            <div class="bg-white rounded shadow shadow-sm   overflow-hidden" data-reveal>
                <img src="<?php echo asset_url('uploads/global_patnership.jpg'); ?>" alt="global_partnership" class="h-2/3  w-full object-cover ">
                <div class="px-2">
                <h3 class="font-semibold text-xl text-green-700  flex justify-between"> <span> Global Partnerships</span> <i class="bi bi-chevron-right text-green-700 "></i></h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">International collaborations and cross-border youth engagement across Cameroon, Liberia, and Equatorial Guinea.</p>
                </div>
                
            </div>
            </div>
        </section>
          <section id="get involved" data-reveal>
            <h1 class="text-green-700 text-2xl md:text-3xl font-semibold text-center mx-8 capitalize">ways to get involved</h1>
            <div class="grid grid-cols-2 gap-10 my-12" >
            <div class=" flex justify-between gap-8 bg-white shadow shadow-sm   overflow-hidden p-1 " data-reveal>
                <img src="<?php echo asset_url('uploads/leadership.jpg'); ?>" alt="leadership" class="w-1/2 object-cover ">
                <div class="px-2 my-4">
                <h3 class="font-semibold text-xl text-green-700">Become a Volunteer</h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">Mentorship, coaching, and youth leadership engagement programs designed to raise confident and purpose-driven changemakers.</p>
                   <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
             Join us
               </a></button>
                </div>
                
            </div>
            <div class=" flex justify-between gap-8 bg-white shadow shadow-sm   overflow-hidden p-1 " data-reveal>
                <img src="<?php echo asset_url('uploads/global_patnership.jpg'); ?>" alt="leadership" class="w-1/2 object-cover ">
                <div class="px-2 my-4">
                <h3 class="font-semibold text-xl text-green-700">Partner With Us</h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">Collaborate with us as an organization, institution, or corporate body. Together, we can expand opportunities for youth across borders.</p>
                   <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
             partner now
               </a></button>
                </div>
                
            </div>
             <div class=" flex justify-between gap-8 bg-white shadow shadow-sm   overflow-hidden p-1 " data-reveal>
                <img src="<?php echo asset_url('uploads/leadership.jpg'); ?>" alt="leadership" class="w-1/2 object-cover ">
                <div class="px-2 my-4">
                <h3 class="font-semibold text-xl text-green-700">Support the Missionr</h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">Contribute resources that help us run leadership programs and community outreach initiatives. Every contribution helps shape future changemakers.</p>
                   <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
           Support Now
               </a></button>
                </div>
                
            </div>
             <div class=" flex justify-between gap-8 bg-white shadow shadow-sm   overflow-hidden  p-1" data-reveal>
                <img src="<?php echo asset_url('uploads/leadership.jpg'); ?>" alt="leadership" class="w-1/2 object-cover ">
                <div class="px-2 my-4">
                <h3 class="font-semibold text-xl text-green-700">Join the Youth Network</h3>
                <p class="text-sm text-gray-600 mt-2  mx-2 ">Are you a young leader or aspiring professional? Connect with a growing network of purpose-driven youth.</p>
                   <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
            Join the Network
               </a></button>
                </div>
                
            </div>
            </div>
        </section>
        <section id="" class="   bg-brand text-white grid md:grid-cols-2 gap-6 justify-center items-center mb-12  py-8" data-reveal>
            <div class="flex justify-center items-center">
                <h2 class="text-3xl font-semibold text-green-700 mb-4 ">“Our youths are not the problem, they are the promise.”</h2>
           
            </div>
                
             <div class="bg-white w-[710px] h-[544px] shadow-lg p-4 transform translate-y-2" data-reveal>
                <img src="<?php echo asset_url('uploads/initiative.jpg'); ?>" alt="initiative" class="w-full object-cover ">
            </div>
            
        </section>
        <section class="bg-[#639E82] py-8 mb-12" data-reveal>
        <div class="flex justify-center items-center flex-col leading-[2] my-8 gap-1 text-center ">
            <h2 class="font-semibold text-2xl text-green-700 tex-center uppercase">
                We have the youth, the talent,<br> the drive
            </h2>
            <p class="text-sm text-gray-600 mt-2  mx-2 text-center">All we need is the opportunity</p>
             <button><a href="<?php echo base_url('members'); ?>" class="inline-block bg-red-700 text-white px-4 py-2 mt-4 transition font-medium">
        Donate today
               </a></button>
        </div>    
        </section>
        <section id="asked questions" class="py-8 my-[2vw]" data-reveal>
            <h1 class="text-2xl md:text-4xl font-semibold text-center mx-8 text-green-700">Frequently asked questions</h1>
            <div class="grid md:grid-cols-3 gap-6 my-12" >
                <div class="text-left">
                <h3 class="font-bold text-2xl  mb-4 text-green-700"> What about boys?</h3>
                <p class="mt-2 text-sm text-gray-600">Whether you choose to sponsor a girl or a boy, you’ll help projects focused on equal opportunities for all children. We know girls are the most vulnerable and we ensure that boys play an important role in building secure communities that value girls.</p>
            </div>
             <div class="text-left">
                <h3 class="font-bold text-2xl  mb-4 text-green-700"> Is sponsorship like adoption?</h3>
                <p class="mt-2 text-sm text-gray-600">No, it's not. The girl you sponsor will
                have a family of her own, but your words
                of encouragement play an important role
                for her and her community. Letter writing
                also helps girls learn about other cultures
                and improve their literacy skills.</p>
            </div>
            <div class="text-left">
                <h3 class="font-bold text-2xl  mb-4 text-green-700">How can I get more
                information?</h3>
                <p class="mt-2 text-sm text-gray-600">Our Supporter Engagement team are
                happy to answer any questions about
                sponsorship and our work. You can
                reach them on 0300 777 9779 or
                supporterquestions@plan-uk.org.</p>
            </div>
            </div>
            <div class="py-10 text-center">
                <p class="text-lg text-green-700 text-center font-bold">More FAQs <i class="bi bi-chevron-right text-green-700  mx-2"></i></p>
            </div>
        </section>
        <section id="why_choose_golf" class="py-8 my-[2vw] flex justify-center item-center " data-reveal>
            <div class="flex flex-col  justify-center items-center w-2/3">
                <h1 class="text-2xl md:text-3xl font-semibold text-center mx-8 text-green-700 uppercase">Why choose THE GOLFS CAMeroon?</h1>
                <p class="mt-2 text-sm text-gray-600 ">We are a youth-focused organization striving to empower the next generation of leaders and changemakers. For years, we’ve worked alongside young people and their communities to ensure every youth can reach their full potential and every young person has access to guidance, mentorship, and opportunities. We bring people together to nurture talent, build skills, and create pathways to leadership, even in underserved areas.</p>
        
            </div>
            
        </section>
        <!-- <section id="members" data-reveal>
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
        </section> -->
    
    </main>

  <!-- Donation Modal -->
  <!-- <div id="donateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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
  </div> -->

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
