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

lock();
// すでに始まっている場合はgame.phpへ
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$player_number = $array["player_number"];
$ifstart = $array["ifstart"];
if ($ifstart == 1) {
    header("Location: game.php");
} else {
    // ゲーム開始
    $sql = "UPDATE rooms SET ifstart =  1 WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $cards_already = $array["cards_already"];
    $cards_yet = 36 - $cards_already;
    if ($cards_yet < $player_number) {
        $cards_yet = 36;
    }
    $cards = $array["cards"];
    // blackが出たかどうかを変数で保持する
    $ifblack = 0;
    // カードの並びを変える
    for ($i = 0; $i < $player_number; $i++) {
        $position = mt_rand(0, $cards_yet - $i - 1);
        $card = substr($cards, $position, 1);
        // カードの並びを変える
        $cards = substr($cards, 0, $position) . substr($cards, $position+1) . $card;
        // blackが出たかどうか
        if ($card == "b") {
            $ifblack = 1;
        }
    }
    // すでに出たカードの枚数を更新
    if ($ifblack = 0) {
        $cards_already += $player_number;
    } else {
        $cards_already = 0;
    }
    $sql = "UPDATE rooms SET cards_already =  " . $cards_already . " WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
    $sql = "UPDATE rooms SET cards =  '" . $cards . "' WHERE room_id = " . $room_id . ";";
    $mysqli->query($sql);
}

unlock();
header("Location: game.php");

