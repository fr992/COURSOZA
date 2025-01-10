window.addEventListener('scroll', function() {
  var navbar = document.querySelector('.priv-navbar');
    if (window.scrollY > 130) {
        navbar.classList.add('fixed');
    } else {
        navbar.classList.remove('fixed');
    }
});

window.addEventListener('scroll', function() {
    const jumpToTopButton = this.document.querySelector('.jump-to-top');
    if(this.window.scrollY > 200) { //scrollY osht kur leviz ekrani posht Y vertikal, X horizontal, numri 200 eshte px
        jumpToTopButton.classList.add('show');
    } else {
        jumpToTopButton.classList.remove('show');
    }
});

document.querySelector('.jump-to-top').addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}); // kjo o veq shtes, e kthen krejt nalt pra 0 hapsir nalt, edhe kadale jo perniher pra smooth

const sliderContainer = document.querySelector('.slider-container');
const slider = document.querySelector('.slider');

sliderContainer.addEventListener('wheel', (event) => {
    if(event.deltaY !== 0) {
        event.preventDefault();
        sliderContainer.scrollLeft += event.deltaY; 
        // pra tu e leviz rroten e mausit ose doren ne mauspad per lart, fotot levizin anash...
        // nese e bejme deltaX behet me levizje anash per anash pra mund te nderrohet por...
        // por mua me pershtatet per anash
    }
})