<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';
    checkToken();
    $userId = getToken($_COOKIE['JWT'])['user'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/mypage.css">
    <title>마이페이지</title>
</head>
<body>
    <h1>마이페이지</h1>
    <div class="container">
        <h2>
            당신은 <?php echo $userId; ?>입니다!
        </h2>
        <form action="/application/controller/MyPageController.php" method="post">
            <fieldset class="fieldset-box">
                <legend>아이디 수정</legend>
                <label for="oldId">기존 ID:</label>
                <input class="input-box" type="text" name="oldId" id="oldId" value="<?php echo $userId ?>" readonly>
                <br>
                <label for="newId">바꿀 ID:</label>
                <input class="input-box" type="text" name="newId" id="newId" required>
                <br>
                <input class="btn" type="submit" name="changeId" value="아이디 수정">
            </fieldset>
        </form>
        <form action="/application/controller/MyPageController.php" method="post">
            <fieldset class="fieldset-box">
                <legend>비밀번호 수정</legend>
                <label for="oldPw">기존 PW:</label>
                <input class="input-box" type="text" name="oldPw" id="oldPw" required>
                <br><br>
                <label for="newPw">바꿀 PW:</label>
                <input class="input-box" type="text" name="newPw" id="newPw" required>
                <br><br>
                <input class="btn" type="submit" name="changePw" value="비밀번호 수정">
            </fieldset>
        </form>
        <div class="footer">
            <form action="/index.php">
                <input class="btn" type="submit" value="뒤로">
            </form>
            <form action="/application/service/logout/LogoutService.php">
                <input class="btn" type="submit" value="로그아웃">
            </form>
        </div>
    </div>
</body>
</html>