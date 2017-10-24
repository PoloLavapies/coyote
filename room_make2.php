<?php
$room_name = $_POST["room_name"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT room_name FROM rooms WHERE room_name = '" . $room_name . "';";
$result = $mysqli->query($sql);
if ($result) {
    $array = $result->fetch_array();
    if ($array[0] === $room_name) {
        header("Location: room_already.php");
        exit;
    } else {
        $sql = "INSERT INTO rooms (room_name) VALUES ('" . $room_name . "');";
        $mysqli->query($sql);
        header("Location: room_make_complete.php");
        exit;
    }
}

