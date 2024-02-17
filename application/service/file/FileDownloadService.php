<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    class FileDownloadService {
        public function __construct() {
        }

        public function download($boardId) {
            $conn = DBConnectionUtil::getConnection();
            $boardRepository = new BoardRepository();

            $s3Client = S3Manager::getClient();
            $bucketName = S3Manager::getBucketName();
            $filePath = 'path/upload/'.$fileName;
            $originalFileName = explode("_", $fileName)[1];

            try {
                $findUserId = $boardRepository->findUserIdById($conn, $boardId);
                $userId = getToken($_COOKIE['JWT'])['user'];
                if ($findUserId != $userId) {
                    throw new Exception;
                }

                $fileName = $boardRepository->findFileNameById($conn, $boardId);
                if (!$fileName) {
                    echo "<script>alert('파일이 존재하지 않습니다!');</script>";
                    echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
                }

                $result = $s3Client->getObject([
                    'Bucket' => $bucketName,
                    'Key' => $filePath,
                ]);

                header('Content-Type: '.$result['ContentType']);
                header('Content-Disposition: attachment; filename='.$originalFileName);
                header('Content-Length: '.$result['ContentLength']);
                echo $result['Body'];
            } catch (Exception $e) {
                echo "<script>alert('파일 다운로드 실패!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            } finally {
                exit();
            }
        }
    }
?>