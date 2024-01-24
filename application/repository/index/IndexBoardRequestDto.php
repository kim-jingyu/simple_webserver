<?php
    class BoardRequestDto {
        private $searchWord;
        private $dateValue;
        private $numPerPage;
        private $pageNow;
        private $blockNow;
        private $startIndexPerPage;
        private $sort;

        public function __construct($searchWord, $dateValue, $numPerPage, $pageNow, $blockNow, $startIndexPerPage, $sort) {
            $this->searchWord = $searchWord;
            $this->dateValue = $dateValue;
            $this->numPerPage = $numPerPage;
            $this->pageNow = $pageNow;
            $this->blockNow = $blockNow;
            $this->startIndexPerPage = $startIndexPerPage;
            $this->sort = $sort;
        }

        public function getSearchWord() {
            return $this->searchWord;
        }

        public function getDateValue() {
            return $this->dateValue;
        }

        public function getNumPerPage() {
            return $this->numPerPage;
        }

        public function getPageNow() {
            return $this->pageNow;
        }
 
        public function getBlockNow() {
            return $this->blockNow;
        }

        public function getStartIndexPerPage() {
            return $this->startIndexPerPage;
        }

        public function getSort() {
            return $this->sort;
        }
    }
?>