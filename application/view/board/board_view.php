<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    checkToken();
    $userId = getToken($_COOKIE['JWT'])['user'];

    session_start();

    $boardController = new BoardController();
    $boardViewResp = $boardController->getIndexBoardView();

    $boardId = $boardViewResp->getBoardId();
    $boardResult = $boardViewResp->getResult();
    $boardData = $boardResult->fetch_assoc();
    $commentResult = $boardController->getComment($boardId);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css?after">
    <title>게시글</title>
</head>
<body>
    
    
    <div class="container">
        <h1><?php echo $boardData['title'] ?></h1>
        <div>
            <p><?php echo '작성자 : '.$boardData['user_id']?></p>
            <p><?php echo '작성일 : '.$boardData['date_value']?></p>
            <p><?php echo '조회수 : '.$boardData['views']?></p>
            <p><?php echo '좋아요 수 : '.$boardData['likes']?></p>
            <p>
                <?php
                    if (isset($boardData['file_name'])) {
                        $file_name = explode('_', $boardData['file_name'])[1];
                        echo '<p>파일명: <a class="link" href="/application/controller/board/FileDownloadController.php?boardId='.$boardId.'">'.$file_name.'</a></p>';
                    }
                ?>
            </p>
        </div>
        <div class="content">    
            <h2>CONTENT</h2>
            <?php
                if (mysqli_num_rows($boardResult)) {
                    echo '<textarea class="textarea-content" rows="20" cols="40" readonly>'.$boardData['body'].'</textarea>';
                }
            ?>
        </div>
        <div>
            <?php
                echo "<div class='footer'>";
                if ($boardData['user_id'] == $userId) {
                    echo "<form action='board_fix.php' method='get'>
                            <input type='hidden' name='boardId' value='".$boardId."'>
                            <p><button class='btn' type='submit'>게시물 수정</button></p>
                        </form>

                        <form action='/application/controller/board/BoardDeleteController.php' method='get'>
                            <input type='hidden' name='boardId' value='".$boardId."'>
                            <p><button class='btn' type='submit'>게시글 삭제</button></p>
                        </form>
                        ";
                }
                echo "
                        <form action='/application/controller/board/BoardLikeController.php' method='post'>
                            <input type='hidden' name='boardId' value='".$boardId."'>
                            <p><button class='btn' type='submit'>좋아요!</button></p>
                        </form>
                        
                        <form action='/index.php'>
                            <input class='btn' type='submit' value='뒤로'> 
                        </form>
                    ";
                echo "</div>";
            ?>
        </div>
        <hr>
        <div class="comment-box">
            <h2>COMMENT</h2>
            <form action='/application/controller/comment/CommentWriteController.php' method='post'>
                <input type='hidden' name='commenterId' value='<?php echo $userId?>'>
                <input type='hidden' name='boardId' value='<?php echo $boardId?>'>
                <textarea class="textarea-comment" type="text" name="body" rows="20" cols="20" maxlength="200" placeholder="댓글 작성. 최대 200자"></textarea>
                <button class='btn' type='submit'>댓글 작성</button>
            </form>
            <?php
                $rows = mysqli_num_rows($commentResult);
                echo '<div class="comment">';
                if (!$rows) {
                    echo '<div class="comments">';
                    echo '<h2>No Comments</h2>';
                    echo '</div>';
                } else {
                    $num = 0;
                    while ($commentData = mysqli_fetch_array($commentResult)) {
                        $num++;
                        echo '<div class="comments">';
                        echo '<div class="comment-top">';
                        echo '<a class="commenter">'.$commentData['commenter_id'].'</a>';
                        echo '<span class="dot">.</span>';
                        echo '<span class="date">'.$commentData['comment_date'].'</span>';
                        if ($commentData['commenter_id'] == $userId) {
                            echo "<a class='fix-comment-btn' href='#'>수정</a>";
                            echo '<div class="modals">
                                    <div class="modal-window">
                                        <div class="title">
                                            <h2>수정</h2>
                                        </div>
                                        <div class="close-area">X</div>
                                        <form class="fix-content" action="/application/controller/comment/CommentFixController.php" method="post">
                                            <textarea class="textarea-fix" name="body" maxlength="500">'.$commentData['body'].'</textarea>
                                            <input type="hidden" name="id" value="'.$commentData['id'].'">
                                            <input type="hidden" name="boardId" value="'.$boardId.'">
                                            <button class="btn" type="submit">댓글 수정</button>
                                        </form>
                                    </div>
                                </div>';
                            echo "<span>/</span>";
                            echo '<a class="delete-comment-btn" href="#" onclick="deleteFunc('.$commentData['id'].','.$boardId.');">삭제</a>';
                        }
                        echo '</div>';
                        echo '<div class="comment-body">'.$commentData['body'].'</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            ?>
        </div>
    </div>
    <script src="/js/board.js"></script>
</body>
</html>