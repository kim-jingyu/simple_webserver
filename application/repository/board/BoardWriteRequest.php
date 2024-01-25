<?php
    class BoardWriteRequest {
        private $title;
        private $body;
        private $userId;
        private $today;
        private $storedFileName;

        public function __construct($title, $body, $userId, $today, $storedFileName) {
            $this->title = $title;
            $this->body = $body;
            $this->userId = $userId;
            $this->today = $today;
            $this->storedFileName = $storedFileName;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getBody() {
            return $this->body;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getToday() {
            return $this->today;
        }

        public function getStoredFileName() {
            return $this->storedFileName;
        }
    }
?>