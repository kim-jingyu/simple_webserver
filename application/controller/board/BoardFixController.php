<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/BoardController.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    function close($message, $boardId) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
        exit();
    }

    try {
        $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
        $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $file = $_FILES['file'];

        $boardController = new BoardController();
        $boardController->fixIndexBoard($boardId, $title, $body, $userId, $file);

        close('수정이 완료되었습니다.', $boardId);
    } catch (IdNotMatchedException $e) {
        close($e->errorMessage(), $boardId);
    } catch (Exception $e) {
        close('수정에 실패했습니다!', $boardId);
    }
?>