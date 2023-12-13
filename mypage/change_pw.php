<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류발생!'.mysqli_connect_error());
    }

    $old_pw = $conn -> real_escape_string(filter_var(strip_tags(md5($_POST['OldPw'])), FILTER_SANITIZE_SPECIAL_CHARS));

    $login_id = $_SESSION['loginId'];
    $select_pw_sql = "select pw from member where user_id = '$loginId'";
    $select_pw_result = $conn -> query($select_pw_sql);

    if (!mysqli_num_rows($select_pw_result)) {
        echo "<script>alert('계정 정보가 없습니다!');</script>";
        echo "<script>location.replace('/mypage/mypage.php');</script>";
    }

    $row = $select_pw_result -> fetch_assoc();
    if ($old_pw != $row['user_pw']) {
        echo "<script>alert('비밀번호가 일치하지 않습니다!');</script>";
        echo "<script>location.replace('/mypage/mypage.php');</script>";
    }

    $new_pw = $conn -> real_escape_string(filter_var(strip_tags(md5($_POST['NewPw'])), FILTER_SANITIZE_SPECIAL_CHARS));

    $update_sql = "update member set user_pw = '$new_pw' where user_pw = '$old_pw'";
    $update_result = $conn -> query($update_sql);

    if ($update_result) {
        echo "<script>alert('PW가 수정되었습니다!');</script>";
    } else {
        echo "<script>alert('PW 수정에 실패했습니다!');</script>";
    }
    echo "<script>location.replace('/mypage/mypage.php');</script>";

    $conn -> close();
    exit();
?>