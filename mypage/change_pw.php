<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류발생!'.mysqli_connect_error());
    }

    $old_pw = md5($_POST['oldPw']);
    $new_pw = md5($_POST['newPw']);

    $update_sql = "update member set user_pw = '$new_pw' where user_pw = '$old_pw'";
    $result = $conn -> query($update_sql);

    if ($result) {
        echo "<script>alert('PW가 수정되었습니다!');</script>";
    } else {
        echo "<script>alert('PW 수정에 실패했습니다!');</script>";
    }
    header("location:/mypage/mypage.php");

    $conn -> close();
    exit();
?>