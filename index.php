<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Home</title>
</head>

<body>
    <?php
        if (!isset($_COOKIE['JWT'])) {
            header("location:/login/login.html");
            exit;
        }
        require_once $_SERVER['DOCUMENT_ROOT'].'/jwt/jwt.php';
        $id = getToken($_COOKIE['JWT'])['user'];
    ?>
    <div class="container">
        <h2><?php echo "어서오세요. $id"; ?>님!</h2>
        <button type="button" class="logout_btn" onclick="location.href='/logout/logout.php'">
            로그아웃
        </button>
        <form action="/board/board.php">
            <p><input type="submit" value="메뉴"></p>
        </form>
        <form action="/mypage/mypage.php">
            <p><input type="submit" value="마이페이지"></p>
        </form>
    </div>
</body>

</html>