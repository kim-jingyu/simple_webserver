<?php
    class PwNotMatchedException extends Exception {
        public function errorMessage() {
            return $this->getMessage();
        }
    }
?>