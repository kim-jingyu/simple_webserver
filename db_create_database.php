<?php
    require 'db_conn.php';

    $conn = connect_db();

    $sql = "create database test1";

    if (mysqli_query($conn, $sql)) {
        echo "DB 생성 성공";
    } else {
        echo "DB 생성 실패".mysqli_error($conn);
    }

    mysqli_close($conn);
?>