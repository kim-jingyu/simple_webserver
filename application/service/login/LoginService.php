<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/connection/DBConnectionUtil.php';

    class LoginService {
        public function __construct() {
        }

        public function login(LoginDto $loginDto) {
            $conn = DBConnectionUtil::getConnection();
            $memberRepository = new MemberRepository();

            try {
                $conn->beginTransaction();
                $member = $memberRepository->findById($conn, $loginDto->getUserId());
                if (empty($member)) {
                    throw new Exception("로그인 실패!");
                }
            
                $userPw = $loginDto->getUserPw();
                if (password_verify($userPw, $member['user_pw'])) {
                    $jwt = createToken($member['user_id'], $member['user_name']);
                    setcookie('JWT', $jwt, time() + 30 * 60, '/');
                }
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
?>