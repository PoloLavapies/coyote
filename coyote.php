<?php
session_start();
$room_id = $_SESSION["room_id"];
$player = $_SESSION["player"];

function lock()
{
    while (1) {
        if (@mkdir("lock1")) {
            break;
        } else {
            sleep(1);
        }
    }
}

function unlock()
{
    @rmdir("lock1");
}

$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
// 他のプレイヤーがgame_end.phpを実行した場合はgame_start.phpへ
$ifstart = $array["ifstart"];
if ($ifstart == 0) {
    header("Location: game_start.php");
    exit();
}

// ifcoyoteを1にすることで、他のプレイヤーがこのページに来られるようになる。
$sql = "UPDATE rooms SET ifcoyote = 1 WHERE room_id = " . $room_id . ";";
$mysqli->query($sql);
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
$player_number = $array["player_number"];
$cards = $array["cards"];
$ifhatena = 0;
$ifmax0 = 0;
$ifdouble = 0;
$card_list = array();
for ($i = 1; $i < $player_number + 1; $i++) {
    // プレイヤー名の取得
    $player_name = $array["player" . $i];
    $card = substr($cards, -$i, 1);
    echo $player_name . ":" . $card;
    echo "<br>";
    if ($card === "?") {
        $ifhatena = 1;
    } else if ($card === "m") {
        $ifmax0 = 1;
    } else if ($card === "d") {
        $ifdouble = 1;
    } else {
        $card_list[] = $card;
    }
}

// ?がある場合はもう一枚引く
if ($ifhatena == 1) {
    $card = substr($cards, -($player_number + 1), 1);
    $card_list[] = $card;
    // 引いたカードを表示
    echo "<div class='comment'>?:" . $card . "</div>";
}

$length = count($card_list);

$score_list = array();

// 数値のリストに変換
for ($i = 0; $i < $length; $i++) {
    $card = $card_list[$i];
    if ($card == "8") {
        $score_list[] = 20;
    } elseif ($card == "7") {
        $score_list[] = 15;
    } elseif ($card == "6") {
        $score_list[] = 10;
    } elseif ($card == "b") {
        $score_list[] = 0;
    } elseif ($card == "f") {
        $score_list[] = -5;
    } elseif ($card == "t") {
        $score_list[] = -10;
    } else {
        $score_list[] = (int)$card;
    }
}

// Max0がある場合
if ($ifmax0 == 1) {
    rsort($score_list);
    $score_list = array_slice($score_list, 1);
    $length -= 1;
}

// 合計計算
$sum = 0;
for ($i = 0; $i < $length; $i++) {
    $score = $score_list[$i];
    $sum += $score;
}

// ×2がある場合
if ($ifdouble == 1) {
    $sum *= 2;
}

echo "合計:" . $sum;

if (substr($player, -1) < $player_number + 1) {
    echo "<a href='game_end.php'>Reset</a>";
}
?>