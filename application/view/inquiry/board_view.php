<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardController.php';

    $boardController = new BoardController();
    $response = $boardController->getInquiryBoardView();

    $boardId = $response->getBoardId();
    $result = $response->getResult();
    $row = $result->fetch_assoc();
    
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
    <link rel="stylesheet" href="/css/board.css">
    <title>문의게시글</title>
</head>
<body>
<div class="container">
        <h1><?php echo $title ?></h1>
        <div>
            <p><?php echo '작성자 : '.$writerName?></p>
            <p><?php echo '작성일 : '.$dateValue?></p>
            <p>
                <?php
                    if (isset($row['file_name'])) {
                        $fileName = implode('_', array_slice(explode('_', $row['file_name']), 1));
                        echo '<p>파일명: <a href="/application/service/file/FileDownloadService.php?file='.$row['file_name'].'">'.$fileName.'</a></p>';
                    }
                ?>
            </p>
        </div>
        <div class="content">    
            <h2>CONTENT</h2>
            <?php
                if (mysqli_num_rows($result)) {
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
                <form action='/application/controller/inquiry/InquiryBoardDeleteController.php' method='post'>
                    <input type='hidden' name='boardId' value='<?php echo $boardId ?>'>
                    <input type='hidden' name='writerName' value='<?php echo $writerName ?>'>
                    <input type='hidden' name='writerPw' value='<?php echo $writerPw ?>'>
                    <button class="btn" type='submit'>게시글 삭제</button>
                </form>
                <form action='board.php'>
                    <input class='btn' type='submit' value='뒤로'> 
                </form>
            </div>
        </div>
    </div>
</body>