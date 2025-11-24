<?php
// <div class="text">Caption Two</div>

class MainPageContent{
    static function render() {
        return '<section id="welcome">
            <div class="h1">
                    <h1> Jay Closet is Coming Soon! </h1>
            </div>
            <div class="p" style="background-color: #E1261C; box-shadow: 0 10px 10px rgba(0,0,0,0.15);">
                <h3>
                   Jay Closet is Elizabethtown College\'s Career Closet! If students are in need of business casual clothing, whether for an interview or a meet and greet, this website is the place to go. Students can create an account and reserve clothing in the library catalog.
                </h3>
            </div>
            <br> <br> <br> <br> <br>
            <div class="slideshow-container">
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/careerclosetlogo.png" alt="Slide 1" style="width:100%">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/CCE.LOGO.horizontal1.png" alt="Slide 2" style="width:100%">
                
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="/JayCloset/images/Teams&Logo/coming_soon_slideshow_icon.png" alt="Slide 3" style="width:100%">

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
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <div class="card">
              <p> <strong> Blue Jay Career Closet (BSC 201) </strong>
                <br> <br>
                The Blue Jay Career Closet, established in 2022, provides career-ready attire to students. 
                All students can keep or borrow clothes for future use, ensuring equitable access to 
                clothing for interviews, internships, and other career related opportunities.
              </p>
            </div>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <div class="row">
            <div class="column" style="background-color:#aaa;">
                <a href="/JayCloset/src/closet.php" style="display:block;color:inherit;text-decoration:none;">
                <br>
                <h1><center>Shirts</center></h1>
                <p>
                    <br>
                    <img src="/JayCloset/images/items/127/127-1.png" alt="Shirt Ref" class="main-item-image">
                </p>
                </a>
            </div>
            <div class="column" style="background-color:#bbb;">
                <a href="/JayCloset/src/closet.php" style="display:block;color:inherit;text-decoration:none;">
                <br>
                <h1><center>Pants</center></h1>
                <p>
                    <br>
                    <img src="/JayCloset/images/items/108/108-3.png" alt="Pant Ref" class="main-item-image">
                </p>
                </a>
            </div>
            <div class="column" style="background-color:#ccc;">
                <a href="/JayCloset/src/closet.php" style="display:block;color:inherit;text-decoration:none;">
                <br>
                <h1><center>Shoes</center></h1>
                <p>
                    <br>
                    <img src="/JayCloset/images/items/120/120-1.png" alt="Shoe Ref" class="main-item-image">
                </p>
                </a>
            </div>
            </div>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <div class="rounded-box">
                <br>
                <p> <strong> Center for Community and Civic Engagement (CCCE) 
                    located in BSC 247
                    Educate for Service. Engage with Purpose. </strong>
                    <br> <br>
                    The Center for Community and Civic Engagement (CCCE)
                    strengthens Elizabethtown College\'s commitment to learning through service by
                    connecting education with meaningful action. 
                    Through hands-on experiences, reciprocal partnerships, and real-world learning, 
                    the CCCE fosters collaborative opportunities that create positive change—on campus, 
                    in the community, and beyond.
                    <br> <br>
                    <strong> Recognized Commitment to Engagement </strong>
                    <br> <br>
                    In 2020, Elizabethtown College earned the Carnegie Community Engagement Classification, 
                    a national honor recognizing the College\'s leadership in fostering mutually beneficial 
                    partnerships, civic learning, and community-prioritized initiatives through engaged teaching, scholarship, and service.
                    <br> <br>
                    <strong> Get Involved </strong>
                    <br> <br>
                    Whether through community-based academic work, leadership pathways, 
                    or one-time projects, the CCCE offers all students meaningful 
                    opportunities to engage in partnership with communities, reflect 
                    deeply, and grow as <strong>compassionate leaders</strong> prepared to advance 
                    <strong>Elizabethtown College\'s mission</strong> and contribute to a more just and 
                    engaged society.
                    <br> <br>
                    Learn more at: <a href="https://etown.edu/centers/community-civic">etown.edu/centers/community-civic</a>
                    Follow us on Instagram: <a href="https://www.instagram.com/etowncivicengagement/?hl=en">@etowncivicengagement</a>
 
                </p>
            </div>
            <div class = "right-leaning">
                <a href="/JayCloset/src/about.php" style="display:block;color:inherit;text-decoration:none;">
                <p><img src="/JayCloset/images/Teams&Logo/Hannah_Photo_2.jpg" alt="Employee Photo" class="employee-photo"></p>
            </div>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            <br> <br> <br> <br> <br>
            </section><script src="/JayCloset/src/js/slideshow.js"></script>';
    }
}

$extraStyleSheets = ["css/style.css"];

?>