<?php
    class InquiryBoardUpdateRequest {
        private $boardId;
        private $title;
        private $body;
        private $today;

        public function __construct($boardId, $title, $body, $today) {
            $this->boardId = $boardId;
            $this->title = $title;
            $this->body = $body;
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

        public function getToday() {
            return $this->today;
        }
    }
?>