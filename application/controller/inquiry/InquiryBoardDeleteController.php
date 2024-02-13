<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryBoardRepository.php';

    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$boardId) {
        header("location:/application/view/inquiry/board_view.php?boardId=".$boardId);
        exit();
    }

    $writerName = filter_var(strip_tags($_POST['writerName']), FILTER_SANITIZE_SPECIAL_CHARS);
    $writerPw = filter_var(strip_tags($_POST['writerPw']), FILTER_SANITIZE_SPECIAL_CHARS);

    $inquiryBoardRepository = new InquiryBoardRepository();
    $findPw = $inquiryBoardRepository->findPwByWriterName($writerName);

    if ($findPw != $writerPw) {
        echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board_view.php?boardId=".$boardId."');</script>";
        exit();
    }
    
    try {
        $inquiryBoardRepository->deleteById($boardId);
        echo "<script>alert('게시글이 삭제되었습니다!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board.php');</script>";
    } catch (Exception $e) {
        echo "<script>alert('게시글 삭제에 실패했습니다!!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board_view.php?boardId=".$boardId."');</script>";
    } finally {
        exit();
    }
?>