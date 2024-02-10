<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/comment/CommentController.php';

    $userId = filter_var(strip_tags($_GET['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    $commentController = new CommentController();
    $commentController->fix($userId, $boardId);
?>