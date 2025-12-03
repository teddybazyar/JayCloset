<?php

    class Footer {

        private $text;
    private $links = 'Connect with us:<br>
    <a href="https://www.facebook.com/etowncollege/"><img src="/JayCloset/images/Teams&Logo/facebook.png" width="20" height="20" alt="Facebook Logo"></a>
    <a href="https://www.instagram.com/etowncivicengagement/"><img src="/JayCloset/images/Teams&Logo/instagram.png" width="20" height="20" alt="Instagram Logo"></a>
    <a href="https://twitter.com/EtownCollege?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><img src="/JayCloset/images/Teams&Logo/x.png" width="20" height="20" alt="X Logo"></a>
    <a href="https://www.linkedin.com/school/elizabethtown-college/"><img src="/JayCloset/images/Teams&Logo/linkedin.png" width="20" height="20" alt="LinkedIn Logo"></a>
    <a href="https://www.etown.edu/centers/community-civic/career-closet/index.aspx"><img src="/JayCloset/images/Teams&Logo/etown.png" width="15" height="20" alt="Etown Logo"></a>';
    
        public function __construct($text) {

            $this->text = $text;
            $this->text .= "<br>&copy Powered by CS341
                           | about | ";

        }

        public function render() {

            return "<footer><p>{$this->text}<br><br>{$this->links}</p></footer>";

        }

    }