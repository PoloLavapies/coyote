<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>

<div class="comment">同じ名前のプレイヤーがすでにいます。</div>
<?php
$room_id = $_GET["room_id"];
echo "<a href='enter_room.php?room_id=" . $room_id . "'>戻る</a>"
?>

</body>
