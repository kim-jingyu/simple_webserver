<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>문의게시글 작성</title>
</head>

<body>
    <h1>문의게시글 작성</h1>
    <form action="board_write_func.php" method="post">
        <p><input type="text" name="name" maxlength="20" placeholder="작성자 이름" required></p>
        <p><input type="password" name="pw" maxlength="20" placeholder="비밀번호 입력" required></p>
        <p><input type="text" name="title" maxlength="20" placeholder="게시글 제목 입력. 최대 20자" required></p>
        <p><textarea type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자" required></textarea></p>
        <p><input type="submit" value="게시글 등록"></p>
    </form>
    <form action="board.php">
        <p><input type="submit" value="뒤로"></p>
    </form>
</body>

</html>