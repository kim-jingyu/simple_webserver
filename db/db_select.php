<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'jingyu');
    define('DB_PASSWORD', '1234');
    define('DB_NAME', 'test');

	$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($db_conn) {
        echo "<script>alert('DB connected!')</script>";
    }

	$sql = "SELECT * FROM member where user_id = 'test';";
	
	$result = mysqli_query($db_conn, $sql);
    
    while ($row = $result -> fetch_assoc()) {
        echo "<script>alert('{$row['user_id']}')</script>";
    }
?>
