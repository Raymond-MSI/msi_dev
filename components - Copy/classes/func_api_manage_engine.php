<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    class API_Manage_Engine extends API
    {

        function get_token()
        {
            $scope = "SDPOnDemand.assets.ALL";
            $CURLOPT_URL = 'https://accounts.zoho.com/oauth/v2/token';
            $CURLOPT_CUSTOMREQUEST="POST";
            $CURLOPT_POSTFIELDS = 'refresh_token=1000.15fbac42ad6e153ead2b9f9a47e8b22f.9f5c5fac334fb3699b5a85efb84c4ea4&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=' . $scope;
            $CURLOPT_HTTPHEADER = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: _zcsr_tmp=f3c68615-bc90-4c30-be5c-f17c7787aad8; b266a5bf57=57c7a14afabcac9a0b9dfc64b3542b70; iamcsr=f3c68615-bc90-4c30-be5c-f17c7787aad8'
            );

            $response = $this->call_api($CURLOPT_URL, $CURLOPT_CUSTOMREQUEST, $CURLOPT_HTTPHEADER, $CURLOPT_POSTFIELDS);
            $status = json_decode($response, true);
            if(isset($status['error']))
            {
                $json_pretty = json_encode($status, JSON_PRETTY_PRINT);
                echo "<pre>Get Token:<br/>" . $json_pretty . "</pre>";
                return $json_pretty;
            } else 
            {
                $dataToken = json_decode($response, true);
                $accessToken = $dataToken["access_token"];
                return $accessToken;
            }
        }

        function get_assets($accessToken, $json="")
        {
            $input_data = "";
            // if($json!="" && str_contains($json, "list_info"))
            if($json!="" && strpos($json, "list_info")>0)
            {
                $input_data = "?input_data=" . trim($this->json_code($json));
            // } elseif($json!="" && str_contains($json, "id"))
            } elseif($json!="" && strpos($json, "id")>0)
            {
                $x1 = explode(":", $json);
                $x2 = explode('"', $x1[1]);
                $input_data = "/" . $x2[1];
            }

            $CURLOPT_URL = "https://sdpondemand.manageengine.com/api/v3/assets$input_data";
            $CURLOPT_CUSTOMREQUEST = 'GET';
            $CURLOPT_HTTPHEADER = array(
                "Accept: application/vnd.manageengine.sdp.v3+json",
                "Content-Type: application/x-www-form-urlencoded",
                ": ",
                "Authorization: Bearer $accessToken",
                "Cookie: 6bc9ae5955=5d9b87a0b6f7a2e742ded0b710bb5c14; JSESSIONID=68C49F1B77A3F74E53C848DD213678A7; _zcsr_tmp=8051b473-6315-462a-bdf3-9f60ed8a6c37; sdpcscook=8051b473-6315-462a-bdf3-9f60ed8a6c37"
            );

            $response = $this->call_api($CURLOPT_URL, $CURLOPT_CUSTOMREQUEST, $CURLOPT_HTTPHEADER);

            $status = json_decode($response, true);
            if(isset($status['error']))
            {
                $json_pretty = json_encode($status, JSON_PRETTY_PRINT);
                echo "<pre>Get List :<br/>" . $json_pretty . "</pre>";
            } else 
            {
                $assets = json_decode($response, true);
                return $assets;
            }
        }

        function put_asset($accessToken, $json, $asset_id)
        {
            $input_data = "";
            if($asset_id!="" && str_contains($asset_id, "list_info"))
            {
                $input_data = "?input_data=" . $this->json_code($asset_id);
            } elseif($asset_id!="" && str_contains($asset_id, "id"))
            {
                $x1 = explode(":", $asset_id);
                $x2 = explode('"', $x1[1]);
                $input_data = "/" . $x2[1];
            } 
            $CURLOPT_URL = "https://sdpondemand.manageengine.com/api/v3/assets$input_data";
            $CURLOPT_CUSTOMREQUEST = 'PUT';
            $postdata = $this->json_code($json);
            $CURLOPT_POSTFIELDS = "input_data=$postdata";
            $CURLOPT_HTTPHEADER = array(
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/vnd.manageengine.sdp.v3+json",
                "Authorization: Bearer $accessToken",
                "Cookie: 6bc9ae5955=5d9b87a0b6f7a2e742ded0b710bb5c14; JSESSIONID=68C49F1B77A3F74E53C848DD213678A7; _zcsr_tmp=8051b473-6315-462a-bdf3-9f60ed8a6c37; sdpcscook=8051b473-6315-462a-bdf3-9f60ed8a6c37"
            );

            $response = $this->call_api($CURLOPT_URL, $CURLOPT_CUSTOMREQUEST, $CURLOPT_HTTPHEADER, $CURLOPT_POSTFIELDS);
            $status = json_decode($response, true);
            return $status;
        }

        function post_asset($accessToken, $json)
        {
            // $input_data = "";
            // if($asset_id!="" && str_contains($asset_id, "list_info"))
            // {
            //     $input_data = "?input_data=" . $this->json_code($asset_id);
            // } elseif($asset_id!="" && str_contains($asset_id, "id"))
            // {
            //     $x1 = explode(":", $asset_id);
            //     $x2 = explode('"', $x1[1]);
            //     $input_data = "/" . $x2[1];
            // } 
            $CURLOPT_URL = "https://sdpondemand.manageengine.com/api/v3/assets";
            $CURLOPT_CUSTOMREQUEST = 'POST';
            $postdata = $this->json_code($json);
            $CURLOPT_POSTFIELDS = "input_data=$postdata";
            $CURLOPT_HTTPHEADER = array(
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/vnd.manageengine.sdp.v3+json",
                "Authorization: Bearer $accessToken",
                "Cookie: 6bc9ae5955=5d9b87a0b6f7a2e742ded0b710bb5c14; JSESSIONID=68C49F1B77A3F74E53C848DD213678A7; _zcsr_tmp=8051b473-6315-462a-bdf3-9f60ed8a6c37; sdpcscook=8051b473-6315-462a-bdf3-9f60ed8a6c37"
            );

            $response = $this->call_api($CURLOPT_URL, $CURLOPT_CUSTOMREQUEST, $CURLOPT_HTTPHEADER, $CURLOPT_POSTFIELDS);
            $status = json_decode($response, true);
            return $status;
        }

        function convert_date($date)
        {
            // $finalDate = strtotime(date("M d, Y h:i A", $date))*1000;
            $finalDate = strtotime($date)*1000;
            return $finalDate;
        }
    }
}
?>