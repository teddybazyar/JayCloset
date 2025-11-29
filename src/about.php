<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");

?>
<!DOCTYPE html>
<html lang='en'>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title> About - Jay Closet </title>
    <style>
        /* Enhanced About Page Styling */
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Hero Section */
        .about-hero {
            background: linear-gradient(135deg, #0a2240 0%, #004b98 50%, #3db5e6 100%);
            padding: 80px 20px 60px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 60px;
        }

        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, 30px) rotate(180deg); }
        }

        .about-hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .about-hero p {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease 0.3s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin: 80px 0 50px;
            position: relative;
        }

        .section-header h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            color: #0a2240;
            font-weight: 700;
            margin-bottom: 15px;
            display: inline-block;
            position: relative;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #e1261c 0%, #f0928d 100%);
            border-radius: 2px;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #495057;
            max-width: 700px;
            margin: 20px auto 0;
        }

        /* Team Container */
        .team-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 80px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }

        /* Team Member Card */
        .team-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #004b98 0%, #3db5e6 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .team-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(0, 75, 152, 0.2);
        }

        .team-card:hover::before {
            transform: scaleX(1);
        }

        .card-header {
            background: linear-gradient(135deg, #B4D1F8 0%, #3db5e6 100%);
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: white;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .profile-pic-wrapper {
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
            position: relative;
            z-index: 2;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 6px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
        }

        .team-card:hover .profile-pic {
            transform: scale(1.05);
            border-color: #e1261c;
        }

        .card-body {
            padding: 40px 30px 30px;
        }

        .card-body h2 {
            color: #0a2240;
            font-size: 1.6rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-align: center;
        }

        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .card-body p {
            color: #495057;
            line-height: 1.8;
            font-size: 1rem;
            margin: 15px 0;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
            color: white;
            border-radius: 50%;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-links a:hover {
            transform: translateY(-3px) rotate(5deg);
            box-shadow: 0 6px 20px rgba(0, 75, 152, 0.4);
        }

        /* Empty Card Placeholder */
        .placeholder-card {
            background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
            border: 3px dashed #c8c8c8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 30px;
            min-height: 400px;
        }

        .placeholder-card i {
            font-size: 80px;
            color: #c8c8c8;
            margin-bottom: 20px;
        }

        .placeholder-card h3 {
            color: #6c757d;
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .placeholder-card p {
            color: #adb5bd;
            font-size: 1rem;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #0a2240 0%, #004b98 100%);
            padding: 60px 20px;
            margin: 80px 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3db5e6 0%, #B4D1F8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 600;
        }

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 80px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            color: #0a2240;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .cta-section p {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            color: white;
            padding: 18px 45px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(225, 38, 28, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(225, 38, 28, 0.5);
            background: linear-gradient(135deg, #c82333 0%, #e1261c 100%);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-hero {
                padding: 60px 15px 40px;
            }

            .team-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .section-header {
                margin: 50px 0 30px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }

            .stat-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animation on scroll */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
  </head>
  
  <body>
    <?php $nav->display(); ?>
    
    <main>
        <!-- Hero Section -->
        <div class="about-hero">
            <h1>
                <i class="fas fa-users"></i> Meet Our Team
            </h1>
            <p>
                Dedicated professionals committed to helping students succeed through accessible career resources and professional attire.
            </p>
        </div>

        <!-- Current Staff Section -->
        <div class="team-container">
            <div class="section-header">
                <h2>
                    <i class="fas fa-user-tie"></i> Career Closet Staff
                </h2>
                <p>Our wonderful team working to make Jay Closet a success!</p>
            </div>
            
            <div class="team-grid">
                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/Hannah_Photo_2.jpg" alt="Hannah" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Hannah Sharp</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-star"></i> Staff Member
                            </span>
                        </div>
                        <p>My name is Hannah Sharp, and I am a junior Biology Secondary Education major. I am a part of the Bonner Leadership Program here on campus where I am a student office assistant in the Center for Community and Civic Engagement. Through this position I help coordinate the Blue Jay Career Closet. On campus I am also involved in a number of clubs and organizations and serve as a peer mentor for first year students!</p>
                        <!-- <div class="social-links">
                            <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        </div> -->
                    </div>
                </div>

                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/Nate_photo.jpg" alt="Nate" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Nate Wade</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-star"></i> Staff Member
                            </span>
                        </div>
                        <p>My name is Nathan Wade, and I am a freshman Mechanical Engineering major. I am a part of the Bonner Leadership Program as well, where I am a student office assistant in the Center for Community and Civic Engagement. In this position I help co-direct the Blue Jay Career Closet. I am also a part of the Honors Program and like to get involved in other campus-wide events.</p>
                        <!-- <div class="social-links">
                            <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        </div> -->
                    </div>
                </div>

                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/Brianna_photo.jpg" alt="Brianna" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Brianna Tati</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-star"></i> Staff Member
                            </span>
                        </div>
                        <p>Program Manager of CCCE and oversees day-to-day operations for the office, which the Blue Jay Career Closet is a part of.
                        In a previous job, I witnessed firsthand the barriers people faced when they didn’t have access to career-ready attire. Having the right clothing is more than meeting workplace expectations—it helps individuals feel confident, prepared, and capable. 
                        That’s why the Blue Jay Career Closet is so meaningful. It demonstrates how students, employees, and community partners come together to ensure that every student has the resources they need to succeed in their current roles and future careers.</p>
                        <!-- <div class="social-links">
                            <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        </div> -->
                    </div>
                </div>

                <!-- <div class="team-card placeholder-card fade-in">
                    <i class="fas fa-user-plus"></i>
                    <h3>Staff Member</h3>
                    <p>Coming Soon</p>
                </div> -->
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">2025</div>
                        <div class="stat-label">Established</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Items Available</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Students Helped</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Online Access</div>
                    </div>
                </div>
            </div>

            <!-- Founding Team Section -->
            <div class="section-header">
                <h2>
                    <i class="fas fa-rocket"></i> Founding Team
                </h2>
                <p>JayCloset was founded by four talented students from the CS 341 Software Engineering class of Fall 2025.</p>
            </div>

            <div class="team-grid">
                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/teddy.jpg" alt="Teddy" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Farnaz (Teddy) Bazyar</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-code"></i> Co-Founder
                            </span>
                        </div>
                        <p>Computer Science student with a concentration in cybersecurity passionate about creating technology solutions that help the campus community.</p>
                    </div>
                </div>

                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/Edwards_Isabella.JPG" alt="Isabella Edwards" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Isabella Edwards</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-code"></i> Co-Founder
                            </span>
                        </div>
                        <p>Isabella is a Computer Science major with a concentration in Hardware. Her intended graduation date is May 2027, and she hopes to help students succeed in their professional journeys!</p>
                    </div>
                </div>

                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/Bella.jpg" alt="Isabella La Face" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Isabella La Face</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-code"></i> Co-Founder
                            </span>
                        </div>
                        <p>Hello everyone! My name is Isabella, and I'm a Computer Science major with a concentration in cybersecurity in the Class of 2027.</p>
                        <p>Coming into this field, I’ve encountered plenty of challenges, but each one has pushed me to grow, learn, and stay curious.</p>
                        <p>I’m excited by the new experiences and opportunities that come with studying cybersecurity, and I hope to support others along the way by sharing what I've learned and encouraging those who are beginning their own journey.</p>
                    </div>
                </div>

                <div class="team-card fade-in">
                    <div class="card-header">
                        <div class="profile-pic-wrapper">
                            <img src="/JayCloset/images/Teams&Logo/kylie.png" alt="Kylie Ueda" class='profile-pic'>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2>Kylie (Kanade) Ueda</h2>
                        <div style="text-align: center;">
                            <span class="role-badge">
                                <i class="fas fa-code"></i> Co-Founder
                            </span>
                        </div>
                        <p>Hello everyone! My name is Kylie, and I am a Computer Science major in the Class of 2027. I am particularly interested in AI and Data Science.</p>
                        <p>Coming from Japan with no prior experience in this field, I have faced many challenges since I came here. However, I am now enjoying my learning journey, and I hope to support others by sharing my experiences and growth along the way!</p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="cta-section">
                <h2>Ready to Browse?</h2>
                <p>Explore our collection of professional attire and find the perfect outfit for your next interview, networking event, or career fair!</p>
                <a href="closet.php" class="cta-button">
                    <i class="fas fa-shopping-bag"></i>
                    Browse the Closet
                </a>
            </div>
        </div>
    </main>

    <?php echo $footer->render(); ?>

    <script src="js/hamburger.js" defer></script>
    <script>
        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
  </body>
</html>