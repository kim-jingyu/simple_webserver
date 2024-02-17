<?php
    class PwFixFailException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>