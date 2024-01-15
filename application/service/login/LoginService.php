<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';

    class LoginService {
        public function __construct() {
        }

        public function login(MemberRepository $memberRepository, LoginDto $loginDto) {
            $result = $memberRepository->findById($loginDto->getUserId());

            if ($result->num_rows > 0) {
                $member = $result->fetch_array();
                $userPw = $loginDto->getUserPw();
                if (password_verify($userPw, $member['user_pw'])) {
                    $jwt = createToken($member['user_id'], $member['user_name']);
                    setcookie('JWT', $jwt, time() + 30 * 60, '/');
                }
                return true;
            } else {
                return false;
            }
        }
    }
?>