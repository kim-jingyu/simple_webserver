<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/login/LoginDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/login/LoginService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberRepository.php';

    function close($message, $url) {
        echo "<script>alert('{$message}')</script>";
        echo "<script>location.replace('{$url}');</script>";
        exit();
    }

    $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
    $userPw = filter_var(strip_tags($_POST['password']), FILTER_SANITIZE_SPECIAL_CHARS);

    $loginDto = new LoginDto($userId, $userPw);
    $loginService = new LoginService($loginDto);
    $memberRepository = new MemberRepository();
    $result = $loginService->login($memberRepository, $loginDto);
    if ($result) {
        close("로그인 성공!", "/index.php");
    } else {
        close("로그인 실패!", "/application/view/login/login.html");
    }
?>