<?php
	function connect_db() {
		define('DB_SERVER', 'localhost');
		define('DB_USERNAME', 'jingyu');
		define('DB_PASSWORD', '1234');
		define('DB_NAME', 'test');

		$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

		if (!$db_conn) {
            die("DB서버 연결 실패 : ".mysqli_connect_error());
        } else {
			echo "DB서버 연결 성공";
			return $db_conn;
		}
	}
?>
