<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/IdDuplicatedException.php';

    class SignupService {
        public function __construct() {
        }

        public function signup(MemberRepository $memberRepository, MemberSaveDto $memberSaveDto) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $userId = $memberRepository->findById($conn, $memberSaveDto->getId());
                if (!empty($userId)) {
                    throw new IdDuplicatedException();
                }
    
                $memberRepository->save($conn, $memberSaveDto);
                $conn->commit();
            } catch (IdDuplicatedException $e) {
                $conn->rollback();
                throw $e;
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