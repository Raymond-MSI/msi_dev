<?php
if(isset($_GET['sub'])) {
    include("components/modules/hcm/mod_" . $_GET['sub'] . ".php");
}
?>