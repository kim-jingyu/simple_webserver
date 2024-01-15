<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/appliaction/config/jwt/JwtManager.php';

    class LoginService {
        private $memberRepository;

        public function __construct() {
            $memberRepository = new MemberRepository();
        }

        public function login(LoginDto $loginDto) {
            $member = $memberRepository->findByIdAndPw($loginDto->getUserId(), $loginDto->getUserPw());

            if ($member) {
                $jwt = createToken($member['user_id'], $member['user_pw']);
                setcookie('JWT', $jwt, time() + 30 * 60, '/');
                return true;
            } else {
                return false;
            }
        }
    }
?>