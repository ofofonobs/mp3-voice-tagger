<?php
/******************

* Developed by Ofofonobs Developer 

* Project: Mp3 Mixer

* Coded by Ofofonobs Developer 

* ofofonobs.com | ofofonobs@gmail.com

******************/


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=\'Content-Type\' content=\'text/html; charset=ISO-8859-1;charset=windows-1252\' />
<meta name="keywords" content="mp3 tag editor,php mp3 tag editor,automatic mp3 tag editor" />
<meta name="description" content="Edit mp3 tags online." />
<title>Ofofonobs Developer  – Online mp3 Tagger</title>
<link rel="stylesheet" href="style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>';

function friendly_size($size,$round=2)
{
$sizes=array(' Byts',' Kb',' Mb',' Gb',' Tb');
$total=count($sizes)-1;
for($i=0;$size>1024 && $i<$total;$i++){
$size/=1024;
}
return round($size,$round).$sizes[$i];
}
$default_mp3_directory =  "datas/";
$sitename = "ofofonobs.com"; // default site title
$url = "https://www.ofofonobs.com"; // no slash at end
if(isset($_GET['site']))
{
$sitename= strip_tags($_GET['site']);
}
if(isset($_GET['url']))
{
$url = strip_tags($_GET['url']);
}
$default_filename_prefix = '.mp3';
$default_songname_prefix = ''.$sitename.'.mp3';
$default_comment = 'Mp3 Tag Editor by'.$sitename.'';
$default_artist = $sitename;
$default_album = "None";
$default_year = date("Y");
$default_genre = "Ofofonobs.com";
?>
<table style="width: 100%;" class="logo"><tbody><tr><td valign="bottom"><a href="#"><img src="logo.png" width="100%" height="100" alt="logo" title=""></a></td><td align="right"></td></tr></tbody></table>
<?php
if(isset($_POST['submit'])){
$mp3_filepath = $_POST['mp3_filepath'];
$mp3_filename = $_POST['mp3_filename'];
$mp3_songname = $_POST['mp3_songname'];
$mp3_comment = $_POST['mp3_comment'];
$mp3_band = $_POST['mp3_band'];
$mp3_publisher = $_POST['mp3_publisher'];
$mp3_composer = $_POST['mp3_composer'];
$mp3_original_artist = $_POST['mp3_original_artist'];
$mp3_copyright = $_POST['mp3_copyright'];
$mp3_encoded_by = $_POST['mp3_encoded_by'];
$mp3_user_defined = $_POST['mp3_user_defined'];
$mp3_wxxx = $_POST['mp3_wxxx'];
$mp3_artist = $_POST['mp3_artist'];
$mp3_album = $_POST['mp3_album'];
$mp3_year = $_POST['mp3_year'];
$mp3_genre = $_POST['mp3_genre'];
$mp3_track = $_POST['track'];

if(filter_var($mp3_filepath,FILTER_VALIDATE_URL)){
if($mp3_filename!="")
{
$mp3_filename = str_replace(DIRECTORY_SEPARATOR,"-X-",$mp3_filename);
if(strtolower(@end(@explode(".",basename($mp3_filepath))))!="mp3"){
exit("<br />URL must have a .mp3 extension !");
}
if(strtolower(@end(@explode(".",basename($mp3_filename))))!="mp3"){
exit("<br />Filename must have a .mp3 exntension !");
}
 $sname = $default_mp3_directory.$mp3_filename;
 if((file_exists($sname)) || (file_exists("voicetracks/".$mp3_filename)))
 {
 $extension = @end(@explode('.',$sname));
 $mp3_filename = str_replace(".".$extension,"_".rand(0,999).".".$extension,$mp3_filename);   
 } 
 $sname = $default_mp3_directory.$mp3_filename;
  $j_sname = $default_mp3_directory.$mp3_filename;
 if(@$_POST['mp3_voice_track']=="on")
 {
 $sname = "voicetracks/".$mp3_filename;
 }
if(copy($mp3_filepath,$j_sname)){
if(@$_POST['mp3_voice_track']=="on" && $_POST['mp3_voicetrack']!='http://')
{
if(copy($_POST['mp3_voicetrack'],"temp_joins/".$mp3_filename))
{
include 'function.joinmp3.php';
$place = $_POST['mp3_voice_track_add'];
if($place=="first")
{
$FilenamesIn[] = "temp_joins/".$mp3_filename;
$FilenamesIn[] = $j_sname;
}
else
{
$FilenamesIn[] = $j_sname;
$FilenamesIn[] = "temp_joins/".$mp3_filename;
}
 if (CombineMultipleMP3sTo($sname, $FilenamesIn)) {
unlink("temp_joins/".$mp3_filename);
unlink($default_mp3_directory."/".$mp3_filename);
rename("voicetracks/".$mp3_filename,$default_mp3_directory."/".$mp3_filename);
 }
 }
 else echo '<div class="rmenu">Unable to upload Voice Track</div><br />';
}
$sname = $default_mp3_directory."/".$mp3_filename;
$size = friendly_size(filesize($sname));        
$mp3_tagformat = 'UTF-8';
require_once('getid3/getid3.php');
$mp3_handler = new getID3;
$mp3_handler->setOption(array('encoding'=>$mp3_tagformat));
require_once('getid3/write.php'); 
$mp3_writter = new getid3_writetags;
$mp3_writter->filename       = $sname;
$mp3_writter->tagformats     = array('id3v1', 'id3v2.3');
$mp3_writter->overwrite_tags = true;
$mp3_writter->tag_encoding   = $mp3_tagformat;
$mp3_writter->remove_other_tags = true;
$mp3_data['title'][]   = $mp3_songname;
$mp3_data['artist'][]  = $mp3_artist;
$mp3_data['album'][]   = $mp3_album;
$mp3_data['year'][]    = $mp3_year;
$mp3_data['genre'][]   = $mp3_genre;
$mp3_data['comment'][] = $mp3_comment;
$mp3_data['publisher'][] = $mp3_publisher;
$mp3_data['composer'][] = $mp3_composer;
$mp3_data['band'][] = $mp3_band;
$mp3_data['commercial_information'][] = $mp3_wxxx;
$mp3_data['url_user'][] = $mp3_user_defined;
$mp3_data['track'][] = $mp3_track;
$mp3_data['original_artist'][] = $mp3_original_artist;
$mp3_data['copyright_message'][] = $mp3_copyright;
$mp3_data['encoded_by'][] = $mp3_encoded_by;

$album_art = @end(@explode('/',$_POST['album_art']));
$album_path = "temp_albumarts/".$album_art;
$album_ext = @end(@explode('.',$album_art));
if(in_array($album_ext,array("png","jpeg","gif","jpg")) && (copy($_POST['album_art'],$album_path)))
{
if($album_ext=="jpeg" || $album_ext=="jpg") $type = "image/jpeg";
if($album_ext=="png") $type="image/png";
if($album_ext=="gif") $type="image/png";
$mp3_data['attached_picture'][0]['data'] = file_get_contents($album_path);
$mp3_data['attached_picture'][0]['picturetypeid'] = $type;
$mp3_data['attached_picture'][0]['description'] = $album_art;
$mp3_data['attached_picture'][0]['mime'] = $type;
}
else
{
echo '<div class="rmenu">Incompartible image !</div>'; 
}

$mp3_writter->tag_data = $mp3_data;

if($mp3_writter->WriteTags()) {
$id3 = new getID3;
$info = $id3->analyze($sname);
echo '<div class="phdr"><b>MP3 Taged Notice</b></div><div class="gmenu">
<center><font color="green"><b>Tags Were Successfully Written.</b></font></center>
</div><div class="list1"><b>Copied:</b><br><div class="func">
<a href="'.$mp3_filepath.'">'.$mp3_filepath.'</a></div><p><b>To:</b></p><br><center>
<a class="fmenu" href="'.$sname.'"><b>Download Now<br>'.basename($sname).'</b></a>
<br>Size: <font color="blue">( '.$size.' )</font></center></div>
<div class="phdr"><b>Direct Download Url</b></div><div align="center" class="list2">
<input size="25" type="text" class="input" name="mp3_filename" value="'.$url.'/'.$sname.'"></div><div class="rmenu">
<font color="red"><b>Warning:</b></font> Download Link Is AVAILABLE 1 hour</div>
';
if(@$_POST['mp3_info_view']=="on")
{
echo '<div class="phdr"><b>Mp3 Tag Information</b>
</div>';
if(!empty($_POST['album_art']))echo '<div class="list2"><center><img class="gmenu" src="cover.php?type='.$type.'&p='.basename($sname).'&img='.$album_art.'" width="200" height="200" alt="art">
</center></div>';
echo '<div class="list1">Title: '.$info['id3v1']['title'].'</div><div class="list2">Artist: '.$info['id3v1']['artist'].'</div>
<div class="list1">Album: '.$info['id3v1']['album'].'</div>
<div class="list2">Comment: '.$info['id3v1']['comment'].'</div><div class="list1">Year: '.$info['id3v1']['year'].'</div>
<div class="list2">Duration: '.$info['playtime_string'].'</div>';
}
}
else{
echo"<br />Failed to write tags!<br>".implode("<br /><br />",$mp3_writter->errors);
}
}
else{echo '<div class="rmenu">Unable to copy file.</div>';}
}
else{echo '<div class="rmenu">Empty filename.</div>';}
}
else{echo '<div class="rmenu">Invalid FilePath.</div>';}
}
else{
?>
<div class="phdr"><b>Mp3 Tag Editor</b></div>
<form method="post" action="" enctype="multipart/form-data">
<div class="list1"><b> &raquo; Mp3 url</b><br /><input size="30" type="text" class="input" name="mp3_filepath" value="" /></div>
<div class="list2"><b> &raquo; Album art/Cover</b><br /><input size="25" type="text" class="input" name="album_art" /></div>
<div class="list1"><b> &raquo; Filename</b><br /><input size="25" type="text" class="input" name="mp3_filename" value="<?php echo $default_filename_prefix ; ?>" /> </div>
<div class="list2"><b> &raquo; Song name/title</b><br /><input size="25" type="text" class="input" name="mp3_songname" value="<?php echo $default_songname_prefix ; ?>" /></div>
<div class="list1"><b> &raquo; Album</b><br /><input size="25" type="text" class="input" name="mp3_album" value="<?php echo $default_album ; ?>" /> </div>
<div class="list2"><b> &raquo; Artist(s)</b><br /><input size="25" type="text" class="input" name="mp3_artist" value="<?php echo $default_artist ; ?>" /></div>
<div class="list1"><b> &raquo; Comment</b><br /><input size="25" type="text" class="input" name="mp3_comment" value="<?php echo $default_comment ; ?>" /></div>
<div class="list2"><b> &raquo; Band</b><br /><input size="25" type="text" class="input" name="mp3_band" value="<?php echo $sitename; ?>" /></div>
<div class="list1"><b> &raquo; Publisher</b><br /><input size="25" type="text" class="input" name="mp3_publisher" value="<?php echo $url; ?>" /></div>
<div class="list2"><b> &raquo; Composer</b><br /><input size="25" type="text" class="input" name="mp3_composer" value="<?php echo $sitename; ?>" /></div>
<div class="list1"><b> &raquo; Original Artist</b><br /><input size="25" type="text" class="input" name="mp3_original_artist" value="<?php echo $sitename; ?>" /></div>
<div class="list2"><b> &raquo; Copyright</b><br /><input size="25" type="text" class="input" name="mp3_copyright" value="<?php echo $sitename; ?>" /></div>
<div class="list1"><b> &raquo; Encoded By</b><br /><input size="25" type="text" class="input" name="mp3_encoded_by" value="<?php echo $sitename; ?>" /></div>
<div class="list2"><b> &raquo; User Defined Url</b><br /><input size="25" type="text" class="input" name="mp3_user_defined" value="<?php echo $url; ?>" /></div>
<div class="list1"><b> &raquo; Commercial Url</b><br /><input size="25" type="text" class="input" name="mp3_wxxx" value="<?php echo $url; ?>" /></div>
<div class="list2"><b> &raquo; Track Number</b><br /><select name="track" class="input"><?php for($i=0;$i<=19;$i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?></select></div> 
<div class="list1"><b> &raquo; Released Year</b><br /><select class="input" name="mp3_year"><?php for($d=date('Y');$d>=2000;$d--) echo '<option value="'.$d.'">'.$d.'</option>'; ?> </select></div>
<div class="list2"><b> &raquo; Genre</b><br /><select name="mp3_genre" class="input">
<option value="<?php echo $default_genre ; ?>"><?php echo $default_genre ; ?></option>
<option value="Blues">Blues</option>
<option value="Classical">Classical</option>
<option value="Classic Rock">Classic Rock</option>
<option value="Country">Country</option>
<option value="Dance">Dance</option>
<option value="Drum Solo">Drum Solo</option>
<option value="Fusion">Fusion</option>
<option value="Gangstar">Gangstar</option>
<option value="Hip-Hop">Hip-Hop</option>
<option value="Instrumental Rock">Instrumental Rock</option>
<option value="Instrumental PoP">Instrumental PoP</option>
<option value="Jazz">Jazz</option>
<option value="Metal">Metal</option>
<option value="Oldies">Oldies</option>
<option value="PoP">PoP</option>
<option value="PoP-Folk">PoP-Folk</option>
<option value="Porn Groove">Porn Groove</option>
<option value="R&amp;B">R&amp;B</option>
<option value="Symphony">Symphony</option>
<option value="Slow Rock">Slow Rock</option>
<option value="Symphonic Rock">Symphonic Rock</option>
<option value="Sound Track">Sound Track</option>
<option value="Other">Other</option>
</select></div>
<div class="phdr"><input type="checkbox" name="mp3_voice_track" /><b>Add Voice Track</b> (Optional)</div>
<div class="list1"><b> &raquo; Voice Track Url</b> [<a href="http://fromtexttospeech.com/"><b>Make Track</b></a>]<br /><input size="25" type="text" class="input" name="mp3_voicetrack" value="http://" /><br />
Add Track On <input type="radio" name="mp3_voice_track_add" value="first" checked="yes" />First <input type="radio" name="mp3_voice_track_add" value="last" />Last</div>
<div class="gmenu"><input type="checkbox" name="mp3_info_view" checked="checked" />View Taged File Info?<br />
<center><input size="15" type="submit" name="submit" value="Edit Tags" /></center></div>
</form>
<?php
}
?>
<div class="bmenu"><center>Total <font color="red"><b><?php echo intval((count(scandir($default_mp3_directory))+count(scandir("voicetracks")))-4); ?></b></font> Mp3 Taged</center></div>
<div class="footer"><center><b> &copy; 2018-<?php echo date('Y'); ?> Ofofonobs Developer <br />All Rights Reserved</b></center></div>
<p></p><center>Developed By: <a href="http://ofofonobs.com/developer"><b>Ofofonobs.com</b><br /></a>
</body>
</html>