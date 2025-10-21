<?php

    class Footer {

        private $text;
    
        public function __construct($text) {

            $this->text = $text;
            $this->text .= "<br>&copy Powered by CS341";

        }

        public function render() {

            return "<footer><p>{$this->text}</p></footer>";

        }

    }