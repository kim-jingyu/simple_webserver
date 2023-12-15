<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
</head>
<body>
    <h1>마이페이지</h1>
    <br>
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';
        
        session_start();

        if (!isset($_SESSION['loginId'])) {
            header("location:/login/login.html");
            exit();
        }

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if (mysqli_connect_errno()) {
            die('데이터베이스 오류 발생'.mysqli_connect_error());
        }

        $login_id = $_SESSION['loginId'];
    ?>
    <h2>
        당신은 <?php echo $login_id; ?>입니다!
    </h2>
    <form action="change_id.php" method="post">
        <fieldset>
            <legend>아이디 수정</legend>
            <label for="OldId">기존 ID:</label>
            <input type="text" name="OldId" id="OldId" value="<?php echo $login_id ?>" readonly>
            <br><br>
            <label for="NewId">바꿀 ID:</label>
            <input type="text" name="NewId" id="NewId" placeholder="바꿀 ID" required>
            <br><br>
            <input type="submit" value="아이디 수정">
        </fieldset>
    </form>
    <form action="change_pw.php" method="post">
        <fieldset>
            <legend>비밀번호 수정</legend>
            <label for="OldPw">기존 PW:</label>
            <input type="text" name="OldPw" id="OldPw" placeholder="기존 PW" required>
            <br><br>
            <label for="NewPw">바꿀 PW:</label>
            <input type="text" name="NewPw" id="NewPw" placeholder="바꿀 PW" required>
            <br><br>
            <input type="submit" value="비밀번호 수정">
        </fieldset>
    </form>
    <form action="/index.php">
        <p><input type="submit" value="뒤로"></p>
    </form>
    <form action="/logout/logout.php">
        <input type="submit" value="로그아웃">
    </form>
</body>
</html>