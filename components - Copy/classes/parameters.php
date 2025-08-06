<?php
if((isset($property)) && ($property == 1)) {
  $version = '1.0';
  $author = 'Syamsul Arham';
} else {
?>
<?php
//define("DOMAIN", "http://localhost:82/excelmudah/");
define("authorCaption", "Author : ");
define("datetimeCaption", "Created at : ");
define("formatDate", "d-F-Y G:i:s");
define("hitsCaption", "Hits : ");
define("categoryCaption", "");
define("tagsCaption", "Tags");
//define("MEDIA_IMAGE_FOLDER", "media/images");
//define("MEDIA_DOCUMENT_FOLDER", "media/documents");
//define("MEDIA_VIDEO_FOLDER", "media/videos");
//define("MEDIA_AUDIO_FOLDER", "media/audios");

$condition = "`parent`=8 OR `parent`=6";
$gWebcfg = $DB->get_data("cfg_web", $condition);
$dWebcfg = $gWebcfg[0];
$qWebcfg = $gWebcfg[1];
do {
    $webParams[$dWebcfg["config_key"]] = $dWebcfg["config_value"];
    if(isset($_GET['template']) && $dWebcfg['config_key'] == 'TEMPLATE') {
      define("TEMPLATE", $_GET['template']);
    } else {
      define($dWebcfg["config_key"], $dWebcfg["config_value"]);
    }
  } while($dWebcfg=$qWebcfg->fetch_assoc());
define("DOMAIN", "http://" . $webParams["DOMAIN_NAME"] . "/");
?>

<?php } ?><?php
if((isset($property)) && ($property == 1)) {
	 // collected module information
     include("components/classes/func_property.php");
} else {
?>
<?php } ?>