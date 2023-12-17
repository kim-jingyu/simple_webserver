<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';
    require $_SERVER['DOCUMENT_ROOT'].'/jwt/jwt.php';

    if (!isset($_COOKIE['JWT'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류발생!'.mysqli_connect_error());
    }

    $old_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['OldId']), FILTER_SANITIZE_SPECIAL_CHARS));

    if ($old_id != getToken($_COOKIE['JWT'])['user']) {
        echo "<script>alert('ID 수정에 실패했습니다!');</script>";
        echo "<script>location.replace('/mypage/mypage.php');</script>";
        exit;
    }

    $new_id = $conn -> real_escape_string(filter_var(strip_tags($_POST['NewId']), FILTER_SANITIZE_SPECIAL_CHARS));

    $member_update_sql = "update member set user_id = '$new_id' where user_id = '$old_id'";
    $board_update_sql = "update board set user_id = '$new_id' where user_id = '$old_id'";
    $member_update_result = $conn -> query($member_update_sql);
    $board_update_result = $conn -> query($board_update_sql);

    $member_select_sql = "select user_id, user_name from member where user_id = '$new_id'";
    $member_select_result = $conn -> query($member_select_sql);
    $member_select_row = $member_select_result -> fetch_assoc();

    if ($member_update_result && $board_update_result) {
        $jwt = createToken($member_select_row['user_id'], $member_select_row['user_name']);
        setcookie('JWT', $jwt, time() + 30*60, "/");
        echo "<script>alert('ID가 수정되었습니다!');</script>";
    } else {
        echo "<script>alert('ID 수정에 실패했습니다!');</script>";
    }
    echo "<script>location.replace('/mypage/mypage.php');</script>";

    $conn -> close();
    exit();
?>