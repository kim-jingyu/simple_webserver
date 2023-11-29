<?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'jingyu');
        define('DB_PASSWORD', '1234');
        define('DB_NAME', 'test');

	$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	$sql = "INSERT INTO member (user_id, user_pw, user_name, user_level, user_info) VALUES ('id','123','user','student','i am a user');";
	
	$result = mysqli_query($db_conn, $sql);

	if ($result) {
		echo "<script>alert('SignUp Succeeded!')</script>";
                echo "<script>window.location.href='index.php';</script>";
        } else {
                echo "<script>alert('This ID already exists')</script>";
                echo "<script>window.location.href='signup.php';</script>";
        }
?>
