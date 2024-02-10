<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';

    class CommentController {
        public function __construct() {
        }

        public function write($commenterId, $comment, $boardId) {
            try {
                $commentRepository = new CommentRepository();

                $dateValue = date("Y-m-d h:i:s");
                $commentRepository->write($commenterId, $comment, $dateValue, $boardId);

                echo "<script>alert('댓글 작성완료!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('댓글 작성중 문제가 발생했습니다!');</script>";
            } finally {
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function fix($commenterId, $boardId) {
            try {
                $commentRepository = new CommentRepository();

                $commentRepository->fix();

                echo "<script>alert('댓글 수정완료!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('댓글 수정중 문제가 발생했습니다!');</script>";
            } finally {
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }
    }
?>