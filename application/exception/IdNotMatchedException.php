<?php
    class IdNotMatchedException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>