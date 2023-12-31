<?php
    ini_set('display_erros', 1);
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';
    require $_SERVER['DOCUMENT_ROOT'].'/jwt/jwt.php';

    if (!isset($_COOKIE['JWT'])) {
        header("location:/login/login.html");
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

    $file = $_FILES['file'];
    if ($file['size'] != 0) {
        $file_name = $file['name'];
        $file_temp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/zip', 'applicatoin/msword', 'application/pdf'];
        $allowed_extensions = ['jpg', 'png', 'gif', 'txt', 'zip', 'word', 'pdf'];

        $timestamp = time();
        $stored_file_name = $timestamp.'_'.$file_name;
        $stored_file_name = $conn -> real_escape_string($stored_file_name);

        if ($file_error == UPLOAD_ERR_OK) {
            // 파일 MIME 타입 검증
            $file_mime_type = mime_content_type($file_temp_name);
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_mime_type = finfo_file($file_info, $file_temp_name);
            finfo_close($file_info);
            if (in_array($file_mime_type, $allowed_mime_types)) {
                // 파일 확장자 검증
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                if (in_array($file_extension, $allowed_extensions)) {
                    $upload_path = '/path/upload/'.$stored_file_name;
                    // 파일 이동 및 저장
                    if (move_uploaded_file($file_temp_name, $upload_path)) {
                        echo "<script>alert('파일 업로드 성공!');</script>";
                    } else {
                        echo "<script>alert('파일 저장에 실패했습니다!');</script>";
                        echo "<script>location.replace('board.php');</script>";
                        mysqli_close($conn);
                        exit(1);
                    }
                } else {
                    echo "<script>alert('파일 확장자 검증에 실패했습니다!');</script>";
                    echo "<script>location.replace('board.php');</script>";
                    mysqli_close($conn);
                    exit(1);
                }
            } else {
                echo "<script>alert('파일 MIME 타입 검증에 실패했습니다!');</script>";
                echo "<script>location.replace('board.php');</script>";
                mysqli_close($conn);
                exit(1);
            }
        } else {
            echo "<script>alert('파일 업로드에 실패했습니다!');</script>";
            echo "<script>location.replace('board.php');</script>";
            mysqli_close($conn);
            exit(1);
        }           
    }

    $board_id = $_POST['board_id'] ? $conn -> real_escape_string(filter_var(strip_tags($_POST['board_id']), FILTER_SANITIZE_SPECIAL_CHARS)) : null ;
    $title = $conn -> real_escape_string(filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS));
    $body = $conn -> real_escape_string(filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS));
    $user_id = getToken($_COOKIE['JWT'])['user'];
    $today = date("Y-m-d");

    if ($board_id) {
        $sql = "update board set title = '$title', body = '$body', user_id = '$user_id', date_value = '$today', file_name = '$stored_file_name' where id = '$board_id'";
    } else {
        $sql = "insert into board (title, body, user_id, date_value, file_name, views, likes) values ('$title', '$body', '$user_id', '$today', '$stored_file_name', 0, 0)";
    }
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if ($board_id) {
            echo "<script>alert('수정이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?board_id=$board_id');</script>";
        } else {
            $board_id = $conn -> insert_id;
            echo "<script>alert('작성이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?board_id=$board_id');</script>";
        }
    } else {
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('board_write.php');</script>";
    }
    mysqli_close($conn);
    exit();
?>