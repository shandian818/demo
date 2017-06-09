<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<b><?php echo ($info['user']['name']);?></b>
<?php echo '我我我';?>
<?php if (!empty($info["user"]["name"])) {?>
显示<b><?php echo ($info['user']['name']);?></b>
<?php }else {?>
    ads
<?php }?>
<hr>
<?php foreach ($_SERVER as $v) { ?>
    <?php echo ($v);?><br>
<?php }?>
testAAAAA<br/>
<?php echo ($name);?><br/>
<?php echo ($info['name']);?><br/>
<?php echo ($info['user']['name']);?><br/>
<?php echo ($info["user"]["name"]);?><br/>

<?php echo ($info["name"]);?><br/>
<?php echo ($info["name"]);?><br/>
<?php echo ($info["name"]);?><br/>
<?php echo ($info["name"]);?><br/>
<hr/>
<b><?php echo ($info['user']['name']);?></b>
<?php echo '我我我';?>
<?php if (!empty($info["user"]["name"])) {?>
显示<b><?php echo ($info['user']['name']);?></b>
<?php }else {?>
    ads
<?php }?>
<hr>
<?php foreach ($_SERVER as $v) { ?>
    <?php echo ($v);?><br>
<?php }?>


</body>
</html>