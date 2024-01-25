<?php
    class InquiryBoardResponse {
        private $searchWord;
        private $dateValue;
        private $blockNow;
        private $sort;
        private $totalPages;
        private $result;

        public function __construct($searchWord, $dateValue, $blockNow, $sort, $totalPages, $result) {
            $this->searchWord = $searchWord;
            $this->dateValue = $dateValue;
            $this->blockNow = $blockNow;
            $this->sort = $sort;
            $this->totalPages = $totalPages;
            $this->result = $result;
        }
        
        public function getSearchWord() {
            return $this->searchWord;
        }

        public function getDateValue() {
            return $this->dateValue;
        }

        public function getBlockNow() {
            return $this->blockNow;
        }

        public function getSort() {
            return $this->sort;
        }

        public function getTotalPages() {
            return $this->totalPages;
        }

        public function getResult() {
            return $this->result;
        }
    }
?>