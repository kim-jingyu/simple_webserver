<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류 발생'.mysqli_connect_error());
    }

    function updateMember() {
        $newId = $_POST['newId'];

        $member_update_sql = "update member set userId = '$newId' where userId = '$oldId'";
        $board_update_sql = "update board set userId = '$newId' where userId = '$oldId'";
        $member_update_result = $conn -> query($member_update_sql);
        $board_update_result = $conn -> query($board_update_sql);

        $member_select_sql = "select userId, user_name from member where userId = '$newId'";
        $member_select_result = $conn -> query($member_select_sql);
        $member_select_row = $member_select_result -> fetch_assoc();

        if ($member_update_result && $board_update_result) {
            $jwt = createToken($member_select_row['userId'], $member_select_row['user_name']);
            setcookie('JWT', $jwt, time() + 30*60, "/");
            echo "<script>alert('ID가 수정되었습니다!');</script>";
        } else {
            echo "<script>alert('ID 수정에 실패했습니다!');</script>";
        }
        echo "<script>location.replace('/mypage/mypage.php');</script>";

        $conn -> close();
    }
?>