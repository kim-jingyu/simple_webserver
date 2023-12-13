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
            <label for="oldId">기존 ID:</label>
            <input type="text" name="oldId" id="oldId" value="<?php echo $login_id ?>" readonly>
            <br><br>
            <label for="newId">바꿀 ID:</label>
            <input type="text" name="newId" id="newId" placeholder="바꿀 ID" required>
            <br><br>
            <input type="submit" value="아이디 수정">
        </fieldset>
    </form>
    <form action="change_pw.php" method="post">
        <fieldset>
            <legend>비밀번호 수정</legend>
            <label for="oldPw">기존 PW:</label>
            <input type="text" name="oldPw" placeholder="기존 PW" required>
            <br><br>
            <label for="newPw">바꿀 PW:</label>
            <input type="text" name="newPw" placeholder="바꿀 PW" required>
            <br><br>
            <input type="submit" value="비밀번호 수정">
        </fieldset>
    </form>
    <form action="/logout/logout.php">
        <input type="submit" value="로그아웃">
    </form>
</body>
</html>