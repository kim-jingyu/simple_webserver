<?php
    class InquiryPagenateResponse {
        private $totalCnt;
        private $result;

        public function __construct($totalCnt, $result) {
            $this->totalCnt = $totalCnt;
            $this->result = $result;
        }

        public function getTotalCnt() {
            return $this->totalCnt;
        }

        public function getResult() {
            return $this->result;
        }
    }
?>