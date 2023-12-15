<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die("데이터베이스 오류 발생.".mysqli_connect_error());
    }

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_GET['id']), FILTER_SANITIZE_SPECIAL_CHARS));

    $select_sql = "select * from inquiry_board where id = '$board_id'";
    $select_result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($select_result)) {
        $row = $select_result -> fetch_assoc();
        echo '<p>작성자 이름: '.$row['writer_name'].'</p>';
        echo '<p>타이틀: '.$row['title'].'</p>';
        echo '<p>글 내용: '.$row['body'].'</p>';
        echo '<p>작성일: '.$row['date_value'].'</p>';

        echo "<form action='board_fix.php' method='get'>
                <input type='hidden' name='board_id' value='".$board_id."'>
                <p><button type='submit'>게시물 수정</button></p>
            </form>
            <form action='board_delete.php' method='post'>
                <input type='hidden' name='board_id' value='".$board_id."'>
                <input type='hidden' name='name' value='".$row['writer_name']."'>
                <input type='hidden' name='pw' value='".$row['writer_pw']."'>
                <p><button type='submit'>게시글 삭제</button></p>
            </form>
            <form action='board.php'>
                <input type='submit' value='뒤로'> 
            </form>
            ";
    }
?>