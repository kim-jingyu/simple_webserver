<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    function close($message, $boardId) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
        exit();
    }
    
    $commentId = filter_var(strip_tags($_GET['commentId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        $commentController = new CommentController();

        $commentController->delete($commentId, $boardId);
        close("댓글 삭제완료!", $boardId); 
    } catch (Exception $e) {
        close($e->getMessage(), $boardId);
    }
?>