<?php
    include $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['board_id']), FILTER_SANITIZE_SPECIAL_CHARS));

    if (!$board_id) {
        header("location:board_view.php");
        exit();
    }

    $name = $conn -> real_escape_string(filter_var(strip_tags($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS));
    $pw = $conn -> real_escape_string(filter_var(strip_tags($_POST['pw']), FILTER_SANITIZE_SPECIAL_CHARS));

    $select_pw_sql = "select writer_pw from inquiry_board where writer_name='$name'";
    $pw_result = $conn -> query($select_pw_sql);
    $pw_row = $pw_result -> fetch_assoc();
    if ($pw_row['writer_pw'] != $pw) {
        echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
        echo "<script>location.replace('board_fix.php');</script>";
        mysqli_close($conn);
        exit();
    }
    
    $sql = "delete from inquiry_board where id = '$board_id'";
    $conn -> query($sql);
    echo "<script>alert('게시글이 삭제되었습니다!');</script>";
    echo "<script>location.replace('board.php');</script>";
    mysqli_close();
    exit();
?>