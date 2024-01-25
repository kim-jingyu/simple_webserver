<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    
    class MypageService {
        public function __construct() {
        }

        public function changeId($newId, $originalId) {
            $memberRepository = new MemberRepository();
            $boardRepository = new BoardRepository();

            try {
                $memberRepository->updateId($newId, $originalId);
                $boardRepository->updateId($newId, $originalId);
                
                $result = $memberRepository->findById($newId);
                $row = $result->fetch_assoc();
                $userId = $row['user_id'];
                $userName = $row['user_name'];
                $jwt = createToken($userId, $userName);
                setcookie('JWT', $jwt, time() + 30 * 60, "/");
                return "ID가 수정되었습니다!";
            } catch (Exception $e) {
                return "ID 수정에 실패했습니다!";
            }
        }
    
        public function changePw($originalId, $oldPw, $newPw) {
            $memberRepository = new MemberRepository();

            $result = $memberRepository->findById($originalId);
            $row = $result->fetch_assoc();
            $originalPw = $row['user_pw'];
            if (password_verify($oldPw, $originalPw)) {
                return "비밀번호가 일치하지 않습니다!";
            }
    
            try {
                $memberRepository->updatePw($newPw, $oldPw);
                return "PW가 수정되었습니다!";
            } catch (Exception $e) {
                return "PW 수정에 실패했습니다!";
            }
        }
    }
?>