<?php
    require $_SERVER['DOCUMENT_ROOT'].'/db/db_info.php';
    require $_SERVER['DOCUMENT_ROOT'].'/jwt/jwt.php';
    
    if (!isset($_COOKIE['JWT'])) {
        header("location:/login/login.html");
        exit();
    }

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        die('데이터베이스 오류 발생'.mysqli_connect_error());
    }

    $user_id = getToken($_COOKIE['JWT'])['user'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mypage.css">
    <title>마이페이지</title>
</head>
<body>
    <h1>마이페이지</h1>
    <div class="container">
        <h2>
            당신은 <?php echo $user_id; ?>입니다!
        </h2>
        <form action="change_id.php" method="post">
            <fieldset>
                <legend>아이디 수정</legend>
                <label for="OldId">기존 ID:</label>
                <input type="text" name="OldId" id="OldId" value="<?php echo $user_id ?>" readonly>
                <br>
                <label for="NewId">바꿀 ID:</label>
                <input type="text" name="NewId" id="NewId" required>
                <br>
                <input class="btn" type="submit" value="아이디 수정">
            </fieldset>
        </form>
        <form action="change_pw.php" method="post">
            <fieldset>
                <legend>비밀번호 수정</legend>
                <label for="OldPw">기존 PW:</label>
                <input type="text" name="OldPw" id="OldPw" required>
                <br><br>
                <label for="NewPw">바꿀 PW:</label>
                <input type="text" name="NewPw" id="NewPw" required>
                <br><br>
                <input class="btn" type="submit" value="비밀번호 수정">
            </fieldset>
        </form>
        <div class="footer">
            <form action="/index.php">
                <input class="btn" type="submit" value="뒤로">
            </form>
            <form action="/logout/logout.php">
                <input class="btn" type="submit" value="로그아웃">
            </form>
        </div>
    </div>
</body>
</html>