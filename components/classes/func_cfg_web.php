<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    class WebConfig extends Databases {
        const TBLNAME = "cfg_web";

        function getConfig($configkey) {
            $condition = "config_key = '$configkey'";
            $getconfig = $this->get_data(self::TBLNAME, $condition);
            $value = $getconfig[0]['config_value'];
            return $value;
        }

        function getParams($configkey) {
            $condition = "config_key = '$configkey'";
            $getconfig = $this->get_data(self::TBLNAME, $condition);
            $params = $getconfig[0]['params'];
            return $params;
        }
    }
}
?>