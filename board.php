<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board</title>
</head>
<body>
    <h1>게시판</h1>
    <form action="" method="post">
        <input type="text" name="search" placeholder="검색">
        <input type="submit" value="검색">
    </form>
    <?php
        require 'db_info.php';

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if (mysqli_connect_errno()) {
            die('데이터베이스 오류 발생.'.mysqli_connect_error());
        }

        session_start();

        if (!isset($_SESSION['loginId'])) {
            header("location:login.html");
            exit();
        }

        $search_word = mysqli_real_escape_string($conn, $_POST['search']);

        $select_sql = "select * from board where title like '%$search_word%'";
        $result = mysqli_query($conn, $select_sql);
        
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<p>'.$row['id'].'. <a href="board_view.php?id='.$row['id'].'">'.$row['title'].'</a> 작성자 : '.$row['user_id'].'</p>';
            }
        } else {
            echo "게시물이 없습니다.";
        }
    ?>
    <form action="board_write.php" method="post">
        <p><input type="submit" name="writeBoard" value="게시글 작성"></p>
    </form>
</body>

</html>