<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardController.php';

    $inquiryBoardController = new InquriyBoardController();
    $response = $inquiryBoardController->getInquiryBoardFix();
    $boardId = $response->getBoardId();
    $result = $response->getResult();
    
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $body = $row['body'];
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
        <form action="/application/controller/inquiry/InquiryBoardFixController.php" method="post" enctype="multipart/form-data">
            <input class="input-title" type="text" name="name" maxlength="20" placeholder="작성자 이름" required>
            <input class="input-title" type="password" name="pw" maxlength="20" placeholder="비밀번호 입력" required>
            <input class="input-title" type="text" name="title" maxlength="20" value="<?php echo "$title"; ?>" placeholder="게시글 제목 입력. 최대 20자" required>
            <textarea class="textarea-content" type="text" name="body" rows="20" cols="40" maxlength="100" placeholder="게시글 본문 입력. 최대 100자" required><?php echo "$body"; ?></textarea>
            <input type='hidden' name='boardId' value='<?php echo "$boardId"; ?>'>
            <input class="btn" type="submit" value="게시글 수정">
        </form>
        <form action="board_view.php">
            <input type='hidden' name='boardId' value='<?php echo "$boardId"; ?>'>
            <input class="btn" type="submit" value="뒤로">
        </form>
    </div>
</body>
</html>