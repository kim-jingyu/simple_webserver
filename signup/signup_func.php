<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

	$db_conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

	$id = mysqli_real_escape_string($db_conn, $_POST['userId']);
	$password = mysqli_real_escape_string($db_conn, $_POST['password']);
	$encoded_pw = md5($password);
	$username = mysqli_real_escape_string($db_conn, $_POST['userName']);
	$userinfo = mysqli_real_escape_string($db_conn, $_POST['userInfo']);


	if ($id == NULL or $password == NULL or $username == NULL) {
		echo "<script>alert('ID, Password, Username 칸을 입력해주세요.')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
	}
	
	$address = trim(mysqli_real_escape_string($db_conn, $_POST['address']));
	$encryption_key = 'secret_key';
	$encrypted_address = openssl_encrypt($address, 'aes-256-cbc', $encryption_key, OPENSSL_ZERO_PADDING, '1234567890123456');

	$select_sql = "select * from member where user_id = '$id'";
	$select_result = mysqli_num_rows(mysqli_query($db_conn, $select_sql));
	if ($select_result > 0) {
		echo "<script>alert('ID가 중복됩니다!')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
		exit();
	}

	$insert_sql = "INSERT INTO member (user_id, user_pw, user_name, user_level, user_info, user_address) VALUES ('$id', '$encoded_pw', '$username', 'student', '$userinfo', '$encrypted_address');";

	$insert_result = mysqli_query($db_conn, $insert_sql);

	if ($insert_result) {
		echo "<script>alert('SignUp Succeeded!')</script>";
		echo "<script>window.location.href='/index.php';</script>";
	} else {
		echo "<script>alert('SignUp Fail!')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
	}
	mysqli_close($db_conn);

	exit();
?>
