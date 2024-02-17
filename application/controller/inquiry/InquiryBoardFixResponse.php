<?php
    class InquiryBoardFixResponse {
        public $boardId;
        public $data;

        public function __construct($boardId, $data) {
            $this->boardId = $boardId;
            $this->data = $data;
        }

        public function getBoardId() {
            return $this->boardId;
        }

        public function getData() {
            return $this->data;
        }
    }
?>