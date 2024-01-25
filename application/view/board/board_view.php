<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    checkToken();
    $userId = getToken($_COOKIE['JWT'])['user'];

    session_start();

    $boardController = new BoardController();
    $response = $boardController->getIndexBoardView();

    $boardId = $response->getBoardId();
    $result = $response->getResult();
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/board.css">
    <title>게시글</title>
</head>
<body>
    
    
    <div class="container">
        <h1><?php echo $row['title'] ?></h1>
        <div>
            <p><?php echo '작성자 : '.$row['user_id']?></p>
            <p><?php echo '작성일 : '.$row['date_value']?></p>
            <p><?php echo '조회수 : '.$row['views']?></p>
            <p><?php echo '좋아요 수 : '.$row['likes']?></p>
            <p>
                <?php
                    if (isset($row['file_name'])) {
                        $file_name = implode('_', array_slice(explode('_', $row['file_name']), 1));
                        echo '<p>파일명: <a href="/application/service/file/FileDownloadService.php?file='.$row['file_name'].'">'.$file_name.'</a></p>';
                    }
                ?>
            </p>
        </div>
        <div class="content">    
            <h2>CONTENT</h2>
            <?php
                if (mysqli_num_rows($result)) {
                    echo '<textarea class="textarea-content" rows="20" cols="40" readonly>'.$row['body'].'</textarea>';
                }
            ?>
        </div>
        <div>
            <?php
                echo "<div class='footer'>";
                if ($row['user_id'] == $userId) {
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
    </div>
</body>
</html>