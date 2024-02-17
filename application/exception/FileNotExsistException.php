<?php
    class FileNotExsistException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>