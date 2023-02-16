<?php
/******************

* Developed by Ofofonobs

* Project: Mp3 Mixer

* Coded by Febin Baiju

* Ofofonobs | Ofofonobs@gmail.com

******************/


$type = @$_GET['type'];
$p = @$_GET['p'];
$img = @$_GET['img'];
if((in_array($type,array('image/jpeg','image/png','image/gif'))) && (file_exists("datas/".$p)))
{
header('Content-Type: '.$type.'');
readfile("temp_albumarts/".$img);
unlink("temp_albumarts/".$img);
}
else
{
die('File doesn\'t exists');
}
?>