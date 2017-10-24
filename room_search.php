<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>

<?php
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms;";
$result = $mysqli->query($sql);
while ($array = $result->fetch_array()) {
    $room_id = $array["room_id"];
    echo "部屋名:";
    echo $array["room_name"];
    echo "<br>";
    echo "メンバー";
    echo "<br>";
    $player_number = $array["player_number"];
    for ($i = 1; $i < $player_number+1 ; $i++) {
        $player = "player" . $i;
        echo $array[$player];
        if ($i < $player_number) {
            echo ", ";
        }
    }
    echo "<div><a href='enter_room.php?room_id=" . $room_id . "'>参加</a></div>";
}
?>
</body>
