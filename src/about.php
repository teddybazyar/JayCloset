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
  <link rel="stylesheet" href="css/style.css">
    <title> About - Jay Closet </title>
  </head>
  
  <body>
    <?php $nav->display(); ?>
    <main>
        <section>
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
                <img src="/JayCloset/images/Teams&Logo/teddy.jpg" alt="Teddy" width="300" height="270">
                <h2>Teddy (Farnaz) Bazyar</h2>
              </div>
              <div class="card-right">
                <p>Insert bio here</p>
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/Edwards_Isabella.JPG" alt="Isabella Edwards" width="300" height="250">
                <h2>Isabella Edwards</h2>
              </div>
              <div class="card-right">
                <p> Isabella is a Computer Science major with a concentration in Hardware. Her intended graduation date is May 2027, and hopes to help students! </p>
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/" alt="" width="300" height="250">
                <h2>Isabella La Face</h2>
              </div>
              <div class="card-right">
                <p>Insert bio here</p>
              </div>
            </div>

            <div class="card">
              <div class="card-left">
                <img src="/JayCloset/images/Teams&Logo/kylie.png" alt="Kylie Ueda" width="300" height="300">
                <h2>Kylie (Kanade) Ueda</h2>
              </div>
              <div class="card-right">
                <p>Insert bio here</p>
              </div>
            </div>
          </div>
        </section>
    </main>
    <?php echo $footer->render(); ?>
  </body>
</html>
