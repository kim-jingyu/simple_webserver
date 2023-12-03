<?php
    require 'db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die("데이터베이스 오류 발생.".mysqli_connect_error());
    }

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header("location:login.html");
        exit();
    }

    $board_id = $_GET['id'];

    $select_sql = "select title, body, user_id, date from board where idx = '$board_id'";
    $result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<p>USER ID: '.$row['user_id'].'</p>';
            echo '<p>타이틀: '.$row['title'].'</p>';
            echo '<p>글 내용: '.$row['body'].'</p>';
            echo '<p>작성일: '.$row['date'].'</p>';
        }
    }
?>