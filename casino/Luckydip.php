<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='Luckydip.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.cdnfonts.com/css/casino" rel="stylesheet">
    <style>
        @import url('https://fonts.cdnfonts.com/css/casino');
    </style>
    <!--<script src='Luckydip.js'></script>-->
</head>

<style>
    body {
        overflow-x: hidden;
        background-color: gray;
    }
</style>

<body>

    <div class="Value">
        <form action="Luckydip.php" method="POST">
            <label for="Betchip" class="question">How many Bet?</label><br>
            <input type="number" class="form-control passwordInput" id="inputPassword3" name="chips"
                placeholder="Example:1000">
            <div class="Btnall"><button type="submit" class="Betbtn">Bet</button>
        </form>

        <!--<button type="submit" disabled>UP</button>
        <button type="submit" disabled>FOLD</button>-->
    </div>
    </div>

    <!--From Uiverse.io by adamgiebl -->
    <button id="resetBtn" class="reset">RESET</button>

    <?php

    session_start();
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
    // var_dump($email);
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

            if ((int) $result["chips"] > 0) {
                (int) $result["chips"] = (int) $result["chips"] - (int) $_POST["chips"];
                $sql = "UPDATE infos SET chips = :chips WHERE user_email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':chips', $result["chips"], PDO::PARAM_INT);
                $stmt->bindValue(':email', $_SESSION["email"], PDO::PARAM_STR);
                $stmt->execute();

            } else {
                (int) $result["chips"] += 10000;
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

    $ary = array("○", "○", "×", "×", "×", "×");
    shuffle($ary);

    echo '<div class="cards">';

    for ($i = 0; $i < 6; $i++) {
        echo '
    <div class="flip-card" data-result="' . ($ary[$i] === '○' ? 'true' : 'false') . '">
        <div class="flip-card-inner">
            <div class="flip-card-front">
                <p class="title">GET YOUR DREAM</p>
            </div>
            <div class="flip-card-back">
                <p class="title">' . $ary[$i] . '</p>
            </div>
        </div>
    </div>';
    }

    echo '</div>';

    ?>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.flip-card');
            const resetBtn = document.getElementById('resetBtn');
            const chipDisplay = document.getElementById("chipAmount");

            let gameEnded = false;

            cards.forEach(card => {
                card.addEventListener('click', () => {
                    if (gameEnded || card.classList.contains('flipped')) return;

                    card.classList.add('flipped');
                    const inner = card.querySelector('.flip-card-inner');

                    inner.addEventListener('transitionend', () => {
                        if (gameEnded) return;

                        const result = card.getAttribute('data-result');
                        const outcome = result === "true" ? "win" : "lose";

                        // 勝敗のアラート表示
                        if (outcome === "win") {
                            alert("成功！チップが増えました！");
                        } else {
                            alert("失敗！ゲーム終了です。");
                        }

                        // サーバーに勝敗だけ送る（chipsはサーバーで把握）
                        fetch('win.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `result=${outcome}`
                        })
                            .then(res => res.text())
                            .then(data => {
                                chipDisplay.textContent = `Current Chip: ${data}`;

                                const resultMessage = document.getElementById("resultMessage");
                                resultMessage.style.display = "block";
                                resultMessage.textContent = (outcome === "win")
                                    ? "YOU WIN!"
                                    : "YOU LOST!";
                            })

                            .catch(err => {
                                console.error("サーバー通信エラー:", err);
                            });

                        gameEnded = true;
                    }, { once: true });

                });
            });

            resetBtn.addEventListener('click', () => {
                gameEnded = false;
                cards.forEach(card => card.classList.remove('flipped'));
                console.log('ゲームリセット');
            });
        });

    </script>

    <div id="resultMessage"  class="resulttext"></div>

    <div class="chip-display">
        <p id="chipAmount">Current Chip: <?php echo $result["chips"] ?? 0; ?></p>
    </div>

</body>

</html>