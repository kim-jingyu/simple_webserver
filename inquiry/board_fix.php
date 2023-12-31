<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    $board_id = $conn -> real_escape_string(filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS));
    $select_sql = "select * from inquiry_board where id = '$board_id'";
    $result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $body = $row['body'];
    }
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css">
    <title>게시판 수정</title>
</head>
<body>
    <h1>게시글 수정</h1>
    <div class="container">
        <form action="board_write_func.php" method="post" enctype="multipart/form-data">
            <input class="input-title" type="text" name="name" maxlength="20" placeholder="작성자 이름" required>
            <input class="input-title" type="password" name="pw" maxlength="20" placeholder="비밀번호 입력" required>
            <input class="input-title" type="text" name="title" maxlength="20" value="<?php echo "$title"; ?>" placeholder="게시글 제목 입력. 최대 20자" required>
            <textarea class="textarea-content" type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자" required><?php echo "$body"; ?></textarea>
            <input type='hidden' name='board_id' value='<?php echo "$board_id"; ?>'>
            <input class="btn" type="submit" value="게시글 수정">
        </form>
        <form action="board_view.php">
            <input type='hidden' name='board_id' value='<?php echo "$board_id"; ?>'>
            <input class="btn" type="submit" value="뒤로">
        </form>
    </div>
</body>
</html>