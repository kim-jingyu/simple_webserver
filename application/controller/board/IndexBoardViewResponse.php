<?php
    class IndexBoardViewResponse {
        private $boardId;
        private $result;

        public function __construct($boardId, $result) {
            $this->boardId = $boardId;
            $this->result = $result;
        }

        public function getBoardId() {
            return $this->boardId;
        }

        public function getResult() {
            return $this->result;
        }
    }
?>