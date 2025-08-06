<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    class API 
    {
        public $CURLOPT_RETURNTRANSFER;
        public $CURLOPT_ENCODING;
        public $CURLOPT_MAXREDIRS;
        public $CURLOPT_TIMEOUT;
        public $CURLOPT_FOLLOWLOCATION;

        function __construct($CURLOPT_RETURNTRANSFER=true, $CURLOPT_ENCODING="", $CURLOPT_MAXREDIRS=10, $CURLOPT_TIMEOUT=0, $CURLOPT_FOLLOWLOCATION=true)
        {
            $this->returntransfer = $CURLOPT_RETURNTRANSFER;
            $this->encoding = $CURLOPT_ENCODING;
            $this->maxredirs = $CURLOPT_MAXREDIRS;
            $this->timeout = $CURLOPT_TIMEOUT;
            $this->followlocation = $CURLOPT_FOLLOWLOCATION;
        }

        function call_api($CURLOPT_URL, $CURLOPT_CUSTOMREQUEST, $CURLOPT_HTTPHEADER, $CURLOPT_POSTFIELDS="")
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $CURLOPT_URL,
                CURLOPT_RETURNTRANSFER => $this->returntransfer,
                CURLOPT_ENCODING => $this->encoding,
                CURLOPT_MAXREDIRS => $this->maxredirs,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_FOLLOWLOCATION => $this->followlocation,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $CURLOPT_CUSTOMREQUEST,
                CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
                CURLOPT_HTTPHEADER => $CURLOPT_HTTPHEADER,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

        function json_code($json)
        {
            $x1 = str_replace("
    ", "", $json);
            $x2 = str_replace("{", "%7B", $x1);
            $x3 = str_replace("}", "%7D", $x2);
            $x4 = str_replace('"', "%22", $x3);
            $x5 = str_replace(":", "%3A", $x4);
            $x6 = str_replace(",", "%2C", $x5);
            return str_replace(" ", "%20", $x6);
        }

        function print_json($json)
        {
            $xxx = json_encode($json, JSON_PRETTY_PRINT);
            echo "<p><pre>" . $xxx . "</pre></p>";
        }
    }
}
?>