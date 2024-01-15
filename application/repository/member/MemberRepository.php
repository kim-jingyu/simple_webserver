<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/connection/DBConnectionUtil.php';

    class MemberRepository {
        public function __construct() {
        }

        public function save($memberSaveDto) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO member(user_id, user_pw, user_name, user_level, user_info, user_address) VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $memberSaveDto->getId(), $memberSaveDto->getPw(), $memberSaveDto->getName(), $memberSaveDto->getLevel(), $memberSaveDto->getInfo(), $memberSaveDto->getAddress());
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Save - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    
        public function findById($userId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM member WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("FindById - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function findByIdAndPw($userId, $userPw) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM member WHERE user_id = ? AND user_pw = ?";
                $stmt = prepared_query($conn, $sql, [$userId, $userPw]);
                $user = $stmt->get_result()->fetch_assoc();
                return $user;
            } catch (Exception $e) {
                throw new Exception("FindByIdAndPw - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    
        public function updateId($newId, $oldId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE member SET user_id = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update Id - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function updatePw($newPw, $oldPw) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE member SET user_pw = ? WHERE user_pw = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newPw, $oldPw);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update Pw - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    
        public function delete($id) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "DELETE FROM member WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Delete - DB Exception 발생!");
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