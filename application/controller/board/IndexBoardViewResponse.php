<?php
    class IndexBoardViewResponse {
        private $boardId;
        private $row;

        public function __construct($boardId, $row) {
            $this->boardId = $boardId;
            $this->row = $row;
        }

        public function getBoardId() {
            return $this->boardId;
        }

        public function getRow() {
            return $this->row;
        }
    }
?>