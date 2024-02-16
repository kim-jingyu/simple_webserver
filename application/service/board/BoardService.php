<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardWriteRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardFixRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/aws/S3Manager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    class BoardService {
        private $storedFileName;

        public function __construct() {
        }

        private function checkUser($boardRepository, $boardId) {
            $findUserId = $boardRepository->findUserIdById($boardId);
            $userId = getToken($_COOKIE['JWT'])['user'];
            if ($findUserId != $userId) {
                throw new Exception;
            }
        }

        private function fileUpload($file, $fileName, $storedFileName) {
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
                        $uploadPath = 'path/upload/'.$storedFileName;
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
            try {
                $conn = DBConnectionUtil::getConnection();
                $conn->beginTransaction();

                $fileName = $file['name'];
                $timestamp = time();
                $storedFileName = $timestamp.'_'.$fileName;
    
                $boardWriteRequest = new BoardWriteRequest($title, $body, $userId, $today, $storedFileName);
                $boardRepository = new BoardRepository();
                $boardId = $boardRepository->write($conn, $boardWriteRequest);

                if ($file['size'] != 0) {
                    $this->fileUpload($file, $fileName,$storedFileName);
                }

                $conn->commit();
                return $boardId;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            
        }

        public function fix($boardId, $title, $body, $userId, $today, $file) {
            $boardRepository = new BoardRepository();
            $boardFixRequest = null;

            try {
                $conn = DBConnectionUtil::getConnection();
                $conn->beginTransaction();

                checkUser($boardRepository, $boardId);

                $fileName = $boardRepository->findFileNameById($conn, $boardId);
                if ($file['size'] != 0) {
                    $this->fileUpload($file);
    
                    $s3Client = S3Manager::getClient();
                    $bucketName = S3Manager::getBucketName();
                    $filePath = 'path/upload/'.$fileName;
                    $s3Client->deleteObject([
                        'Bucket' => $bucketName,
                        'Key' => $filePath,
                    ]);
    
                    $boardFixRequest = new BoardFixRequest($boardId, $title, $body, $userId, $today, $this->storedFileName);
                } else {
                    $boardFixRequest = new BoardFixRequest($boardId, $title, $body, $userId, $today, $fileName);                
                }
                $boardRepository->fix($conn, $boardFixRequest);

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
        }

        public function getIndexBoard(BoardRequestDto $boardRequestDto) {
            try {
                $boardRepository = new BoardRepository();

                $boardResponseDto = $boardRepository->pagenate($boardDto);
                
                $totalCnt = $boardResponseDto->getTotalCnt();
                $totalPages = ceil($totalCnt / $numPerPage);
                $boardData = $boardResponseDto->getBoardData();

                $indexBoardResponse = new IndexBoardResponse($searchWord, $dateValue, $pageNow, $blockNow, $sort, $totalPages, $boardData);
                return $indexBoardResponse;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function getIndexBoardFix($boardId) {
            try {
                $boardRepository = new BoardRepository();
                checkUser($boardRepository, $boardId);

                $row = $boardRepository->findAllById($boardId);

                $indexBoardFixResponse = new IndexBoardFixResponse($boardId, $row);
                return $indexBoardFixResponse;
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        public function getIndexBoardView($boardId) {
            try {
                $boardRepository = new BoardRepository();
                checkUser($boardRepository, $boardId);

                // 조회수 기능
                $lastViewTimePerBoard = 'last_view_time_of_'.$boardId;

                if (!isset($_SESSION[$lastViewTimePerBoard])) {
                    $_SESSION[$lastViewTimePerBoard] = time();
                    $boardRepository->view($boardId);
                } else {
                    $lastViewTime = $_SESSION[$lastViewTimePerBoard];
                    $currentTime = time();
                    $gapTime = $currentTime - $lastViewTime;
                    if ($gapTime > 5) {
                        $boardRepository->view($boardId);
                        $_SESSION[$lastViewTimePerBoard] = $currentTime;
                    }
                }
            
                $row = $boardRepository->findAllById($boardId);
                
                $indexBoardViewResponse = new IndexBoardViewResponse($boardId, $row);
                return $indexBoardViewResponse;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function getComment($boardId) {
            try {
                $boardRepository = new BoardRepository();
                checkUser($boardRepository, $boardId);

                $row = $boardRepository->findWithComments($boardId);
                return $row;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
?>