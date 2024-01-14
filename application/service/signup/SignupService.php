<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';

    class SignupService {
        private $memberRepository;

        public function __construct() {
            $this->memberRepository = new MemberRepository();
        }

        public function signup(MemberSaveDto $memberSaveDto) {
            try {
                $duplicatedMember = $memberRepository->findById($memberSaveDto->getId());
                if ($duplicatedMember) {
                    return "ID가 중복됩니다!";
                }
    
                $memberRepository->save($memberSaveDto);
                return true;
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>