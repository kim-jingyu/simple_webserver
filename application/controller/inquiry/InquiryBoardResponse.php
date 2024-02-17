<?php
    class InquiryBoardResponse {
        private $searchWord;
        private $dateValue;
        private $pageNow;
        private $blockNow;
        private $sort;
        private $totalPages;
        private $boardData;

        public function __construct($searchWord, $dateValue, $pageNow, $blockNow, $sort, $totalPages, $boardData) {
            $this->searchWord = $searchWord;
            $this->dateValue = $dateValue;
            $this->pageNow = $pageNow;
            $this->blockNow = $blockNow;
            $this->sort = $sort;
            $this->totalPages = $totalPages;
            $this->boardData = $boardData;
        }
        
        public function getSearchWord() {
            return $this->searchWord;
        }

        public function getDateValue() {
            return $this->dateValue;
        }

        public function getPageNow() {
            return $this->pageNow;
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

        public function getBoardData() {
            return $this->boardData;
        }
    }
?>