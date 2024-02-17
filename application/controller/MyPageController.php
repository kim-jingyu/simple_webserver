<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/mypage/MypageService.php';

    checkToken();

    function close($message) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/mypage/mypage.php');</script>";
        exit();
    }

    $myPageService = new MypageService();

    try {
        if ($_REQUEST['changeId']) {
            $newId = filter_var(strip_tags($_POST['newId']), FILTER_SANITIZE_SPECIAL_CHARS);
            
            $message = $myPageService->changeId($newId, $originalId);
            close($message);
        } else if ($_REQUEST['changePw']) {
            $originalId = getToken($_COOKIE['JWT'])['user'];
            $oldPw = filter_var(strip_tags($_POST['oldPw']), FILTER_SANITIZE_SPECIAL_CHARS);
            $newPw = filter_var(strip_tags($_POST['newPw']), FILTER_SANITIZE_SPECIAL_CHARS);
    
            $message = $myPageService->changePw($originalId, $oldPw, $newPw);
            close($message);
        }
    } catch (IdFixFailException $e) {
        close($e->errorMessage());
    } catch (PwFixFailException $e) {
        close($e->errorMessage());
    } catch (IdDuplicatedException $e) {
        close($e->errorMessage());
    } catch (PwNotMatchedException $e) {
        close($e->errorMessage());
    }
?>