<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/board/BoardService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    checkToken();

    function close($message, $url) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('{$url}');</script>";
        exit();
    }

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($boardId)) {
        header("location:/index.php");
        exit();
    }

    $boardService = new BoardService();
    try {
        $boardService->delete($boardId);
        close('삭제 완료!', '/index.php');
    } catch (IdNotMatchedException $e) {
        close(e->errorMessage(), '/application/view/board/board_view.php?boardId='.$boardId);
    } catch (Exception $e) {
        close('삭제 실패!', '/application/view/board/board_view.php?boardId='.$boardId);
    }
?>