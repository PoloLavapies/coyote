<?php
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

session_start();
$player_name = $_SESSION['player_name'];
$room_id = $_SESSION['room_id'];
lock();
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$player_number = $array["player_number"];
$player_number = (int)$player_number;
// 抜けたのが何番目のプレイヤーか調べる
for ($i = 1; $i < $player_number + 1; $i++) {
    $player_name_test = $array["player" . (string)$i];
    if ($player_name_test === $player_name) {
        $player_id = (int)$i;
        $player = "player" . (string)$i;
        break;
    }
}
// プレイヤー数を減らす
$player_number2 = $player_number - 1;
$sql = "UPDATE rooms SET player_number = '" . $player_number2 . "' WHERE room_id = " . $room_id . ";";
$mysqli->query($sql);
// プレイヤー名を前にずらしていく
for ($i = $player_id; $i < $player_number; $i++) {
    $new_player = $array["player" . (string)($i + 1)];
    $sql = "UPDATE rooms SET player" . (string)$i . " = '" . $new_player . "' WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
}
unlock();

header("Location: index.php");
exit();
?>