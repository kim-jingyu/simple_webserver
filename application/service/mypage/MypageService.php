<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    
    class MypageService {
        private $memberRepository;
        private $mypageUrl;

        public function __construct() {
            $this->memberRepository = new MemberRepository();
            $this->mypageUrl = $_SERVER['DOCUMENT_ROOT'].'/view/mypage/mypage.php';
        }

        public function changeId($newId, $originalId) {
            try {
                $memberRepository->updateId($newId, $originalId);
                return "ID가 수정되었습니다!";
            } catch (Exception $e) {
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
                return "PW 수정에 실패했습니다!";
            }
        }
    }
?>