<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardController.php';

    $inquiryBoardController = new InquiryBoardController();
    $response = $inquiryBoardController->getInquriyBoard();

    $searchWord = $response->getSearchWord();
    $dateValue = $response->getDateValue();
    $blockNow = $response->getBlockNow();
    $sort = $response->getSort();
    $totalPages = $response->getTotalPages();
    $result = $response->getResult();
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
                        <option value=""  <?php if(isset($sort)) {echo "disabled"; } ?>>정렬 옵션</option>
                        <option value="author" <?php if($sort == 'author') {echo "selected"; } ?>>작성자순</option>
                        <option value="date" <?php if($sort == 'date') {echo "selected"; } ?>>날짜순</option>
                    </select>
                </div>
                <div class="search">
                    <input class="search_text" type="text" name="search" value="<?php echo $searchWord; ?>" placeholder="검색">
                    <input class="btn" type="submit" value="검색">
                </div>
                <div class="date">
                    <label for="dateValue">날짜:</lable>
                    <input type="date" id="dateValue" name="dateValue" value="<?php echo $dateValue; ?>">    
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
                if (mysqli_num_rows($result)) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<tr>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td><a href="/application/view/inquiry/board_view.php?boardId='.$row['id'].'">'.$row['title'].'</a></td>';
                        echo '<td>'.$row['writer_name'].'</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '<p class="page"> [ ';
                    if ($blockNow > 1) {
                        $prevBlockStart = $blockNow - 5;
                        if ($prevBlockStart == 0) {
                            $prevBlockStart = 1;
                        }
                        echo '<a href="?page='.$prevBlockStart.'&search='.$searchWord.'&dateValue'.$dateValue.'">이전 페이지</a>"';
                    }

                    for ($pageNum = $blockNow + 1; $pageNum <= min($blockNow + 5, $totalPages); $pageNum++) {
                        if ($pageNum == $pageNow) {
                            echo $pageNum;
                        } else {
                            echo '<a href="?page='.$pageNum.'&search='.$searchWord.'&dateValue='.$dateValue.'">'.$pageNum.'</a>';
                        }
                        echo ' ';
                    }

                    if ($blockNow + 5 < $totalPages) {
                        $nextBlockStart = $blockNow + 6;
                        echo '<a href="?page='.$nextBlockStart.'&search='.$searchWord.'&dateValue='.$dateValue.'">다음 페이지</a>';
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
            <form action="/application/view/login/login.html">
                <input class="btn" type="submit" value="뒤로">
            </form>
        </div>
    </div>
</body>
</html>