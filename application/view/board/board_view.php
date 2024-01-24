<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    $conn = DBConnectionUtil::getConnection();
    checkToken();

    session_start();

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    // 조회수 기능
    $lastViewTimePerBoard = 'last_view_time_of_'.$boardId;

    if (!isset($_SESSION[$lastViewTimePerBoard])) {
        $_SESSION[$lastViewTimePerBoard] = time();
        $update_sql = "UPDATE board SET views = views + 1 WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $boardId);
        $stmt->execute();
    } else {
        $last_view_time = $_SESSION[$lastViewTimePerBoard];
        $current_time = time();
        $gap_time = $current_time - $last_view_time;
        if ($gap_time > 5) {
            $update_sql = "update board set views = views + 1 where id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $boardId);
            $stmt->execute();
            $_SESSION[$lastViewTimePerBoard] = $current_time;
        }
    }

    $select_sql = "select * from board where id = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $boardId);
    $stmt->execute();
    $select_result = $stmt->get_result();
    $row = $select_result->fetch_assoc();
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
                        $file_path = '/path/upload/'.$stored_file_name;
                        echo '<p>파일명: <a href="/application/service/file/file_download.php?file='.$row['file_name'].'">'.$file_name.'</a></p>';
                    }
                ?>
            </p>
        </div>
        <div class="content">    
            <h2>CONTENT</h2>
            <?php
                if (mysqli_num_rows($select_result)) {
                    echo '<textarea class="textarea-content" rows="20" cols="40" readonly>'.$row['body'].'</textarea>';
                }
            ?>
        </div>
        <div>
            <?php
                    $user_id = getToken($_COOKIE['JWT'])['user'];

                    echo "<div class='footer'>";
                    if ($row['user_id'] == $user_id) {
                        echo "<form action='board_fix.php' method='get'>
                                <input type='hidden' name='boardId' value='".$boardId."'>
                                <p><button class='btn' type='submit'>게시물 수정</button></p>
                            </form>

                            <form action='board_delete.php' method='get'>
                                <input type='hidden' name='boardId' value='".$boardId."'>
                                <p><button class='btn' type='submit'>게시글 삭제</button></p>
                            </form>
                            ";
                    }
                    echo "
                            <form action='/application/service/board/BoardLikeService.php' method='post'>
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