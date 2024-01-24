<?php
    class BoardFixRequest {
        private $boardId;
        private $title;
        private $body;
        private $userId;
        private $today;
        private $storedFileName;

        public function __construct($boardId, $title, $body, $userId, $today, $storedFileName) {
            $this->boardId = $boardId;
            $this->title = $title;
            $this->body = $title;
            $this->userId = $userId;
            $this->today = $today;
            $this->storedFileName = $storedFileName;
        }

        public function getBoardId() {
            return $this->boardId;
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