<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    class DBConnectionUtil {
        public function __construct() {
        }

        public static function getConnection() {
            $dbHost = getenv("DB_HOST");
            $dbName = getenv("DB_NAME");
            $dbChar = "utf8";
            $dbUsername = getenv("DB_USERNAME");
            $dbPassword = getenv("DB_PASSWORD");

            try {
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=$dbChar", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            } catch (PDOException $e) {
                die("서버 연결 실패!".$e->getMessage());
                echo "<script>location.replace('/application/view/login/login.html');</script>";
                exit();
            }
        }
    }
?>