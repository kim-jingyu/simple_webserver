<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'jingyu');
    define('DB_PASSWORD', '1234');
    define('DB_NAME', 'test');

	$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($db_conn) {
        echo "<script>alert('DB connected!')</script>";
    }

	$sql = "SELECT * FROM member where user_id = 'test' # user_pw = 'b';";
	
	$result = mysqli_query($db_conn, $sql);
    
	if ($result) {
        echo "<script>alert('{$result}')</script>";
        // echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('This ID already exists')</script>";
        // echo "<script>window.location.href='signup.php';</script>";
    }
?>
