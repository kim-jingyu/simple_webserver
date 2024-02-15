<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';

    class FileDownloadService {
        public function __construct() {
        }

        public function download($boardId) {
            $boardRepository = new BoardRepository();
            $fileName = $boardRepository->findFileNameById($boardId);
            if (!$fileName) {
                echo "<script>alert('파일이 존재하지 않습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }

            $s3Client = S3Manager::getClient();
            $bucketName = S3Manager::getBucketName();
            $filePath = 'path/upload/'.$fileName;
            $originalFileName = explode("_", $fileName)[1];

            try {
                // $url = $s3Client->getObjectUrl($bucketName, $filePath);
                $result = $s3Client->getObject([
                    'Bucket' => $bucketName,
                    'Key' => $filePath,
                ]);

                header('Content-Type: '.$result['ContentType']);
                header('Content-Disposition: attachment; filename='.$originalFileName);
                header('Content-Length: '.$result['ContentLength']);
                readfile($result['Body']);
            } catch (Exception $e) {
                echo "<script>alert('파일 다운로드 실패!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            } finally {
                exit();
            }
        }
    }
?>