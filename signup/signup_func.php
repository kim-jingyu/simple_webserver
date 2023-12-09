<?php
	require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

	session_start();

	$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

	if (!$conn) {
        die("DB서버 연결 실패 : ".mysqli_connect_error());
    }

	$id = $conn -> real_escape_string(filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS));
	$password = $conn -> real_escape_string($_POST['password']);
	$encoded_pw = md5($password);
	$username = $conn -> real_escape_string(filter_var(strip_tags($_POST['userName']), FILTER_SANITIZE_SPECIAL_CHARS));
	$userinfo = $conn -> real_escape_string(filter_var(strip_tags($_POST['userInfo']), FILTER_SANITIZE_SPECIAL_CHARS));


	if ($id == NULL or $password == NULL or $username == NULL) {
		echo "<script>alert('ID, Password, Username 칸을 입력해주세요.')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
	}
	
	$address = trim(mysqli_real_escape_string($conn, $_POST['address']));
	$encryption_key = 'secret_key';
	$encrypted_address = openssl_encrypt($address, 'aes-256-cbc', $encryption_key, OPENSSL_ZERO_PADDING, '1234567890123456');

	$select_sql = "select * from member where user_id = '$id'";
	$select_result = mysqli_num_rows(mysqli_query($conn, $select_sql));
	if ($select_result > 0) {
		echo "<script>alert('ID가 중복됩니다!')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
		exit();
	}

	$insert_sql = "INSERT INTO member (user_id, user_pw, user_name, user_level, user_info, user_address) VALUES ('$id', '$encoded_pw', '$username', 'student', '$userinfo', '$encrypted_address');";

	$insert_result = mysqli_query($conn, $insert_sql);

	if ($insert_result) {
		echo "<script>alert('SignUp Succeeded!')</script>";
		echo "<script>window.location.href='/index.php';</script>";
	} else {
		echo "<script>alert('SignUp Fail!')</script>";
		echo "<script>window.location.href='/signup/signup.html';</script>";
	}
	mysqli_close($conn);

	exit();
?>
