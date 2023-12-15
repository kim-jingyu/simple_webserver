<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die("데이터베이스 오류발생.".mysqli_connect_error());
    }

    if (!isset($_POST['pw']) or !isset($_POST['name'])) {
        echo "<script>alert('작성자 정보를 입력하세요!');</script>";
        echo "<script>location.replace('board_write.php');</script>";
        mysqli_close($conn);
        exit();
    }

    $board_id = $_POST['board_id'] ? $conn -> real_escape_string(filter_var(strip_tags($_POST['board_id']), FILTER_SANITIZE_SPECIAL_CHARS)) : null ;
    $name = $conn -> real_escape_string(filter_var(strip_tags($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS));
    $pw = $conn -> real_escape_string(filter_var(strip_tags($_POST['pw']), FILTER_SANITIZE_SPECIAL_CHARS));
    $title = $conn -> real_escape_string(filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS));
    $body = $conn -> real_escape_string(filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS));
    $today = date("Y-m-d");

    if ($board_id) {
        $select_pw_sql = "select writer_pw from inquiry_board where writer_name='$name'";
        $pw_result = $conn -> query($select_pw_sql);
        $pw_row = $pw_result -> fetch_assoc();
        if ($pw_row['writer_pw'] != $pw) {
            echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
            echo "<script>location.replace('board_fix.php');</script>";
            mysqli_close($conn);
            exit();
        }

        $sql = "update inquiry_board set title = '$title', body = '$body', writer_name = '$name', writer_pw = '$pw', date_value = '$today' where id = '$board_id'";
    } else {
        $sql = "insert into inquiry_board (title, body, writer_name, writer_pw, date_value) values ('$title', '$body', '$name', '$pw', '$today')";
    }
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if ($board_id) {
            echo "<script>alert('수정이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?id=$board_id');</script>";
        } else {
            $board_id = $conn -> insert_id;
            echo "<script>alert('작성이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?id=$board_id');</script>";
        }
    } else {
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('board_write.php');</script>";
    }
    mysqli_close($conn);
    exit();
?>