<?php

class METimeElapsedGateway
{
    private PDO $conn;
    public $account_id;
    private $database;
    private $tblname;

    public function __construct(Database $database, Authentication $authentication, $tblname)
    {
        $this->conn = $database->getConnection();
        $this->account_id = $authentication->getAccount();
        $this->database = $database;
        $this->tblname = $tblname;
    }

    public function getAll($postFields): array
    {
        if(!isset($_GET['time_elapsed']))
        {
            $params = $this->database->getFilter($postFields);
            $fields = isset($postFields['fields']) ? ("cfg_id, ". $postFields['fields'] . ", parent") : "*";
            $sql = sprintf("SELECT %s FROM %s %s",
                $this->database->GetSQLValueString($fields, "defined", $fields, $fields),
                $this->database->GetSQLValueString($this->tblname, "defined", $this->tblname, $this->tblname),
                $this->database->GetSQLValueString($params, "defined", $params, $params)
            );
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } else
        {
            $cond = "";
            $sambung = "";
            $bulan = [
                "January" => "01", "February"=> "02", "March"=> "03", "April"=> "04", "May"=> "05", "June"=> "06", "July"=> "07", "Augustus"=> "08", "September"=> "09", "October"=> "10", "November"=> "11", "December"=> "12"
            ];
            if(isset($_GET['start']) && isset($_GET['end']))
            {
                $cond = "month >= '" . $_GET['year'] . "-" . $bulan[$_GET['start']] . "-01" . "' AND month <= '" . $_GET['year'] . "-" . $bulan[$_GET['end']] . "-01" . "'";
                $sambung = " AND ";
            }
            if(isset($_GET['level']))
            {
                $cond .= $sambung . "level = '" . str_replace("_", " ", $_GET['level']) . "'";
            }
            if($cond != "")
            {
                $cond = " WHERE " . $cond;
            }
            $sql = "SELECT * FROM sa_view_time_elapsed $cond";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        $totrows = $stmt->rowCount();
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return array($data, $totrows);
    }

    public function get(string $id, array $postFields)
    {
        // $fields = isset($postFields['fields']) ? ("id, ". $postFields['fields']) : "*";
        // $sql = sprintf("SELECT %s FROM %s WHERE id = %s",
        //     $this->database->GetSQLValueString($fields, "defined", $fields, $fields),
        //     $this->database->GetSQLValueString($this->tblname, "defined", $this->tblname, $this->tblname),
        //     $this->database->GetSQLValueString($id, "text")
        // );
        $sql = sprintf("SELECT * FROM %s WHERE id = %s",
            $this->database->GetSQLValueString($this->tblname, "defined", $this->tblname, $this->tblname),
            $this->database->GetSQLValueString($id, "text")
        );
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $totrows = $stmt->rowCount();

        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return array($data, $totrows);
    }

    public function create(array $data): string
    {
        $sql = sprintf(
            "INSERT INTO " . $this->tblname . " 
                (`id`, `display_id`, `subject`, `description`, `brand`, `part_number`, `serial_number`, `customer_name`, `city`, `requester_name`, `requester_email`, `created_time`, `created_by`, `responded_time`, `is_first_response_overdue`, `resolved_time`, `time_elapsed`, `is_overdue`, `completed_time`, `template`, `request_type`, `level`, `impact`, `priority`, `urgency`, `sla`, `status`, `mode`, `category`, `subcategory`, `resolution`)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->database->GetSQLValueString($data["id"], "text"),
            $this->database->GetSQLValueString($data["display_id"], "text"),
            $this->database->GetSQLValueString($data["subject"], "text"),
            $this->database->GetSQLValueString($data["description"], "text"),
            $this->database->GetSQLValueString($data["brand"], "text"),
            $this->database->GetSQLValueString($data["part_number"], "text"),
            $this->database->GetSQLValueString($data["serial_number"], "text"),
            $this->database->GetSQLValueString($data["customer_name"], "text"),
            $this->database->GetSQLValueString($data["city"], "text"),
            $this->database->GetSQLValueString($data["requester_name"], "text"),
            $this->database->GetSQLValueString($data["requester_email"], "text"),
            $this->database->GetSQLValueString($data["created_time"], "text"),
            $this->database->GetSQLValueString($data["created_by"], "text"),
            $this->database->GetSQLValueString($data["responded_time"], "text"),
            $this->database->GetSQLValueString($data["is_first_response_overdue"], "text"),
            $this->database->GetSQLValueString($data["resolved_time"], "text"),
            $this->database->GetSQLValueString($data["time_elapsed"], "text"),
            $this->database->GetSQLValueString($data["is_overdue"], "text"),
            $this->database->GetSQLValueString($data["completed_time"], "text"),
            $this->database->GetSQLValueString($data["template"], "text"),
            $this->database->GetSQLValueString($data["request_type"], "text"),
            $this->database->GetSQLValueString($data["level"], "text"),
            $this->database->GetSQLValueString($data["impact"], "text"),
            $this->database->GetSQLValueString($data["priority"], "text"),
            $this->database->GetSQLValueString($data["urgency"], "text"),
            $this->database->GetSQLValueString($data["sla"], "text"),
            $this->database->GetSQLValueString($data["status"], "text"),
            $this->database->GetSQLValueString($data["mode"], "text"),
            $this->database->GetSQLValueString($data["category"], "text"),
            $this->database->GetSQLValueString($data["subcategory"], "text"),
            $this->database->GetSQLValueString($data["resolution"], "text")
        );
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $totrows = $stmt->rowCount();

        return $data["id"];
    }

    public function update($id, array $current, array $new): int
    {
        $sql = sprintf("UPDATE " . $this->tblname . "
                SET `subject`=%s, `description`=%s, `brand`=%s, `part_number`=%s, `serial_number`=%s, `customer_name`=%s, `city`=%s, `requester_name`=%s, `requester_email`=%s, `responded_time`=%s,`is_first_response_overdue`=%s,`resolved_time`=%s,`time_elapsed`=%s,`is_overdue`=%s,`completed_time`=%s,`request_type`=%s,`level`=%s,`impact`=%s,`priority`=%s,`urgency`=%s,`sla`=%s,`status`=%s,`mode`=%s,`category`=%s,`subcategory`=%s, `resolution`=%s
                WHERE `id` = %s",
            $this->database->GetSQLValueString($new["subject"], "text"),
            $this->database->GetSQLValueString($new["description"], "text"),
            $this->database->GetSQLValueString($new["brand"], "text"),
            $this->database->GetSQLValueString($new["part_number"], "text"),
            $this->database->GetSQLValueString($new["serial_number"], "text"),
            $this->database->GetSQLValueString($new["customer_name"], "text"),
            $this->database->GetSQLValueString($new["city"], "text"),
            $this->database->GetSQLValueString($new["requester_name"], "text"),
            $this->database->GetSQLValueString($new["requester_email"], "text"),
            $this->database->GetSQLValueString($new["responded_time"], "text"),
            $this->database->GetSQLValueString($new["is_first_response_overdue"], "text"),
            $this->database->GetSQLValueString($new["resolved_time"], "text"),
            $this->database->GetSQLValueString($new["time_elapsed"], "text"),
            $this->database->GetSQLValueString($new["is_overdue"], "text"),
            $this->database->GetSQLValueString($new["completed_time"], "text"),
            $this->database->GetSQLValueString($new["request_type"], "text"),
            $this->database->GetSQLValueString($new["level"], "text"),
            $this->database->GetSQLValueString($new["impact"], "text"),
            $this->database->GetSQLValueString($new["priority"], "text"),
            $this->database->GetSQLValueString($new["urgency"], "text"),
            $this->database->GetSQLValueString($new["sla"], "text"),
            $this->database->GetSQLValueString($new["status"], "text"),
            $this->database->GetSQLValueString($new["mode"], "text"),
            $this->database->GetSQLValueString($new["category"], "text"),
            $this->database->GetSQLValueString($new["subcategory"], "text"),
            $this->database->GetSQLValueString($new["resolution"], "text"),
            $this->database->GetSQLValueString($id, "text")
        );
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM " . $this->tblname . "
                WHERE cfg_id= :cfg_id";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":cfg_id", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function TimeElapsed()
    {
        $sql = "SELECT * FROM sa_view_time_elapsed";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $totrows = $stmt->rowCount();
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return array($data, $totrows);
    }
}