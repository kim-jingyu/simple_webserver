<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardWriteRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardFixRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/board/BoardServiceResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/IdNotMatchedException.php';

    class BoardService {
        public function __construct() {
        }

        private function checkUser($conn, $boardId) {
            $boardRepository = new BoardRepository();
            $findUserId = $boardRepository->findUserIdById($conn, $boardId);
            $userId = getToken($_COOKIE['JWT'])['user'];
            if ($findUserId != $userId) {
                throw new IdNotMatchedException("아이디 검증에 실패했습니다!");
            }
        }

        private function fileUpload($file, $fileName) {
            // 파일 업로드
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $conn->close();
                exit();
            }
            
            $fileTempName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/zip', 'applicatoin/msword', 'application/pdf'];
            $allowedExtensions = ['jpg', 'png', 'gif', 'txt', 'zip', 'word', 'pdf'];

            if ($fileError == UPLOAD_ERR_OK) {
                // 파일 MIME 타입 검증
                $fileMimeType = mime_content_type($fileTempName);
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileMimeType = finfo_file($fileInfo, $fileTempName);
                finfo_close($fileInfo);
                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    // 파일 확장자 검증
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $uploadPath = 'path/upload/'.$fileName;
                        // 파일 이동 및 저장
                        try {
                            $s3Client = S3Manager::getClient();
                            $bucketName = S3Manager::getBucketName();
                            
                            $result = $s3Client->putObject([
                                'Bucket' => $bucketName,
                                'Key' => $uploadPath,
                                'SourceFile' => $fileTempName,
                            ]);

                            echo "<script>alert('파일 업로드 성공!');</script>";
                        } catch (Exception $e) {
                            echo "<script>alert('파일 저장에 실패했습니다!');</script>";
                            echo "<script>location.replace('/application/view/board/board_write.php');</script>";
                            exit(1);
                        }
                    } else {
                        echo "<script>alert('파일 확장자 검증에 실패했습니다!');</script>";
                        echo "<script>location.replace('/application/view/board/board_write.php');</script>";
                        exit(1);
                    }
                } else {
                    echo "<script>alert('파일 MIME 타입 검증에 실패했습니다!');</script>";
                    echo "<script>location.replace('/application/view/board/board_write.php');</script>";
                    exit(1);
                }
            } else {
                echo "<script>alert('파일 업로드에 실패했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_write.php');</script>";
                exit(1);
            }           
        }

        public function write($title, $body, $userId, $today, $file) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $fileName = $file['name'];
                $timestamp = time();
                $storedFileName = $timestamp.'_'.$fileName;
    
                $boardWriteRequest = new BoardWriteRequest($title, $body, $userId, $today, $storedFileName);
                $boardRepository = new BoardRepository();
                $boardId = $boardRepository->write($conn, $boardWriteRequest);

                if ($file['size'] != 0) {
                    $this->fileUpload($file, $storedFileName);
                }

                $conn->commit();
                return $boardId;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function fix($boardId, $title, $body, $userId, $today, $file) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $boardFixRequest = null;
                $conn->beginTransaction();

                $this->checkUser($conn, $boardId);

                $fileName = $file['name'];
                $timestamp = time();
                $newFileName = $timestamp.'_'.$fileName;

                $boardRepository = new BoardRepository();

                $storedFileName = $boardRepository->findFileNameById($conn, $boardId);
                if ($file['size'] != 0) {
                    $this->fileUpload($file, $newFileName);
    
                    $s3Client = S3Manager::getClient();
                    $bucketName = S3Manager::getBucketName();
                    $filePath = 'path/upload/'.$storedFileName;
                    $s3Client->deleteObject([
                        'Bucket' => $bucketName,
                        'Key' => $filePath,
                    ]);
    
                    $boardFixRequest = new BoardFixRequest($boardId, $title, $body, $userId, $today, $newFileName);
                } else {
                    $boardFixRequest = new BoardFixRequest($boardId, $title, $body, $userId, $today, $storedFileName);                
                }
                $boardRepository->fix($conn, $boardFixRequest);

                $conn->commit();
            } catch (IdNotMatchedException $e) {
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

        public function delete($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $this->checkUser($conn, $boardId);

                $boardRepository = new BoardRepository();

                $s3Client = S3Manager::getClient();
                $bucketName = S3Manager::getBucketName();
                $fileName = $boardRepository->findFileNameById($conn, $boardId);
                $filePath = 'path/upload/'.$fileName;
                $s3Client->deleteObject([
                    'Bucket' => $bucketName,
                    'Key' => $filePath,
                ]);

                $boardRepository->delete($conn, $boardId);

                $conn->commit();
            } catch (IdNotMatchedException $e) {
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

        public function getIndexBoard(BoardRequestDto $boardRequestDto) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $boardRepository = new BoardRepository();
                $boardResponseDto = $boardRepository->pagenate($conn, $boardRequestDto);
                
                $totalCnt = $boardResponseDto->getTotalCnt();
                $numPerPage = $boardRequestDto->getNumPerPage();
                $totalPages = ceil($totalCnt / $numPerPage);
                $boardData = $boardResponseDto->getBoardData();

                $conn->commit();

                $boardServiceResponse = new BoardServiceResponse($totalPages, $boardData);
                return $boardServiceResponse;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getIndexBoardFix($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $this->checkUser($conn, $boardId);

                $boardRepository = new BoardRepository();
                $row = $boardRepository->findAllById($conn, $boardId);

                $indexBoardFixResponse = new IndexBoardFixResponse($boardId, $row);
                $conn->commit();
                return $indexBoardFixResponse;
            } catch (IdNotMatchedException $e) {
                $conn->rollback();
                throw $e;
            } catch (PDOException $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getIndexBoardView($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $boardRepository = new BoardRepository();

                // 조회수 기능
                $lastViewTimePerBoard = 'last_view_time_of_'.$boardId;

                if (!isset($_SESSION[$lastViewTimePerBoard])) {
                    $_SESSION[$lastViewTimePerBoard] = time();
                    $boardRepository->view($conn, $boardId);
                } else {
                    $lastViewTime = $_SESSION[$lastViewTimePerBoard];
                    $currentTime = time();
                    $gapTime = $currentTime - $lastViewTime;
                    if ($gapTime > 5) {
                        $boardRepository->view($conn, $boardId);
                        $_SESSION[$lastViewTimePerBoard] = $currentTime;
                    }
                }
            
                $data = $boardRepository->findAllById($conn, $boardId);
                $conn->commit();
                return $data;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getComment($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $boardRepository = new BoardRepository();
                $conn->beginTransaction();

                $rows = $boardRepository->findWithComments($conn, $boardId);
                $conn->commit();
                return $rows;
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