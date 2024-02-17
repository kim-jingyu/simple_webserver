<?php
    class MemberRepository {
        public function __construct() {
        }

        public function save($conn, $memberSaveDto) {
            $stmt = null;
            try {
                $sql = "INSERT INTO member(user_id, user_pw, user_name, user_level, user_info, user_address) VALUES (:userId,:userPw,:userName,:userLevel,:userInfo,:userAddress)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":userId", $memberSaveDto->getId());
                $stmt->bindValue(":userPw", $memberSaveDto->getPw());
                $stmt->bindValue(":userName", $memberSaveDto->getName());
                $stmt->bindValue(":userLevel", $memberSaveDto->getLevel());
                $stmt->bindValue(":userInfo", $memberSaveDto->getInfo());
                $stmt->bindValue(":userAddress", $memberSaveDto->getAddress());
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }
    
        public function findById($conn, $userId) {
            $stmt = null;
            try {
                $sql = "SELECT * FROM member WHERE user_id = :userId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":userId", $userId);
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
    
        public function updateId($conn, $newId, $oldId) {
            $stmt = null;
            try {
                $sql = "UPDATE member SET user_id = :newId WHERE user_id = :oldId";
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

        public function updatePw($conn, $newPw, $originalId) {
            $stmt = null;
            try {
                $sql = "UPDATE member SET user_pw = :hasedPw WHERE user_id = :originalId";
                $stmt = $conn->prepare($sql);
                $hasedPw = password_hash($newPw, PASSWORD_DEFAULT);
                $stmt->bindValue(":hasedPw", $hasedPw);
                $stmt->bindValue(":originalId", $originalId);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }
    
        public function delete($conn, $id) {
            $stmt = null;
            try {
                $sql = "DELETE FROM member WHERE id = :id";
                $stmt = $conn->prepare($sql);
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
    }
?>