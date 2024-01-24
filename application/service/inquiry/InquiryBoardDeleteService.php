<?php
    $boardId = filter_var(strip_tags($_POST['board_id']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$boardId) {
        header("location:/index.php");
        exit();
    }

    $name = filter_var(strip_tags($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $pw = filter_var(strip_tags($_POST['pw']), FILTER_SANITIZE_SPECIAL_CHARS);

    $inquiryRepository = new InquiryRepository();
    $findPw = $inquiryRepository->findPwByWriterName($name);

    if ($findPw != $pw) {
        echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
        echo "<script>location.replace('/application/view/inquiry/board_view.php');</script>";
        exit();
    }
    
    $inquiryRepository->deleteById($boardId);
    echo "<script>alert('게시글이 삭제되었습니다!');</script>";
    echo "<script>location.replace('/index.php');</script>";
    exit();
?>