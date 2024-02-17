<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    try {
        $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
        $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $file = $_FILES['file'];

        $boardController = new BoardController();
        $boardId = $boardController->writeIndexBoard($title, $body, $userId, $file);
        echo "<script>alert('작성이 완료되었습니다.');</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
    } catch (Exception $e) {
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('/application/view/board/board_write.php?boardId=$boardId');</script>";
    }
?>