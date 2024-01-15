<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    class DBConnectionUtil {
        public function __construct() {
        }

        public static function getConnection() {
            $info = DbInfo::getInfo();
            $conn = new mysqli($info[0], $info[1], $info[2], $info[3]);
            
            if (mysqli_connect_errno()) {
                die('데이터베이스 오류 발생' . mysqli_connect_error());
            }

            return $conn;
        }
    }
?>