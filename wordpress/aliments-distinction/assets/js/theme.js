(() => {
  'use strict';
  const nav = document.getElementById('mainNav');
  const updateNav = () => nav?.classList.toggle('scrolled', window.scrollY > 60);
  updateNav();
  window.addEventListener('scroll', updateNav, { passive: true });
  const toggle = document.querySelector('.ad-menu-toggle');
  const menu = document.getElementById('ad-navigation');
  const closeMenu = () => { toggle?.setAttribute('aria-expanded', 'false'); menu?.classList.remove('is-open'); };
  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    menu?.classList.toggle('is-open', open);
  });
  menu?.addEventListener('click', (event) => { if (event.target.closest('a')) closeMenu(); });
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && toggle?.getAttribute('aria-expanded') === 'true') { closeMenu(); toggle.focus(); } });
  const elements = document.querySelectorAll('[data-reveal]');
  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    elements.forEach(el => el.classList.add('revealed')); return;
  }
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('revealed'); observer.unobserve(entry.target); } });
  }, { threshold: 0.1 });
  elements.forEach(el => observer.observe(el));
})();
