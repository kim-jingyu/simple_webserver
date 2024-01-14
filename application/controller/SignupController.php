<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/signup/SignupService.php';

    function getParams() {
        $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userPw = filter_var(strip_tags($_POST['passord']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userName = filter_var(strip_tags($_POST['userName']), FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$userId or !$userPw or !$userName) {
            echo "<script>alert('ID, Password, Username 칸을 입력해주세요.')</script>";
            echo "<script>window.location.href='/application/view/signup/signup.html';</script>";
            exit();
        }

        $hashedPw = hash('sha256', $userPw);
        $userInfo = filter_var(strip_tags($_POST['userInfo']), FILTER_SANITIZE_SPECIAL_CHARS);
        
        $userAddress = trim($_POST['address']);
        $encryption_key = 'secret_key';
        $encrypted_address = openssl_encrypt($userAddress, 'aes-256-cbc', $encryption_key, OPENSSL_ZERO_PADDING, '1234567890123456');

        $memberSaveDto = new MemberSaveDto($userId, $hashedPw, $userName, $userInfo, $encrypted_address);
        return $memberSaveDto;
    }

    function close($message, $url) {
        echo "<script>alert('{$message}')</script>";
        echo "<script>location.replace('{$url}');</script>";
        exit();
    }
    
    $signupService = new SignupService();
    $isSucceed = $signupService->signup(getParams());
    if ($isSucceed) {
        close("회원가입 성공!", "/index.php");
    } else {
        close("회원가입 실패!", "/application/view/signup/signup.html");
    }
?>