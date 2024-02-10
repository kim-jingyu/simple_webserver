<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    $commenterId = filter_var(strip_tags($_POST['commenterId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $comment = filter_var(strip_tags($_POST['comment']), FILTER_SANITIZE_SPECIAL_CHARS);

    $boardController = new BoardController();
    $boardController->comment($commenterId, $comment, $boardId);
?>