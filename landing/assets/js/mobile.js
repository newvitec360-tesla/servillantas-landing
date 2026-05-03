/* Mobile only: interactions here do not affect desktop. */
const menuBtn = document.getElementById('menuBtn');
const menu = document.getElementById('mobileMenu');

menuBtn.addEventListener('click', () => {
  const isOpen = menu.classList.toggle('open');
  menuBtn.setAttribute('aria-expanded', String(isOpen));
  menuBtn.textContent = isOpen ? '×' : '☰';
});

menu.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', () => {
    menu.classList.remove('open');
    menuBtn.setAttribute('aria-expanded', 'false');
    menuBtn.textContent = '☰';
  });
});
