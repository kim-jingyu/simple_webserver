<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardController.php';

    $boardController = new InquiryBoardController();
    $response = $boardController->getInquiryBoardView();

    $boardId = $response->getBoardId();
    $row = $response->getData();
    
    $title = $row['title'];
    $body = $row['body'];
    $writerName = $row['writer_name'];
    $writerPw = $row['writer_pw'];
    $dateValue = $row['date_value'];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css?after">
    <title>문의게시글</title>
</head>
<body>
<div class="container">
        <h1><?php echo $title ?></h1>
        <div>
            <p><?php echo '작성자 : '.$writerName?></p>
            <p><?php echo '작성일 : '.$dateValue?></p>
        </div>
        <div class="content">    
            <h2>CONTENT</h2>
            <?php
                if (!empty($body)) {
                    echo '<textarea class="textarea-content" rows="20" cols="40" readonly>'.$body.'</textarea>';
                }
            ?>
        </div>
        <div>
            
            <div class="footer">
                <form action='board_fix.php' method='get'>
                    <input type='hidden' name='boardId' value='<?php echo "$boardId" ?>'>
                    <p><button class='btn' type='submit'>게시물 수정</button></p>
                </form>
                <a class='inquiry-delete-btn' href='#'>게시글 삭제</a>
                <div class="modals">
                    <div class="modal-window">
                        <div class="title">
                            <h2>삭제</h2>
                        </div>
                        <div class="close-area">X</div>
                        <form class="fix-content" action="/application/controller/inquiry/InquiryBoardDeleteController.php" method="post">
                            <input type='hidden' name='boardId' value='<?php echo $boardId ?>'>
                            <input class="input-title" type='text' name='writerName' placeholder="작성자 이름">
                            <input class="input-title" type='text' name='writerPw' placeholder="비밀번호 입력">
                            <button class="btn" type="submit">게시글 삭제</button>
                        </form>
                    </div>
                </div>
                <a class="btn" style="text-decoration: none;" href="board.php">뒤로</a>
            </div>
        </div>
    </div>
    <script src="/js/inquiry.js"></script>
</body>