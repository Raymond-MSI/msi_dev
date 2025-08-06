<script>
$(document).ready(function () {
    var tblEDO = $('#tblApproval').DataTable( {
        dom: 'Blfrtip',
        ordering: false,
        buttons: [
            {
                text: "<i class='fa fa-check approvalz' id='approvalz' data-bs-toggle='modal' data-bs-target='#approveModal'></i>"
            },
            {
                text: "<i class='fa fa-xmark rejectedz' data-bs-toggle='modal' data-bs-target='#rejectModal'></i>"
            }
        ]
    });

    $(function() {
        $('.selectAll').click(function() {
            $('.chk_boxes1').prop('checked', this.checked);
            tblEDO.buttons().disabled();
        });
    });

    tblEDO.on( 'select deselect', function () { alert("aaa");
        $('.chk_boxes1').prop('checked', this.checked); 
        tblEDO.buttons(1).disabled();
    });
});
</script>

<?php
function get_message($to, $subject, $message, $footer="", $cc="", $bcc="", $reply="")
{
    if($footer=="")
    {
        $footer = "Dikirim dari system MSIZone.<br/>";
        $footer .= "Jangan mereply email ini.</p>";
    }
    $x1 = $message;
    $from = $_SESSION['Microservices_UserLogin'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $x2 = array("to"=>$to, "cc"=>$cc, "bcc"=>$bcc, "from"=>$from, "reply"=>$reply, "subject"=>$subject, "message"=>$x1);
    return $x2;
}

function get_LastApproval($i)
{
    global $DBHCM;
    $mysql = sprintf(
        "SELECT `overtime_approval_by`
        FROM `sa_trx_edo_request`
        WHERE `edo_id` = %s",
        GetSQLValueString($_POST['select'][$i], "int"),
    );
    $rsApproval = $DBHCM->get_sql($mysql);

    $ApprovalBy = "";
    if($rsApproval[2]>0)
    {
        $ApprovalBy = $rsApproval[0]['overtime_approval_by'];
    }
    return $ApprovalBy;
}

$username = $_SESSION['Microservices_UserName'];
$userEmail = $_SESSION['Microservices_UserEmail'];
$MyFullName = $username . "<" . $userEmail . ">";

$tblname = "mst_users";
$condition = '`email`="' . $userEmail . '"';
$userlogin = $DB->get_data($tblname, $condition);
$permission = json_decode($userlogin[0]['permission'], true);
if($userlogin[0]['usertype']!='Administrator' && $userlogin[0]['usertype']!='Super Admin')
{
    $mdlpermission = $permission['MODULE_EDO']['user_level'];
} else
{
    $mdlpermission = "Approval";
}

if($userlogin[2]>0 && (isset($permission['MODULE_EDO']) || $userlogin[0]['usertype']=='Administrator'))
{
    $mdlname = "HCM";
    $DBEDO = get_conn($mdlname);
    $tblname = "trx_edo_request";
    $condition = "";

    $MyName = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";

    if(isset($_POST['approve']))
    {
        if(isset($_POST['select']))
        {
            for($i=0;$i<count($_POST['select']);$i++)
            {
                $condition = '`edo_id`=' . $_POST['select'][$i];
                $data = $DBEDO->get_data($tblname, $condition);
                if($data[2]>0)
                {
                    $ApprovalBy = get_LastApproval($i);

                    if($data[0]['status']=="edo submitted" && $data[0]['manager']==$data[0]['leader'])
                    { echo "a";
                        $update = '`status`="request approved", `overtime_approval_by`="' . $MyName . "; " . $MyName . '; ", `flag_approval` = 2, `performed_by`="' . addslashes($_SESSION['Microservices_UserName']) . '<' . $_SESSION['Microservices_UserEmail'] . '>"';
                        $status = "request approved";
                    } else
                    if($data[0]['status']=="edo submitted")
                    { echo 'b';
                        $update = '`status`="request approved", `overtime_approval_by`="' . $MyName . '; ", `flag_approval` = 1, `performed_by`="' . addslashes($_SESSION['Microservices_UserName']) . '<' . $_SESSION['Microservices_UserEmail'] . '>"';
                        $status = "request approved";
                    } else
                    { echo "c";
                        $update = '`status`="request approved", `overtime_approval_by`="' . $ApprovalBy . $MyName . '; ", `flag_approval` = 2, `performed_by`="' . addslashes($_SESSION['Microservices_UserName']) . '<' . $_SESSION['Microservices_UserEmail'] . '>"';
                        $status = "request approved";
                    }
                    $res = $DBEDO->update_data($tblname, $update, $condition);
                }
                $mysqlApproval = sprintf(
                    "INSERT INTO `sa_trx_edo_approval`(`approve_id_number`, `approve_module`, `approve_note`, `approve_status`, `approve_by`) 
                    VALUES (%s,%s,%s,%s,%s)",
                    GetSQLValueString($_POST['select'][$i], "int"),
                    GetSQLValueString("EDO", "text"),
                    GetSQLValueString($_POST['approval_note'], "text"),
                    GetSQLValueString("approved", "text"),
                    GetSQLValueString($MyFullName, "text")
                );
                $rs = $DBHCM->get_sql($mysqlApproval, false);

                $xxx = $DBHCM->split_email($data[0]['employee_name']);
                $employee_name = $xxx[0];
                $xxx = $DBHCM->split_email($data[0]['leader']);
                $leader = $xxx[0];
                $xxx = $DBHCM->split_email($data[0]['manager']);
                $manager = $xxx[0];
                
                if($MyFullName == $data[0]['manager'])
                {
                    $subject = "[EDO] Your EDO has been approved #1";
                    $msg = sprintf(
                        "<p>To %s, </p>

                        <p>I hereby approved %s EDO request for date %s with the following notes: </p>
                        
                        <p>%s </p>
                        
                        <p>Thank You, <br/>
                        %s</p>",
                        GetSQLValueString($leader, "defined", $leader, $leader),
                        GetSQLValueString($employee_name . "'s", "defined", $employee_name . "'s", $employee_name . "'s"),
                        GetSQLValueString(date("d-M-Y", strtotime($data[0]['start_date'])), "date"),
                        GetSQLValueString($_POST['approval_note'], "text"),
                        GetSQLValueString($MyName, "defined", $MyName, $MyName)
                    );
                    $to = $data[0]['employee_name'] . "; " . $data[0]['leader'] . ";";
                } else
                if($MyFullName == $data[0]['leader'])
                {
                    $subject = "[EDO] Your EDO has been approved #2";
                    $msg = sprintf(
                        "<p>To %s, </p>

                        <p>I hereby approved your EDO request for date %s with the following notes: </p>
                        
                        <p>%s </p>
                        
                        <p>Thank You, <br/>
                        %s</p>",
                        GetSQLValueString($employee_name, "defined", $employee_name, $employee_name),
                        GetSQLValueString(date("d-M-Y", strtotime($data[0]['start_date'])), "date"),
                        GetSQLValueString($_POST['approval_note'], "text"),
                        GetSQLValueString($MyName, "defined", $MyName, $MyName)
                    );
                    $to = $data[0]['employee_name'];
                }
                $statusx = "Approved";
            }
        }
    } elseif(isset($_POST['reject']))
    {
        if(isset($_POST['select']))
        {
            for($i=0;$i<count($_POST['select']);$i++)
            {
                $condition = '`edo_id`=' . $_POST['select'][$i];
                $data = $DBEDO->get_data($tblname, $condition);
                if($data[2]>0)
                {
                    if($data[0]['status']=="edo submitted" || $data[0]['status'] == "request approved")
                    {
                        $approvalBy = get_LastApproval($i);
                        $update = '`status`="edo rejected", `overtime_approval_by`=NULL, `flag_approval` = 0,  `performed_by`="' . addslashes($_SESSION['Microservices_UserName']) . '<' . $_SESSION['Microservices_UserEmail'] . '>"';
                        $status = "edo rejected";
                    }
                    $res = $DBEDO->update_data($tblname, $update, $condition);
                }
                $mysqlApproval = sprintf(
                    "INSERT INTO `sa_trx_edo_approval`(`approve_id_number`, `approve_module`, `approve_note`, `approve_status`, `approve_by`) 
                    VALUES (%s,%s,%s,%s,%s)",
                    GetSQLValueString($_POST['select'][$i], "int"),
                    GetSQLValueString("EDO", "text"),
                    GetSQLValueString($_POST['rejection_note'], "text"),
                    GetSQLValueString("rejected", "text"),
                    GetSQLValueString($MyFullName, "text")
                );
                $rs = $DBHCM->get_sql($mysqlApproval, false);

                $x1 = explode("<", $data[0]['employee_name']);
                $x2 = explode(">", $x1[1]);
                $employee_name = $x2[0];
                $subject = "[EDO] Your EDO has been rejected";
                $msg = sprintf(
                    "<p>To %s, </p>

                    <p>I hereby do not approved your EDO request for date %s with the following notes: </p>
                    
                    <p>%s</p>
                    
                    <p>Thank You, <br/>
                    %s</p>",
                    GetSQLValueString($employee_name, "defined", $employee_name, $employee_name),
                    GetSQLValueString(date("d-M-Y", strtotime($data[0]['start_date'])), "date"),
                    GetSQLValueString($_POST['rejection_note'], "text"),
                    GetSQLValueString($MyName, "defined", $MyName, $MyName)
                );
                $to = $employee_name;
                $statusx = "Rejected";
            }
        } 
    }

    if((isset($_POST['approve']) || isset($_POST['reject'])))
    {
        if($condition !="")
        {
            $edos = $DBEDO->get_data($tblname, $condition); 
            if($edos[2]>0)
            {
                $type = "EDO";
                $data = array();
                $footer = "";
                $cc = "";
                $x2 = get_message($to, $subject, $msg, $footer, $cc);
                array_push($data, $x2);
                foreach($data as $data_msg)
                {
                    include("components/templates/template_notif.php");
                }
            }
        }
    }

    $status = "(`status`='edo submitted' OR `status`='leave submitted')";
        $tblnamex = "view_employees";
        // $condition = "employee_email = '" . $_SESSION['Microservices_UserEmail'] . "' AND `resign_date` IS NULL";
        $condition = "employee_email = '" . $_SESSION['Microservices_UserEmail'] . "'";
        $employee_info = $DBHCM->get_data($tblnamex, $condition);

        $subs = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
        $subordinats = "";
        $i = 0;
        if($subs[0]!="None")
        {
            foreach($subs[2] as $namex)
            {
                $i>0 ? $sambung=" OR " : $sambung="";
                $subordinats .= $sambung . '`employee_name`="' . addslashes($namex) . '"';
                $i++;
            }
            if($subordinats != "")
            {
                $subordinats = " OR " . $subordinats;
            }
        }

        $xxx = $employee_info[0]['job_name'];
        $job_name = str_replace("0", "", $xxx);
        $conditionUser = "`employee_name` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `entry_by` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `performed_by` LIKE '" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `leader` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' " . $subordinats;



        $condition = "(`manager` = '" . addslashes($_SESSION['Microservices_UserName']) . "<" . $_SESSION['Microservices_UserEmail'] . ">' AND `status` = 'edo submitted' AND `flag_approval` = 0) OR (`leader` = '" . addslashes($_SESSION['Microservices_UserName']) . "<" . $_SESSION['Microservices_UserEmail'] . ">' AND `status` = 'request approved' AND `flag_approval` = 1) AND `start_date` >= '" . date("Y", strtotime("-1 year")) . "-01-01'";
    $edos = $DBEDO->get_data($tblname, $condition);
    ?>
    <form method="post" action="index.php?mod=hcm&sub=edo_approval&status=<?php echo $_GET['status']; ?>">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-secondary">Extra Day Off (Overtime Approval)</h6>
                <?php spinner(); ?>
                <div class="align-items-right">
                    <a href="https://msiguide.mastersystem.co.id/?cat=157" target="_blank"><i class="fa-solid fa-circle-info fs-4"></i></a>
                </div>
            </div>
            <div class="card-body">
                <?php 
                include("components/modules/hcm/mod_edo_menu.php"); 
                ?>
                <div class="table-responsive">
                    <table class="table" id="tblApproval">
                        <thead class=" text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle">
                            <tr>
                                <th class="align-middle text-center" rowspan="2"><input class="selectAll chk_boxes1" type="checkbox" <?php echo $mdlpermission=='Approval' ? "" : "disabled"; ?>></th>
                                <th class="align-middle" rowspan="2">EMPLOYEE NAME</th>
                                <th class="text-center" colspan="2">OVERTIME</th>
                                <th class="align-middle text-center" rowspan="2">DURATION</th>
                                <th class="align-middle text-center" rowspan="2">ONSITE</th>
                                <th class="align-middle" rowspan="2">REASON</th>
                                <th class="align-middle text-center" rowspan="2">STATUS</th>
                            </tr>
                            <tr>
                            <th class="align-middle text-center col-sm-1">START</th>
                                <th class="align-middle text-center col-sm-1">END</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($edos[2]>0)
                            { 
                                $i = 0;
                                do { 
                                    ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            // if(date("Y-m-d", strtotime($edos[0]['performed_date'])) >= date("Y-m-d", strtotime("-1 week")))
                                            // {
                                                ?>
                                                <div class="form-check text-center">
                                                    <input class="form-check-input chk_boxes1" type="checkbox" name="select[]" id="select[]" value="<?php echo $edos[0]['edo_id']; ?>">
                                                </div>
                                                <?php
                                            // } else
                                            // {
                                            //     echo "Over Due";
                                            // }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $edos[0]['employee_name']; ?> 
                                            <span style="font-size:12px">[<?php echo $edos[0]['jabatan']; ?>]<br/>
                                                <?php $x1 = explode("<", $edos[0]['employee_name']); ?>
                                                <?php $email = explode(">", $x1[1]); ?>
                                                <?php $manager = $DBHCM->get_leader($email[0]); ?>
                                                <span class="text-nowrap">Direct Leader : <?php echo $manager; ?> | </span>
                                                <span class="text-nowrap">Indirect Leader : <?php echo $edos[0]['leader']; ?> | </span>
                                                <span class="text-nowrap">Created By : <?php echo $edos[0]['entry_by']; ?></span>
                                            </span>
                                        </td>
                                        <td><?php echo $edos[0]['start_date']>"1970-01-01" ? date("d-M-Y G:m:s", strtotime($edos[0]['start_date'])) : ""; ?></td>
                                        <td><?php echo $edos[0]['end_date']>"1970-01-01" ? date("d-M-Y G:m:s", strtotime($edos[0]['end_date'])) : ""; ?></td>
                                        <td class="text-center <?php echo ($edos[0]['duration']>12 || $edos[0]['duration']<0) ? 'fw-bold text-danger' : ''; ?>"><?php echo $edos[0]['duration']; ?></td>
                                        <td class="text-center"><?php echo $edos[0]['category']; ?></td>
                                        <td>
                                            <?php
                                            if($edos[0]['project_code']!="")
                                            {
                                                $DBNAV = get_conn("NAVISION");
                                                $mysql = sprintf(
                                                    "SELECT `project_name` 
                                                    FROM `sa_mst_order_number` 
                                                    WHERE `project_code` = %s",
                                                    GetSQLValueString($edos[0]['project_code'], "text")
                                                );
                                                $rsProjects = $DBNAV->get_sql($mysql);
                                                echo $edos[0]['project_code'] . " - ";
                                                if($rsProjects[2]>0)
                                                {
                                                    echo $rsProjects[0]['project_name'] . "<br/>";
                                                }
                                            } else
                                            {
                                                echo "Non-Project<br/>";
                                            }
                                            echo "Reason : " . $edos[0]['reason']; 
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                            if($edos[0]['status']=="edo submitted")
                                            {
                                                echo "Overtime Request";
                                            } else
                                            {
                                                echo "Approval Request";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php 
                                    $i++; 
                                } while($edos[0]=$edos[1]->fetch_assoc());
                            } else
                            {
                                ?>
                                <tr><td colspan="14">Data not ready</td></tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfood>
                            <tr class=" text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle">
                                <th class="align-middle text-center"><input class="selectAll chk_boxes1" type="checkbox" <?php echo $mdlpermission=='Approval' ? "" : "disabled"; ?>></th>
                                <th>EMPLOYEE NAME</th>
                                <th class="align-middle text-center">EDO START</th>
                                <th class="align-middle text-center">EDO END</th>
                                <th class="align-middle text-center">DURATION</th>
                                <th class="text-center">ONSITE</th>
                                <th class="align-middle text-center">REASON</th>
                                <th class="align-middle text-center">STATUS</th>
                            </tr>
                        </tfood>
                    </table>
                </div>
            </div>
            <?php //show_footer("control", "Syamsul Arham", $msg="Testing"); ?>
        </div>

    <!-- Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Approval Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" name="approval_note" id="approval_note" rows="3" placeholder="State the reason for approval if necessary."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" name="approve" id="approve" >Approve</button>
                <script>
                // document.getElementById("approve").disabled = true;
                // function chNoteApp()
                // {
                //     if(document.getElementById("approval_note")!="")
                //     {
                //         document.getElementById("approve").disabled = false;
                //     } else
                //     {
                //         document.getElementById("approve").disabled = true;
                //     }
                // }
                </script>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Rejection Note</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <textarea class="form-control" name="rejection_note" id="rejection_note" rows="3" placeholder="State the reason for rejection which is mandatory."></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="reject">Reject</button>
        </div>
        </div>
    </div>
    </div>
    </form>

    <?php 
} else
{
    $ALERT->notpermission();
}
?>


<script src="components/modules/hcm/java_edo.js"></script>