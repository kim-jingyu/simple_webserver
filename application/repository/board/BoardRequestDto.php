<?php
    class BoardRequestDto {
        private $searchWord;
        private $dateValue;
        private $numPerPage;
        private $startIndexPerPage;
        private $sort;

        public function __construct($searchWord, $dateValue, $numPerPage, $startIndexPerPage, $sort) {
            $this->searchWord = $searchWord;
            $this->dateValue = $dateValue;
            $this->numPerPage = $numPerPage;
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

        public function getStartIndexPerPage() {
            return $this->startIndexPerPage;
        }

        public function getSort() {
            return $this->sort;
        }
    }
?>