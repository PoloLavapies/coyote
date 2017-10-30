<?php
session_start();
$room_id = $_SESSION["room_id"];
$player_name = $_SESSION["player_name"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$player_number = $array["player_number"];
// 何番目のプレイヤーか調べる
for ($i = 1; $i < $player_number + 1; $i++) {
    $player_name_test = $array["player" . (string)$i];
    if ($player_name_test === $player_name) {
        $player = "player" . (string)$i;
        break;
    }
}
// すでにコヨーテボタンが押されている場合は、遷移
$ifcoyote = $array["ifcoyote"];
if ($ifcoyote == 1) {
    header("Location: coyote.php");
    exit();
}
?>

    <!DOCTYPE html>
    <head>
        <link rel="stylesheet" type="text/css" href="common.css">
    </head>

    <!-- 2秒ごとに読み込む。ifstartが1ならgame_start2.phpを経由してgame.phpへ。 -->
    <Script LANGUAGE="JavaScript">
        setTimeout("location.reload()", 1000 * 2);
    </Script>

    <body>
    <div id="title">COYOTE</div>

<?php
$cards = $array["cards"];
for ($i = 1; $i < $player_number + 1; $i++) {
    // プレイヤー名の取得
    $player_name_other = $array["player" . $i];
    $card = substr($cards, -$i, 1);
    if (!("player" . $i === $player)) {
        if ($card == "8") {
            $score = "20";
        } elseif ($card == "7") {
            $score = "15";
        } elseif ($card == "6") {
            $score = "10";
        } elseif ($card == "b") {
            $score = "0(Black)";
        } elseif ($card == "f") {
            $score = "-5";
        } elseif ($card == "t") {
            $score = "-10";
        } elseif ($card == "m") {
            $score = "Max→0";
        } elseif ($card == "d") {
            $score = "×2";
        } else {
            $score = $card;
        }
        echo $player_name_other . ":" . $score;
        echo "<br>";
    } else {
        echo $player_name . ":???";
        echo "<br>";
    }
}

if (substr($player, -1) < $player_number + 1) {
    echo "<a href='coyote.php'>Coyote!</a>";
}
?>