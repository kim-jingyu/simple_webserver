<?php
    setcookie('JWT', NULL, time()-30*60, "/");
    echo "<script>location.replace('/login/login.html');</script>";
?>