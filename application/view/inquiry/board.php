<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/connection/DBConnectionUtil.php';
    
    $conn = DBConnectionUtil::getConnection();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/index.css">
    <title>문의게시판</title>
</head>
<body>
    <div class="container">
        <h1>문의게시판</h1>
        <div class="board">
            <form class="funcs" action="" method="get">
                <div>
                    <select name="sort" onchange="this.form.submit()">
                        <option value=""  <?php if(isset($_GET['sort'])) {echo "disabled"; } ?>>정렬 옵션</option>
                        <option value="author" <?php if($_GET['sort'] == 'author') {echo "selected"; } ?>>작성자순</option>
                        <option value="date" <?php if($_GET['sort'] == 'date') {echo "selected"; } ?>>날짜순</option>
                    </select>
                </div>
                <div class="search">
                    <input class="search_text" type="text" name="search" value="<?php echo $_GET['search']; ?>" placeholder="검색">
                    <input class="btn" type="submit" value="검색">
                </div>
                <div class="date">
                    <label for="date_value">날짜:</lable>
                    <input type="date" id="date_value" name="date_value" value="<?php echo $_GET['date_value'] ?>">    
                </div>
            </form>
        </div>
        <table>
            <tr>
                <th></th>
                <th>제목</th>
                <th>작성자</th>
            </tr>
            <?php
                $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
                $dateValue = filter_var(strip_tags($_GET['date_value']), FILTER_SANITIZE_SPECIAL_CHARS);

                $numPerPage = 5;

                $page_now = $_GET['page'] ? filter_var(strip_tags($_GET['page']), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
                $block_now = floor(($page_now - 1) / 5) * 5;
                $startIdxPerPage = ($page_now - 1) * $num_per_page;

                $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

                $inquiryBoardRequest = new InquiryBoardRequest($searchWord, $dateValue, $numPerPage, $startIdxPerPage, $sort);
                $inquiryRepository = new InquiryRepository();
                $inquiryBoardResponse = $inquiryRepository->pagenate($inquiryBoardRequest);

                $totalCnt = $inquiryBoardResponse->getTotalCnt();
                $result = $inquiryBoardResponse->getResult();
                $total_pages = ceil($totalCnt / $numPerpage);

                if (mysqli_num_rows($result)) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<tr>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td><a href="/application/view/inquiry/board_view.php?board_id='.$row['id'].'">'.$row['title'].'</a></td>';
                        echo '<td>'.$row['writer_name'].'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '<p class="page"> [ ';
                    if ($block_now > 1) {
                        $prev_block_start = $block_now - 5;
                        if ($prev_block_start == 0) {
                            $prev_block_start = 1;
                        }
                        echo '<a href="?page='.$prev_block_start.'&search='.$searchWord.'&date_value'.$dateValue.'">이전 페이지</a>"';
                    }

                    for ($page_num = $block_now + 1; $page_num <= min($block_now + 5, $total_pages); $page_num++) {
                        if ($page_num == $page_now) {
                            echo $page_num;
                        } else {
                            echo '<a href="?page='.$page_num.'&search='.$searchWord.'&date_value='.$dateValue.'">'.$page_num.'</a>';
                        }
                        echo ' ';
                    }

                    if ($block_now + 5 < $total_pages) {
                        $next_block_start = $block_now + 6;
                        echo '<a href="?page='.$next_block_start.'&search='.$searchWord.'&date_value='.$dateValue.'">다음 페이지</a>';
                    }
                    echo ' ]</p>';
                } else {
                    echo '</table>';
                    echo "게시물이 없습니다.";
                }
            ?>
        <div class="footer">
            <form action="board_write.php" method="post">
                <input class="btn" type="submit" name="writeBoard" value="게시글 작성">
            </form>
            <form action="/login/login.html">
                <input class="btn" type="submit" value="뒤로">
            </form>
        </div>
    </div>
</body>
</html>