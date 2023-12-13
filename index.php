<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
</head>

<body>
    <?php
        session_start();
        if (!isset($_SESSION['loginId'])) {
            header("location:/login/login.html");
            exit;
        } else {
            $id = $_SESSION['loginId'];
        }
    ?>
    <h2><?php echo "어서오세요. $id"; ?>님!</h2>
    <button type="button" class="btn btn-secondary" onclick="location.href='/logout/logout.php'">
        로그아웃
    </button>
    <form action="/board/board.php">
        <p><input type="submit" value="메뉴"></p>
    </form>
    <form action="/mypage/mypage.php">
        <p><input type="submit" value="마이페이지"></p>
    </form>
</body>

</html>