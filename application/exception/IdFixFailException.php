<?php
    class IdFixFailException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>