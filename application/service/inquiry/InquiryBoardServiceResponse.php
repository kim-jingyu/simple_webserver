<?php
    class InquiryBoardServiceResponse {
        private $totalPages;
        private $boardData;

        public function __construct($totalPages, $boardData) {
            $this->totalPages = $totalPages;
            $this->boardData = $boardData;
        }

        public function getTotalPages() {
            return $this->totalPages;
        }

        public function getBoardData() {
            return $this->boardData;
        }
    }
?>