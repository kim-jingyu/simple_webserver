<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/comment/CommentService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    class CommentController {
        public function __construct() {
        }

        public function write($commenterId, $body, $boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentService = new CommentService();
            try {
                $commentService->write($commenterId, $body, $boardId);
            } catch (Exception $e) {
                throw new Exception("댓글 작성중 문제가 발생했습니다!");
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
                $commenterId = $commentRepository->findCommenterIdById($conn, $id);

                $userId = getToken($_COOKIE['JWT'])['user'];
                if ($commenterId != $userId) {
                    throw new Exception;
                }

                $commentRepository->fix($conn, $body, $id);

                $conn->commit();
                echo "<script>alert('댓글 수정완료!');</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('댓글 수정중 문제가 발생했습니다!');</script>";
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
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
                echo "<script>alert('댓글을 가져오는 도중에 문제가 발생했습니다!');</script>";
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function delete($commentId, $boardId) {
            $conn = DBConnectionUtil::getConnection();
            $commentRepository = new CommentRepository();
            try {
                $conn->beginTransaction();
                $commenterId = $commentRepository->findCommenterIdById($conn, $commentId);

                $userId = getToken($_COOKIE['JWT'])['user'];
                if ($commenterId != $userId) {
                    throw new Exception;
                }

                $commentRepository->delete($conn, $commentId);

                $conn->commit();
                echo "<script>alert('댓글 삭제완료!');</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('댓글 삭제중 문제가 발생했습니다!');</script>";
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }
    }
?>