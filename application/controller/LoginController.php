<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    function getParams() {
        $userId = filter_var(strip_tags($_POST['userId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userPw = filter_var(strip_tags($_POST['password']), FILTER_SANITIZE_SPECIAL_CHARS);
    }
?>