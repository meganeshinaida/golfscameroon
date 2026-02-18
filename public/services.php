<?php $page_title = 'Services'; include __DIR__ . '/header.php'; ?>
  <!-- <header class="bg-white border-b border-gray-200 p-6">
    <div class="max-w-6xl mx-auto text-center">
      <h1 class="text-3xl font-bold text-green-700">Our Services</h1>
    </div>
  </header> -->
  <header class="bg-center h-screen  object-fit no-repeat bg-cover flex justify-center items-center" style="background-image: linear-gradient(rgba(56, 51, 51, 0.79), rgba(0,0,0,0.6)), url('uploads/hands_smile.jpg') " >
        <div class="max-w-7xl mx-auto  p-8 md:p-16 flex flex-col md:flex-row items-center gap-8">
            <div class="text-center md:text-left text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Services</h1>
                <p class="text-lg md:text-xl">Empowering youth through education and community projects.</p>
                 <h1 class="text-4xl md:text-5xl font-semibold mb-4 flex justify-end" >“The Power of Positive Thinking”</h1>
            </div>
        </div>
    </header>

  <main class="max-w-6xl mx-auto p-6">
   <h1>Our Focus Areas</h1> 
      <p>Guided by our motto, “The Power of Positive Thinking”, The Golfs Cameroon designs programs that empower young people, strengthen communities, and build meaningful partnerships across borders. Each focus area reflects our commitment to leadership, education, inclusion, and sustainable development.</p>

      <section id="leadership_development" data-reveal>
            <div class="grid md:grid-cols-2 gap-10 my-12" >
              <div class="relative overflow-hidden shadow-lg p-4">
                  <img src="<?php echo asset_url('uploads/leadership.jpg'); ?>" alt="leadership" class="object-cover">
              </div>
               
                <div class="px-2 ">
                <h3 class="font-semibold text-3xl text-green-700 mb-4"> Leadership Development</h3>
                <h5 class="font-bold">Building confident, purpose-driven changemakers</h5>
                <!-- <p class="text-sm text-gray-600 mt-2  mx-2 ">Mentorship, coaching, and youth leadership engagement programs designed to raise confident and purpose-driven changemakers.</p>           -->
              <div>
                <h3 class="font-bold text-2xl px-2">What we do</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
              </div>
            <ul>
              <li>Provide mentorship and coaching led by experienced role models</li>
              <li>Engage youths in leadership discussions, workshops, and activities
            </li>
              <li>Encourage self-confidence, critical thinking, and positive values</li>
            </ul>
            <div>
                <h3 class="font-bold text-2xl px-2">Why it matters:</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
            </div>
            <p>By nurturing leadership at an early stage, we help youths discover their potential, define their purpose, and take active roles in driving positive social change.</p>
            </div>
            </div>
        </section>
         <section id="top-projects" data-reveal>
            <div class="grid md:grid-cols-2 gap-10 my-12" >
              
                <div class="px-2 ">
                <h3 class="font-semibold text-3xl text-green-700 mb-4">Education & School Engagement</h3>
                <h5 class="font-bold">Empowering students through discipline, excellence, and opportunity</h5>
              <div>
                <h3 class="font-bold text-2xl px-2">What we do</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
              </div>
             <ul>
              <li>Conduct school visits to inspire and educate students</li>
              <li>Recognize and reward hardworking and high-performing students</li>
              <li>Promote academic excellence and personal growth through motivation sessions</li>
             </ul>
             <div>
                <h3 class="font-bold text-2xl px-2">Why it matters:</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
               </div>
               <p>By encouraging students and celebrating achievement, we help create an environment where young people believe in themselves and strive for excellence.</p>
               </div>
               <div class="relative overflow-hidden shadow-lg p-4">
                  <img src="<?php echo asset_url('uploads/school_engagement.jpg'); ?>" alt="leadership" class="object-cover">
              </div>
            </div>
        </section>
            <section id="top-projects" data-reveal>
            <div class="grid md:grid-cols-2 gap-10 my-12" >
              <div class="relative overflow-hidden shadow-lg p-4 overflow-hidden">
                  <img src="<?php echo asset_url('uploads/community_outreach.png'); ?>" alt="leadership" class="object-cover">
              </div>
                <div class="px-2 ">
                <h3 class="font-semibold text-3xl text-green-700 mb-4">Community Outreach</h3>
                <h5 class="font-bold">Strengthening communities through collective action</h5>
              <div>
                <h3 class="font-bold text-2xl px-2">What we do</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
              </div>
             <ul>
              <li>Organize community visits and outreach programs</li>
              <li>Distribute essential items and support local initiatives</li>
              <li>Collaborate with community leaders to identify pressing needs</li>
             </ul>
             <div>
                <h3 class="font-bold text-2xl px-2">Why it matters:</h3>
                <img src="<?php echo asset_url('uploads/vector.png'); ?>" alt="green line" class="w-20">
               </div>
               <p>By encouraging students and celebrating achievement, we help create an environment where young people believe in themselves and strive for excellence.</p>
               </div>
               
            </div>
        </section>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
      <div class="p-6 bg-white rounded shadow">
        <h4 class="font-semibold">Community Assistance</h4>
        <p class="mt-2 text-sm">Direct support and resources for communities in need.</p>
      </div>
      <div class="p-6 bg-white rounded shadow">
        <h4 class="font-semibold">Youth Mentorship</h4>
        <p class="mt-2 text-sm">Mentorship programs connecting youth with role models.</p>
      </div>
      <div class="p-6 bg-white rounded shadow">
        <h4 class="font-semibold">Educational Outreach</h4>
        <p class="mt-2 text-sm">Workshops and resources to improve learning outcomes.</p>
      </div>
      <div class="p-6 bg-white rounded shadow">
        <h4 class="font-semibold">Webinars & Workshops</h4>
        <p class="mt-2 text-sm">Online and offline events for skills and capacity building.</p>
      </div>
      <div class="p-6 bg-white rounded shadow">
        <h4 class="font-semibold">Scholarships</h4>
        <p class="mt-2 text-sm">Financial support to promising students.</p>
      </div>
    </div>
  </main>
<?php include __DIR__ . '/footer.php'; ?>
