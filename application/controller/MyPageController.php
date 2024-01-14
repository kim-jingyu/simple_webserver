<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/member/MemberService.php';

    function close($message) {
        echo "<script>alert('{$message}')</script>";
        echo "<script>location.replace('{$mypageUrl}');</script>";
        exit();
    }

    $memberService = new MemberService();
    $mypageUrl = $_SERVER['DOCUMENT_ROOT'].'/view/mypage/mypage.php';

    if ($_REQUEST['changeId']) {
        $newId = filter_var(strip_tags($_POST['newId']), FILTER_SANITIZE_SPECIAL_CHARS);
        $originalId = getToken($_COOKIE['JWT'])['user'];
        if ($newId == $originalId) {
            echo "<script>alert('ID가 같습니다!');</script>";
            echo "<script>location.replace('{$mypageUrl}');</script>";
            exit;
        }
        
        $message = $memberService->changeId($newId, $originalId);
        close($message);
    } else if ($_REQUEST['changePw']) {
        $originalId = getToken($_COOKIE['JWT'])['user'];
        $oldPw = filter_var(strip_tags(md5($_POST['oldPw'])), FILTER_SANITIZE_SPECIAL_CHARS);
        $newPw = filter_var(strip_tags(md5($_POST['newPw'])), FILTER_SANITIZE_SPECIAL_CHARS);

        $message = $memberService->changePw($originalId, $oldPw, $newPw);
        close($message);
    }
?>