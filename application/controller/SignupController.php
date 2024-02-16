<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require $_SERVER['DOCUMENT_ROOT'].'/application/service/signup/SignupService.php';
    require $_SERVER['DOCUMENT_ROOT'].'/application/repository/member/MemberSaveDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/exception/LoginIdDuplicatedException.php';

    function close($message, $url) {
        echo "<script>alert('{$message}')</script>";
        echo "<script>location.replace('{$url}');</script>";
        exit();
    }

    $userId = isset($_POST['userId']) ? filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $userPw = isset($_POST['password']) ? filter_var(strip_tags($_POST['password']), FILTER_SANITIZE_SPECIAL_CHARS) : "";
    $userName = isset($_POST['userName']) ? filter_var(strip_tags($_POST['userName']), FILTER_SANITIZE_SPECIAL_CHARS) : "";

    if ($userId == null or $userPw == null or $userName == null) {
        echo "<script>alert('ID, Password, Username 칸을 입력해주세요.')</script>";
        echo "<script>window.location.href='/application/view/signup/signup.html';</script>";
        exit();
    }

    $hashedPw = password_hash($userPw, PASSWORD_DEFAULT);
    $userInfo = filter_var(strip_tags($_POST['userInfo']), FILTER_SANITIZE_SPECIAL_CHARS);
    
    $userAddress = trim($_POST['address']);
    $encryptionKey = 'secret_key';
    $encryptedAddress = openssl_encrypt($userAddress, 'aes-256-cbc', $encryptionKey, OPENSSL_ZERO_PADDING, '1234567890123456');

    $memberSaveDto = new MemberSaveDto($userId, $hashedPw, $userName, "student", $userInfo, $encryptedAddress);
    
    try {
        $signupService = new SignupService();
        $succeedMessage = $signupService->signup($memberSaveDto);
        close("회원가입 성공!", "/index.php");
    } catch (LoginIdDuplicatedException $e) {
        close("ID가 중복됩니다!", "/application/view/signup/signup.html");
    } catch (Exception $e) {
        close("회원가입 실패!", "/application/view/signup/signup.html");
    }
?>