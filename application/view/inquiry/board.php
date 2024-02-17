<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardController.php';

    $inquiryBoardController = new InquiryBoardController();
    $response = $inquiryBoardController->getInquriyBoard();

    $searchWord = $response->getSearchWord();
    $dateValue = $response->getDateValue();
    $pageNow = $response->getPageNow();
    $blockNow = $response->getBlockNow();
    $sort = $response->getSort();
    $totalPages = $response->getTotalPages();
    $boardData = $response->getBoardData();
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
        <h1 class="board-title">문의게시판</h1>
        <div class="board">
            <div>
                <form class="funcs" action="" method="get">
                    <div>
                        <select class="sort" name="sort" onchange="this.form.submit()">
                            <option value=""  <?php if(isset($sort)) {echo "disabled"; } ?>>정렬 옵션</option>
                            <option value="author" <?php if($sort == 'author') {echo "selected"; } ?>>작성자순</option>
                            <option value="date" <?php if($sort == 'date') {echo "selected"; } ?>>날짜순</option>
                        </select>
                    </div>
                    <div class="search">
                        <label for="search">타이틀 검색:</lable>
                        <input class="search_text" type="text" name="search" value="<?php echo $searchWord; ?>" placeholder="검색">
                    </div>
                    <div class="date-box">
                        <label for="dateValue">날짜:</lable>
                        <input type="date" class="search_date" id="dateValue" name="dateValue" value="<?php echo $dateValue; ?>">    
                    </div>
                    <input class="btn" type="submit" value="검색">
                </form>
            </div>
            <table>
                <tr>
                    <th></th>
                    <th>제목</th>
                    <th>작성자</th>
                </tr>
                <?php
                    if (!empty($boardData)) {
                        foreach ($boardData as $row) {
                            echo '<tr>';
                            echo '<td>'.$row['id'].'</td>';
                            echo '<td><a class="link" href="/application/view/inquiry/board_view.php?boardId='.$row['id'].'">'.$row['title'].'</a></td>';
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
                            echo '<a class="link" href="?page='.$prevBlockStart.'&search='.$searchWord.'&dateValue'.$dateValue.'">이전 페이지</a>"';
                        }

                        for ($pageNum = $blockNow + 1; $pageNum <= min($blockNow + 5, $totalPages); $pageNum++) {
                            if ($pageNum == $pageNow) {
                                echo $pageNum;
                            } else {
                                echo '<a class="link" href="?page='.$pageNum.'&search='.$searchWord.'&dateValue='.$dateValue.'">'.$pageNum.'</a>';
                            }
                            echo ' ';
                        }

                        if ($blockNow + 5 < $totalPages) {
                            $nextBlockStart = $blockNow + 6;
                            echo '<a class="link" href="?page='.$nextBlockStart.'&search='.$searchWord.'&dateValue='.$dateValue.'">다음 페이지</a>';
                        }
                        echo ' ]</p>';
                    } else {
                        echo '<tr>';
                        echo '<td>게시물이</td>';
                        echo '<td>없습니다.</td>';
                        echo '<td>🤪</td>';
                        echo '</tr>';
                        echo '</table>';
                    }
                ?>
            <div class="footer">
                <a class="btn" style="text-decoration: none;" href="board_write.php">게시글 작성</a>
                <a class="btn" style="text-decoration: none;" href="/application/view/login/login.html">뒤로</a>
            </div>
        </div>
    </div>
</body>
</html>