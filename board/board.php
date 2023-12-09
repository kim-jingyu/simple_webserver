<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Board</title>
</head>
<body>
    <h1>게시판</h1>
    <form action="" method="get">
        <input type="text" name="search" placeholder="검색">
        <input type="submit" value="검색">
        <label for="date_value">날짜:</lable>
        <input type="date" id="date_value" name="date_value">
    </form>
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';

        session_start();

        if (!isset($_SESSION['loginId'])) {
            header("location:/login/login.html");
            exit();
        }

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if (mysqli_connect_errno()) {
            die('데이터베이스 오류 발생.'.mysqli_connect_error());
        }

        // 검색
        $search_word = $conn -> real_escape_string(filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS));
        // 날짜 검색
        $date_value = $conn -> real_escape_string($_GET['date_value']);

        // 페이징
        $num_per_page = 5;

        $total_count_sql = "select count(*) as cnt from board where title like '%$search_word%' and date_value like '%$date_value%'";
        $total_result = $conn -> query($total_count_sql);
        $total_row = $total_result -> fetch_assoc();
        $total_cnt = $total_row['cnt'];
        $total_pages = ceil($total_cnt / $num_per_page);

        // 현재 페이지 번호
        $page_now = $_GET['page'] ? $conn -> real_escape_string(filter_var(strip_tags(intval($_GET['page'])), FILTER_SANITIZE_SPECIAL_CHARS))  : 1;

        // 현재 페이지 블록 번호
        $block_now = floor(($page_now - 1) / 5) * 5;

        // 각 페이지 시작 인덱스
        $start = ($page_now - 1) * $num_per_page;
       
        $select_sql = "select * from board where title like '%$search_word%' and date_value like '%$date_value%' order by id asc limit $start, $num_per_page";
        $result = mysqli_query($conn, $select_sql);
        
        
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<p>'.$row['id'].'. <a href="board_view.php?id='.$row['id'].'">'.$row['title'].'</a> 작성자 : '.$row['user_id'].' ,조회수 : '.$row['views'].'</p>';
            }
            echo '<p> [ ';
            // 이전 페이지 블록이 있으며, 이전 페이지 블록 링크 출력
            if ($block_now > 1) {
                $prev_block_start = $block_now - 5;
                if ($prev_block_start == 0) {
                    $prev_block_start = 1;
                }
                echo '<a href="?page='.$prev_block_start.'&search='.$search_word.'&date_value'.$date_value.'">이전 페이지</a>';
            }

            for ($page_num = $block_now + 1; $page_num <= min($block_now + 5, $total_pages); $page_num++) {
                if ($page_num == $page_now) {
                    echo $page_num;
                } else {
                    echo '<a href="?page='.$page_num.'&search='.$search_word.'&date_value='.$date_value.'">'.$page_num.'</a>';
                }
                echo ' ';
            }

            // 다음 페이지 블록이 있으며, 다음 페이지 블록 링크 출력
            if ($block_now + 5 < $total_pages) {
                $next_block_start = $block_now + 6;
                echo '<a href="?page='.$next_block_start.'&search='.$search_word.'&date_value'.$date_value.'">다음 페이지</a>';
            }

            echo ' ]</p>';
        } else {
            echo "게시물이 없습니다.";
        }
    ?>
    <form action="board_write.php" method="post">
        <p><input type="submit" name="writeBoard" value="게시글 작성"></p>
    </form>
</body>

</html>