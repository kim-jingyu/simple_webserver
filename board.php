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

        // 검색
        $search_word = mysqli_real_escape_string($conn, $_POST['search']);

        // 페이징
        $num_per_page = 5;

        $total_count_sql = "select count(*) as cnt from board where title like '%$search_word%'";
        $total_result = $conn -> query($total_count_sql);
        $total_row = $total_result -> fetch_assoc();
        $total_cnt = $total_row['cnt'];
        $total_pages = ceil($total_cnt / $num_per_page);

        // 현재 페이지 번호
        $page_now = $_GET['page'] ? intval($_GET['page']) : 1;

        // 각 페이지 시작 인덱스
        $start = ($page_now - 1) * $num_per_page;

        $select_sql = "select * from board where title like '%$search_word%' order by id asc";
        $select_sql .= $search_word ? " limit 0, $num_per_page" : " limit $start, $num_per_page";
        $result = mysqli_query($conn, $select_sql);
        
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<p>'.$row['id'].'. <a href="board_view.php?id='.$row['id'].'">'.$row['title'].'</a> 작성자 : '.$row['user_id'].'</p>';
            }
            echo '<p> [ ';
            for ($page_num = 1; $page_num <= $total_pages; $page_num++) {
                if ($page_num == $page_now) {
                    echo $page_num;
                } else {
                    echo '<a href="?page='.$page_num.'">'.$page_num.'</a>';
                }
                echo ' ';
            }
            echo ' ]</p>';
            if ($search_word) {
                echo '<form action="" method="post">
                        <input type="hidden" name="search" value="'.$search_word.'">
                    </form>';
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