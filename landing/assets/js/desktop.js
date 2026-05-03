/* Desktop only: interactions here do not affect mobile. */
document.querySelectorAll('.main-nav a').forEach((link) => {
  link.addEventListener('click', () => {
    document.querySelectorAll('.main-nav a').forEach((item) => item.classList.remove('active'));
    link.classList.add('active');
  });
});

const tabCards = document.querySelectorAll('.tab-card');
const dots = document.querySelectorAll('.hero-dots span');
tabCards.forEach((card, index) => {
  card.addEventListener('mouseenter', () => {
    tabCards.forEach((item) => item.classList.remove('active-card'));
    dots.forEach((dot) => dot.classList.remove('on'));
    card.classList.add('active-card');
    if (dots[index]) dots[index].classList.add('on');
  });
});
