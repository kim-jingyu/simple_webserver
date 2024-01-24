<?php
    $boardId = filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS);

    $inquiryRepository = new InquiryRepository();
    $result = $inquiryRepository->findById($boardId);
    $row = $result->fetch_assoc();
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
        <h1><?php echo $row['title'] ?></h1>
        <div>
            <p><?php echo '작성자 : '.$row['writer_name']?></p>
            <p><?php echo '작성일 : '.$row['date_value']?></p>
            <p>
                <?php
                    if (isset($row['file_name'])) {
                        $file_name = implode('_', array_slice(explode('_', $row['file_name']), 1));
                        $file_path = '/path/upload/'.$stored_file_name;
                        echo '<p>파일명: <a href="/file/file_download.php?file='.$row['file_name'].'">'.$file_name.'</a></p>';
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
            
            <div class="footer">
                <form action='board_fix.php' method='get'>
                    <input type='hidden' name='board_id' value='<?php echo "$board_id" ?>'>
                    <p><button class='btn' type='submit'>게시물 수정</button></p>
                </form>
                <form action='board_delete.php' method='post'>
                    <input type='hidden' name='board_id' value='<?php echo "$board_id" ?>'>
                    <input type='hidden' name='name' value='<?php echo "$writer_name" ?>'>
                    <input type='hidden' name='pw' value='<?php echo "$writer_pw" ?>'>
                    <button class="btn" type='submit'>게시글 삭제</button>
                </form>
                <form action='board.php'>
                    <input class='btn' type='submit' value='뒤로'> 
                </form>
            </div>
        </div>
    </div>
</body>