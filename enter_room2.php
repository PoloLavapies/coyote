<?php
session_start();

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

$room_id = $_POST["room_id"];
$player_name = $_POST["player_name"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
lock();
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$player_number = $array["player_number"];
$player_number = (int)$player_number;
$watch_number = $array["watch_number"];
$watch_number = (int)$watch_number;
$total_number = $player_number + $watch_number;
// 10人を超えていないか
if ($total_number > 9) {
    unlock();
    header("Location: max_players.php");
    exit();
}

// 同名のプレイヤーが存在しないか
for ($i = 1; $i < $player_number + 1; $i++) {
    $player_other = "player" . $i;
    $player_other = $array[$player_other];
    if ($player_name === $player_other) {
        unlock();
        header("Location: same_name.php?room_id=" . $room_id);
        exit();
    }
}

// プレイヤーとして参加する場合
$ifstart = $array["ifstart"];
if ($ifstart == 0) {
    $player_number += 1;
    $player = "player" . $player_number;
    $sql = "UPDATE rooms SET player_number = " . $player_number . " WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $sql = "UPDATE rooms SET " . $player . " = '" . $player_name . "' WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $_SESSION['room_id'] = $room_id;
    $_SESSION['player'] = $player;

    unlock();
    header("Location: game_start.php");
    exit();

    // 観戦する場合
} else {
    $watch_number += 1;
    $total_number += 1;
    $player = "player" . $total_number;
    $sql = "UPDATE rooms SET watch_number = " . $watch_number . " WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $sql = "UPDATE rooms SET " . $player . " = '" . $player_name . "' WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $_SESSION['room_id'] = $room_id;
    $_SESSION['player'] = $player;
    unlock();
    header("Location: game.php");
    exit();
}

