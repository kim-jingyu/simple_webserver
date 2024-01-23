<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS));

    if (!$board_id) {
        header("location:board_view.php");
        exit();
    }
    
    $sql = "delete from board where id = '$board_id'";
    $conn -> query($sql);
    header("location:/index.php");
    mysqli_close();
    exit();
?>