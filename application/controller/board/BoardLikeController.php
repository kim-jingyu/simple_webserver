<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    try {
        $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

        $boardRepository = new BoardRepository();

        $conn = DBConnectionUtil::getConnection();
        $conn->beginTransaction();
        $boardRepository->like($conn, $boardId);

        $conn->commit();
        echo "<script>alert('좋아요!');</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('좋아요 실패!');</script>";
        echo "<script>location.replace('/index.php);</script>";
    }
?>