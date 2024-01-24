<?php
    $fileName = filter_var(strip_tags($_GET['file']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($fileName)) {
        $filePath = '/path/upload/'.$fileName;

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "<script>alert('파일이 존재하지 않습니다!');</script>";
        }
    } else {
        echo "<script>alert('파일 다운로드 실패!');</script>";
    }
?>