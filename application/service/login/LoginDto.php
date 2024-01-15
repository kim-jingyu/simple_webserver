<?php
    class LoginDto {
        private $userId;
        private $userPw;

        public function __construct($userId, $userPw) {
            $this->userId = $userId;
            $this->userPw = $userPw;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getUserPw() {
            return $this->userPw;
        }
    }
?>