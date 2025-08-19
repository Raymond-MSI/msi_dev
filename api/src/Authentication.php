<?php
class Authentication
{
    private PDO $conn;
    private string $username;
    private string $password;

    public function __construct(Database $database,
				                string $username,
				                string $password)
    {
        $this->conn = $database->getConnection();
        $this->username = $username;
        $this->password = $password;
    }

    public function getAccount()
    {
        $headers = $this->getHeader();
        $headersExplode = explode(" ", $headers['Authorization']);

        // $sql = "SELECT *
        //         FROM sa_user_member
        //         WHERE authentication_id = '" . $headersExplode[1] . "'";
        // $stmt = $this->conn->prepare($sql);
        // $stmt->execute();
        // $totrows = $stmt->rowCount();
        $username = "wsyakinah@gmail.com";
        $password = "P@ssw0rd123";
        $account_id = base64_encode($username.":".$password);

        if($account_id==$headersExplode[1])
        // if($totrows>0)
        {
            // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // $account_id = $row['account_id'];
            // file_put_contents("log_" . date("Ymd") . ".txt", date("G:i:s") . " - PASS : " . $account_id . " - " . $headersExplode[1] . "\r\n", FILE_APPEND);
            return $account_id;
        } else
        {
            http_response_code(401);
            $jsn = [
                "title" => "Authentication Account",
                "status" => "Not Found",
                "message" => "Account is not valid"
            ];
            file_put_contents("log/api/log_" . date("Ymd") . ".txt", date("G:i:s") . " - FAILED : " . $account_id . " - " . $headersExplode[1] . "\r\n", FILE_APPEND);
            //echo json_encode($jsn);
            exit;
        }
    }

    public function getHeader()
    {
        $headers = apache_request_headers();
        return $headers;

        // if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        //     $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        //     return $authHeader;
        // } else {
        //     // Handle missing Authorization header
        //     echo json_encode([
        //         "code" => 0,
        //         "message" => "Authorization header missing"
        //     ]);
        //     exit;
        // }
        
    }
}