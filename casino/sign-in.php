<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>sign-in</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="./sign-in.css" rel="stylesheet">
    <script src=" js/bootstrap.bundle.min.js">
    </script>
</head>

<body>

    <form action="sign-in.php" method="POST">

        <div class="username">
            <div class="row mb-3">
                <label for="inputEmail" class="col-sm-2 col-form-label usernameBox">e-mail</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail3" name="email">
                </div>
            </div>
        </div>

        <div class="username">
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label usernameBox">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail" name="username">
                </div>
            </div>
        </div>

        <div class="password">
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label passwordBox">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" name="password">
                </div>
            </div>
        </div>

        </fieldset>
        <br><button type="submit" class="btn btn-primary registerBtn">ログイン</button>
        <br><br><a href="getid.php" class="register">新規登録はこちら</a><br>
    </form>

    <?php
    require_once("Email.php");

    if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=casino",
                "root",
                "",
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            $sql = "SELECT * FROM users WHERE user_email=:user_email";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_email', $_POST["email"], PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetch();

            $correctEmail = $result["user_email"];
            $correctUsername = $result["username"];
            $correctPasswordHash = $result["password"];

            $correct = password_verify($_POST["password"],$correctPasswordHash);

            if ($correctEmail == $_POST["email"] && $correctUsername == $_POST["username"] && $correct)
            {
                echo "a";
                $_SESSION["email"]= $_POST["email"];
                header("Location: qrcode.php");
                exit;
            } else {
                echo "i";
            }
            


        } catch (Exception $e) {
            print ($e->getMessage() . "<br>");
        }
    }
    ?>

</body>

</html>


</body>

</html>