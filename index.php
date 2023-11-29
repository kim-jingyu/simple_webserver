<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Home</title>
</head>

<body>
    <?php
        session_start();
        if (!isset($_SESSION['UserId'])) {
            header("location:login.html");
            exit;
        } else {
            $id = $_SESSION['UserId'];
        }
    ?>
    <div class="base">
        <h2><?php echo "어서오세요. $id"; ?>님!</h2>
        <button type="button" class="btn" onclick="location.href='logout.php'">
            로그아웃
        </button>
    </div>
</body>

</html>