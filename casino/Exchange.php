<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Flip Card Test</title>
  <link rel='stylesheet' type='text/css' media='screen' href='Exchange.css'>
  <script src='Exchange.js'></script>
  <link href="https://fonts.cdnfonts.com/css/casino" rel="stylesheet">
  <style>
    @import url('https://fonts.cdnfonts.com/css/casino');
  </style>
</head>

<body>
  <div class="gazou"><img src="online.webp"></img></div>

  <div class="tytol">Exchange Coin</div><br>
  <div class="explain">RATE</div>

  <?php

  session_start();
  $email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
  if (!empty($_POST["chips"])) {
    $_SESSION["bet_chips"] = (int) $_POST["chips"];
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
      $sql = "SELECT chips FROM infos WHERE user_email=:email";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email', $_SESSION["email"], PDO::PARAM_STR);

      $stmt->execute();

      $result = $stmt->fetch();

      if ((int) $result["chips"] > $_POST["chips"]) {
        (int) $result["chips"] = (int) $result["chips"] - (int) $_POST["chips"];
        $sql = "UPDATE infos SET chips = :chips WHERE user_email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':chips', $result["chips"], PDO::PARAM_INT);
        $stmt->bindValue(':email', $_SESSION["email"], PDO::PARAM_STR);
        $stmt->execute();

      }
    } catch (Exception $e) {
      print ($e->getMessage() . "<br>");
    }
  }

  ?>

  <div class="Current">Current Chips:<?php echo $result["chips"] ?></div>

</body>

</html>