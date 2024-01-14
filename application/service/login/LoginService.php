<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/jwt/jwt.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (!$conn) {
        die("DB서버 연결 실패 : ".mysqli_connect_error());
    }

    $id = $conn -> real_escape_string(filter_var(strip_tags($_POST['UserId']), FILTER_SANITIZE_SPECIAL_CHARS));
    $pw = $conn -> real_escape_string($_POST['Password']);

    $encoded_pw = md5($pw);

    $sql = "SELECT * FROM member WHERE user_id = '$id' and user_pw = '$encoded_pw';";

    $result = mysqli_fetch_array(mysqli_query($conn, $sql));

    if ($result) {
        $jwt = createToken($result['user_id'], $result['user_name']);
        setcookie('JWT', $jwt, time() + 30 * 60, '/');
        echo "<script>location.replace('/index.php');</script>";
    } else {
        $_SESSION['loginError'] = "로그인 실패!";
        echo "<script>location.replace('login.html');</script>";
    }
    mysqli_close($conn);
?>