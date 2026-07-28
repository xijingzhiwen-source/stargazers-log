<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Roulette</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' type='text/css' media='screen' href='Rou.css'>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
  <script src='Rou.js'></script>
  <link href="https://fonts.cdnfonts.com/css/casino" rel="stylesheet">
  <style>
    @import url('https://fonts.cdnfonts.com/css/casino');
  </style>
</head>

<body>
  <div class="down"></div>
  <!-- originally from https://codepen.io/daniandl/pen/mMQmGV -->

  <div class="roulette">
    <div class="wheel spin">
      <div class="arrow">
      </div>
      <img src="https://i.imgur.com/N01W3Ks.png">
    </div>
  </div>

  <div class="lead">SPIN!</div>



  <div class="Value">
    <form action="Roulette.php" method="POST">
      <label for="Betchip" class="question">How many Bet?</label><br>
      <input type="number" class="form-control passwordInput" id="inputPassword3" name="chips"><br>
      <label>What's number?</label><br>
      <input type="numver" class="Beter" name="number"><br>
      <input type="hidden" name="goon" id="goonVal">
      <button type="submit" class="Betbtn">Bet</button>

    </form>
  </div>

  <div id="result" class="result">
  </div>

  <?php
  $bet = isset($_POST["chips"]) ? (int) $_POST["chips"] : 0;
  $chosenNumber = isset($_POST["number"]) ? (string) $_POST["number"] : "";
  $goon = isset($_POST["goon"]) ? (string) $_POST["goon"] : "";


  if (isset($_SESSION["email"])) {
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

      $sql = "SELECT * FROM infos WHERE user_email=:user_email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':user_email', $_SESSION["email"], PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch();

      // $bet = (int) $_POST["chips"];
      // $chosenNumber = (string) $_POST["number"];
      // $goon = (string) $_POST["goon"]; // ← hidden inputから取得
  
      if ((int) $result["chips"] >= $bet) {
        $newChips = (int) $result["chips"] - $bet;

        // 勝った場合
        if ($goon === $chosenNumber) {
          $newChips += $bet * 15;
        }

        // SQL更新
        $sql = "UPDATE infos SET chips = :chips WHERE user_email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':chips', $newChips, PDO::PARAM_INT);
        $stmt->bindValue(':email', $_SESSION["email"], PDO::PARAM_STR);
        $stmt->execute();
      }
      if ($result) {
        echo "<div class='chip-count'>Current Chips: " . htmlspecialchars($result['chips']) . "</div>";
      }

    } catch (Exception $e) {
      print ($e->getMessage() . "<br>");
    }
  }

  ?>
</body>

</html>