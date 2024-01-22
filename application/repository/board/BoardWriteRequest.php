<?php
    class BoardWriteRequest {
        private $boardId;
        private $title;
        private $body;
        private $userId;
        private $today;

        public function __construct($boardId, $title, $body, $userId, $today) {
            $this->boardId = $boardId;
            $this->title = $title;
            $this->body = $title;
            $this->userId = $userId;
            $this->today = $today;
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
    }
?>