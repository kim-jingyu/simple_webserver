<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($boardId)) {
        header("location:/index.php");
        exit();
    }

    $boardRepository = new BoardRepository();
    try {
        $boardRepository->delete($boardId);

        echo "<script>alert('삭제 완료!');</script>";
        header("location:/index.php");
    } catch (Exception $e) {
        echo "<script>alert('삭제 실패!');</script>";
        header("location:/index.php");
    }
?>