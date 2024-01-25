<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';

    $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
    $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
    

    $boardController = new BoardController();
    $boardController->writeIndexBoard($title, $body, $userId);
?>