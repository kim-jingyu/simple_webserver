<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/connection/DBConnectionUill.php';

    class MemberRepository {
        public function save($memberSaveDto) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO member(user_id, user_pw, user_name, user_level, user_info, user_address) VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare();
                $stmt->bind_param("ssssss", $memberSaveDto->getId, $memberSaveDto->getPw, $memberSaveDto->getName, $memberSaveDto->getLevel, $memberSaveDto->getInfo, $memberSaveDto->getAddress);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Save - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }
    
        public function findById($memberId) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM member WHERE user_id = ?";
                $stmt = prepared_query($conn, $sql, [$memberId]);
                $user = $stmt->get_result()->fetch_assoc();
                return $user;
            } catch (Exception $e) {
                throw new Exception("FindById - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }
    
        public function updateId($newId, $oldId) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE member SET user_id = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update Id - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }
    
        public function delete($id) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "DELETE FROM member WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Delete - DB Exception 발생!");
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