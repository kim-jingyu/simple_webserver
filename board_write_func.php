<?php
    require 'db_info.php';

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header('location:login.html');
        exit();
    }

    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    // 데이터베이스 오류발생시 종료
    if (mysqli_connect_errno()) {
        die("데이터베이스 오류발생.".mysqli_connect_error());
    }

    $board_id = $_POST['board_id'] ? $_POST['board_id'] : null ;
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);
    $user_id = $_SESSION['loginId'];
    $today = date("Y-m-d");

    if ($board_id) {
        $sql = "update board set title = '$title', body = '$body', user_id = '$user_id', date = '$today' where idx = '$board_id'";
    } else {
        $sql = "insert into board (title, body, user_id, date) values ('$title', '$body', '$user_id', '$today')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('작성이 완료되었습니다.');</script>";
        echo "<script>location.replace('board.php');</script>";
    } else {
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('board_write.php');</script>";
    }
    mysqli_close($conn);
    exit();
?>