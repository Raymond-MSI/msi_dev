<?php

class MSIZoneGateway
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
        $params = $this->database->getFilter($postFields);
        $fields = isset($postFields['fields']) ? ("cfg_id, ". $postFields['fields'] . ", parent") : "*";
        $sql = sprintf("SELECT %s FROM %s %s",
            $this->database->GetSQLValueString($fields, "defined", $fields, $fields),
            $this->database->GetSQLValueString($this->tblname, "defined", $this->tblname, $this->tblname),
            $this->database->GetSQLValueString($params, "defined", $params, $params)
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

    public function get(string $id, array $postFields)
    {
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
        if($this->tblname == "sa_preschedule")
        {
            $sql = sprintf(
                "INSERT INTO " . $this->tblname . " 
                    (`project_id`, `project_code`, `task_id`, `task_name`, `catalogue_code`, `task_category`, `work_date`, `start_date`, `due_date`, `duration_date_minute`, `resource_id`, `resource_email`, `resource_role`, `assignment_type`, `permalink`, `google_event_id`, `flag`, `flag_update`)
                    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                $this->database->GetSQLValueString($data["project_id"], "text"),
                $this->database->GetSQLValueString($data["project_code"], "text"),
                $this->database->GetSQLValueString($data["task_id"], "text"),
                $this->database->GetSQLValueString($data["task_name"], "text"),
                $this->database->GetSQLValueString($data["catalogue_code"], "text"),
                $this->database->GetSQLValueString($data["task_category"], "text"),
                $this->database->GetSQLValueString($data["work_date"], "text"),
                $this->database->GetSQLValueString($data["start_date"], "text"),
                $this->database->GetSQLValueString($data["due_date"], "text"),
                $this->database->GetSQLValueString($data["duration_date_minute"], "text"),
                $this->database->GetSQLValueString($data["resource_id"], "text"),
                $this->database->GetSQLValueString($data["resource_email"], "text"),
                $this->database->GetSQLValueString($data["resource_role"], "text"),
                $this->database->GetSQLValueString($data["assignment_type"], "text"),
                $this->database->GetSQLValueString($data["permalink"], "text"),
                $this->database->GetSQLValueString($data["google_event_id"], "text"),
                $this->database->GetSQLValueString($data["flag"], "text"),
                $this->database->GetSQLValueString($data["flag_update"], "text")
            );
        } else
        if($this->tblname == "sa_schedule")
        {
            $sql = sprintf(
                "INSERT INTO " . $this->tblname . "
                    (`calendar_id`, `event_id`, `summary`, `description`, `permalink`, `status`, `html_link`, `created_event`, `updated_event`, `location`, `start_time`, `end_time`, `diff_time`, `iCalUID`, `attendees_email`, `response_status`, `project_type`, `flag_post_timelog`)
                    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                $this->database->GetSQLValueString($data["calendar_id"], "text"),
                $this->database->GetSQLValueString($data["event_id"], "text"),
                $this->database->GetSQLValueString($data["summary"], "text"),
                $this->database->GetSQLValueString($data["description"], "text"),
                $this->database->GetSQLValueString($data["permalink"], "text"),
                $this->database->GetSQLValueString($data["status"], "text"),
                $this->database->GetSQLValueString($data["html_link"], "text"),
                $this->database->GetSQLValueString($data["created_event"], "text"),
                $this->database->GetSQLValueString($data["updated_event"], "text"),
                $this->database->GetSQLValueString($data["location"], "text"),
                $this->database->GetSQLValueString($data["start_time"], "text"),
                $this->database->GetSQLValueString($data["end_time"], "text"),
                $this->database->GetSQLValueString($data["diff_time"], "text"),
                $this->database->GetSQLValueString($data["iCalUID"], "text"),
                $this->database->GetSQLValueString($data["attendees_email"], "text"),
                $this->database->GetSQLValueString($data["response_status"], "text"),
                $this->database->GetSQLValueString($data["project_type"], "text"),
                $this->database->GetSQLValueString($data["flag_post_timelog"], "text")
            );
        } else
        if($this->tblname == "sa_resource_assignment")
        {
            $sql = sprintf(
                "INSERT INTO " . $this->tblname . "
                    (`project_code`, `project_id`, `order_number`, `no_so`, `project_type`, `customer_name`, `project_name`, `resource_email`, `roles`, `project_roles`, `start_progress`, `end_progress`, `start_date`, `end_date`, `status`, `description`, `approval_status`, `approval_to`, `created_by`, `modified_by`, `flag_assign_wrike`, `created_in_msizone`, `modified_in_msizone`, `timestamp_wrike`)
                    VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                $this->database->GetSQLValueString($data["project_code"], "text"),
                $this->database->GetSQLValueString($data["project_id"], "text"),
                $this->database->GetSQLValueString($data["order_number"], "text"),
                $this->database->GetSQLValueString($data["no_so"], "text"),
                $this->database->GetSQLValueString($data["project_type"], "text"),
                $this->database->GetSQLValueString($data["customer_name"], "text"),
                $this->database->GetSQLValueString($data["project_name"], "text"),
                $this->database->GetSQLValueString($data["resource_email"], "text"),
                $this->database->GetSQLValueString($data["roles"], "text"),
                $this->database->GetSQLValueString($data["project_roles"], "text"),
                $this->database->GetSQLValueString($data["start_progress"], "text"),
                $this->database->GetSQLValueString($data["end_progress"], "text"),
                $this->database->GetSQLValueString($data["start_date"], "text"),
                $this->database->GetSQLValueString($data["end_date"], "text"),
                $this->database->GetSQLValueString($data["status"], "text"),
                $this->database->GetSQLValueString($data["description"], "text"),
                $this->database->GetSQLValueString($data["approval_status"], "text"),
                $this->database->GetSQLValueString($data["approval_to"], "text"),
                $this->database->GetSQLValueString($data["created_by"], "text"),
                $this->database->GetSQLValueString($data["modified_by"], "text"),
                $this->database->GetSQLValueString($data["flag_assign_wrike"], "text"),
                $this->database->GetSQLValueString($data["created_in_msizone"], "text"),
                $this->database->GetSQLValueString($data["modified_in_msizone"], "text"),
                $this->database->GetSQLValueString($data["timestamp_wrike"], "text")
            );
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $totrows = $stmt->rowCount();

        return $this->conn->lastInsertId();
    }

    public function update($id, array $current, array $new): int
    {
        if($this->tblname == "sa_preschedule")
        {
            $sql = sprintf("UPDATE " . $this->tblname . "
                    SET `permalink` = %s, `google_event_id` = %s, `flag` = %s, `flag_update` = %s
                    WHERE `id` = %s",
                $this->database->GetSQLValueString($new["permalink"], "text"),
                $this->database->GetSQLValueString($new["google_event_id"], "text"),
                $this->database->GetSQLValueString($new["flag"], "text"),
                $this->database->GetSQLValueString($new["flag_update"], "text"),
                $this->database->GetSQLValueString($id, "int")
            );
        } else
        if($this->tblname == "sa_schedule")
        {
            $sql = sprintf(
                "UPDATE sa_schedule " . $this->tblname . "
                SET `permalink` = %s, `start_time` = %s, `end_time` = %s, `diff_time` = %s, `response_status` = %s, `flag_post_timelog` = %s
                WHERE id = %s",
                $this->database->GetSQLValueString($new["permalink"], "text"),
                $this->database->GetSQLValueString($new["start_time"], "text"),
                $this->database->GetSQLValueString($new["end_time"], "text"),
                $this->database->GetSQLValueString($new["diff_time"], "text"),
                $this->database->GetSQLValueString($new["response_status"], "text"),
                $this->database->GetSQLValueString($new["flag_post_timelog"], "text"),
                $this->database->GetSQLValueString($current["id"], "int")
            );
        } else
        if($this->tblname == "sa_resource_assignment")
        {
            $sql = sprintf(
                "UPDATE " . $this->tblname . "
                SET `start_date` = %s, `end_date` = %s, `flag_assign_wrike` = %s
                WHERE id = %s",
                $this->database->GetSQLValueString($new["start_date"], "text"),
                $this->database->GetSQLValueString($new["end_date"], "text"),
                $this->database->GetSQLValueString($new["flag_assign_wrike"], "text"),
                $this->database->GetSQLValueString($current["id"], "int")
            );
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = sprintf(
            "DELETE FROM " . $this->tblname . "
                WHERE id = %s",
            $this->database->GetSQLValueString($id, "int")
        );
        $stmt = $this->conn->prepare($sql);
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