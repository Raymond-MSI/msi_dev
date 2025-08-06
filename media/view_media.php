<?php
$strlen = strlen($_GET['link']);
$typefile = substr($_GET['link'], $strlen-3, 3);
if($typefile=="pdf") {
   echo '<embed src="' . $_GET['link'] . '" width="1000" height="1000">';
} else {
    echo '<embed src="' . $_GET['link'] . '">';
}
?>