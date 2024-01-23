<?php
    if (!isset($_POST['pw']) or !isset($_POST['name'])) {
        echo "<script>alert('작성자 정보를 입력하세요!');</script>";
        echo "<script>location.replace('board_write.php');</script>";
        exit();
    }

    $board_id = $_POST['board_id'] ? filter_var(strip_tags($_POST['board_id']), FILTER_SANITIZE_SPECIAL_CHARS) : null ;
    $name = filter_var(strip_tags($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $pw = filter_var(strip_tags($_POST['pw']), FILTER_SANITIZE_SPECIAL_CHARS);
    $title = filter_var(strip_tags($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var(strip_tags($_POST['body']), FILTER_SANITIZE_SPECIAL_CHARS);
    $today = date("Y-m-d");

    $inquiryBoardUpdateRequest = new InquiryBoardUpdateRequest($board_id, $title, $body, $name, $pw, $today);
    $inquiryRepository = new InquiryRepository();
    $findPw = $inquiryRepository->findPwByWriterName($name);
    if ($findPw != $pw) {
        echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
        echo "<script>location.replace('board_fix.php');</script>";
        exit();
    }

    try {
        if ($board_id) {
            $inquiryRepository->update($inquiryBoardUpdateRequest);
            echo "<script>alert('수정이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?board_id=$board_id');</script>";
        } else {
            $inquiryRepository->save($inquiryBoardUpdateRequest);
            echo "<script>alert('작성이 완료되었습니다.');</script>";
            echo "<script>location.replace('board_view.php?board_id=$board_id');</script>";
        }
    } catch (Exception $e) {
        echo $e;
        echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
        echo "<script>location.replace('board_write.php');</script>";
    } finally {
        exit();
    }
?>