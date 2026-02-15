// Basic UI utilities: dark-mode toggle, toasts, simple page transitions
(function(){
  // dark mode based on localStorage
  var root = document.documentElement;
  var current = localStorage.getItem('gc_theme') || 'light';
  if (current === 'dark') root.classList.add('dark');

  window.toggleTheme = function(){
    if (root.classList.contains('dark')){ root.classList.remove('dark'); localStorage.setItem('gc_theme','light'); }
    else { root.classList.add('dark'); localStorage.setItem('gc_theme','dark'); }
  }

  window.toast = function(msg, timeout=3000){
    var t = document.createElement('div');
    t.textContent = msg; t.className = 'fixed right-4 bottom-4 bg-black text-white px-4 py-2 rounded shadow';
    document.body.appendChild(t);
    setTimeout(()=>{ t.classList.add('opacity-0'); setTimeout(()=>t.remove(),400); }, timeout);
  }
  // select-all utility
  window.selectAll = function(masterCheckboxSelector, itemCheckboxSelector) {
    var master = document.querySelector(masterCheckboxSelector);
    if (!master) return;
    master.addEventListener('change', function(){
      document.querySelectorAll(itemCheckboxSelector).forEach(function(cb){ cb.checked = master.checked; });
    });
  }

  // confirmation modal
  window.showConfirm = function(message, onConfirm) {
    var modal = document.getElementById('gc-confirm-modal');
    if (!modal) {
      // fallback to simple confirm
      if (confirm(message)) onConfirm();
      return;
    }
    modal.querySelector('.gc-confirm-message').textContent = message;
    modal.classList.remove('hidden');
    var okBtn = modal.querySelector('.gc-confirm-ok');
    var cancelBtn = modal.querySelector('.gc-confirm-cancel');
    function cleanup(){ modal.classList.add('hidden'); okBtn.removeEventListener('click',ok); cancelBtn.removeEventListener('click',cancel); }
    function ok(){ cleanup(); onConfirm(); }
    function cancel(){ cleanup(); }
    okBtn.addEventListener('click', ok);
    cancelBtn.addEventListener('click', cancel);
  }

  // Mobile menu helper
  window.initMobileMenu = function(buttonSelector, navSelector) {
    var btn = document.querySelector(buttonSelector);
    var nav = document.querySelector(navSelector);
    if (!btn || !nav) return;
    btn.addEventListener('click', function(){
      nav.classList.toggle('hidden');
    });
  }

  // simple scroll reveal for elements with 'data-reveal'
  window.initScrollReveal = function() {
    var els = document.querySelectorAll('[data-reveal]');
    function reveal(){
      var h = window.innerHeight;
      els.forEach(function(el){
        var r = el.getBoundingClientRect();
        if (r.top < h - 60) el.classList.add('opacity-100','translate-y-0');
      });
    }
    els.forEach(function(el){ el.classList.add('transition','duration-700','opacity-0','translate-y-6'); });
    window.addEventListener('scroll', reveal);
    window.addEventListener('resize', reveal);
    reveal();
  }

})();
