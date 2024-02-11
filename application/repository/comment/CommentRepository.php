<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    
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
                throw new Exception("Write At Comment - DB Exception 발생!");
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
                throw new Exception("Fix At Comment - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function findAllByBoard($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM comment WHERE board_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("findAllByBoard At Comment - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function delete($commentId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "DELETE FROM comment WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $commentId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Delete At Comment - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function updateCommenterId($newId, $oldId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE comment SET commenter_id = ? WHERE commenter_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("UpdateCommenterId At Comment - DB Exception 발생!");
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