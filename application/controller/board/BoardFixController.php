<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
    $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $file = $_FILES['file'];

    try {
        $boardController = new BoardController();
        $boardController->fixIndexBoard($boardId, $title, $body, $userId, $file);

        echo "<script>alert('수정이 완료되었습니다.');</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
    } catch (Exception $e) {
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('/application/view/board/board_fix.php?boardId=$boardId');</script>";
    }
?>