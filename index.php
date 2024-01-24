<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardResponse.php';

    checkToken();
    $id = getToken($_COOKIE['JWT'])['user'];

    $boardController = new BoardController();
    $indexBoardResponse = $boardController->getIndexBoard();
    
    $searchWord = $indexBoardResponse->getSearchWord();
    $dateValue = $indexBoardResponse->getDateValue();
    $blockNow = $indexBoardResponse->getBlockNow();
    $sort = $indexBoardResponse->getSort();
    $totalPages = $indexBoardResponse->getTotalPages();
    $result = $indexBoardResponse->getResult();
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
        <a href="/application/view/mypage/mypage.php" class="mypage_btn">마이페이지</a>
    </div>
    <div class="container">
        <h1>게시판</h1>
        <div class="board">    
            <div>
                <form class="funcs" action="" method="get">
                    <div>
                        <select name="sort" onchange="this.form.submit()">
                            <option value=""  <?php if(isset($sort)) {echo "disabled"; } ?>>정렬 옵션</option>
                            <option value="author" <?php if($sort == 'author') {echo "selected"; } ?>>작성자순</option>
                            <option value="date" <?php if($sort == 'date') {echo "selected"; } ?>>날짜순</option>
                            <option value="views" <?php if($sort == 'views') {echo "selected"; } ?>>조회수순</option>
                            <option value="likes" <?php if($sort == 'likes') {echo "selected"; } ?>>추천순</option>
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
                    <th>조회수</th>
                    <th>좋아요</th>
                </tr>
                <?php
                    if (mysqli_num_rows($result)) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td>'.$row['id'].'</td>';
                            echo '<td><a href="/application/view/board/board_view.php?boardId='.$row['id'].'">'.$row['title'].'</a></td>';
                            echo '<td>'.$row['user_id'].'</td>';
                            echo '<td>'.$row['views'].'</td>';
                            echo '<td>'.$row['likes'].'</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '<p class="page"> [ ';
                        // 이전 페이지 블록이 있으며, 이전 페이지 블록 링크 출력
                        if ($blockNow > 1) {
                            $prevBlockStart = $blockNow - 5;
                            if ($prevBlockStart == 0) {
                                $prevBlockStart = 1;
                            }
                            echo '<a href="?page='.$prevBlockStart.'&search='.$searchWord.'&dateValue'.$dateValue.'">이전 페이지</a>';
                        }

                        for ($pageNum = $blockNow + 1; $pageNum <= min($blockNow + 5, $totalPages); $pageNum++) {
                            if ($pageNum == $page_now) {
                                echo $pageNum;
                            } else {
                                echo '<a href="?page='.$pageNum.'&search='.$searchWord.'&dateValue='.$dateValue.'">'.$pageNum.'</a>';
                            }
                            echo ' ';
                        }

                        // 다음 페이지 블록이 있으며, 다음 페이지 블록 링크 출력
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
                <form action="/application/view/board/board_write.php" method="post">
                    <input type="submit" name="writeBoard" class="btn" value="게시글 작성">
                </form>
            </div>
        </div>
    </div>
</body>

</html>