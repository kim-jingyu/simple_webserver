<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';

    class CommentService {
        public function __construct() {
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
    }
?>