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
                echo "<script>alert('ID가 수정되었습니다');</script>";
            } catch (Exception $e) {
                echo "<script>alert('ID 수정에 실패했습니다!');</script>";
            } finally {
                echo "<script>location.replace('{$mypageUrl}');</script>";
                exit();
            }
        }
    
        public function changePw($newId, $originalId, $oldPw) {
            
            $originalId = getToken($_COOKIE['JWT'])['user'];

            $originalPw = $memberRepository->findById($originalId)['user_pw'];
            if ($oldPw != $originalPw) {
                echo "<script>alert('비밀번호가 일치하지 않습니다!');</script>";
                echo "<script>location.replace('{$mypageUrl}');</script>";
            }
    
            $newPw = filter_var(strip_tags(md5($_POST['newPw'])), FILTER_SANITIZE_SPECIAL_CHARS);

            try {
                $memberRepository->updatePw($newPw, $oldPw);
                echo "<script>alert('PW가 수정되었습니다!');</script>";
            } catch (Exception $e) {
                echo "<script>alert('PW 수정에 실패했습니다!');</script>";
            } finally {
                echo "<script>location.replace('{$mypageUrl}');</script>";
                exit();
            }
        }
    }
?>