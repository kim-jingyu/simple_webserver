<?php
	$id = $_POST['UserId'];
	$password = $_POST['Password'];
	$username = $_POST['UserName'];
	$userinfo = $_POST['UserInfo'];

	if ($id != NULL and $password != NULL and $username != NULL) {
		define('DB_SERVER', 'localhost');
		define('DB_USERNAME', 'jingyu');
		define('DB_PASSWORD', '1234');
		define('DB_NAME', 'test');

		$db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		
		$encoded_pw = md5($password);

		$sql = "INSERT INTO member (user_id, user_pw, user_name, user_level, user_info) VALUES ('$id', '$encoded_pw', '$username', 'student', '$userinfo');";

		$result = mysqli_query($db_conn, $sql);

		if ($result) {
			echo "<script>alert('SignUp Succeeded!')</script>";
			echo "<script>window.location.href='index.php';</script>";
		} else {
			echo "<script>alert('This ID already exists')</script>";
			echo "<script>window.location.href='signup.php';</script>";
		}
		mysqli_close($db_conn);
	} else {
		echo "<script>alert('Input ID and Password and Username')</script>";
		echo "<script>window.location.href='signup.php';</script>";
	}
?>
