<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/comment/CommentRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/IdFixFailException.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/PwFixFailException.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/LoginIdDuplicatedException.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    
    class MypageService {
        public function __construct() {
        }

        public function changeId($newId, $originalId) {
            $conn = DBConnectionUtil::getConnection();

            $memberRepository = new MemberRepository();
            $boardRepository = new BoardRepository();
            $commentRepository = new CommentRepository();
            
            try {
                $conn->beginTransaction();

                $originalId = getToken($_COOKIE['JWT'])['user'];
                if ($newId == $originalId) {
                    throw new IdDuplicatedException("ID가 같습니다!");
                }

                $memberRepository->updateId($conn, $newId, $originalId);
                $boardRepository->updateId($conn, $newId, $originalId);
                $commentRepository->updateCommenterId($conn, $newId, $originalId);
                
                $row = $memberRepository->findById($conn, $newId);
                $userId = $row['user_id'];
                $userName = $row['user_name'];
                $jwt = createToken($userId, $userName);
                setcookie('JWT', $jwt, time() + 30 * 60, "/");

                $conn->commit();
                return "ID가 수정되었습니다!";
            } catch (IdDuplicatedException $e) {
                $conn->rollback();
                throw $e;
            } catch (PDOException $e) {
                $conn->rollback();
                throw new IdFixFailException("ID 수정에 실패했습니다!");
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }
    
        public function changePw($originalId, $oldPw, $newPw) {
            $conn = DBConnectionUtil::getConnection();
            $memberRepository = new MemberRepository();

            try {
                $conn->beginTransaction();
                
                $row = $memberRepository->findById($conn, $originalId);
                $originalPw = $row['user_pw'];
                if (!password_verify($oldPw, $originalPw)) {
                    throw new PwNotMatchedException("비밀번호가 일치하지 않습니다!");
                }

                $memberRepository->updatePw($conn, $newPw, $originalId);

                $conn->commit();
                return "PW가 수정되었습니다!";
            } catch (PwNotMatchedException $e) {
                $conn->rollback();
                throw $e;
            } catch (PDOException $e) {
                $conn->rollback();
                throw new PwFixFailException("PW 수정에 실패했습니다!");
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }
    }
?>