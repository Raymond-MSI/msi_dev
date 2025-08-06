<?php
if((isset($property)) && ($property == 1)) {
	$version = '1.0';
	$author = 'Syamsul Arham';
} else {
    if(isset($_GET['sub'])) {
        include("components/modules/kpi_project/" . $_GET['sub'] . ".php");
    }
}
?>

<script src="components/vendor/canvasjs-3.7.2/canvasjs.min.js"></script>