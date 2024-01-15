<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';

    class SignupService {
        public function __construct() {
        }

        public function signup(MemberRepository $memberRepository, MemberSaveDto $memberSaveDto) {
            try {
                $duplicatedMember = $memberRepository->findById($memberSaveDto->getId());
                if (mysqli_num_rows($duplicatedMember)) {
                    return "ID가 중복됩니다!";
                }
    
                $memberRepository->save($memberSaveDto);
                return "회원가입 성공!";
            } catch (Exception $e) {
                echo $e->getMessage();
                return "회원가입 실패!";
            }
        }
    }
?>