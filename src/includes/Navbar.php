<?php 
    Class Navbar {
        private $links = [];
        private static $defaultNavLinks = [
            'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
            'About' => ['url' => 'about.php', 'icon' => 'fas fa-info-circle'],
            'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
            'Login' => ['url' => 'index.php?page=login', 'icon' => 'fas fa-key']
        ];

        private static $loggedInNavLinks = [
            'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
            'About' => ['url' => 'about.php', 'icon' => 'fas fa-info-circle'],
            'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
            'Cart' => ['url' => 'cart_page.php', 'icon' => 'fas fa-shopping-cart'],
            'Profile' => ['url' => 'profile.php', 'icon' => 'fas fa-user'],
            'Logout' => ['url' => 'login/logout.php', 'icon' => 'fas fa-sign-out-alt']
        ];

        public function __construct($links = []) {
            // If custom links are provided, use them
            if (!empty($links)) {
                $this->links = $links;
            } else {
                // Check if user is logged in
                if (isset($_SESSION["LoginStatus"]) && $_SESSION["LoginStatus"] == "YES") {
                    $this->links = self::$loggedInNavLinks;
                } else {
                    $this->links = self::$defaultNavLinks;
                }
            }
        }

        public function display() {
            // Determine the correct path to the logo based on current directory
            $currentPath = $_SERVER['PHP_SELF'];
            $logoPath = 'images/Teams&Logo/JayClosetLogo.png';

            // If we're in the admin or login folder, adjust the path
            if (strpos($currentPath, '/admin/') !== false) {
                $logoPath = '../../images/Teams&Logo/JayClosetLogo.png';
            } elseif (strpos($currentPath, '/login/') !== false) {
                $logoPath = '../../images/Teams&Logo/JayClosetLogo.png';
            } else {
                // We're in the root directory
                $logoPath = '../images/Teams&Logo/JayClosetLogo.png';
            }

            echo '<nav class="navbar">';

            // Logo section
            echo '<div class="nav_logo">';
            echo '    <a href="index.php" class="logo-btn">';
            echo '        <img src="' . $logoPath . '" alt="Jay Closet Logo">';
            echo '    </a>';
            echo '</div>';
            // Mobile hamburger toggle button (no animation, simple dropdown)
            echo '  <button class="nav-toggle" aria-controls="primary-navigation" aria-expanded="false" aria-label="Toggle navigation">';
            echo '      &#9776;';
            echo '  </button>';

            echo '      <ul id="primary-navigation" class="nav-links">';

            // Links (Right)
            foreach ($this->links as $name => $data) {
                echo '      <li>';
                echo '          <a href="' . htmlspecialchars($data['url']) . '">';
                echo '          <i class="' . htmlspecialchars($data['icon']) . '"></i> ';
                echo                htmlspecialchars($name);
                echo '          </a>';
                echo        '</li>';
            }
            echo '      </ul>';
            // echo '    </div>';
            echo '  </nav>';
        }
        
    }

    

?>