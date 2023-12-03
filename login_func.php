<?php
    require 'db_info.php';

    if (isset($_POST['UserId']) && isset($_POST['Password'])) {
        session_start();

        $db_conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if (!$db_conn) {
            die("DB서버 연결 실패 : ".mysqli_connect_error());
        }

        $id = mysqli_real_escape_string($db_conn, $_POST['UserId']);
        $pw = mysqli_real_escape_string($db_conn, $_POST['Password']);

        $encoded_pw = md5($pw);
    
        $sql = "SELECT * FROM member WHERE user_id = '$id' and user_pw = '$encoded_pw';";
    
        $result = mysqli_fetch_array(mysqli_query($db_conn, $sql));

        if ($result) {
            session_regenerate_id();    // ID 자동 갱신
            $_SESSION['loginId'] = $result['user_id'];
            echo "<script>location.replace('index.php');</script>";
        } else {
            $_SESSION['loginError'] = "로그인 실패!";
            echo "<script>location.replace('login.html');</script>";
        }
        mysqli_close($db_conn);
    }
?>