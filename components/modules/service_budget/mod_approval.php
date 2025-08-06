<?php
include( "../../../applications/connections/connections.php" );
include("../../classes/func_databases_v3.php");
include("../../classes/func_modules.php");
include("../../classes/func_cfg_web.php");

$DB = new WebConfig($hostname, $username, $password, $database);
$mdlname = "SERVICE_BUDGET";
$DBNOTIF = get_conn($mdlname);
$tblname = "trx_approval_link";
$id = '';
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $condition = "approval_key='" . $id . "'";
    $notif = $DBNOTIF->get_data($tblname, $condition);
    if($notif[2]>0)
    {
        if($notif[0]['expired_date'] >= date("Y-m-d G:i:s"))
        {
            $_SESSION['Microservices_UserEmail'] = $notif[0]['approval_by'];
            $project_id = $notif[0]['project_id'];
            $condition = "project_id=" . $project_id;
            if($_GET['status']==1)
            {
                // Approved
                $status = "approved";
            } else
            {
                // Rejected
                $status = "rejected";
            }
            $tblname = "trx_project_list";
            $update = "status='$status', modified_by='" . $notif[0]['approval_by'] . "'";
            $condition = "project_id=$project_id";
            $res = $DBNOTIF->update_data($tblname, $update, $condition);
            $tblname = "trx_approval";
            $insert = sprintf("(`approve_by`, `approve_status`, `project_id`) VALUES (%s,%s,%s)",
                GetSQLValueString($notif[0]['approval_by'], "text"),
                GetSQLValueString($status, "text"),
                GetSQLValueString($notif[0]['project_id'], "int")
            );
            $res = $DBNOTIF->insert_data($tblname, $insert);
        } else
        {
            echo "Sudah expired. Anda harus approval lewat aplikasi MSIZone.";
        }
        
    } else
    {
        echo "Link tidak valid.(01)";
    }
} else
{
    echo "Link tidak valid.(02)";
}
?>