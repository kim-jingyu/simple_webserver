<?php
    class CommentRepository {
        public function __construct() {
        }

        public function write($conn, $commenterId, $body, $dateValue, $boardId) {
            $stmt = null;
            try {
                $sql = "INSERT INTO comment(commenter_id, body, comment_date, board_id) VALUES (:commenterId,:body,:dateValue,:boardId)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":commenterId", $commenterId);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":dateValue", $dateValue);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();

                $commentId = $conn->lastInsertId();
                return $commentId;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function fix($conn, $body, $id) {
            $stmt = null;
            try {
                $sql = "UPDATE comment SET body = :body WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function findAllByBoard($conn, $boardId) {
            $stmt = null;
            try {
                $sql = "SELECT * FROM comment WHERE board_id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function findCommenterIdById($conn, $id) {
            $stmt = null;
            try {
                $sql = "SELECT commenter_id FROM comment WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $commenterId = $stmt->fetchColumn();
                return $commenterId;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function delete($conn, $commentId) {
            $stmt = null;
            try {
                $sql = "DELETE FROM comment WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $commentId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function deleteByBoardId($conn, $boardId) {
            $stmt = null;
            try {
                $sql = "DELETE FROM comment WHERE board_id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function updateCommenterId($conn, $newId, $oldId) {
            $stmt = null;
            try {
                $sql = "UPDATE comment SET commenter_id = :newId WHERE commenter_id = :oldId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":newId", $newId);
                $stmt->bindValue(":oldId", $oldId);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }
    }
?>