<?php
    require $_SERVER['DOCUMENT_ROOT'].'/application/config/db/db_info.php';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류 발생'.mysqli_connect_error());
    }

    function save() {
        $stmt = $conn -> prepare("INSERT INTO member(user_id, user_pw, user_name, user_level, user_info, user_address) VALUES (?,?,?,?,?,?)");
        $stmt -> bind_param("ssssss", );
    }

    function findById() {

    }

    function update() {

    }

    function delete() {

    }
?>