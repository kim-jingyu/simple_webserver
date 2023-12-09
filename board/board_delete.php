<?php
    include $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    $board_id = $_POST['board_id'];

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