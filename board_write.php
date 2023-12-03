<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 작성</title>
</head>

<body>
    <?php
        session_start();

        if (!isset($_SESSION['loginId'])) {
            header("location:login.html");
            exit();
        }
    ?>
    <h1>게시글 작성</h1>
    <form action="board_write_func.php" method="post">
        <p><input type="text" name="title" maxlength="20" placeholder="게시글 제목 입력. 최대 20자"></p>
        <p><textarea type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자"></textarea></p>
        <p><input type="submit" value="게시글 등록"></p>
    </form>
    <form action="board.php">
        <p><input type="submit" value="뒤로"></p>
    </form>
</body>

</html>