<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($boardId)) {
        header("location:/index.php");
        exit();
    }

    $boardRepository = new BoardRepository();
    try {
        $findUserId = $boardRepository->findUserIdById($boardId);
        $userId = getToken($_COOKIE['JWT'])['user'];
        if ($findUserId != $userId) {
            throw new Exception;
        }

        $s3Client = S3Manager::getClient();
        $bucketName = S3Manager::getBucketName();
        $fileName = $boardRepository->findFileNameById($boardId);
        $filePath = 'path/upload/'.$fileName;
        $s3Client->deleteObject([
            'Bucket' => $bucketName,
            'Key' => $filePath,
        ]);

        $boardRepository->delete($boardId);

        echo "<script>alert('삭제 완료!');</script>";
        header("location:/index.php");
    } catch (Exception $e) {
        echo "<script>alert('삭제 실패!');</script>";
        header("location:/application/view/board/board_view.php?boardId=$boardId");
    }
?>