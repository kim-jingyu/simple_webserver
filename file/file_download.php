<?php
    $file_name = filter_var(strip_tags($_GET['file']), FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($file_name)) {
        $file_path = '/path/upload/'.$file_name;

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "<script>alert('파일이 존재하지 않습니다!');</script>";
        }
    } else {
        echo "<script>alert('파일 다운로드 실패!');</script>";
    }
?>