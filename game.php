<?php
session_start();
$room_id = $_SESSION["room_id"];
$player = $_SESSION["player"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$ifcoyote = $array["ifcoyote"];
// すでにコヨーテボタンが押されている場合は、遷移
if ($ifcoyote == 1) {
    header("Location: coyote.php");
    exit();
}
$player_number = $array["player_number"];
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
    $player_name = $array["player" . $i];
    $card = substr($cards, -$i, 1);
    if (!("player" . $i === $player)) {
        echo $player_name . ":" . $card;
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