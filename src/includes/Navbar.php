<?php 
    Class Navbar {
        private $links = [];
        private static $defaultNavLinks = [
            'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
            'About' => ['url' => 'about.php', 'icon' => 'fas fa-info-circle'],
            'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
            'Login' => ['url' => 'index.php?page=login', 'icon' => 'fas fa-key']
        ];

        public function __construct($links = []) {
            $this->links = !empty($links) ? $links : self::$defaultNavLinks;
        }

        public function display() {
            echo '<nav class="navbar">';

            // Logo section
            echo '<div class="nav_logo">';
            echo '    <a href="index.php" class="logo-btn">';
            echo '        <img src="../images/Teams&Logo/JayClosetLogo.png" alt="Jay Closet Logo">';
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