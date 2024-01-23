<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    $boardId = filter_var(strip_tags($_GET['board_id']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$boardId) {
        header("location:board_view.php");
        exit();
    }
    
    $boardRepository = new BoardRepository();
    try {
        $boardRepository->delete($boardId);
        header("location:/index.php");
    } catch (Exception $e) {
        echo $e;
    }
?>