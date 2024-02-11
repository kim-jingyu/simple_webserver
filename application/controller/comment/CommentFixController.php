<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';

    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
    $id = filter_var(strip_tags($_POST['id']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    $commentController = new CommentController();
    $commentController->fix($body, $id, $boardId);
?>