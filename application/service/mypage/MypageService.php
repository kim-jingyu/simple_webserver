<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    
    class MypageService {
        private $memberRepository;
        private $boardRepository;

        public function __construct() {
            $this->memberRepository = new MemberRepository();
            $this->boardRepository = new BoardRepository();
        }

        public function changeId($newId, $originalId) {
            try {
                $memberRepository->updateId($newId, $originalId);
                $boardRepository->updateId($newId, $originalId);
                
                $member = $memberRepository->findById($newId);
                $jwt = createToken($member['user_id'], $member['user_name']);
                setcookie('JWT', $jwt, time() + 30 * 60, "/");
                return "ID가 수정되었습니다!";
            } catch (Exception $e) {
                echo $e->getMessage();
                return "ID 수정에 실패했습니다!";
            }
        }
    
        public function changePw($originalId, $oldPw, $newPw) {
            $originalPw = $memberRepository->findById($originalId)['user_pw'];
            if ($oldPw != $originalPw) {
                return "비밀번호가 일치하지 않습니다!";
            }
    
            try {
                $memberRepository->updatePw($newPw, $oldPw);
                return "PW가 수정되었습니다!";
            } catch (Exception $e) {
                echo $e->getMessage();
                return "PW 수정에 실패했습니다!";
            }
        }
    }
?>