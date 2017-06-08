<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
testAAAAA<br/>
<?php echo ($name);?><br/>
<?php echo ($info['name']);?><br/>
<?php echo ($info['user']['name']);?><br/>
<?php echo ($info["user"]["name"]);?><br/>
<hr/>
<include file="test" />

</body>
</html>