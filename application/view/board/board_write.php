<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    checkToken();
    $userId = getToken($_COOKIE['JWT'])['user'];
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css">
    <title>게시글 작성</title>
</head>

<body>
    <div class="container">
    <h1>게시글 작성</h1>
        <form action="/application/controller/board/BoardWriteController.php" method="post" enctype="multipart/form-data">
            <input class="input-title" type="text" name="title" maxlength="100" placeholder="게시글 제목 입력. 최대 100자" required>
            <textarea class="textarea-content" type="text" name="body" rows="20" cols="40" maxlength="500" placeholder="게시글 본문 입력. 최대 500자" required></textarea>
            <label for="file">
                <div class="btn-upload">파일 업로드하기</div>
            </label>
            <input type="file" name="file" id="file">
            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
            <input class="btn" type="submit" value="게시글 등록">
        </form>
        <form action="/index.php">
            <input class="btn" type="submit" value="뒤로">
        </form>
    </div>
</body>

</html>