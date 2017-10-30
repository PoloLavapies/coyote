<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>

<?php
$room_id = $_GET["room_id"];
$mysqli = new mysqli('157.112.147.201', 'coyotepkai_root', 'root1234', 'coyotepkai_db');
$sql = "SELECT ifstart FROM rooms WHERE room_id = '" . $room_id . "';";
$result = $mysqli->query($sql);
$array = $result->fetch_array();
$ifstart = $array["ifstart"];
// ゲーム中の場合
if ($ifstart==1){
    echo "ゲーム中のため、この部屋を削除することはできません。";
    echo "<div><a href='room_search.php'>戻る</a></div>";
}
// そうでない場合
else {
    $sql = "DELETE FROM rooms WHERE room_id = '" . $room_id . "';";
    $mysqli->query($sql);
    echo "<div class='comment'>削除しました。</div>";
    echo "<div><a href='room_search.php'>戻る</a></div>";
}
?>

</body>