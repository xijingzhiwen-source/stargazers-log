<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>2段階認証</title>
</head>

<body>
    <?php
    
    session_start();
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;

    $oneCode = $oneCode ?? '';
    $is_valid = $is_valid ?? false;
    $checkResult = $checkResult ?? false;
    $secret = $secret ?? '';
    ?>

    <?php
    require_once __DIR__ . '/../vendor/autoload.php';

    // Google認証システムで表示される認証名です
    $auth_title = "2段階認証";
    $secret = "";

    $ga = new PHPGangsta_GoogleAuthenticator();

    // 本来はユーザーごとにsecretを発行してDBなどに保存し、最初のログイン処理で$secretを呼び出す。
    // ない場合は「$ga->createSecret();」で生成して保存します。 
    if (isset($_SESSION["secret"])) {
        $secret=$_SESSION["secret"];
    }
    if ($secret == "") {
        $secret = $ga->createSecret();
        $_SESSION["secret"] = $secret;
    }

    // QRコードのURLを生成する
    $qrCodeUrl = $ga->getQRCodeGoogleUrl($auth_title, $secret);

    $checkResult = false;
    $is_valid = false;
    $oneCode = "";
    // コードを入力された場合はvarifyCodeで認証する。
    if (isset($_POST["oneCode"]) != "") {
        $is_valid = true;
        $oneCode = $_POST["oneCode"];
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
    }
    ?>

    <div class="container">

        <?php if (isset($is_valid) && $is_valid): ?>
            <?php if ($checkResult): ?>
                <div class="alert alert-success" role="alert">
                    認証OK
                </div>
                <?php header("Location:sclool.html")?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        認証NG
                    </div>
                    <a href="sign-in.php">One More Try?<a></a>
                    <?php endif; ?>
                <?php else: ?>
                    <img src="<?php echo $qrCodeUrl; ?>" class="img-thumbnail">
                <?php endif; ?>

                <form action="qrcode.php" method="POST">
                    <div class="form-group">
                        <br><label for="exampleInputPassword1">パスコード</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="oneCode"
                            placeholder="6桁" maxlength="6" minlength="6">
                    </div><br>
                    <button type="submit" name="cmd" value="confirm" class="btn btn-primary">認証</button>
                </form>

                <?php htmlspecialchars($oneCode, ENT_QUOTES, 'UTF-8') ?>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>-->

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>