<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/inquiry/InquiryBoardService.php';

    if (!isset($_POST['pw']) or !isset($_POST['name'])) {
        echo "<script>alert('작성자 정보를 입력하세요!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board_write.php');</script>";
        exit();
    }

    $writerName = filter_var(strip_tags($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $writerPw = filter_var(strip_tags($_POST['pw']), FILTER_SANITIZE_SPECIAL_CHARS);
    $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);

    $inquiryBoardService = new InquiryBoardService();
    $inquiryBoardService->write($writerName, $writerPw, $title, $body);
?>