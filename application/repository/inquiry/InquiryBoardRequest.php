<?php
    class InquiryBoardRequest {
        private $searchWord;
        private $dateValue;
        private $numPerPage;
        private $startInxPerPage;
        private $sort;

        public function __construct($searchWord, $dateValue, $numPerPage, $startInxPerPage, $sort) {
            $this->searchWord = $searchWord;
            $this->dateValue = $dateValue;
            $this->numPerPage = $numPerPage;
            $this->startInxPerPage = $startInxPerPage;
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

        public function getStartIdxPerPage() {
            return $this->startInxPerPage;
        }

        public function getSort() {
            return $this->sort;
        }
    }
?>