<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    checkToken();
    $userId = getToken($_COOKIE['JWT'])['user'];

    $boardController = new BoardController();
    $response = $boardController->getIndexBoardFix();

    $boardId = $response->getBoardId();
    $result = $response->getResult();

    $title = null;
    $body = null;
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $body = $row['body'];
    }
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
        <form action="/application/service/board/BoardWriteService.php" method="post" enctype="multipart/form-data">
            <input class="input-title" type="text" name="title" maxlength="20" value="<?php echo "$title"; ?>" placeholder="게시글 제목 입력. 최대 20자" required>
            <textarea class="textarea-content" type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자" required><?php echo "$body"; ?></textarea>
            <label for="file">
                <div class="btn-upload">파일 업로드하기</div>
            </label>
            <input type="file" name="file" id="file">
            <input type='hidden' name='boardId' value='<?php echo "$boardId"; ?>'>
            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
            <input class="btn" type="submit" value="게시글 수정">
        </form>
        <form action="board_view.php">
            <input type='hidden' name='boardId' value='<?php echo "$boardId"; ?>'>
            <input class="btn" type="submit" value="뒤로">
        </form>
    </div>
</body>
</html>