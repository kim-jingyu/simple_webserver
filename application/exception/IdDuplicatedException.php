<?php
    class IdDuplicatedException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>