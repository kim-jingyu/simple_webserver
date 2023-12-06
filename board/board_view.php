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

    $board_id = $_GET['id'];

    $select_sql = "select * from board where id = '$board_id'";
    $result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result) ;
        echo '<p>USER ID: '.$row['user_id'].'</p>';
        echo '<p>타이틀: '.$row['title'].'</p>';
        echo '<p>글 내용: '.$row['body'].'</p>';
        echo '<p>작성일: '.$row['date'].'</p>';
        if (isset($row['file_name'])) {
            $file_name = explode('_', $row['file_name'])[1];
            $file_path = '/path/upload/'.$stored_file_name;
            echo '<p>파일명: <a href="/file/file_download.php?file='.$row['file_name'].'">'.$file_name.'</p>';
        }

        $login_id = $_SESSION['loginId'];

        if ($row['user_id'] == $login_id) {
            echo "<form action='board_fix.php' method='post'>
                    <input type='hidden' name='board_id' value='".$board_id."'>
                    <button type='submit'>게시물 수정</button>
                </form>
                <form action='board_delete.php' method='post'>
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