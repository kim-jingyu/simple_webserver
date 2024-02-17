<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/IdNotMatchedException.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/FileNotExsistException.php';

    class FileDownloadService {
        public function __construct() {
        }

        public function download($boardId) {
            $conn = DBConnectionUtil::getConnection();
            $boardRepository = new BoardRepository();

            try {
                $findUserId = $boardRepository->findUserIdById($conn, $boardId);
                $userId = getToken($_COOKIE['JWT'])['user'];
                if ($findUserId != $userId) {
                    throw new IdNotMatchedException("아이디 검증에 실패했습니다!");
                }

                $fileName = $boardRepository->findFileNameById($conn, $boardId);
                if (!$fileName) {
                    throw new FileNotExsistException("파일이 존재하지 않습니다!");
                }

                $s3Client = S3Manager::getClient();
                $bucketName = S3Manager::getBucketName();
                $filePath = 'path/upload/'.$fileName;
                $originalFileName = explode("_", $fileName)[1];

                $result = $s3Client->getObject([
                    'Bucket' => $bucketName,
                    'Key' => $filePath,
                ]);

                header('Content-Type: '.$result['ContentType']);
                header('Content-Disposition: attachment; filename='.$originalFileName);
                header('Content-Length: '.$result['ContentLength']);
                echo $result['Body'];
            } catch (IdNotMatchedException $e) {
                $conn->rollback();
                throw $e;
            } catch (FileNotExsistException $e) {
                $conn->rollback();
                throw $e;
            } catch (PDOException $e) {
                $conn->rollback();
                throw $e;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }
    }
?>