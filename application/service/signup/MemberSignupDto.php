<?php
    class MemberSignupDto {
        private $id;
        private $pw;
        private $name;
        private $level;
        private $info;
        private $address;

        public function __construct($id, $pw, $name, $level, $info, $address) {
            $this->id = $id;
            $this->pw = $pw;
            $this->name = $name;
            $this->level = $level;
            $this->info = $info;
            $this->address = $address;
        }

        public function getId() {
            return $this->id;
        }

        public function getPw() {
            return $this->pw;
        }

        public function getName() {
            return $this->name;
        }

        public function getLevel() {
            return $this->level;
        }

        public function getInfo() {
            return $this->info;
        }

        public function getAddress() {
            return $this->address;
        }
    }
?>