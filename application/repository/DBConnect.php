<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류 발생'.mysqli_connect_error());
    }
?>