<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die("데이터베이스 오류 발생.".mysqli_connect_error());
    }

    session_start();

    if (!isset($_SESSION['loginId'])) {
        header("location:/login/login.html");
        exit();
    }

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_GET['id']), FILTER_SANITIZE_SPECIAL_CHARS));

    // 조회수 기능
    $last_view_time_per_board = 'last_view_time_of_'.$board_id;

    if (!isset($_SESSION[$last_view_time_per_board])) {
        $_SESSION[$last_view_time_per_board] = time();
        $update_sql = "update board set views = views + 1 where id = $board_id";
        $conn -> query($update_sql);
    } else {
        $last_view_time = $_SESSION[$last_view_time_per_board];
        $current_time = time();
        $gap_time = $current_time - $last_view_time;
        if ($gap_time > 5) {
            $update_sql = "update board set views = views + 1 where id = $board_id";
            $conn -> query($update_sql);
            $_SESSION[$last_view_time_per_board] = $current_time;
        }
    }

    $select_sql = "select * from board where id = '$board_id'";
    $select_result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($select_result)) {
        $row = $select_result -> fetch_assoc();
        echo '<p>조회수: '.$row['views'].'</p>';
        echo '<p>USER ID: '.$row['user_id'].'</p>';
        echo '<p>타이틀: '.$row['title'].'</p>';
        echo '<p>글 내용: '.$row['body'].'</p>';
        echo '<p>작성일: '.$row['date_value'].'</p>';
        if (isset($row['file_name'])) {
            $file_name = implode('_', array_slice(explode('_', $row['file_name']), 1));
            $file_path = '/path/upload/'.$stored_file_name;
            echo '<p>파일명: <a href="/file/file_download.php?file='.$row['file_name'].'">'.$file_name.'</p>';
        }

        $login_id = $_SESSION['loginId'];

        if ($row['user_id'] == $login_id) {
            echo "<form action='board_fix.php' method='get'>
                    <input type='hidden' name='board_id' value='".$board_id."'>
                    <button type='submit'>게시물 수정</button>
                </form>
                <form action='board_delete.php' method='get'>
                    <input type='hidden' name='board_id' value='".$board_id."'>
                    <button type='submit'>게시글 삭제</button>
                </form>
                <form action='board.php'>
                    <input type='submit' value='뒤로'> 
                </form>
                ";
        }
    }
?>