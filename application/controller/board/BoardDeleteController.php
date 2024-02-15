<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($boardId)) {
        header("location:/index.php");
        exit();
    }

    $boardRepository = new BoardRepository();
    $commentRepository = new CommentRepository();
    try {
        $findUserId = $boardRepository->findUserIdById($boardId);
        $userId = getToken($_COOKIE['JWT'])['user'];
        if ($findUserId != $userId) {
            throw new Exception;
        }

        $commentRepository->deleteByBoardId($boardId);
        $boardRepository->delete($boardId);

        echo "<script>alert('삭제 완료!');</script>";
        header("location:/index.php");
    } catch (Exception $e) {
        echo "<script>alert('삭제 실패!');</script>";
        header("location:/application/view/board/board_view.php?boardId=$boardId");
    }
?>