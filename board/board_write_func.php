<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header('location:/login/login.html');
        exit();
    }

    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    // 데이터베이스 오류발생시 종료
    if (mysqli_connect_errno()) {
        die("데이터베이스 오류발생.".mysqli_connect_error());
    }

    // 파일 업로드
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        mysqli_close();
        exit();
    }

    if (!empty($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_temp_name = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_error = $_FILES['file']['error'];
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/zip', 'applicatoin/msword', 'application/pdf'];
    
        $timestamp = time();
        $stored_file_name = $timestamp.'_'.$file_name;
        $stored_file_name = $conn -> real_escape_string($stored_file_name);

        if ($file_error == UPLOAD_ERR_OK) {
            $file_mime_type = mime_content_type($file_temp_name);
            if (in_array($file_mime_type, $allowed_mime_types)) {
                $upload_path = '/path/upload/'.$stored_file_name;
                    
                if (move_uploaded_file($file_temp_name, $upload_path)) {
                    echo "<script>alert('파일 업로드 성공!');</script>";
                } else {
                    echo "<script>alert('파일 업로드 실패!');</script>";
                }
            } else {
                echo "<script>alert('잘못된 파일 형식입니다!');</script>";
            }
        } else {
            echo "<script>alert('파일 업로드에 실패했습니다!');</script>";
        }
    }

    $board_id = $_POST['board_id'] ? $_POST['board_id'] : null ;
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $body = mysqli_real_escape_string($conn, $_POST['body']);
    $user_id = $_SESSION['loginId'];
    $today = date("Y-m-d");

    if ($board_id) {
        $sql = "update board set title = '$title', body = '$body', user_id = '$user_id', date = '$today', file_name = '$stored_file_name' where id = '$board_id'";
    } else {
        $sql = "insert into board (title, body, user_id, date, file_name) values ('$title', '$body', '$user_id', '$today', '$stored_file_name')";
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