<?php

    class BoardService {
        public function __construct() {
        }

        private function fileUpload() {
            // 파일 업로드
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $conn->close();
                exit();
            }

            $storedFileName = null;

            $file = $_FILES['file'];
            if ($file['size'] != 0) {
                $fileName = $file['name'];
                $fileTempName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'text/plain', 'application/zip', 'applicatoin/msword', 'application/pdf'];
                $allowedExtensions = ['jpg', 'png', 'gif', 'txt', 'zip', 'word', 'pdf'];

                $timestamp = time();
                $storedFileName = $timestamp.'_'.$fileName;

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
                            $uploadPath = '/path/upload/'.$storedFileName;
                            // 파일 이동 및 저장
                            if (move_uploaded_file($fileTempName, $uploadPath)) {
                                echo "<script>alert('파일 업로드 성공!');</script>";
                            } else {
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
        }

        public function write($title, $body, $userId, $today) {
            fileUpload();

            $boardWriteRequest = new BoardWriteRequest($title, $body, $userId, $today, $storedFileName);
            $boardRepository = new BoardRepository();
            $boardId = $boardRepository->write($boardWriteRequest);
            return $boardId;
        }

        public function fix($boardId, $title, $body, $userId, $today) {
            fileUpload();

            $boardFixRequest = new BoardFixRequest($title, $body, $userId, $today, $storedFileName);
            $boardRepository = new BoardRepository();
            $boardRepository->fix($boardFixRequest);
        }
    }
?>