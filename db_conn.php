<?php
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'jingyu');
	define('DB_PASSWORD', '1234');
	define('DB_NAME', 'test');

	$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if ($db_conn) {
		echo "DB Connect OK";
	} else {
		echo "DB Connect Fail";
	}
?>
