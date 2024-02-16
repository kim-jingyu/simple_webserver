<?php
    class InquiryPagenateResponse {
        private $totalCnt;
        private $boardData;

        public function __construct($totalCnt, $boardData) {
            $this->totalCnt = $totalCnt;
            $this->boardData = $boardData;
        }

        public function getTotalCnt() {
            return $this->totalCnt;
        }

        public function getBoardData() {
            return $this->boardData;
        }
    }
?>