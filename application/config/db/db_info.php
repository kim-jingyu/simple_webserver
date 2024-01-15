<?php
    class DbInfo {
        public function __construct() {
        }

        public function getInfo() {
            $db_host = getenv("DB_HOST");
            $db_username = getenv("DB_USERNAME");
            $db_password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_NAME");

            $info = array($db_host, $db_username, $db_password, $db_name);
            return $info;
        }
    }
?>