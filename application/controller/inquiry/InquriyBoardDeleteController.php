<?php
    $boardId = filter_var(strip_tags($_POST['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$boardId) {
        header("location:/application/view/inquiry/board.php");
        exit();
    }

    $writerName = filter_var(strip_tags($_POST['writerName']), FILTER_SANITIZE_SPECIAL_CHARS);
    $writerPw = filter_var(strip_tags($_POST['writerPw']), FILTER_SANITIZE_SPECIAL_CHARS);

    $inquiryRepository = new InquiryRepository();
    $findPw = $inquiryRepository->findPwByWriterName($writerName);

    if ($findPw != $writerPw) {
        echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board.php');</script>";
        exit();
    }
    
    try {
        $inquiryRepository->deleteById($boardId);
    } catch (Exception $e) {
        echo "<script>alert('게시글이 삭제되었습니다!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board.php');</script>";
    } finally {
        exit();
    }
?>