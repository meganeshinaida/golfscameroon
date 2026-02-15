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
    <header class="bg-gradient-to-r from-green-700 to-red-600 text-white">
        <div class="max-w-6xl mx-auto p-8 md:p-16 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1" data-reveal>
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">Empowering Cameroon&apos;s Youth through Education & Opportunity</h1>
                <p class="mt-4 text-lg md:text-xl text-white/90">We create mentorship, skill-building, and scholarship programs to unlock potential and build resilient communities aligned with the UN SDGs.</p>
                <div class="mt-6 flex gap-3">
                    <a href="<?php echo base_url('donations'); ?>" class="bg-white text-green-700 px-5 py-3 rounded-lg shadow hover:scale-105 transform transition">Donate</a>
                    <a href="<?php echo base_url('members'); ?>" class="bg-white/20 border border-white text-white px-5 py-3 rounded-lg hover:bg-white/30 transition">Join Us</a>
                </div>
            </div>
            <div class="w-full md:w-1/2" data-reveal>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="<?php echo asset_url('assets/hero-placeholder.jpg'); ?>" alt="Youth program" class="w-full h-64 object-cover">
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 space-y-12">
        <section id="services" class="grid md:grid-cols-3 gap-6" data-reveal>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg">Community Assistance</h3>
                <p class="mt-2 text-sm text-gray-600">Direct support, food drives and essential services for vulnerable communities.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg">Youth Mentorship</h3>
                <p class="mt-2 text-sm text-gray-600">Mentorship connecting youth to professionals and role models.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg">Educational Outreach</h3>
                <p class="mt-2 text-sm text-gray-600">Workshops, scholarships and learning resources for improved outcomes.</p>
            </div>
        </section>

        <section id="top-projects" data-reveal>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">Featured Projects</h2>
                <a href="<?php echo base_url('donations'); ?>" class="text-sm text-green-700">See all projects →</a>
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
                            <span>$<?php echo number_format($raised,2); ?> raised</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo base_url('donations'); ?>" class="inline-block bg-green-600 text-white px-3 py-1 rounded">Donate</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="members" data-reveal>
            <h2 class="text-2xl font-semibold mb-4">Our Members</h2>
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
            <h2 class="text-2xl font-semibold mb-4">How We Change The World</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow">
                    <h4 class="font-semibold">Education</h4>
                    <p class="text-sm text-gray-600 mt-2">Scholarships and training programs that increase opportunities for youth.</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h4 class="font-semibold">Mentorship</h4>
                    <p class="text-sm text-gray-600 mt-2">Long-term mentoring relationships providing guidance and networks.</p>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h4 class="font-semibold">Community</h4>
                    <p class="text-sm text-gray-600 mt-2">Local initiatives that strengthen community resilience and wellbeing.</p>
                </div>
            </div>
        </section>

        <section id="testimonials" data-reveal>
            <h2 class="text-2xl font-semibold mb-4">Testimonials</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow">"This program helped me get a scholarship." — A Beneficiary</div>
                <div class="bg-white p-6 rounded shadow">"Volunteering changed how I see community service." — Volunteer</div>
                <div class="bg-white p-6 rounded shadow">"We partnered with Golfs Cameroon for local outreach." — Partner Org</div>
            </div>
        </section>
    </main>
<?php include __DIR__ . '/footer.php'; ?>
    <script>
        // initialize mobile nav and scroll reveal
        document.addEventListener('DOMContentLoaded', function(){
            initMobileMenu('#mobile-menu-btn', '#mobile-nav');
            initScrollReveal();
        });
    </script>
