<?php
    class CommentRepository {
        public function __construct() {
        }

        public function write($commenterId, $comment, $dateValue, $boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO comment(commenter_id, comment, comment_date, board_id) VALUES (?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $commenterId, $comment, $dateValue, $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Comment At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function fix($commenterId, $boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO comment(commenter_id, comment, comment_date, board_id) VALUES (?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $commenterId, $comment, $dateValue, $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Comment At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    }
?>