<?php 
    Class Navbar {
        private $links = [];
        private static $defaultNavLinks = [
            'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
            'About' => ['url' => 'about.php', 'icon' => 'fas fa-info-circle'],
            'Login' => ['url' => 'index.php?page=login', 'icon' => 'fas fa-key']
        ];

        public function __construct($links = []) {
            $this->links = !empty($links) ? $links : self::$defaultNavLinks;
        }

        public function display() {
            echo '<nav><ul>';
            foreach ($this->links as $name => $data) {
                echo '<li>';
                echo '<a href="' . htmlspecialchars($data['url']) . '">';
                echo '<i class="' . htmlspecialchars($data['icon']) . '"></i> ';
                echo htmlspecialchars($name);
                echo '</a></li>';
        }
        echo '</ul></nav>';
    }

    }

?>