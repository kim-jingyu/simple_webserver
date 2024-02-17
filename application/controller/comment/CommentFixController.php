<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    function close($message, $boardId) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
        exit();
    }

    try {
        $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_var(strip_tags($_POST['id']), FILTER_SANITIZE_SPECIAL_CHARS);
        $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    
        $commentController = new CommentController();

        $commentController->fix($body, $id, $boardId);
        close("댓글 수정완료!", $boarId);
    } catch (Exception $e) {
        close(e->getMessage(), $boarId);
    }
?>