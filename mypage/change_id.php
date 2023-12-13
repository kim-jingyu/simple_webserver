<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류발생!'.mysqli_connect_error());
    }

    $old_id = $_POST['OldId'];
    echo "<script>alert('{$old_id}');</script>";
    $new_id = $_POST['NewId'];
    echo "<script>alert('{$new_id}');</script>";

    $update_sql = "update member set user_id = '$new_id' where user_id = '$old_id'";
    $result = $conn -> query($update_sql);

    if ($result) {
        session_regenerate_id();    // ID 자동 갱신
        $_SESSION['loginId'] = $new_id;
        echo "<script>alert('ID가 수정되었습니다!');</script>";
    } else {
        echo "<script>alert('ID 수정에 실패했습니다!');</script>";
    }
    echo "<script>location.replace('/mypage/mypage.php');</script>";

    $conn -> close();
    exit();
?>