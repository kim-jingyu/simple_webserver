<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판 수정</title>
</head>
<body>
    <?php
        require 'db_info.php';
        
        session_start();

        if (!isset($_SESSION['loginId'])) {
            header('location:login.html');
            exit();
        }

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        $board_id = $_POST['board_id'];
        $select_sql = "select * from board where id = '$board_id'";
        $result = mysqli_query($conn, $select_sql);

        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $title = $row['title'];
            $body = $row['body'];
        }
    ?>

    <h1>게시글 수정</h1>
    <form action="board_write_func.php" method="post">
        <p><input type="text" name="title" maxlength="20" value="<?php echo "$title"; ?>" placeholder="게시글 제목 입력. 최대 20자"></p>
        <p><textarea type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자"><?php echo "$body"; ?></textarea></p>
        <p><input type="submit" value="게시글 수정"></p>
        <input type='hidden' name='board_id' value='<?php echo "$board_id"; ?>'>
    </form>
    <form action="board_view.php">
        <p><input type="submit" value="뒤로"></p>
    </form>
</body>
</html>