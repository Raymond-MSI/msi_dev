<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    class API 
    {
        public $mailto;
        public $mailcc;
        public $mailreply;
        public $subject;
        public $body;
        public $headers;

        function __construct()
        {
            // $this->returntransfer = $CURLOPT_RETURNTRANSFER;
            // $this->encoding = $CURLOPT_ENCODING;
            // $this->maxredirs = $CURLOPT_MAXREDIRS;
            // $this->timeout = $CURLOPT_TIMEOUT;
            // $this->followlocation = $CURLOPT_FOLLOWLOCATION;
        }

        function SendEmail()
        {
            //SEND Mail
            if (mail($mailto, $subject, $body, $headers)) {
                echo "mail send ... OK"; // or use booleans here
            } else {
                echo "mail send ... ERROR!";
                print_r( error_get_last() );
            }
        }

    }
}
?>

<?php
$filename = 'logo_cidb.png';
$path = 'media/images/profiles';
$file = $path . "/" . $filename;

$mailto = 'syamsul@mastersystem.co.id';
$subject = '[MSIZone] Send Email With Attatchment';
$message = 'Ini contoh email dengan attachment.';

$content = file_get_contents($file);
$content = chunk_split(base64_encode($content));

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (RFC)
$eol = "\r\n";

// main header (multipart mandatory)
$headers = "From: MSIZone <msizone@mastersystem.co.id>" . $eol;
$headers .= "MIME-Version: 1.0" . $eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
$headers .= "This is a MIME encoded message." . $eol;

// message
$body = "--" . $separator . $eol;
$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
$body .= "Content-Transfer-Encoding: 8bit" . $eol;
$body .= $message . $eol;

// attachment
$body .= "--" . $separator . $eol;
$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
$body .= "Content-Transfer-Encoding: base64" . $eol;
$body .= "Content-Disposition: attachment" . $eol;
$body .= $content . $eol;
$body .= "--" . $separator . "--";

//SEND Mail
if (mail($mailto, $subject, $body, $headers)) {
    echo "mail send ... OK"; // or use booleans here
} else {
    echo "mail send ... ERROR!";
    print_r( error_get_last() );
}
?>