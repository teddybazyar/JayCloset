document.addEventListener('DOMContentLoaded', function () {
  window.slideIndex = 1;
  window.plusSlides = function(n) { showSlides(window.slideIndex += n); };
  window.currentSlide = function(n) { showSlides(window.slideIndex = n); };

  function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    if (slides.length === 0) return; 
    if (n > slides.length) { window.slideIndex = 1; }
    if (n < 1) { window.slideIndex = slides.length; }
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[window.slideIndex-1].style.display = "block";
    if (dots.length >= window.slideIndex) {
      dots[window.slideIndex-1].className += " active";
    }
  }

  // show the first slide
  showSlides(window.slideIndex);
});