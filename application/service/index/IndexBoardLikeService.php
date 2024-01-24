<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();
    $conn = DBConnectionUtil::getConnection();

    $boardId = filter_var(strip_tags($_POST['board_id']));

    $boardRepository = new BoardRepository();
    try {
        $result = $boardRepository->like($boardId);

        if ($result) {
            echo "<script>alert('좋아요!');</script>";
            echo "<script>location.replace('/application/view/board/board_view.php?board_id=$boardId');</script>";
        } else {
            echo "<script>alert('좋아요 실패!');</script>";
            echo "<script>location.replace('/index.php);</script>";
        }
    } catch (Exception $e) {
        echo $e;
    }
?>