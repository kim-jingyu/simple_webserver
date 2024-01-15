<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/BoardController.php';

    checkToken();
    $id = getToken($_COOKIE['JWT'])['user'];
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/index.css">
    <title>Home</title>
</head>

<body>
    <div class="header">
        <p><?php echo "어서오세요. $id"; ?>님!</p>
        <a href="/application/service/logout/LogoutService.php" class="logout_btn">로그아웃</a>
        <a href="/appliaction/view/mypage/mypage.php" class="mypage_btn">마이페이지</a>
    </div>
    <div class="container">
        <h1>게시판</h1>
        <div class="board">    
            <div>
                <form class="funcs" action="" method="get">
                    <div>
                        <select name="sort" onchange="this.form.submit()">
                            <option value=""  <?php if(isset($_GET['sort'])) {echo "disabled"; } ?>>정렬 옵션</option>
                            <option value="author" <?php if($_GET['sort'] == 'author') {echo "selected"; } ?>>작성자순</option>
                            <option value="date" <?php if($_GET['sort'] == 'date') {echo "selected"; } ?>>날짜순</option>
                            <option value="views" <?php if($_GET['sort'] == 'views') {echo "selected"; } ?>>조회수순</option>
                            <option value="likes" <?php if($_GET['sort'] == 'likes') {echo "selected"; } ?>>추천순</option>
                        </select>
                    </div>
                    <div class="search">
                        <input class="search_text" type="text" name="search" value="<?php echo $_GET['search']; ?>" placeholder="검색">
                        <input class="btn" type="submit" value="검색">
                    </div>
                    <div class="date">
                        <label for="date_value">날짜:</lable>
                        <input type="date" id="date_value" name="dateValue" value="<?php echo $_GET['dateValue'] ?>">    
                    </div>
                </form>
            </div>
            <table>
                <tr>
                    <th></th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>조회수</th>
                    <th>좋아요</th>
                </tr>
                <?php
                    if (mysqli_num_rows($result)) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td>'.$row['id'].'</td>';
                            echo '<td><a href="/board/board_view.php?board_id='.$row['id'].'">'.$row['title'].'</a></td>';
                            echo '<td>'.$row['user_id'].'</td>';
                            echo '<td>'.$row['views'].'</td>';
                            echo '<td>'.$row['likes'].'</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '<p class="page"> [ ';
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
                            echo '<a href="?page='.$next_block_start.'&search='.$search_word.'&date_value='.$date_value.'">다음 페이지</a>';
                        }

                        echo ' ]</p>';
                    } else {
                        echo '</table>';
                        echo "게시물이 없습니다.";
                    }
                ?>
            <div class="footer">
                <form action="/board/board_write.php" method="post">
                    <input type="submit" name="writeBoard" class="btn" value="게시글 작성">
                </form>
            </div>
        </div>
    </div>
</body>

</html>