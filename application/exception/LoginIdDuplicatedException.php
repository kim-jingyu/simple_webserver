<?php
    class LoginIdDuplicatedException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>