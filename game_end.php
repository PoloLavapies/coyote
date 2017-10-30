<?php
session_start();
$room_id = $_SESSION["room_id"];
$player_name = $_SESSION["player_name"];

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

lock();

$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();

$ifstart = $array["ifstart"];
// すでに他のプレイヤーがリセットした場合
if ($ifstart == 0) {
    unlock();
    header("Location: game_start.php");
} else {
    // game.php→coyote.php→game_end.phpが瞬時に行われるとバグが発生するため、2秒待つ
    sleep(2);
    // 観戦者をプレイヤーに加える
    $player_number = $array["player_number"];
    $watch_number = $array["watch_number"];
    $player_number += $watch_number;
    $sql = "UPDATE rooms SET player_number = " . $player_number . " WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $sql = "UPDATE rooms SET watch_number = 0 WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    //ifstartをともに0にする
    $sql = "UPDATE rooms SET ifstart = 0 WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    unlock();
    header("Location: game_start.php");
}