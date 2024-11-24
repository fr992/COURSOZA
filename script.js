window.addEventListener('scroll', function() {
  var navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.classList.add('fixed');
    } else {
        navbar.classList.remove('fixed');
    }
});