<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
<div id="title">COYOTE</div>

<div class="comment">部屋を作成しました。</div>
<Script language="JavaScript">
    mnt = 2; // 何秒後に移動するか？
    url = "index.php"; // 移動するアドレス
    function jumpPage() {
        location.href = url;
    }
    setTimeout("jumpPage()", mnt * 1000);
</Script>
</body>