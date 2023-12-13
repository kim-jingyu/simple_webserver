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

    $old_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['OldId']), FILTER_SANITIZE_SPECIAL_CHARS));

    if ($old_id != $_SESSION['loginId']) {
        echo "<script>alert('ID 수정에 실패했습니다!');</script>";
        echo "<script>location.replace('/mypage/mypage.php');</script>";
    }

    $new_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['NewId']), FILTER_SANITIZE_SPECIAL_CHARS));

    $member_update_sql = "update member set user_id = '$new_id' where user_id = '$old_id'";
    $board_update_sql = "update board set user_id = '$new_id' where user_id = '$old_id'";
    $member_result = $conn -> query($member_update_sql);
    $board_result = $conn -> query($board_update_sql);

    if ($member_result && $board_result) {
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