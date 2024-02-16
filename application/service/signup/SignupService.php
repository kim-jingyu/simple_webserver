<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';

    class SignupService {
        public function __construct() {
        }

        public function signup(MemberRepository $memberRepository, MemberSaveDto $memberSaveDto) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $userId = $memberRepository->findById($conn, $memberSaveDto->getId());
                if (!empty($userId)) {
                    return "ID가 중복됩니다!";
                }
    
                $memberRepository->save($conn, $memberSaveDto);
                $conn->commit();
                return "회원가입 성공!";
            } catch (PDOException $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }
    }
?>