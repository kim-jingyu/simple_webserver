<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $commenterId = filter_var(strip_tags($_POST['commenterId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);

    $commentController = new CommentController();
    $commentController->write($commenterId, $body, $boardId);
?>