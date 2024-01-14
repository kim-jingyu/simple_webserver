<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/connection/DBConnectionUill.php';

    class BoardRepository {
        public function __construct() {
        }

        public function updateId($newId, $oldId) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET user_id = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update UserId At Board - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }

        private function close($conn, $stmt) {
            if ($stmt != null) {
                $stmt->close();
            }

            if ($conn != null) {
                $conn->close();
            }
        }
    }
?>