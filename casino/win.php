<?php
session_start();
$email = $_SESSION["email"];
$chipsBet = isset($_SESSION["bet_chips"]) ? (int) $_SESSION["bet_chips"] : 0;
$result = $_POST["result"] ?? '';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=casino", "root", "", [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $stmt = $pdo->prepare("SELECT chips FROM infos WHERE user_email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();

    if (!$row) {
        echo "0";
        exit;
    }

    $chips = (int)$row['chips'];

    if ($result === "win") {
        $chips += $chipsBet*2 ;
    } elseif ($result === "lose") {
    }

    $update = $pdo->prepare("UPDATE infos SET chips = :chips WHERE user_email = :email");
    $update->bindValue(':chips', $chips, PDO::PARAM_INT);
    $update->bindValue(':email', $email, PDO::PARAM_STR);
    $update->execute();

    echo $chips;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
