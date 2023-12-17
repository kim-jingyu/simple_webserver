<?php
    include $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    if (!isset($_COOKIE['JWT'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS));

    if (!$board_id) {
        header("location:board_view.php");
        exit();
    }
    
    $sql = "delete from board where id = '$board_id'";
    $conn -> query($sql);
    header("location:board.php");
    mysqli_close();
    exit();
?>