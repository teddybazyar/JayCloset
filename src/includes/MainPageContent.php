<?php

class MainPageContent{
    static function render() {
        return '
        <section id="welcome">
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="h1">
                    <h1>
                        <i class="fas fa-star"></i> 
                        Jay Closet is Coming Soon! 
                        <i class="fas fa-star"></i>
                    </h1>
                </div>
            </div>

            <!-- Mission Statement -->
            <div class="mission-box">
                <h3>
                    <i class="fas fa-graduation-cap"></i> 
                    Jay Closet is Elizabethtown College\'s Online Career Closet Website! If students are in need of business casual clothing, whether for an interview or a meet and greet, this website is the place to go. Students can create an account and reserve clothing in the library catalog.
                </h3>
            </div>

            <!-- Slideshow Section -->
            <div class="slideshow-section">
                <div class="slideshow-container">
                    <div class="mySlides fade">
                        <div class="numbertext">1 / 3</div>
                        <img src="/JayCloset/images/Teams&Logo/careerclosetlogo.png" alt="Career Closet Logo" style="width:100%">
                    </div>
                    <div class="mySlides fade">
                        <div class="numbertext">2 / 3</div>
                        <img src="/JayCloset/images/Teams&Logo/CCE.LOGO.horizontal1.png" alt="CCE Logo" style="width:100%">
                    </div>
                    <div class="mySlides fade">
                        <div class="numbertext">3 / 3</div>
                        <img src="/JayCloset/images/Teams&Logo/coming_soon_slideshow_icon.png" alt="Coming Soon" style="width:100%">
                    </div>
                    
                    <a class="prev" onclick="plusSlides(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a class="next" onclick="plusSlides(1)">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <br>
                <div style="text-align:center">
                    <span class="dot" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>
            </div>

            <!-- About Career Closet -->
            <div class="info-card">
                <p>
                    <strong>
                        <i class="fas fa-building"></i> 
                        Blue Jay Career Closet (BSC 201)
                    </strong>
                    <br><br>
                    The Blue Jay Career Closet, established in 2022, provides career-ready attire to students. 
                    All students can keep or borrow clothes for future use, ensuring equitable access to 
                    clothing for interviews, internships, and other career related opportunities.
                </p>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="/JayCloset/src/closet.php" class="cta-button">
                        <i class="fas fa-shopping-bag"></i> 
                        Browse the Closet
                    </a>
                </div>
            </div>

            <!-- Category Section -->
            <div class="category-section">
                <h2 class="category-title">
                    <i class="fas fa-th-large"></i> 
                    Shop by Category
                </h2>
                <div class="row">
                    <div class="column">
                        <a href="https://jaycloset.etowndb.com/src/closet.php?gender=All&category=Tops%2FBlouse&size=All&color=All">
                            <h1>
                                <i class="fas fa-shirt"></i> 
                                Shirts
                            </h1>
                            <p>
                                <img src="/JayCloset/images/items/127/127-1.png" alt="Professional Shirts" class="main-item-image">
                            </p>
                        </a>
                    </div>
                    <div class="column">
                        <a href="https://jaycloset.etowndb.com/src/closet.php?gender=All&category=Bottoms&size=All&color=All">
                            <h1>
                                <i class="fas fa-user-tie"></i> 
                                Pants
                            </h1>
                            <p>
                                <img src="/JayCloset/images/items/108/108-3.png" alt="Professional Pants" class="main-item-image">
                            </p>
                        </a>
                    </div>
                    <div class="column">
                        <a href="https://jaycloset.etowndb.com/src/closet.php?gender=All&category=Shoes&size=All&color=All">
                            <h1>
                                <i class="fas fa-shoe-prints"></i> 
                                Shoes
                            </h1>
                            <p>
                                <img src="/JayCloset/images/items/120/120-1.png" alt="Professional Shoes" class="main-item-image">
                            </p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CCCE Section -->
            <div class="ccce-section">
                <p>
                    <strong>
                        <i class="fas fa-hands-helping"></i> 
                        Center for Community and Civic Engagement (CCCE) 
                        <br>
                        Located in BSC 247
                        <br>
                        Educate for Service. Engage with Purpose.
                    </strong>
                    <br><br>
                    The Center for Community and Civic Engagement (CCCE)
                    strengthens Elizabethtown College\'s commitment to learning through service by
                    connecting education with meaningful action. 
                    Through hands-on experiences, reciprocal partnerships, and real-world learning, 
                    the CCCE fosters collaborative opportunities that create positive change—on campus, 
                    in the community, and beyond.
                    <br><br>
                    <strong>
                        <i class="fas fa-award"></i> 
                        Recognized Commitment to Engagement
                    </strong>
                    <br><br>
                    In 2020, Elizabethtown College earned the Carnegie Community Engagement Classification, 
                    a national honor recognizing the College\'s leadership in fostering mutually beneficial 
                    partnerships, civic learning, and community-prioritized initiatives through engaged teaching, scholarship, and service.
                    <br><br>
                    <strong>
                        <i class="fas fa-users"></i> 
                        Get Involved
                    </strong>
                    <br><br>
                    Whether through community-based academic work, leadership pathways, 
                    or one-time projects, the CCCE offers all students meaningful 
                    opportunities to engage in partnership with communities, reflect 
                    deeply, and grow as <strong>compassionate leaders</strong> prepared to advance 
                    <strong>Elizabethtown College\'s mission</strong> and contribute to a more just and 
                    engaged society.
                    <br><br>
                    <i class="fas fa-link"></i> 
                    Learn more at: <a href="https://etown.edu/centers/community-civic" target="_blank">etown.edu/centers/community-civic</a>
                    <br>
                    <i class="fab fa-instagram"></i> 
                    Follow us on Instagram: <a href="https://www.instagram.com/etowncivicengagement/?hl=en" target="_blank">@etowncivicengagement</a>
                </p>
            </div>

            <!-- Team Preview -->
            <div class="team-preview">
                <h2>
                    <i class="fas fa-user-friends"></i> 
                    Meet Our Team
                </h2>
                <p style="font-size: 1.1rem; color: #495057; margin: 20px 0;">
                    The Jay Closet is run by dedicated staff who are here to help you succeed!
                </p>
                <a href="/JayCloset/src/about.php" style="display: inline-block; margin: 20px 0;">
                    <img src="/JayCloset/images/Teams&Logo/Hannah_Photo_2.jpg" alt="Hannah - Staff Member" class="employee-photo">
                </a>
                <a href="/JayCloset/src/about.php" style="display: inline-block; margin: 20px 0;">
                    <img src="/JayCloset/images/Teams&Logo/Nate_photo.jpg" alt="Nate - Staff Member" class="employee-photo">
                </a>
                <a href="/JayCloset/src/about.php" style="display: inline-block; margin: 20px 0;">
                    <img src="/JayCloset/images/Teams&Logo/Brianna_photo.jpg" alt="Brianna - Staff Member" class="employee-photo">
                </a>
                <br>
                <a href="/JayCloset/src/about.php" class="cta-button" style="margin-top: 20px;">
                    <i class="fas fa-info-circle"></i> 
                    Learn More About Us
                </a>
            </div>

        </section>
        <script src="/JayCloset/src/js/slideshow.js"></script>';
    }
}

$extraStyleSheets = ["css/style.css"];

?>