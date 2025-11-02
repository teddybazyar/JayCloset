<?php

class MainPageContent{

    static function render() {
        return '<section id="welcome">
            <div class="h1">
                    <h1> Jay Closet is Coming Soon! </h1>
            </div>
            <div class="p">
                <p>
                    JayCloset is Elizabethtown College\'s Career Closet! If students are in need of business casual clothing, whether for an interview or a meet and greet, this website is the place to go. Students can create an account and reserve clothing in the library catalog.
                </p>
            </div>
            <div class="slideshow-container">
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/coming_soon_slideshow_icon.png" alt="Slide 1" style="width:100%">
                <div class="text">Caption Text</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/coming_soon_slideshow_icon.png" alt="Slide 2" style="width:100%">
                <div class="text">Caption Two</div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/coming_soon_slideshow_icon.png" alt="Slide 3" style="width:100%">
                <div class="text">Caption Three</div>
            </div>
                <!-- Next and previous buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <br>
            <!-- The dots/circles -->
            <div style="text-align:center">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
            <br>
            </section>';
    }
}

$extraStyleSheets = ["css/style.css"];

?>