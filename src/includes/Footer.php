<?php

    class Footer {

        private $text;
    private $links = 'Connect with us:<br>
    <a href="https://www.facebook.com/etowncollege/" class="fa fa-facebook"></a>
    <a href="https://www.instagram.com/etowncollege/" class="fa fa-instagram"></a>
    <a href="https://twitter.com/EtownCollege?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" class="fa fa-twitter"></a>
    <a href="https://www.linkedin.com/school/elizabethtown-college/" class="fa fa-linkedin"></a>';
    
        public function __construct($text) {

            $this->text = $text;
            $this->text .= "<br>&copy Powered by CS341
                           | about | ";

        }

        public function render() {

            return "<footer><p>{$this->text}<br><br>{$this->links}</p></footer>"
                . "\n<script src=\"/JayCloset/src/js/slideshow.js\"></script>";

        }

    }