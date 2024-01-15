<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    class DBConnectionUtil {
        public function __construct() {
        }

        public static function getConnection() {
            $dbInfo = new DbInfo();
            $conn = new mysqli($dbInfo->getInfo()[0], $dbInfo->getInfo()[1], $dbInfo->getInfo()[2], $dbInfo->getInfo()[3]);
            
            if (mysqli_connect_errno()) {
                die('데이터베이스 오류 발생' . mysqli_connect_error());
            }

            return $conn;
        }
    }
?>