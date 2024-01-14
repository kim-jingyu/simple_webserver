<?php
    setcookie('JWT', NULL, time()-30*60, "/");
    echo "<script>location.replace('/application/view/login/login.html');</script>";
?>