<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/comment/CommentService.php';

    class CommentController {
        public function __construct() {
        }

        public function write($commenterId, $body, $boardId) {
            $commentService = new CommentService();
            try {
                $commentService->write($commenterId, $body, $boardId);
            } catch (Exception $e) {
                throw new Exception("댓글 작성중 문제가 발생했습니다!");
            }
        }

        public function fix($body, $id) {
            $commentService = new CommentService();
            try {
                $commentService->fix($body, $id);
            } catch (Exception $e) {
                throw new Exception("댓글 수정중 문제가 발생했습니다!");
            }
        }

        public function getComments($boardId) {
            $commentService = new CommentService();
            try {
                $row = $commentService->getComments($boardId);
                return $row;
            } catch (Exception $e) {
                echo "<script>alert('댓글을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function delete($commentId) {
            $commentService = new CommentService();
            try {
                $commentService->delete($commentId);
            } catch (Exception $e) {
                throw new Exception("댓글 삭제중 문제가 발생했습니다!");
            }
        }
    }
?>