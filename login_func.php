<?php
    if (isset($_POST['UserId']) && isset($_POST['Password'])) {
        session_start();

        $db_conn = new mysqli('localhost', 'jingyu', '1234', 'test');

        $id = mysqli_real_escape_string($db_conn, $_POST['UserId']);
        $pw = mysqli_real_escape_string($db_conn, $_POST['Password']);

        $encoded_pw = md5($pw);
    
        $sql = "SELECT * FROM member WHERE user_id = '$id' and user_pw = '$encoded_pw';";
    
        $result = mysqli_fetch_array(mysqli_query($db_conn, $sql));

        if ($result) {
            $_SESSION['UserId'] = $id;
            echo "<script>alert('{$_SESSION['UserId']}');</script>";
            echo "<script>location.replace('index.php');</script>";
        } else {
            echo "<script>alert('Unregistered User!')</script>";
            echo "<script>location.replace('login.html');</script>";
        }
        mysqli_close($db_conn);
    }
?>