<?php
ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';

$nav = new Navbar();
$footer =new Footer("Jay Closet 2025");

?>
<!DOCTYPE html>
<html lang='en'>
  <head>
  <!-- <link rel="icon" type="image/jpg" href="/JayCloset/images/images/Teams&Logo/Edwards_Isabella.JPG"/>  
  <link rel="icon" type="image/png" href="/JayCloset/images/images/Teams&Logo/kylie.png"/>  -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
    <title> About - Jay Closet </title>
  </head>
  
  <body>
    <?php $nav->display(); ?>
    <main>
        <!-- <section> -->
          <div class="h1">
            <h1> Career Closet Staff and Employees </h1>
          </div>
          <div class="p">
            <p>   
            A little bit about the Career Closet staff and employees overall!
            </p>
          </div> 
          <div class="team-members">
            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/Hannah_Photo_2.jpg" alt="Hannah" class='profile-pic'>
                <h2>Hannah</h2>
              </div>
              <div class="card-right">
                <!-- <p>Insert bio and contact information here.</p> -->
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="" alt="" class='profile-pic'>
                <h2>Staff Member</h2>
              </div>
              <div class="card-right">
                <!-- <p> Insert bio and contact information here.</p> -->
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="" alt="" class='profile-pic'>
                <h2>Staff Member</h2>
              </div>
              <div class="card-right">
                <!-- <p>Insert bio and contact information here.</p> -->
              </div>
            </div>
          </div>

          <br><br><br><br><br><br><br>

          <div class="h1">
            <h1> Founding Team </h1>
          </div>
          <div class="p">
            <p>   
            JayCloset was founded by four members from CS 341 Software Engineering class of Fall 2025. See below more info!
            </p>
          </div>     

          <div class="team-members">
            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/teddy.jpg" alt="Teddy" class='profile-pic'>
                <h2>Teddy (Farnaz) Bazyar</h2>
              </div>
              <div class="card-right">
                <!-- <p>Insert bio here</p> -->
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/Edwards_Isabella.JPG" alt="Isabella Edwards" class='profile-pic'>
                <h2>Isabella Edwards</h2>
              </div>
              <div class="card-right">
                <p> Isabella is a Computer Science major with a concentration in Hardware. Her intended graduation date is May 2027, and hopes to help students! </p>
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/Bella.jpg" alt="Isabella La Face" class='profile-pic'>
                <h2>Isabella La Face</h2>
              </div>
              <div class="card-right">
                <!-- <p>Insert bio here</p> -->
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/kylie.png" alt="Kylie Ueda" class='profile-pic'>
                <h2>Kylie (Kanade) Ueda</h2>
              </div>
              <div class="card-right">
                <p> Hello everyone! My name is Kylie, and I am a Computer Science major in the Class of 2027. I do not have a concentration, but I am particularly interested in AI and Data Science. </p>
                <br>
                <p>Coming from Japan with no prior experience in this field, I have faced many challenges since I came here. However, I am now enjoying my learning journey, and I hope to support others by sharing my experiences and growth along the way!</p>
              </div>
            </div>
          </div>
        <!-- </section> -->
    </main>
    <?php echo $footer->render(); ?>

    <script src="js/hamburger.js" defer></script>
  </body>
</html>
