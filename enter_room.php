<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>
<?php
$room_id = $_GET["room_id"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT * FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$room_id = $array["room_id"];
echo $array["room_name"];
echo "<br>";
echo "メンバー";
echo "<br>";
$player_number = $_POST["player_number"];
for ($i = 0; $i < $player_number; $i++) {
    $player = "player" . $i;
    echo $_POST[$player];
    if ($i < $player_number - 1) {
        echo ", ";
    }
}

?>

<div class="comment">名前を決めてください。</div>
<form action="enter_room2.php" method="post">
    <?php
    echo "<input type='hidden' name='room_id' value='" . $room_id . "'>";
    ?>
    <input class="input" type="text" name="player_name">
    <input class="submit" type="submit" value="参加">
</form>
</body>