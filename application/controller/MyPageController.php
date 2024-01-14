<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/member/MemberService.php';

    $memberService = new MemberService();

    if ($_REQUEST['changeId']) {
        $newId = filter_var(strip_tags($_POST['newId']), FILTER_SANITIZE_SPECIAL_CHARS);

        $originalId = getToken($_COOKIE['JWT'])['user'];
            
        if ($newId == $originalId) {
            echo "<script>alert('ID가 같습니다!');</script>";
            echo "<script>location.replace('{$mypageUrl}');</script>";
            exit;
        }

        $memberService->changeId($newId, $originalId);
    } else if ($_REQUEST['changePw']) {
        $oldPw = filter_var(strip_tags(md5($_POST['oldPw'])), FILTER_SANITIZE_SPECIAL_CHARS);
    }
?>