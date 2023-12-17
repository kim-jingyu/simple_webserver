<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    if (!isset($_COOKIE['JWT'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die("데이터베이스 오류발생.".mysqli_connect_error());
    }

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['board_id'])));

    $update_sql = "update board set likes = likes + 1 where id = $board_id";
    $result = $conn -> query($update_sql);

    if ($result) {
        echo "<script>alert('좋아요!');</script>";
        echo "<script>location.replace('board_view.php?id=$board_id');</script>";
    } else {
        echo "<script>alert('좋아요 실패!');</script>";
        echo "<script>location.replace('board.php);</script>";
    }
    
    $conn -> close();
    exit();
?>