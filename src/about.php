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
    
  <link rel="stylesheet" href="../style.css">
    <!-- <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/index_about.css">
    <link rel="stylesheet" href="stylesheets/footer.css"> -->
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
            This JayCloset project was founded by four members from CS 341 Software Engineering class of Fall 2025.  
            </p>
          </div>      
        </section>
    </main>
    <?php echo $footer->render(); ?>
  </body>
</html>
