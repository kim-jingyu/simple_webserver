<?php
    class InquiryBoardWriteRequest {
        private $title;
        private $body;
        private $writerName;
        private $writerPw;
        private $today;

        public function __construct($title, $body, $writerName, $writerPw, $today) {
            $this->title = $title;
            $this->body = $body;
            $this->writerName = $writerName;
            $this->writerPw = $writerPw;
            $this->today = $today;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getBody() {
            return $this->body;
        }

        public function getWriterName() {
            return $this->writerName;
        }

        public function getWriterPw() {
            return $this->writerPw;
        }

        public function getToday() {
            return $this->today;
        }
    }
?>