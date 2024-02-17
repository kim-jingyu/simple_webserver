<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/application/service/inquiry/InquiryBoardService.php";

    function close($message, $url) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('{$url}');</script>";
        exit();
    }

    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$boardId) {
        header("location:/application/view/inquiry/board_view.php?boardId=".$boardId);
        exit();
    }

    $writerName = filter_var(strip_tags($_POST['writerName']), FILTER_SANITIZE_SPECIAL_CHARS);
    $writerPw = filter_var(strip_tags($_POST['writerPw']), FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        $inquiryBoardService = new InquiryBoardService();
        $inquiryBoardService->delete($boardId);

        close("게시글이 삭제되었습니다!", "/application/view/inquiry/board.php");
    } catch (PwNotMatchedException $e) {
        close(e->errorMessage(), "/application/view/inquiry/board.php");
    } catch (Exception $e) {
        close("게시글 삭제에 실패했습니다!", "/application/view/inquiry/board_view.php?boardId=".$boardId);
    }
?>