<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css">
    <title>문의게시글 작성</title>
</head>

<body>
    <h1>문의게시글 작성</h1>
    <div class="container">
        <form action="board_write_func.php" method="post" enctype="multipart/form-data">
            <input class="input-title" type="text" name="name" maxlength="20" placeholder="작성자 이름" required>
            <input class="input-title" type="password" name="pw" maxlength="20" placeholder="비밀번호 입력" required>
            <input class="input-title" type="text" name="title" maxlength="20" placeholder="게시글 제목 입력. 최대 20자" required>
            <textarea class="textarea-content" type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자" required></textarea>
            <input class="btn" type="submit" value="게시글 등록">
        </form>
        <form action="board.php">
            <input class="btn" type="submit" value="뒤로">
        </form>
    </div>
</body>

</html>