<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>get-id</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="./sign-in.css" rel="stylesheet">
  <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <form id="register-form" action="getid.php" method="POST">
    <div class="emailBox"><label for="email">e-mail</label></div>
    <div class="emailWrapper">
    <div class="inputBox"><input type="email" id="email" name="email" class="email form-control" required></div>
    <button type="button" id="email-send-btn" class="btn btn-primary">メール送信</button>
    </div>
    <p id="message" style="color:red;" class="alert"></p>

    <div class="row mb-3">
      <div class="usernameBox">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Username(三文字以上)</label>
      </div>
      <div class="col-sm-10 inputBox">
        <input type="text" class="form-control" id="inputEmail3" maxlength="10" minlength="3" name="username">
      </div>
    </div>

    <div class="row mb-3">
      <div class="passwordBox">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Password(4桁以上)</label>
      </div>
      <div class="col-sm-10">
        <input type="password" class="form-control passwordInput" id="inputPassword3" maxlength="15" minlength="4"
          name="password">
      </div>
    </div>

    

    <button type="submit" class="btn btn-primary registerBtn" id="registerBtn" disabled>登録</button>
    

  </form>

  <?php
  session_start();

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
      $sql = "INSERT INTO users (user_email,username,password) VALUES(:user_email,:username,:password);";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':user_email', $_POST["email"], PDO::PARAM_STR);
      $stmt->bindValue(':username', $_POST["username"], PDO::PARAM_STR);
      $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
      $stmt->bindValue(':password', $pass, PDO::PARAM_STR);
      $result = $stmt->execute();
      if ($result == true) {
        $_SESSION["email"]= $_POST["email"];
        $sql2 = "INSERT INTO infos (user_email) VALUES (:user_email);";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindValue(':user_email', $_POST["email"], PDO::PARAM_STR);
        $stmt2->execute();
        header("Location: sclool.html");
        exit;
      }
    } catch (Exception $e) {
      print ($e->getMessage() . "
");
    }
  }
  ?>

  <script>
    document.getElementById("email-send-btn").addEventListener("click", async function () {
  const email = document.getElementById("email").value;
  const messageBox = document.getElementById("message");
  const registerBtn = document.getElementById("registerBtn");

  messageBox.textContent = "";
  registerBtn.disabled = true; 

  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    messageBox.style.color = "red";
    messageBox.textContent = "有効なメールアドレスを入力してください。";
    return;
  }

  try {
    const response = await fetch("http://localhost:3000/send", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ email })
    });

    const text = await response.text();

    if (response.ok) {
      messageBox.style.color = "green";
      messageBox.textContent = "メール送信に成功しました。";
      registerBtn.disabled = false;
    } else {
      messageBox.style.color = "red";
      messageBox.textContent = "メール送信に失敗しました。";
    }
  } catch (error) {
    messageBox.style.color = "red";
    messageBox.textContent = "通信エラーが発生しました。";
    console.error(error);
  }
});

  </script>
</body>

</html>