<?php
session_start();
$room_id = $_SESSION["room_id"];
$player = $_SESSION["player"];

// ゲーム開始後にメンバーが加わった場合を想定し、ここから先でplayer_numberはセッションで受けわたす
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();

// ifcoyoteを0に戻す
// game.php→coyote.php→game_end.phpが瞬時に行われるとバグが発生するため、2秒待つ
$sql = "UPDATE rooms SET ifcoyote = 0 WHERE room_id = " . $room_id . ";";
$mysqli->query($sql);

// ゲームが開始しているかどうかのチェック
$ifstart = $array["ifstart"];
if ($ifstart == 1) {
    header("Location: game_start2.php");
    exit();
}
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>

<!-- 2秒ごとに読み込む。ifstartが1ならgame_start2.phpを経由してgame.phpへ。 -->
<Script LANGUAGE="JavaScript">
    setTimeout("location.reload()", 1000 * 2);
</Script>

<?php
// プレイヤー名
$player_name = $array[$player];
$player_number = $array["player_number"];
$message = "<div class='comment'>ようこそ、" . $player_name . "さん</div>";
echo $message;
// 部屋名
$room_name = $array["room_name"];
echo "<div class='comment'>部屋名:";
echo $room_name;
echo "</div>";
// 他のメンバー
echo "<div class='comment'>メンバー</div><div>";
for ($i = 1; $i < $player_number + 1; $i++) {
    $player = "player" . $i;
    echo $array[$player];
    if ($i < $player_number) {
        echo ", ";
    }
}
echo "</div>";
?>

<a href="game_start2.php">Game Start</a>

</body>