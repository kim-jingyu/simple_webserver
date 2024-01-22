<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();
    $conn = DBConnectionUtil::getConnection();

    $board_id = filter_var(strip_tags($_POST['board_id']));

    $update_sql = "update board set likes = likes + 1 where id = $board_id";
    $result = $conn -> query($update_sql);

    if ($result) {
        echo "<script>alert('좋아요!');</script>";
        echo "<script>location.replace('board_view.php?board_id=$board_id');</script>";
    } else {
        echo "<script>alert('좋아요 실패!');</script>";
        echo "<script>location.replace('/index.php);</script>";
    }
    
    $conn -> close();
    exit();
?>