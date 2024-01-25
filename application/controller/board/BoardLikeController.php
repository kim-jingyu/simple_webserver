<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_POST['board_id']));

    $boardRepository = new BoardRepository();
    try {
        $boardRepository->like($boardId);

        echo "<script>alert('좋아요!');</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";        
    } catch (Exception $e) {
        echo "<script>alert('좋아요 실패!');</script>";
        echo "<script>location.replace('/index.php);</script>";
    }
?>