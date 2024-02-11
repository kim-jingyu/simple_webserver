<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';
    
    $commentId = filter_var(strip_tags($_GET['commentId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    $commentController = new CommentController();
    $commentController->delete($commentId, $boardId);
?>