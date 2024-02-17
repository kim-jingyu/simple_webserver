<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';

    class CommentService {
        public function __construct() {
        }

        private function checkUser($conn, $id) {
            $commentRepository = new CommentRepository();
            $commenterId = $commentRepository->findCommenterIdById($conn, $id);
            $userId = getToken($_COOKIE['JWT'])['user'];
            if ($commenterId != $userId) {
                throw new Exception;
            }
        }

        public function write($commenterId, $body, $boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentRepository = new CommentRepository();
            try {
                $conn->beginTransaction();

                $dateValue = date("Y-m-d H:i:s");
                $commentRepository->write($conn, $commenterId, $body, $dateValue, $boardId);

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function fix($body, $id, $boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentRepository = new CommentRepository();
            try {
                $conn->beginTransaction();
                
                $this->checkUser($conn, $id);

                $commentRepository->fix($conn, $body, $id);

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getComments($boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentRepository = new CommentRepository();
            try {
                $conn->beginTransaction();

                $row = $commentRepository->findAllByBoard($conn, $boardId);
                $conn->commit();
                return $row;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function delete($commentId, $boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentRepository = new CommentRepository();
            try {
                $conn->beginTransaction();
                
                $this->checkUser($conn, $commentId);

                $commentRepository->delete($conn, $commentId);

                $conn->commit();
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