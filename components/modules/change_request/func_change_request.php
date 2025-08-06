<?php
if (!isset($_POST['status_request'])) {
    $status_request = null;
} else {
    $status_request = $_POST['status_request'];
}

if (!isset($_POST['requested_by'])) {
    $requested_by = null;
} else {
    $requested_by = $_POST['requested_by'];
}

if (!isset($_POST['so_number'])) {
    $so_number = null;
} else {
    $so_number = $_POST['so_number'];
}

if (!isset($_POST['hasil_akhir'])) {
    $hasil_akhir = null;
} else {
    $hasil_akhir = $_POST['hasil_akhir'];
}

if (!isset($_POST['riskassesment'])) {
    $riskassesment = null;
} else {
    $riskassesment = $_POST['riskassesment'];
}

if (!isset($_POST['change_impact'])) {
    $change_impact = null;
} else {
    $change_impact = $_POST['change_impact'];
}

if (!isset($_POST['reason_rejected'])) {
    $reason_rejected = null;
} else {
    $reason_rejected = $_POST['reason_rejected'];
}

if (!isset($_POST['schedule'])) {
    $schedule = null;
} else {
    $schedule = $_POST['schedule'];
}

if (!isset($_POST['detail'])) {
    $detail = null;
} else {
    $detail = $_POST['detail'];
}

if (!isset($_POST['item'])) {
    $item = null;
} else {
    $item = $_POST['item'];
}

if (!isset($_POST['perubahan'])) {
    $perubahan = null;
} else {
    $perubahan = $_POST['perubahan'];
}

if (!isset($_POST['reason_request'])) {
    $reason_request = null;
} else {
    $reason_request = $_POST['reason_request'];
}

if (!isset($_POST['technical_assesment'])) {
    $technical_assesment = null;
} else {
    $technical_assesment = $_POST['technical_assesment'];
}

if (!isset($_POST['business_benefit'])) {
    $business_benefit = null;
} else {
    $business_benefit = $_POST['business_benefit'];
}

if (!isset($_POST['ta_pic'])) {
    $ta_pic = null;
} else {
    $ta_pic = $_POST['ta_pic'];
}

if (!isset($_POST['risk_pic'])) {
    $risk_pic = null;
} else {
    $risk_pic = $_POST['risk_pic'];
}

if (!isset($_POST['mandays_pic'])) {
    $mandays_pic = null;
} else {
    $mandays_pic = $_POST['mandays_pic'];
}

if (!isset($_POST['dp_pic'])) {
    $dp_pic = null;
} else {
    $dp_pic = $_POST['dp_pic'];
}

if (!isset($_POST['fp_pic'])) {
    $fp_pic = null;
} else {
    $fp_pic = $_POST['fp_pic'];
}

if (!isset($_POST['prerequisite_pic'])) {
    $prerequisite_pic = null;
} else {
    $prerequisite_pic = $_POST['prerequisite_pic'];
}

if (!isset($_POST['bb_pic'])) {
    $bb_pic = null;
} else {
    $bb_pic = $_POST['bb_pic'];
}

if (!isset($_POST['reviewer'])) {
    $reviewer = null;
} else {
    $reviewer = $_POST['reviewer'];
}

if (!isset($_POST['pic_apr'])) {
    $pic_apr = null;
} else {
    $pic_apr = $_POST['pic_apr'];
}

if (!isset($_POST['used_ticket_amount'])) {
    $used_ticket_amount = null;
} else {
    $used_ticket_amount = $_POST['used_ticket_amount'];
}

if (!isset($_POST['ticket_allocation'])) {
    $ticket_allocation = null;
} else {
    $ticket_allocation = $_POST['ticket_allocation'];
}

if (!isset($_POST['ticket_allocation_sisa'])) {
    $ticket_allocation_sisa = null;
} else {
    $ticket_allocation_sisa = $_POST['ticket_allocation_sisa'];
}

if (!isset($_POST['ticket_allocation_sisa'])) {
    $sisaticket = $ticket_allocation - $used_ticket_amount;
} else {
    $sisaticket = $ticket_allocation_sisa - $used_ticket_amount;
}

if (!isset($_POST['pic_ket_ta'])) {
    $pic_ket_ta = null;
} else {
    $pic_ket_ta = $_POST['pic_ket_ta'];
}

if (!isset($_POST['pic_ket_bb'])) {
    $pic_ket_bb = null;
} else {
    $pic_ket_bb = $_POST['pic_ket_bb'];
}

if (!isset($_POST['pic_ket_ra'])) {
    $pic_ket_ra = null;
} else {
    $pic_ket_ra = $_POST['pic_ket_ra'];
}

if (!isset($_POST['type_of_service'])) {
    $type_of_service = null;
} else {
    $type_of_service = $_POST['type_of_service'];
}

if (!isset($_POST['status_approval'])) {
    $status_approval = null;
} else {
    $status_approval = $_POST['status_approval'];
}

if (!isset($_POST['status_approval2'])) {
    $status_approval2 = null;
} else {
    $status_approval2 = $_POST['status_approval2'];
}

if (!isset($_POST['pic_apr2'])) {
    $pic_apr2 = null;
} else {
    $pic_apr2 = $_POST['pic_apr2'];
}
if (!isset($_POST['mandays_tor'])) {
    $mandays_tor = null;
} else {
    $mandays_tor = $_POST['mandays_tor'];
}
if (!isset($_POST['mandays_tm'])) {
    $mandays_tm = null;
} else {
    $mandays_tm = $_POST['mandays_tm'];
}
if (!isset($_POST['mandays_value'])) {
    $mandays_value = null;
} else {
    $mandays_value = $_POST['mandays_value'];
}
if (!isset($_POST['others_item'])) {
    $others_item = null;
} else {
    $others_item = $_POST['others_item'];
}
if (!isset($_POST['others_detail'])) {
    $others_detail = null;
} else {
    $others_detail = $_POST['others_detail'];
}
if (!isset($_POST['others_price'])) {
    $others_price = null;
} else {
    $others_price = $_POST['others_price'];
}
if (!isset($_POST['cost_type'])) {
    $cost_type = null;
} else {
    $cost_type = $_POST['cost_type'];
}
if (!isset($_POST['nccr'])) {
    $nccr = null;
} else {
    $nccr = $_POST['nccr'];
}
if (!isset($_POST['sales_name'])) {
    $sales_name = null;
} else {
    $sales_name = $_POST['sales_name'];
}
if (!isset($_POST['nomorpo_chargeable'])) {
    $nomorpo_chargeable = null;
} else {
    $nomorpo_chargeable = $_POST['nomorpo_chargeable'];
}
if (!isset($_POST['change_reason'])) {
    $change_reason = null;
} else {
    $change_reason = $_POST['change_reason'];
}
if (!isset($_POST['detail_reason'])) {
    $detail_reason = null;
} else {
    $detail_reason = $_POST['detail_reason'];
}
if (!isset($_POST['customer_requirement_description'])) {
    $customer_requirement_description = null;
} else {
    $customer_requirement_description = $_POST['customer_requirement_description'];
}
if (!isset($_POST['start_date'])) {
    $start_date = null;
} else {
    $start_date = $_POST['start_date'];
}
if (!isset($_POST['finish_date'])) {
    $finish_date = null;
} else {
    $finish_date = $_POST['finish_date'];
}
if (!isset($_POST['so_number'])) {
    $so_number = null;
} else {
    $so_number = $_POST['so_number'];
}
if (!isset($_POST['customer'])) {
    $customer = null;
} else {
    $customer = $_POST['customer'];
}
if (!isset($_POST['project_name'])) {
    $project_name = null;
} else {
    $project_name = $_POST['project_name'];
}
if (!isset($_POST['affected_ci'])) {
    $affected_ci = null;
} else {
    $affected_ci = $_POST['affected_ci'];
}
if (!isset($_POST['serial_number'])) {
    $serial_number = null;
} else {
    $serial_number = $_POST['serial_number'];
}
if (!isset($_POST['part_number'])) {
    $part_number = null;
} else {
    $part_number = $_POST['part_number'];
}
if (!isset($_POST['impact_it'])) {
    $impact_it = null;
} else {
    $impact_it = $_POST['impact_it'];
}
if (!isset($_POST['requested_by'])) {
    $requested_by = null;
} else {
    $requested_by = $_POST['requested_by'];
}
if (!isset($_POST['perlu_backup'])) {
    $perlu_backup = null;
} else {
    $perlu_backup = $_POST['perlu_backup'];
}
if (!isset($_POST['barang_backup'])) {
    $barang_backup = null;
} else {
    $barang_backup = $_POST['barang_backup'];
}
$db_name = "HCM";
$DBHCM = get_conn($db_name);
$pic_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees WHERE employee_email LIKE '%$pic_apr2%'");
$requested_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees WHERE employee_email LIKE '%$requested_by%'");

class Database_cr extends Databases
{
    function get_data_pc()
    {
        $mysql_pc = 'SELECT DISTINCT project_code FROM sa_trx_project_list';
        $data = $this->open_db($mysql_pc);
        return $data;
    }
}
if (isset($_POST['save_sb'])) {
    $cr_type;
    if ($_POST['cr_type'] == "Implementation") {
        $cr_type = "CR-IM";
    } else if ($_POST['cr_type'] == "Maintenance") {
        $cr_type = "CR-MT";
    } else if ($_POST['cr_type'] == "IT") {
        $cr_type = "CR-IT";
    } else if ($_POST['cr_type'] == "Sales/Presales") {
        $cr_type = "CR-SP";
    }

    $db_gi = $DBCR->get_data("general_informations", "cr_no like '%$cr_type%' order by gi_id desc ");

    $number = 1;
    $fzeropadded = sprintf("%04d", $number);
    if (empty($db_gi[0])) {
        $cr_no_count = $fzeropadded;
    } else {
        $cr_no_raw = $db_gi[0]['cr_no'];
        $cr_no_arr = [];
        $cr_no_arr = explode("-", $cr_no_raw);
        $cr_no_count = sprintf("%04d", $cr_no_arr[3] + $fzeropadded);
    }
    $cr_no = date('ymd') . "-" . $cr_type . "-" . $cr_no_count;

    $type_of_serviceimp = "";
    if (isset($_POST['type_of_service'])) {
        foreach ($_POST['type_of_service'] as $type_of_serviceimp0) {
            $type_of_serviceimp .= $type_of_serviceimp0 . " ; ";
        }
    }

    //GENERAL INFORMATION
    //$requested_by_arr = [];
    //$requested_by_arr = explode("|",$_POST['requested_by']);
    $insert = sprintf(
        "(`project_manager`,`so_number`,`customer`,`project_code`,`change_request_type`,`project_name`,`type_of_service`,`request_date`,`cr_no`,`requested_by_email`,`classification`,`impact_it`,`impact`,`scope_of_change`,`reason`, `change_request_status`, `change_request_approval_type`,`pic_leader`, `pic`, `pic_name`, `change_request_approval_type2`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($customer, "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['cr_type'], "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($type_of_serviceimp, "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['classification'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($status_approval2, "text")
    );

    //Detail of Change
    if (isset($_POST['detail'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['detail']); $i++) {
            $combine_arr[] = array($_POST['detail'][$i], $_POST['item'][$i], $_POST['perubahan'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`detail`,`item`,`perubahan`,`schedule`) VALUES (%s,%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
                GetSQLValueString($schedule, "text"),

            );
            $DBCR->insert_data("detail_of_change", $insert_sql);
        }
    }

    //IMPLEMENTATION PLAN
    $insert_implementation = sprintf(
        "(`cr_no`,`start_date`,`finish_date`) VALUES (%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );
    $DBCR->insert_data($tblname, $insert);
    $DBCR->insert_data("implementation_plans", $insert_implementation);

    //Change Request Closing
    $insert_crc = sprintf(
        "(`cr_no`,`hasil_akhir`,`reason_request`, `reason_rejected`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($hasil_akhir, "text"),
        GetSQLValueString($reason_request, "text"),
        GetSQLValueString($reason_rejected, "text"),
    );
    $DBCR->insert_data("change_request_closing", $insert_crc);

    //Mandays
    if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    }

    //Others
    if (isset($_POST['others_item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    }

    //PIC
    $insert = sprintf(
        "(`cr_no`, `name`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($pic_apr, "text"),
    );
    $DBCR->insert_data("master_pic", $insert);

    //CR Reviewer
    if (isset($_POST['reviewer'])) {
        $sequence = 1;
        foreach ($_POST['reviewer'] as $key => $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value, "text"),
                GetSQLValueString($sequence, "text"),
            );
            if ($sequence == 1) {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`, `on_review`) VALUES (%s,%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                    GetSQLValueString("yes", "text"),
                );
            } else {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                );
            }
            $DBCR->insert_data("approvals", $insert_sql);

            $sequence += 1;
        }
    }

    //APPROVAL STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `rejected_reason`, `cr_no`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text"),
        GetSQLValueString($cr_no, "text")

    );
    $DBCR->insert_data("approval_statuses", $insert);

    //COMPLETION STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `incomplete_reason`) VALUES (%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text")
    );
    $DBCR->insert_data("completion_statuses", $insert);

    $msg1 = "<p>Saya telah membuat change request:" . "</p>";
    $to = '';
    if (isset($_POST['save_sb'])) {
        // SUBMIT
        // $approved = 'Submited';
        $status = 'submited';
        $email = $_SESSION['Microservices_UserEmail'];
        $leader = get_leader($email, 1);
        $dleader = $leader[0];
        $qleader = $leader[1];
        $to_name = '';
        do {
            $to .= $dleader['leader_name'] . "<" . $dleader['leader_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
            $to_name = $dleader['leader_name'];
        } while ($dleader = $qleader->fetch_assoc());
        $msg1 = "<p>Dengan ini saya mengajukan change request untuk diapproval:</p>";
        $msg2 = "<p>Mohon untuk direview dan diberikan persetujuan.</p>";
    }

    $owner = get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "[MSIZone] " . ucwords($status) . " Change Request : " . $_POST['project_code'] . " - " . $_POST['project_name'];
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . " dan " . $pic_name[0]['employee_name'] . "</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $_POST['so_number'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Project Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['project_name'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Scope of Change </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['scope_of_change'] . "</td></tr>";
    $msg .= "</table>";
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=Tidak Ada&status_approval=" . $_POST['status_approval'] . "&so_number=" . $_POST['so_number'] . "'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $mdlname1 = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname1);
    $tblnametrx = "trx_notification";
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $notifmsg = $cr_no . "; " . $_POST['project_code'] . "; " . $_POST['so_number'] . "; " . $_POST['project_name'] . ";";
    $notif_link = "index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=Tidak Ada&status_approval=" . $_POST['status_approval'] . "";
    $insert1 = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " CR " . $_POST['project_code'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnametrx, $insert1);


    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`project_name`=%s,`request_date`=%s,`cr_no`=%s,`requested_by`=%s,`scope_of_change`=%s,`reason`=%s,`affected_ci`=%s, `pic`=%s",
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($_POST['affected_ci'], "text"),
        GetSQLValueString($pic_apr, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
if (isset($_POST['save_implemen'])) {
    $cr_type;
    if ($_POST['cr_type'] == "Implementation") {
        $cr_type = "CR-IM";
    } else if ($_POST['cr_type'] == "Maintenance") {
        $cr_type = "CR-MT";
    } else if ($_POST['cr_type'] == "IT") {
        $cr_type = "CR-IT";
    }

    $db_gi = $DBCR->get_data("general_informations", "cr_no like '%$cr_type%' order by gi_id desc ");

    $number = 1;
    $fzeropadded = sprintf("%04d", $number);
    if (empty($db_gi[0])) {
        $cr_no_count = $fzeropadded;
    } else {
        $cr_no_raw = $db_gi[0]['cr_no'];
        $cr_no_arr = [];
        $cr_no_arr = explode("-", $cr_no_raw);
        $cr_no_count = sprintf("%04d", $cr_no_arr[3] + $fzeropadded);
    }
    $cr_no = date('ymd') . "-" . $cr_type . "-" . $cr_no_count;

    $type_of_serviceimp = "";
    if (isset($_POST['type_of_service'])) {
        foreach ($_POST['type_of_service'] as $type_of_serviceimp0) {
            $type_of_serviceimp .= $type_of_serviceimp0 . " ; ";
        }
    }

    //GENERAL INFORMATION
    //$requested_by_arr = [];
    //$requested_by_arr = explode("|",$_POST['requested_by']);
    $insert = sprintf(
        "(`project_manager`,`so_number`,`customer`,`project_code`,`change_request_type`,`project_name`,`type_of_service`,`request_date`,`cr_no`,`requested_by_email`,`classification`,`impact_it`,`impact`,`scope_of_change`,`reason`, `change_request_status`, `change_request_approval_type`, `cost_impact`, `pic_leader`, `pic`, `pic_name`,`perlu_backup`,`barang_backup`,`change_request_approval_type2`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($customer, "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['cr_type'], "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($type_of_serviceimp, "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['classification'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($_POST['cost_impact'], "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    //ASSESMENT
    $insert_assesment = sprintf(
        "(`cr_no`,`technical_assesment`,`business_benefit`,`riskassesment`,`technical_assesment_pic`,`business_benefit_pic`,`risk_pic`,`pic_ket_ta`,`pic_ket_bb`,`pic_ket_ra`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ta, "text"),
        GetSQLValueString($pic_ket_bb, "text"),
        GetSQLValueString($pic_ket_ra, "text")
    );
    //IMPLEMENTATION PLAN
    $insert_implementation = sprintf(
        "(`cr_no`,`start_date`,`finish_date`) VALUES (%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );
    $DBCR->insert_data($tblname, $insert);
    $DBCR->insert_data("implementation_plans", $insert_implementation);
    $DBCR->insert_data("assesments", $insert_assesment);

    //Change Request Closing
    $insert_crc = sprintf(
        "(`cr_no`,`hasil_akhir`,`reason_request`, `reason_rejected`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($hasil_akhir, "text"),
        GetSQLValueString($reason_request, "text"),
        GetSQLValueString($reason_rejected, "text"),
    );
    $DBCR->insert_data("change_request_closing", $insert_crc);

    //Affected CI
    if (isset($_POST['serial_number'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $combine_arr[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`serial_number`,`part_number`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),

            );
            $DBCR->insert_data("affected_ci", $insert_sql);
        }
    }

    //Mandays
    if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    }

    //Others
    if (isset($_POST['others_item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    }

    //DETAIL PLAN
    if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    }

    //FALLBACK PLAN
    if (isset($_POST['working_detail_fallback'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    }

    //Prerequisite
    $insert_sql = sprintf(
        "(`cr_no`,`description`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($customer_requirement_description, "text"),
    );
    $DBCR->insert_data("prerequisites", $insert_sql);

    //PIC
    $insert = sprintf(
        "(`cr_no`, `name`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($pic_apr, "text"),
    );
    $DBCR->insert_data("master_pic", $insert);

    //Customer PIC
    if (isset($_POST['customer_pic_name']) && isset($_POST['customer_pic_position'])) {
        foreach (array_combine($_POST['customer_pic_name'], $_POST['customer_pic_position']) as $value_1 => $value_2) {
            $insert = sprintf(
                "(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value_1, "text"),
                GetSQLValueString($value_2, "date")
            );
            $DBCR->insert_data("customer_pic", $insert);
        }
    }

    //CR Reviewer
    if (isset($_POST['reviewer'])) {
        $sequence = 1;
        foreach ($_POST['reviewer'] as $key => $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value, "text"),
                GetSQLValueString($sequence, "text"),
            );
            if ($sequence == 1) {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`, `on_review`) VALUES (%s,%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                    GetSQLValueString("yes", "text"),
                );
            } else {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                );
            }
            $DBCR->insert_data("approvals", $insert_sql);

            $sequence += 1;
        }
    }

    //Change Cost Plan
    $insert_change_cost_plans = sprintf(
        "(`cr_no`, `cost_type`,`responsibility`, `sales_name`, `nomor_po`, `change_reason`, `detail_reason`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($cost_type, "text"),
        GetSQLValueString($nccr, "text"),
        GetSQLValueString($sales_name, "text"),
        GetSQLValueString($nomorpo_chargeable, "text"),
        GetSQLValueString($change_reason, "text"),
        GetSQLValueString($detail_reason, "text")
        //GetSQLValueString($_POST['used_ticket_amount'], "text"),
        //GetSQLValueString($_POST['ticket_allocation'], "text")

    );
    $DBCR->insert_data("change_cost_plans", $insert_change_cost_plans);


    //APPROVAL STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `rejected_reason`, `cr_no`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text"),
        GetSQLValueString($cr_no, "text")

    );
    $DBCR->insert_data("approval_statuses", $insert);

    //COMPLETION STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `incomplete_reason`) VALUES (%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text")
    );
    $DBCR->insert_data("completion_statuses", $insert);

    $msg1 = "<p>Saya telah membuat change request:" . "</p>";
    $to = '';
    if (isset($_POST['save_implemen'])) {
        // SUBMIT
        // $approved = 'Submited';
        $status = 'submited';
        $email = $_SESSION['Microservices_UserEmail'];
        $leader = get_leader($email, 1);
        $dleader = $leader[0];
        $qleader = $leader[1];
        $to_name = '';
        do {
            $to .= $dleader['leader_name'] . "<" . $dleader['leader_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
            $to_name = $dleader['leader_name'];
        } while ($dleader = $qleader->fetch_assoc());
        $msg1 = "<p>Dengan ini saya mengajukan change request untuk diapproval:</p>";
        $msg2 = "<p>Mohon untuk direview dan diberikan persetujuan.</p>";
        // $notes = $_POST['note_submited'];
    }

    $owner = get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    // $bcc="Syamsul Arham<syamsul@mastersystem.co.id>";
    $bcc = "";
    $reply = $from;
    $subject = "[MSIZone] " . ucwords($status) . " Change Request : " . $_POST['project_code'] . " - " . $_POST['customer'] . ' - ' . $_POST['project_name'];
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . " dan " . $pic_name[0]['employee_name'] . "</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $_POST['so_number'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Project Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['project_name'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Customer Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['customer'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Scope of Change </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['scope_of_change'] . "</td></tr>";
    $msg .= "</table>";
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=" . $_POST['cost_impact'] . "&status_approval=" . $_POST['status_approval'] . "&so_number=" . $_POST['so_number'] . "'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $mdlname1 = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname1);
    $tblname1 = "trx_notification";
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $notifmsg = $cr_no . "; " . $_POST['project_code'] . "; " . $_POST['so_number'] . "; " . $_POST['project_name'] . "; " . $customer . ";";
    $notif_link = "index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=" . $_POST['cost_impact'] . "&status_approval=" . $_POST['status_approval'] . "&so_number=" . $_POST['so_number'] . "";
    $insert1 = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " CR " . $_POST['project_code'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname1, $insert1);

    if ($perlu_backup == "Ya") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $dataToken = json_decode($response, true);
        $accessToken = $dataToken["access_token"];

        $crl = curl_init();
        $nowDate = $start_date;
        $stamp = strtotime($nowDate);
        $finalDate = $stamp * 1000;
        $Date = $finish_date;
        $stampdate = strtotime($Date);
        $final_finish = $stampdate * 1000;
        $data = array(
            "change" => array(
                "template" => array(
                    "inactive" => false,
                    "name" => "General Template",
                    "id" => "145684000000084294"
                ),
                "description" => "$barang_backup, 
                Requested by :" . $requested_name[0]['employee_name'] . "",
                "urgency" => array(
                    "name" => "Low",
                    "id" => "145684000000007923"
                ),
                "services" => [array(
                    "inactive" => false,
                    "name" => "Hardware",
                    "id" => "145684000001014121",
                    "sort_index" => 0
                )],
                "change_type" => array(
                    "color" => "#ffff66",
                    "pre_approved" => false,
                    "name" => "Minor",
                    "id" => "145684000000007955"
                ),
                "title" => "Request Barang Backup $cr_no, KP " . $_POST['project_code'] . "",
                "change_owner" => null,
                "assets" => null,
                "configuration_items" => null,
                "group" => array(
                    "deleted" => false,
                    "name" => "Service Desk",
                    "id" => "145684000000369105"
                ),
                "workflow" => array(
                    "name" => "General Change Workflow",
                    "id" => "145684000000083981"
                ),
                "change_manager" => null,
                "impact" => array(
                    "name" => "Kurang dari 24 Users",
                    "id" => "145684000000008039"
                ),
                "retrospective" => false,
                "priority" => array(
                    "color" => "#f40080",
                    "name" => "P4",
                    "id" => "145684000010971203"
                ),
                "site" => null,
                "reason_for_change" => null,
                "stage" => array(
                    "internal_name" => "submission",
                    "stage_index" => 1,
                    "name" => "Submission",
                    "id" => "145684000000083125"
                ),
                "udf_fields" => array(
                    "udf_char6" => null,
                    "udf_char7" => null,
                    "udf_char8" => null,
                    "udf_date6" => null,
                    "udf_char9" => null,
                    "udf_char1" => null,
                    "udf_char13" => null,
                    "udf_char2" => null,
                    "udf_char3" => null,
                    "udf_char4" => null,
                    "udf_char5" => null,
                    "udf_char12" => null,
                    "udf_char11" => null,
                    "udf_char10" => null,
                    "udf_date1" => null,
                    "udf_date5" => null,
                    "udf_date4" => null,
                    "udf_date3" => null,
                    "udf_date2" => null
                ),
                "comment" => "Testing",
                "risk" => array(
                    "name" => "Low",
                    "id" => "145684000000083080"
                ),
                "scheduled_start_time" => array(
                    "value" => "$finalDate"
                ),
                "scheduled_end_time" => array(
                    "value" => "$final_finish"
                ),
                "category" => null,
                "subcategory" => null,
                "status" => array(
                    "name" => "Requested",
                    "id" => "145684000000083260"
                )
            )
        );

        $postdata = json_encode($data);
        curl_setopt_array($crl, array(
            CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "input_data=$postdata",
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.manageengine.sdp.v3+json',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Zoho-Oauthtoken $accessToken",
                'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
            ),
        ));
        $hasil = curl_exec($crl);
        curl_close($crl);
        $feedback = json_decode($hasil, true);
        $comment = $feedback["change"]["display_id"]["display_value"];
        $result = preg_replace("/['']/", "", $hasil);

        $email = $_SESSION['Microservices_UserEmail'];
        $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
        $to =  "Service Desk <servicedesk@mastersystem.co.id>";
        $cc = $from;
        $bcc = "";
        $reply = $from;
        $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_no;
        $msg = "<table width='100%'";
        $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
        $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
        $msg .= "<br/>";
        $msg .= "<p>Dear Team Service Desk</p>";
        $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
        $msg .= "<p>";
        $msg .= "<table style='width:100%;'>";
        $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
        $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
        $msg .= "</table>";
        $msg .= "</p>";
        $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
        $msg .= "<p>Terimakasih,<br/>";
        $msg .= $_SESSION['Microservices_UserName'] . "</p>";
        $msg .= "</td><td width='30%' rowspan='3'>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail(
            $to,
            $subject,
            $msg,
            $headers
        )) {
            echo
            "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo
            "Email terkirim pada jam " . date("d M Y G:i:s");
        }

        //Backup
        $status = "Sudah";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($result, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    } else {
        $status = "Belum";
        $hasil = "";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($hasil, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    }

    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`project_name`=%s,`request_date`=%s,`cr_no`=%s,`requested_by`=%s,`scope_of_change`=%s,`reason`=%s,`affected_ci`=%s, `pic`=%s",
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($_POST['affected_ci'], "text"),
        GetSQLValueString($pic_apr, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
if (isset($_POST['save_it'])) {
    $cr_type;
    if ($_POST['cr_type'] == "Implementation") {
        $cr_type = "CR-IM";
    } else if ($_POST['cr_type'] == "Maintenance") {
        $cr_type = "CR-MT";
    } else if ($_POST['cr_type'] == "IT") {
        $cr_type = "CR-IT";
    }

    $db_gi = $DBCR->get_data("general_informations", "cr_no like '%$cr_type%' order by gi_id desc ");

    $number = 1;
    $fzeropadded = sprintf("%04d", $number);
    if (empty($db_gi[0])) {
        $cr_no_count = $fzeropadded;
    } else {
        $cr_no_raw = $db_gi[0]['cr_no'];
        $cr_no_arr = [];
        $cr_no_arr = explode("-", $cr_no_raw);
        $cr_no_count = sprintf("%04d", $cr_no_arr[3] + $fzeropadded);
    }
    $cr_no = date('ymd') . "-" . $cr_type . "-" . $cr_no_count;

    //GENERAL INFORMATION
    //$requested_by_arr = [];
    //$requested_by_arr = explode("|",$requested_by);
    $insert = sprintf(
        "(`project_manager`,`project_code`,`change_request_type`,`request_date`,`cr_no`,`requested_by_email`,`classification`,`impact_it`,`impact`,`scope_of_change`,`reason`, `change_request_status`, `change_request_approval_type`,`pic_leader`, `pic`, `pic_name`,`perlu_backup`,`barang_backup`,`change_request_approval_type2`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['cr_type'], "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['classification'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    //ASSESMENT
    $insert_assesment = sprintf(
        "(`cr_no`,`technical_assesment`,`business_benefit`,`riskassesment`,`technical_assesment_pic`,`business_benefit_pic`,`risk_pic`,`pic_ket_ta`,`pic_ket_bb`,`pic_ket_ra`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ta, "text"),
        GetSQLValueString($pic_ket_bb, "text"),
        GetSQLValueString($pic_ket_ra, "text")
    );
    //IMPLEMENTATION PLAN
    $insert_implementation = sprintf(
        "(`cr_no`,`start_date`,`finish_date`) VALUES (%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );
    $DBCR->insert_data($tblname, $insert);
    $DBCR->insert_data("implementation_plans", $insert_implementation);
    $DBCR->insert_data("assesments", $insert_assesment);

    //Change Request Closing
    $insert_crc = sprintf(
        "(`cr_no`,`hasil_akhir`,`reason_request`, `reason_rejected`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($hasil_akhir, "text"),
        GetSQLValueString($reason_request, "text"),
        GetSQLValueString($reason_rejected, "text"),
    );
    $DBCR->insert_data("change_request_closing", $insert_crc);

    //Risk Assesment
    // if(isset($_POST['risk_description'])){
    //     $db = $DBCR->get_data("assesments", "", "assesment_id desc");
    //     $id = $db[0]['assesment_id'];
    //     $combine_arr = array();
    //     for($i = 0; $i < count($_POST['risk_description']); $i++){
    //         $combine_arr[] = array($_POST['risk_description'][$i], $_POST['risk_mitigation'][$i], $_POST['risk_pic'][$i]);
    //     }

    //     foreach($combine_arr as $value) {
    //         $insert_sql = sprintf("(`cr_no`,`assesment_id`,`risk_description`,`risk_mitigation`, `pic`) VALUES (%s,%s,%s,%s,%s)",
    //         GetSQLValueString($cr_no, "text"),
    //         GetSQLValueString($id, "int"),
    //         GetSQLValueString($value[0], "text"),
    //         GetSQLValueString($value[1], "text"),
    //         GetSQLValueString($value[2], "text"),

    //         );
    //         $DBCR->insert_data("risk_assesments", $insert_sql);
    //     }
    // }

    //Affected CI
    if (isset($_POST['serial_number'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $combine_arr[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`serial_number`,`part_number`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),

            );
            $DBCR->insert_data("affected_ci", $insert_sql);
        }
    }

    //DETAIL PLAN
    if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    }


    //FALLBACK PLAN
    if (isset($_POST['working_detail_fallback'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    }

    //Prerequisite
    $insert_sql = sprintf(
        "(`cr_no`,`description`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($customer_requirement_description, "text"),
    );
    $DBCR->insert_data("prerequisites", $insert_sql);

    //PIC
    $insert = sprintf(
        "(`cr_no`, `name`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($pic_apr, "text"),
    );
    $DBCR->insert_data("master_pic", $insert);
    // if(isset($_POST['pic_name']) && isset($_POST['pic_position'])){
    //     foreach(array_combine($_POST['pic_name'], $_POST['pic_position']) as $value_1 => $value_2) {
    //         $insert = sprintf("(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
    //         GetSQLValueString($cr_no, "text"),
    //         GetSQLValueString($value_1, "text"),
    //         GetSQLValueString($value_2, "date")
    //         );
    //         $DBCR->insert_data("master_pic", $insert);
    //     }
    // }

    //CR Reviewer
    if (isset($_POST['reviewer'])) {
        $sequence = 1;
        foreach ($_POST['reviewer'] as $key => $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value, "text"),
                GetSQLValueString($sequence, "text"),
            );
            if ($sequence == 1) {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`, `on_review`) VALUES (%s,%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                    GetSQLValueString("yes", "text"),
                );
            } else {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                );
            }
            $DBCR->insert_data("approvals", $insert_sql);

            $sequence += 1;
        }
    }

    //APPROVAL STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `rejected_reason`, `cr_no`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text"),
        GetSQLValueString($cr_no, "text")

    );
    $DBCR->insert_data("approval_statuses", $insert);

    //COMPLETION STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `incomplete_reason`) VALUES (%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text")
    );
    $DBCR->insert_data("completion_statuses", $insert);

    $msg1 = "<p>Saya telah membuat change request:" . "</p>";
    $to = '';
    if (isset($_POST['save_it'])) {
        // SUBMIT
        // $approved = 'Submited';
        $status = 'submited';
        $email = $_SESSION['Microservices_UserEmail'];
        $leader = get_leader($email, 1);
        $dleader = $leader[0];
        $qleader = $leader[1];
        $to_name = '';
        do {
            $to .= $dleader['leader_name'] . "<" . $dleader['leader_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
            $to_name = $dleader['leader_name'];
        } while ($dleader = $qleader->fetch_assoc());
        $msg1 = "<p>Dengan ini saya mengajukan change request untuk diapproval:</p>";
        $msg2 = "<p>Mohon untuk direview dan diberikan persetujuan.</p>";
        // $notes = $_POST['note_submited'];
    } elseif (isset($_POST['change_request_approval_type'])) {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $dcreateby['modified_by'];
        $owner = get_leader($email, 1);
        $downer = $owner[0];
        $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
        $to_name = $downer['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $reason_request . "</p>";
        $notes = $reason_request;
    }

    $owner = get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    // $bcc="Syamsul Arham<syamsul@mastersystem.co.id>";
    $bcc = "";
    $reply = $from;
    $subject = "[MSIZone] " . ucwords($status) . " Change Request : " . $_POST['project_code'];
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . " dan " . $pic_name[0]['employee_name'] . "</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Scope of Change </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['scope_of_change'] . "</td></tr>";
    $msg .= "</table>";
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $mdlname1 = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname1);
    $tblname1 = "trx_notification";
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $notifmsg = $cr_no . "; " . $_POST['project_code'] . "; " . $_POST['project_name'] . "; ";
    $notif_link = "index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=" . $_POST['cost_impact'] . "&status_approval=" . $_POST['status_approval'] . "&so_number=" . "";
    $insert1 = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " CR " . $_POST['project_code'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname1, $insert1);

    if ($perlu_backup == "Ya") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $dataToken = json_decode($response, true);
        $accessToken = $dataToken["access_token"];

        $crl = curl_init();
        $nowDate = $start_date;
        $stamp = strtotime($nowDate);
        $finalDate = $stamp * 1000;
        $Date = $finish_date;
        $stampdate = strtotime($Date);
        $final_finish = $stampdate * 1000;
        $data = array(
            "change" => array(
                "template" => array(
                    "inactive" => false,
                    "name" => "General Template",
                    "id" => "145684000000084294"
                ),
                "description" => "$barang_backup, 
                Requested by :" . $requested_name[0]['employee_name'] . "",
                "urgency" => array(
                    "name" => "Low",
                    "id" => "145684000000007923"
                ),
                "services" => [array(
                    "inactive" => false,
                    "name" => "Hardware",
                    "id" => "145684000001014121",
                    "sort_index" => 0
                )],
                "change_type" => array(
                    "color" => "#ffff66",
                    "pre_approved" => false,
                    "name" => "Minor",
                    "id" => "145684000000007955"
                ),
                "title" => "Request Barang Backup $cr_no, KP " . $_POST['project_code'] . "",
                "change_owner" => null,
                "assets" => null,
                "configuration_items" => null,
                "group" => array(
                    "deleted" => false,
                    "name" => "Service Desk",
                    "id" => "145684000000369105"
                ),
                "workflow" => array(
                    "name" => "General Change Workflow",
                    "id" => "145684000000083981"
                ),
                "change_manager" => null,
                "impact" => array(
                    "name" => "Kurang dari 24 Users",
                    "id" => "145684000000008039"
                ),
                "retrospective" => false,
                "priority" => array(
                    "color" => "#f40080",
                    "name" => "P4",
                    "id" => "145684000010971203"
                ),
                "site" => null,
                "reason_for_change" => null,
                "stage" => array(
                    "internal_name" => "submission",
                    "stage_index" => 1,
                    "name" => "Submission",
                    "id" => "145684000000083125"
                ),
                "udf_fields" => array(
                    "udf_char6" => null,
                    "udf_char7" => null,
                    "udf_char8" => null,
                    "udf_date6" => null,
                    "udf_char9" => null,
                    "udf_char1" => null,
                    "udf_char13" => null,
                    "udf_char2" => null,
                    "udf_char3" => null,
                    "udf_char4" => null,
                    "udf_char5" => null,
                    "udf_char12" => null,
                    "udf_char11" => null,
                    "udf_char10" => null,
                    "udf_date1" => null,
                    "udf_date5" => null,
                    "udf_date4" => null,
                    "udf_date3" => null,
                    "udf_date2" => null
                ),
                "comment" => "Testing",
                "risk" => array(
                    "name" => "Low",
                    "id" => "145684000000083080"
                ),
                "scheduled_start_time" => array(
                    "value" => "$finalDate"
                ),
                "scheduled_end_time" => array(
                    "value" => "$final_finish"
                ),
                "category" => null,
                "subcategory" => null,
                "status" => array(
                    "name" => "Requested",
                    "id" => "145684000000083260"
                )
            )
        );

        $postdata = json_encode($data);
        curl_setopt_array($crl, array(
            CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "input_data=$postdata",
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.manageengine.sdp.v3+json',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Zoho-Oauthtoken $accessToken",
                'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
            ),
        ));
        $hasil = curl_exec($crl);
        curl_close($crl);
        $feedback = json_decode($hasil, true);
        $comment = $feedback["change"]["display_id"]["display_value"];
        $result = preg_replace("/['']/", "", $hasil);

        $email = $_SESSION['Microservices_UserEmail'];
        $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
        $to =  "Service Desk <servicedesk@mastersystem.co.id>";
        $cc = $from;
        $bcc = "";
        $reply = $from;
        $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_no;
        $msg = "<table width='100%'";
        $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
        $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
        $msg .= "<br/>";
        $msg .= "<p>Dear Team Service Desk</p>";
        $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
        $msg .= "<p>";
        $msg .= "<table style='width:100%;'>";
        $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
        $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
        $msg .= "</table>";
        $msg .= "</p>";
        $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
        $msg .= "<p>Terimakasih,<br/>";
        $msg .= $_SESSION['Microservices_UserName'] . "</p>";
        $msg .= "</td><td width='30%' rowspan='3'>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail(
            $to,
            $subject,
            $msg,
            $headers
        )) {
            echo
            "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo
            "Email terkirim pada jam " . date("d M Y G:i:s");
        }

        //Backup
        $status = "Sudah";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($result, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    } else {
        $status = "Belum";
        $hasil = "";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($hasil, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    }

    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`project_name`=%s,`request_date`=%s,`cr_no`=%s,`requested_by`=%s,`scope_of_change`=%s,`reason`=%s,`affected_ci`=%s, `pic`=%s",
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($_POST['affected_ci'], "text"),
        GetSQLValueString($pic_apr, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $ALERT->savedata();
} elseif (isset($_POST['save_maintenance'])) {
    $cr_type;
    if ($_POST['cr_type'] == "Implementation") {
        $cr_type = "CR-IM";
    } else if ($_POST['cr_type'] == "Maintenance") {
        $cr_type = "CR-MT";
    } else if ($_POST['cr_type'] == "IT") {
        $cr_type = "CR-IT";
    }

    $db_gi = $DBCR->get_data("general_informations", "cr_no like '%$cr_type%' order by gi_id desc ");

    $number = 1;
    $fzeropadded = sprintf("%04d", $number);
    if (empty($db_gi[0])) {
        $cr_no_count = $fzeropadded;
    } else {
        $cr_no_raw = $db_gi[0]['cr_no'];
        $cr_no_arr = [];
        $cr_no_arr = explode("-", $cr_no_raw);
        $cr_no_count = sprintf("%04d", $cr_no_arr[3] + $fzeropadded);
    }
    $cr_no = date('ymd') . "-" . $cr_type . "-" . $cr_no_count;

    //GENERAL INFORMATION
    //$requested_by_arr = [];
    //$requested_by_arr = explode("|",$requested_by);
    $insert = sprintf(
        "(`project_manager`,`customer`,`project_code`, `so_number`, `change_request_type`,`project_name`,`type_of_service`,`request_date`,`cr_no`,`requested_by_email`,`classification`,`impact_it`,`impact`,`scope_of_change`,`reason`, `change_request_status`, `change_request_approval_type`, `cost_impact`, `pic_leader`, `pic`, `pic_name`,`perlu_backup`,`barang_backup`,`change_request_approval_type2`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($_POST['customer'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['so_number'], "text"),
        GetSQLValueString($_POST['cr_type'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($type_of_service, "text"),
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($_POST['classification'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($_POST['cost_impact'], "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    //ASSESMENT
    $insert_assesment = sprintf(
        "(`cr_no`,`technical_assesment`,`business_benefit`,`riskassesment`,`technical_assesment_pic`,`business_benefit_pic`,`risk_pic`,`pic_ket_ra`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ra, "text")
    );
    //IMPLEMENTATION PLAN
    $insert_implementation = sprintf(
        "(`cr_no`,`start_date`,`finish_date`) VALUES (%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );
    $DBCR->insert_data($tblname, $insert);
    $DBCR->insert_data("implementation_plans", $insert_implementation);
    $DBCR->insert_data("assesments", $insert_assesment);

    //Change Request Closing
    $insert_crc = sprintf(
        "(`cr_no`,`hasil_akhir`,`reason_request`, `reason_rejected`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($hasil_akhir, "text"),
        GetSQLValueString($reason_request, "text"),
        GetSQLValueString($reason_rejected, "text"),
    );
    $DBCR->insert_data("change_request_closing", $insert_crc);

    //Risk Assesment
    // if(isset($_POST['risk_description'])){
    //     $db = $DBCR->get_data("assesments", "", "assesment_id desc");
    //     $id = $db[0]['assesment_id'];
    //     $combine_arr = array();
    //     for($i = 0; $i < count($_POST['risk_description']); $i++){
    //         $combine_arr[] = array($_POST['risk_description'][$i], $_POST['risk_mitigation'][$i], $_POST['risk_pic'][$i]);
    //     }

    //     foreach($combine_arr as $value) {
    //         $insert_sql = sprintf("(`cr_no`,`assesment_id`,`risk_description`,`risk_mitigation`, `pic`) VALUES (%s,%s,%s,%s,%s)",
    //         GetSQLValueString($cr_no, "text"),
    //         GetSQLValueString($id, "int"),
    //         GetSQLValueString($value[0], "text"),
    //         GetSQLValueString($value[1], "text"),
    //         GetSQLValueString($value[2], "text"),

    //         );
    //         $DBCR->insert_data("risk_assesments", $insert_sql);
    //     }
    // }

    //Affected CI
    if (isset($_POST['serial_number'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $combine_arr[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`serial_number`,`part_number`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),

            );
            $DBCR->insert_data("affected_ci", $insert_sql);
        }
    }

    //Mandays
    if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    }

    //Others
    if (isset($_POST['others_item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    }

    //DETAIL PLAN
    if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    }


    //FALLBACK PLAN
    if (isset($_POST['working_detail_fallback'])) {
        $db = $DBCR->get_data("implementation_plans", "", "ip_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($cr_no, "text"),

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    }

    //Prerequisite
    $insert_sql = sprintf(
        "(`cr_no`,`description`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($customer_requirement_description, "text"),
    );
    $DBCR->insert_data("prerequisites", $insert_sql);

    //PIC
    $insert = sprintf(
        "(`cr_no`, `name`) VALUES (%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($pic_apr, "text"),
    );
    $DBCR->insert_data("master_pic", $insert);
    // if(isset($_POST['pic_name']) && isset($_POST['pic_position'])){
    //     foreach(array_combine($_POST['pic_name'], $_POST['pic_position']) as $value_1 => $value_2) {
    //         $insert = sprintf("(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
    //         GetSQLValueString($cr_no, "text"),
    //         GetSQLValueString($value_1, "text"),
    //         GetSQLValueString($value_2, "date")
    //         );
    //         $DBCR->insert_data("master_pic", $insert);
    //     }
    // }

    //Customer PIC
    if (isset($_POST['customer_pic_name']) && isset($_POST['customer_pic_position'])) {
        foreach (array_combine($_POST['customer_pic_name'], $_POST['customer_pic_position']) as $value_1 => $value_2) {
            $insert = sprintf(
                "(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value_1, "text"),
                GetSQLValueString($value_2, "date")
            );
            $DBCR->insert_data("customer_pic", $insert);
        }
    }

    //CR Reviewer
    if (isset($_POST['reviewer'])) {
        $sequence = 1;
        foreach ($_POST['reviewer'] as $key => $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                GetSQLValueString($cr_no, "text"),
                GetSQLValueString($value, "text"),
                GetSQLValueString($sequence, "text"),
            );
            if ($sequence == 1) {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`, `on_review`) VALUES (%s,%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                    GetSQLValueString("yes", "text"),
                );
            } else {
                $insert_sql = sprintf(
                    "(`cr_no`,`reviewer_email`, `sequence`) VALUES (%s,%s,%s)",
                    GetSQLValueString($cr_no, "text"),
                    GetSQLValueString($value, "text"),
                    GetSQLValueString($sequence, "text"),
                );
            }
            $DBCR->insert_data("approvals", $insert_sql);

            $sequence += 1;
        }
    }

    //Change Cost Plan
    $insert_change_cost_plans = sprintf(
        "(`cr_no`, `cost_type`,`responsibility`, `sales_name`, `nomor_po`, `change_reason`, `detail_reason`, `used_ticket`, `ticket_allocation`, `ticket_allocation_sisa`, `project_code`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($cr_no, "text"),
        GetSQLValueString($cost_type, "text"),
        GetSQLValueString($nccr, "text"),
        GetSQLValueString($sales_name, "text"),
        GetSQLValueString($nomorpo_chargeable, "text"),
        GetSQLValueString($change_reason, "text"),
        GetSQLValueString($detail_reason, "text"),
        GetSQLValueString($used_ticket_amount, "text"),
        GetSQLValueString($ticket_allocation, "text"),
        GetSQLValueString($sisaticket, "text"),
        GetSQLValueString($_POST['project_code'], "text")

    );
    $DBCR->insert_data("change_cost_plans", $insert_change_cost_plans);

    //APPROVAL STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `rejected_reason`, `cr_no`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text"),
        GetSQLValueString($cr_no, "text")

    );
    $DBCR->insert_data("approval_statuses", $insert);

    //COMPLETION STATUS
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $insert = sprintf(
        "(`gi_id`, `status`, `incomplete_reason`) VALUES (%s,%s,%s)",
        GetSQLValueString($gi_id, "int"),
        GetSQLValueString(null, "text"),
        GetSQLValueString(null, "text")
    );
    $DBCR->insert_data("completion_statuses", $insert);

    $msg1 = "<p>Saya telah membuat change request:" . "</p>";
    $to = '';
    if (isset($_POST['save_maintenance'])) {
        // SUBMIT
        // $approved = 'Submited';
        $status = 'submited';
        $email = $_SESSION['Microservices_UserEmail'];
        $leader = get_leader($email, 1);
        $dleader = $leader[0];
        $qleader = $leader[1];
        $to_name = '';
        do {
            $to .= $dleader['leader_name'] . "<" . $dleader['leader_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
            $to_name = $dleader['leader_name'];
        } while ($dleader = $qleader->fetch_assoc());
        $msg1 = "<p>Dengan ini saya mengajukan change request untuk diapproval:</p>";
        $msg2 = "<p>Mohon untuk direview dan diberikan persetujuan.</p>";
        // $notes = $_POST['note_submited'];
    } elseif (isset($_POST['change_request_approval_type'])) {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $dcreateby['modified_by'];
        $owner = get_leader($email, 1);
        $downer = $owner[0];
        $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">," . $pic_name[0]['employee_name'] . "<" . $pic_apr2 . ">;";
        $to_name = $downer['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $_POST['note_approved'] . "</p>";
        $notes = $_POST['note_approved'];
    }

    $owner = get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    // $bcc="Syamsul Arham<syamsul@mastersystem.co.id>";
    $bcc = "";
    $reply = $from;
    $subject = "[MSIZone] " . ucwords($status) . " Change Request : " . $_POST['project_code'] . " - " . $_POST['customer'] . ' - ' . $_POST['project_name'];
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . " dan " . $pic_name[0]['employee_name'] . "</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $_POST['so_number'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Project Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['project_name'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Customer Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['customer'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Scope of Change </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['scope_of_change'] . "</td></tr>";
    $msg .= "</table>";
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=" . $_POST['cost_impact'] . "&status_approval=" . $_POST['status_approval'] . "&so_number=" . $_POST['so_number'] . "'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $mdlname1 = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname1);
    $tblname1 = "trx_notification";
    $gi_id_db = $DBCR->get_data("general_informations", "", "gi_id desc");
    $gi_id = $gi_id_db[0]['gi_id'];
    $notifmsg = $cr_no . "; " . $_POST['project_code'] . "; " . $_POST['so_number'] . "; " . $_POST['project_name'] . "; " . $customer . ";";
    $notif_link = "index.php?mod=change_request&act=edit&gi_id=" . $gi_id . "&submit=Submit&type=" . $_POST['cr_type'] . "&cr_no=" . $cr_no . "&project_code=" . $_POST['project_code'] . "&costimpact=" . $_POST['cost_impact'] . "&status_approval=" . $_POST['status_approval'] . "&so_number=" . $_POST['so_number'] . "";
    $insert1 = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($requested_by, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " CR " . $_POST['project_code'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname1, $insert1);

    if ($perlu_backup == "Ya") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $dataToken = json_decode($response, true);
        $accessToken = $dataToken["access_token"];

        $crl = curl_init();
        $nowDate = $start_date;
        $stamp = strtotime($nowDate);
        $finalDate = $stamp * 1000;
        $Date = $finish_date;
        $stampdate = strtotime($Date);
        $final_finish = $stampdate * 1000;
        $data = array(
            "change" => array(
                "template" => array(
                    "inactive" => false,
                    "name" => "General Template",
                    "id" => "145684000000084294"
                ),
                "description" => "$barang_backup, 
                Requested by :" . $requested_name[0]['employee_name'] . "",
                "urgency" => array(
                    "name" => "Low",
                    "id" => "145684000000007923"
                ),
                "services" => [array(
                    "inactive" => false,
                    "name" => "Hardware",
                    "id" => "145684000001014121",
                    "sort_index" => 0
                )],
                "change_type" => array(
                    "color" => "#ffff66",
                    "pre_approved" => false,
                    "name" => "Minor",
                    "id" => "145684000000007955"
                ),
                "title" => "Request Barang Backup $cr_no, KP " . $_POST['project_code'] . "",
                "change_owner" => null,
                "assets" => null,
                "configuration_items" => null,
                "group" => array(
                    "deleted" => false,
                    "name" => "Service Desk",
                    "id" => "145684000000369105"
                ),
                "workflow" => array(
                    "name" => "General Change Workflow",
                    "id" => "145684000000083981"
                ),
                "change_manager" => null,
                "impact" => array(
                    "name" => "Kurang dari 24 Users",
                    "id" => "145684000000008039"
                ),
                "retrospective" => false,
                "priority" => array(
                    "color" => "#f40080",
                    "name" => "P4",
                    "id" => "145684000010971203"
                ),
                "site" => null,
                "reason_for_change" => null,
                "stage" => array(
                    "internal_name" => "submission",
                    "stage_index" => 1,
                    "name" => "Submission",
                    "id" => "145684000000083125"
                ),
                "udf_fields" => array(
                    "udf_char6" => null,
                    "udf_char7" => null,
                    "udf_char8" => null,
                    "udf_date6" => null,
                    "udf_char9" => null,
                    "udf_char1" => null,
                    "udf_char13" => null,
                    "udf_char2" => null,
                    "udf_char3" => null,
                    "udf_char4" => null,
                    "udf_char5" => null,
                    "udf_char12" => null,
                    "udf_char11" => null,
                    "udf_char10" => null,
                    "udf_date1" => null,
                    "udf_date5" => null,
                    "udf_date4" => null,
                    "udf_date3" => null,
                    "udf_date2" => null
                ),
                "comment" => "Testing",
                "risk" => array(
                    "name" => "Low",
                    "id" => "145684000000083080"
                ),
                "scheduled_start_time" => array(
                    "value" => "$finalDate"
                ),
                "scheduled_end_time" => array(
                    "value" => "$final_finish"
                ),
                "category" => null,
                "subcategory" => null,
                "status" => array(
                    "name" => "Requested",
                    "id" => "145684000000083260"
                )
            )
        );

        $postdata = json_encode($data);
        curl_setopt_array($crl, array(
            CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "input_data=$postdata",
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.manageengine.sdp.v3+json',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Zoho-Oauthtoken $accessToken",
                'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
            ),
        ));
        $hasil = curl_exec($crl);
        curl_close($crl);
        $feedback = json_decode($hasil, true);
        $comment = $feedback["change"]["display_id"]["display_value"];
        $result = preg_replace("/['']/", "", $hasil);

        $email = $_SESSION['Microservices_UserEmail'];
        $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
        $to =  "Service Desk <servicedesk@mastersystem.co.id>";
        $cc = $from;
        $bcc = "";
        $reply = $from;
        $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_no;
        $msg = "<table width='100%'";
        $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
        $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
        $msg .= "<br/>";
        $msg .= "<p>Dear Team Service Desk</p>";
        $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
        $msg .= "<p>";
        $msg .= "<table style='width:100%;'>";
        $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_no . "</td></tr>";
        $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
        $msg .= "</table>";
        $msg .= "</p>";
        $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
        $msg .= "<p>Terimakasih,<br/>";
        $msg .= $_SESSION['Microservices_UserName'] . "</p>";
        $msg .= "</td><td width='30%' rowspan='3'>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail(
            $to,
            $subject,
            $msg,
            $headers
        )) {
            echo
            "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo
            "Email terkirim pada jam " . date("d M Y G:i:s");
        }

        //Backup
        $status = "Sudah";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($result, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    } else {
        $status = "Belum";
        $hasil = "";
        $insert_backup = sprintf(
            "(`output_json`, `cr_no`, `status`) VALUES (%s,%s,%s)",
            GetSQLValueString($hasil, "text"),
            GetSQLValueString($cr_no, "text"),
            GetSQLValueString($status, "text")

        );
        $DBCR->insert_data("backup", $insert_backup);
    }


    $ALERT->savedata();
} else if (isset($_POST['update_imp'])) {

    $type_of_serviceimp = "";
    if (isset($_POST['type_of_service'])) {
        foreach ($_POST['type_of_service'] as $type_of_serviceimp0) {
            $type_of_serviceimp .= $type_of_serviceimp0 . " ; ";
        }
    }

    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`request_date`=%s,`cr_no`=%s,`scope_of_change`=%s,`reason`=%s,`type_of_service`=%s,`project_manager`=%s,`impact_it`=%s,`impact`=%s,`change_request_status`=%s, `change_request_approval_type`=%s, `pic_leader`=%s, `pic`=%s, `pic_name`=%s, `perlu_backup`=%s, `barang_backup`=%s, `change_request_approval_type2`=%s",
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($type_of_serviceimp, "text"),
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    $condition_ass = "cr_no='" . $_POST['cr_no'] . "'";
    $update_assesment = sprintf(
        "`cr_no`=%s,`riskassesment`=%s,`risk_pic`=%s,`pic_ket_ta`=%s,`pic_ket_bb`=%s,`pic_ket_ra`=%s,`technical_assesment`=%s,`business_benefit`=%s,`technical_assesment_pic`=%s,`business_benefit_pic`=%s",
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ta, "text"),
        GetSQLValueString($pic_ket_bb, "text"),
        GetSQLValueString($pic_ket_ra, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text")
    );

    $data12 = $DBCR->get_data("mandays", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data12[0]['type_of_resources'])) {
        $combine_arr_mandays = array();
        for ($i = 0; $i < count($_POST['mandays_tor']); $i++) {
            $condition_mandays = "mandays_id='" . $_POST['mandays_id'][$i] . "'";
            $combine_arr_mandays[] = array($_POST['mandays_tor'][$i], $_POST['mandays_tm'][$i], $_POST['mandays_value'][$i]);
            foreach ($combine_arr_mandays as $value) {
                $update_mandays = sprintf(
                    "`type_of_resources`=%s,`mandays_total`=%s, `mandays_value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname4, $update_mandays, $condition_mandays);
            }
        }
    } else if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    } else {
        '';
    }

    $data11 = $DBCR->get_data("financial_others", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data11[0]['item'])) {
        $combine_arr_others = array();
        for ($i = 0; $i < count($_POST['others_item']); $i++) {
            $condition_others = "fo_id='" . $_POST['fo_id'][$i] . "'";
            $combine_arr_others[] = array($_POST['others_item'][$i], $_POST['others_detail'][$i], $_POST['others_price'][$i]);
            foreach ($combine_arr_others as $value) {
                $update_others = sprintf(
                    "`item`=%s,`detail_item`=%s,`value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname5, $update_others, $condition_others);
            }
        }
    } else if (isset($_POST['others_item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    } else {
        '';
    }

    $condition_ccp = "cr_no='" . $_POST['cr_no'] . "'";
    $update_ccp = sprintf(
        "`cost_type`=%s,`responsibility`=%s, `nomor_po`=%s, `change_reason`=%s, `detail_reason`=%s",
        GetSQLValueString($cost_type, "text"),
        GetSQLValueString($nccr, "text"),
        GetSQLValueString($nomorpo_chargeable, "text"),
        GetSQLValueString($change_reason, "text"),
        GetSQLValueString($detail_reason, "text")
    );
    $condition_implementation = "cr_no='" . $_POST['cr_no'] . "'";
    $update_implementation = sprintf(
        "`start_date`=%s,`finish_date`=%s",
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );

    // $detailplancek = ;
    $data4 = $DBCR->get_data("detail_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data4[0]['working_detail'])) {
        $combine_arr_detailplan = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $condition_detailplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_detailplan[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
            foreach ($combine_arr_detailplan as $value) {
                $update_detailplan = sprintf(
                    "`working_detail`=%s,`time`=%s, `finish_time`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "date"),
                    GetSQLValueString($value[2], "date"),
                    GetSQLValueString($value[3], "text"),
                );
                $res = $DBCR->update_data($tblname8, $update_detailplan, $condition_detailplan);
            }
        }
    } else if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("detail_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($_POST['cr_no'], "text")
            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    } else {
        "";
    }

    $data5 = $DBCR->get_data("fallback_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data5[0]['working_detail'])) {
        $combine_arr_fallbackplan = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $condition_fallbackplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_fallbackplan[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
            foreach ($combine_arr_fallbackplan as $value) {
                $update_fallbackplan = sprintf(
                    "`working_detail`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                );
                $res = $DBCR->update_data($tblname9, $update_fallbackplan, $condition_fallbackplan);
            }
        }
    } else if (isset($_POST['working_detail_fallback'])) {
        $db = $DBCR->get_data("fallback_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($_POST['cr_no'], "text")

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    } else {
        '';
    }

    $condition_prerequisite = "cr_no='" . $_POST['cr_no'] . "'";
    $update_prerequisite = sprintf("");
    $update_prerequisite = sprintf(
        "`description`=%s",
        GetSQLValueString($customer_requirement_description, "text")
    );
    $res = $DBCR->update_data($tblname10, $update_prerequisite, $condition_prerequisite);

    $condition_mpic = "cr_no='" . $_POST['cr_no'] . "'";
    $update_mpic = sprintf("");
    $update_mpic = sprintf(
        "`name`=%s",
        GetSQLValueString($_POST['pic_apr'], "text"),
    );

    $data9 = $DBCR->get_data("customer_pic", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data9[0]['name'])) {
        $combine_arr_cpic = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $condition_cpic = "cp_id='" . $_POST['cp_id'][$i] . "'";
            $combine_arr_cpic[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
            foreach ($combine_arr_cpic as $value) {
                $update_cpic = sprintf(
                    "`name`=%s,`position`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text")

                );
                $res = $DBCR->update_data($tblname12, $update_cpic, $condition_cpic);
            }
        }
    } else if (isset($_POST['customer_pic_name'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $combine_arr[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
        }
        foreach ($combine_arr as $value) {
            $insert = sprintf(
                "(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date")
            );
            $DBCR->insert_data("customer_pic", $insert);
        }
    } else {
        '';
    }

    $condition_crc = "cr_no='" . $_POST['cr_no'] . "'";
    $update_crc = sprintf(
        "`reason_request`=%s",
        GetSQLValueString($reason_request, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $res = $DBCR->update_data($tblname3, $update_assesment, $condition_ass);
    $res = $DBCR->update_data($tblname6, $update_ccp, $condition_ccp);
    $res = $DBCR->update_data($tblname7, $update_implementation, $condition_implementation);
    $res = $DBCR->update_data($tblname11, $update_mpic, $condition_mpic);
    $res = $DBCR->update_data($tblname13, $update_crc, $condition_crc);

    $msg1 = "<p>Saya telah approved change request:" . "</p>";
    $to = '';
    $checkapproval = isset($_POST['status_approval']);
    if ($checkapproval == null) {
        '';
    } else {
        $checkapproval = $_POST['status_approval'];
    }
    if ($checkapproval == 'submission_approved') {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $_SESSION['Microservices_UserEmail'];
        $owner = $_SESSION['Microservices_UserEmail'];
        $downer = $_POST['requested_by'];
        $to = $requested_name[0]['employee_name'] . "<" . $_POST['requested_by'] . ">;";
        $to_name = $requested_name[0]['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $reason_request . "</p>";
        $notes = $reason_request;
        if ($email == $downer) {
            '';
        } else {
            $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] " . ucwords($status) . " Change Request : CR Number = " . $_POST['cr_no'];
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear " . $to_name . "</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $_POST['cr_no'] . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p>" . $msg2 . "</p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }
        }
    } else {
        '';
    }

    $databackup = $DBCR->get_data("backup", "cr_no='" . $_POST['cr_no'] . "'");
    $cekbackup = isset($databackup[0]['status']);
    if ($cekbackup == null) {
        '';
    } else if ($cekbackup == "Sudah") {
        '';
    } else if ($cekbackup == "Belum") {
        $cr_number = $databackup[0]['cr_no'];
        $idbackup = $databackup[0]['id'];
        if ($perlu_backup == "Ya" && $cekbackup == "Belum") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $dataToken = json_decode($response, true);
            $accessToken = $dataToken["access_token"];

            $crl = curl_init();
            $nowDate = $start_date;
            $stamp = strtotime($nowDate);
            $finalDate = $stamp * 1000;
            $Date = $finish_date;
            $stampdate = strtotime($Date);
            $final_finish = $stampdate * 1000;
            $data = array(
                "change" => array(
                    "template" => array(
                        "inactive" => false,
                        "name" => "General Template",
                        "id" => "145684000000084294"
                    ),
                    "description" => "$barang_backup, Requested By : " .  $requested_name[0]['employee_name'] . "",
                    "urgency" => array(
                        "name" => "Low",
                        "id" => "145684000000007923"
                    ),
                    "services" => [array(
                        "inactive" => false,
                        "name" => "Hardware",
                        "id" => "145684000001014121",
                        "sort_index" => 0
                    )],
                    "change_type" => array(
                        "color" => "#ffff66",
                        "pre_approved" => false,
                        "name" => "Minor",
                        "id" => "145684000000007955"
                    ),
                    "title" => "Request Barang Backup $cr_number, KP " . $_POST['project_code'] . "",
                    "change_owner" => null,
                    "assets" => null,
                    "configuration_items" => null,
                    "group" => array(
                        "deleted" => false,
                        "name" => "Service Desk",
                        "id" => "145684000000369105"
                    ),
                    "workflow" => array(
                        "name" => "General Change Workflow",
                        "id" => "145684000000083981"
                    ),
                    "change_manager" => null,
                    "impact" => array(
                        "name" => "Kurang dari 24 Users",
                        "id" => "145684000000008039"
                    ),
                    "retrospective" => false,
                    "priority" => array(
                        "color" => "#f40080",
                        "name" => "P4",
                        "id" => "145684000010971203"
                    ),
                    "site" => null,
                    "reason_for_change" => null,
                    "stage" => array(
                        "internal_name" => "submission",
                        "stage_index" => 1,
                        "name" => "Submission",
                        "id" => "145684000000083125"
                    ),
                    "udf_fields" => array(
                        "udf_char6" => null,
                        "udf_char7" => null,
                        "udf_char8" => null,
                        "udf_date6" => null,
                        "udf_char9" => null,
                        "udf_char1" => null,
                        "udf_char13" => null,
                        "udf_char2" => null,
                        "udf_char3" => null,
                        "udf_char4" => null,
                        "udf_char5" => null,
                        "udf_char12" => null,
                        "udf_char11" => null,
                        "udf_char10" => null,
                        "udf_date1" => null,
                        "udf_date5" => null,
                        "udf_date4" => null,
                        "udf_date3" => null,
                        "udf_date2" => null
                    ),
                    "risk" => array(
                        "name" => "Low",
                        "id" => "145684000000083080"
                    ),
                    "scheduled_start_time" => array(
                        "value" => "$finalDate"
                    ),
                    "scheduled_end_time" => array(
                        "value" => "$final_finish"
                    ),
                    "category" => null,
                    "subcategory" => null,
                    "status" => array(
                        "name" => "Requested",
                        "id" => "145684000000083260"
                    )
                )
            );

            $postdata = json_encode($data);
            curl_setopt_array($crl, array(
                CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "input_data=$postdata",
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/vnd.manageengine.sdp.v3+json',
                    'Content-Type: application/x-www-form-urlencoded',
                    "Authorization: Zoho-Oauthtoken $accessToken",
                    'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
                ),
            ));
            $hasil = curl_exec($crl);
            curl_close($crl);
            $feedback = json_decode($hasil, true);
            $comment = $feedback["change"]["display_id"]["display_value"];
            $result = preg_replace("/['']/", "", $hasil);

            $email = $_SESSION['Microservices_UserEmail'];
            $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
            $to =  "Service Desk <servicedesk@mastersystem.co.id>";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_number;
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear Team Service Desk</p>";
            $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_number . "</td></tr>";
            $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }

            //Backup
            $status = "Sudah";
            $condition_backup = "id = $idbackup";
            $update_backup = sprintf(
                "`output_json`=%s, `status`=%s",
                GetSQLValueString($result, "text"),
                GetSQLValueString($status, "text")

            );
            $DBCR->update_data($tblname17, $update_backup, $condition_backup);
        }
    }

    $ALERT->savedata();
} else if (isset($_POST['update_mt'])) {

    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`request_date`=%s,`cr_no`=%s,`scope_of_change`=%s,`reason`=%s,`type_of_service`=%s,`project_manager`=%s,`impact_it`=%s,`impact`=%s,`change_request_status`=%s, `change_request_approval_type`=%s, `pic_leader`=%s, `pic`=%s, `pic_name`=%s, `perlu_backup`=%s, `barang_backup`=%s, `change_request_approval_type2`=%s",
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($type_of_service, "text"),
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    $data15 = $DBCR->get_data("affected_ci", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data15[0]['serial_number'])) {
        $combine_arr_ac = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $condition_ac = "ac_id='" . $_POST['ac_id'][$i] . "'";
            $combine_arr_ac[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
            foreach ($combine_arr_ac as $value) {
                $update_ac = sprintf(
                    "`serial_number`=%s,`part_number`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                );
                $res = $DBCR->update_data($tblname14, $update_ac, $condition_ac);
            }
        }
    } else if (isset($_POST['serial_number'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $combine_arr[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`serial_number`,`part_number`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text")

            );
            $DBCR->insert_data("affected_ci", $insert_sql);
        }
    } else {
        '';
    }

    $condition_ass = "cr_no='" . $_POST['cr_no'] . "'";
    $update_assesment = sprintf(
        "`cr_no`=%s,`riskassesment`=%s,`risk_pic`=%s,`pic_ket_ta`=%s,`pic_ket_bb`=%s,`pic_ket_ra`=%s,`technical_assesment`=%s,`business_benefit`=%s,`technical_assesment_pic`=%s,`business_benefit_pic`=%s",
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ta, "text"),
        GetSQLValueString($pic_ket_bb, "text"),
        GetSQLValueString($pic_ket_ra, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text")
    );

    $data12 = $DBCR->get_data("mandays", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data12[0]['type_of_resources'])) {
        $combine_arr_mandays = array();
        for ($i = 0; $i < count($_POST['mandays_tor']); $i++) {
            $condition_mandays = "mandays_id='" . $_POST['mandays_id'][$i] . "'";
            $combine_arr_mandays[] = array($_POST['mandays_tor'][$i], $_POST['mandays_tm'][$i], $_POST['mandays_value'][$i]);
            foreach ($combine_arr_mandays as $value) {
                $update_mandays = sprintf(
                    "`type_of_resources`=%s,`mandays_total`=%s, `mandays_value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname4, $update_mandays, $condition_mandays);
            }
        }
    } else if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    } else {
        '';
    }

    $data11 = $DBCR->get_data("financial_others", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data11[0]['item'])) {
        $combine_arr_others = array();
        for ($i = 0; $i < count($_POST['others_item']); $i++) {
            $condition_others = "fo_id='" . $_POST['fo_id'][$i] . "'";
            $combine_arr_others[] = array($_POST['others_item'][$i], $_POST['others_detail'][$i], $_POST['others_price'][$i]);
            foreach ($combine_arr_others as $value) {
                $update_others = sprintf(
                    "`item`=%s,`detail_item`=%s,`value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname5, $update_others, $condition_others);
            }
        }
    } else if (isset($_POST['item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    } else {
        '';
    }

    $condition_ccp = "cr_no='" . $_POST['cr_no'] . "'";
    $update_ccp = sprintf(
        "`cost_type`=%s,`responsibility`=%s, `nomor_po`=%s, `change_reason`=%s, `detail_reason`=%s, `used_ticket`=%s, `ticket_allocation`=%s, `ticket_allocation_sisa`=%s",
        GetSQLValueString($cost_type, "text"),
        GetSQLValueString($nccr, "text"),
        GetSQLValueString($nomorpo_chargeable, "text"),
        GetSQLValueString($change_reason, "text"),
        GetSQLValueString($detail_reason, "text"),
        GetSQLValueString($used_ticket_amount, "text"),
        GetSQLValueString($ticket_allocation, "text"),
        GetSQLValueString($sisaticket, "text")

    );
    $condition_implementation = "cr_no='" . $_POST['cr_no'] . "'";
    $update_implementation = sprintf(
        "`start_date`=%s,`finish_date`=%s",
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );

    // $detailplancek = ;
    $data4 = $DBCR->get_data("detail_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data4[0]['working_detail'])) {
        $combine_arr_detailplan = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $condition_detailplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_detailplan[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
            foreach ($combine_arr_detailplan as $value) {
                $update_detailplan = sprintf(
                    "`working_detail`=%s,`time`=%s, `finish_time`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "date"),
                    GetSQLValueString($value[2], "date"),
                    GetSQLValueString($value[3], "text"),
                );
                $res = $DBCR->update_data($tblname8, $update_detailplan, $condition_detailplan);
            }
        }
    } else if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("detail_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($_POST['cr_no'], "text")
            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    } else {
        "";
    }

    $data5 = $DBCR->get_data("fallback_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data5[0]['working_detail'])) {
        $combine_arr_fallbackplan = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $condition_fallbackplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_fallbackplan[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
            foreach ($combine_arr_fallbackplan as $value) {
                $update_fallbackplan = sprintf(
                    "`working_detail`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                );
                $res = $DBCR->update_data($tblname9, $update_fallbackplan, $condition_fallbackplan);
            }
        }
    } else if (isset($_POST['working_detail'])) {
        $db = $DBCR->get_data("fallback_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($_POST['cr_no'], "text")

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    } else {
        '';
    }

    $condition_prerequisite = "cr_no='" . $_POST['cr_no'] . "'";
    $update_prerequisite = sprintf("");
    $update_prerequisite = sprintf(
        "`description`=%s",
        GetSQLValueString($customer_requirement_description, "text")
    );
    $res = $DBCR->update_data($tblname10, $update_prerequisite, $condition_prerequisite);

    $condition_mpic = "cr_no='" . $_POST['cr_no'] . "'";
    $update_mpic = sprintf("");
    $update_mpic = sprintf(
        "`name`=%s",
        GetSQLValueString($_POST['pic_apr'], "text"),
    );

    $data9 = $DBCR->get_data("customer_pic", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data9[0]['name'])) {
        $combine_arr_cpic = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $condition_cpic = "cp_id='" . $_POST['cp_id'][$i] . "'";
            $combine_arr_cpic[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
            foreach ($combine_arr_cpic as $value) {
                $update_cpic = sprintf(
                    "`name`=%s,`position`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text")

                );
                $res = $DBCR->update_data($tblname12, $update_cpic, $condition_cpic);
            }
        }
    } else if (isset($_POST['customer_pic_name'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $combine_arr[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
        }
        foreach ($combine_arr as $value) {
            $insert = sprintf(
                "(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date")
            );
            $DBCR->insert_data("customer_pic", $insert);
        }
    } else {
        '';
    }

    $condition_crc = "cr_no='" . $_POST['cr_no'] . "'";
    $update_crc = sprintf(
        "`reason_request`=%s",
        GetSQLValueString($reason_request, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $res = $DBCR->update_data($tblname3, $update_assesment, $condition_ass);
    $res = $DBCR->update_data($tblname6, $update_ccp, $condition_ccp);
    $res = $DBCR->update_data($tblname7, $update_implementation, $condition_implementation);
    $res = $DBCR->update_data($tblname11, $update_mpic, $condition_mpic);
    $res = $DBCR->update_data($tblname13, $update_crc, $condition_crc);

    $msg1 = "<p>Saya telah approved change request:" . "</p>";
    $to = '';
    $checkapproval = isset($_POST['status_approval']);
    if ($checkapproval == null) {
        '';
    } else {
        $checkapproval = $_POST['status_approval'];
    }
    if ($checkapproval == 'submission_approved') {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $_SESSION['Microservices_UserEmail'];
        $owner = $_SESSION['Microservices_UserEmail'];
        $downer = $_POST['requested_by'];
        $to = $requested_name[0]['employee_name'] . "<" . $_POST['requested_by'] . ">;";
        $to_name = $requested_name[0]['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $reason_request . "</p>";
        $notes = $reason_request;
        if ($email == $downer) {
            '';
        } else {
            $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] " . ucwords($status) . " Change Request : CR Number = " . $_POST['cr_no'];
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear " . $to_name . "</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $_POST['cr_no'] . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p>" . $msg2 . "</p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }
        }
    } else {
        '';
    }

    $databackup = $DBCR->get_data("backup", "cr_no='" . $_POST['cr_no'] . "'");
    $cekbackup = isset($databackup[0]['status']);
    if ($cekbackup == null) {
        '';
    } else if ($cekbackup == "Sudah") {
        '';
    } else if ($cekbackup == "Belum") {
        $cr_number = $databackup[0]['cr_no'];
        $idbackup = $databackup[0]['id'];
        if ($perlu_backup == "Ya" && $cekbackup == "Belum") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $dataToken = json_decode($response, true);
            $accessToken = $dataToken["access_token"];

            $crl = curl_init();
            $nowDate = $start_date;
            $stamp = strtotime($nowDate);
            $finalDate = $stamp * 1000;
            $Date = $finish_date;
            $stampdate = strtotime($Date);
            $final_finish = $stampdate * 1000;
            $data = array(
                "change" => array(
                    "template" => array(
                        "inactive" => false,
                        "name" => "General Template",
                        "id" => "145684000000084294"
                    ),
                    "description" => "$barang_backup, Requested By : " .  $requested_name[0]['employee_name'] . "",
                    "urgency" => array(
                        "name" => "Low",
                        "id" => "145684000000007923"
                    ),
                    "services" => [array(
                        "inactive" => false,
                        "name" => "Hardware",
                        "id" => "145684000001014121",
                        "sort_index" => 0
                    )],
                    "change_type" => array(
                        "color" => "#ffff66",
                        "pre_approved" => false,
                        "name" => "Minor",
                        "id" => "145684000000007955"
                    ),
                    "title" => "Request Barang Backup $cr_number, KP " . $_POST['project_code'] . "",
                    "change_owner" => null,
                    "assets" => null,
                    "configuration_items" => null,
                    "group" => array(
                        "deleted" => false,
                        "name" => "Service Desk",
                        "id" => "145684000000369105"
                    ),
                    "workflow" => array(
                        "name" => "General Change Workflow",
                        "id" => "145684000000083981"
                    ),
                    "change_manager" => null,
                    "impact" => array(
                        "name" => "Kurang dari 24 Users",
                        "id" => "145684000000008039"
                    ),
                    "retrospective" => false,
                    "priority" => array(
                        "color" => "#f40080",
                        "name" => "P4",
                        "id" => "145684000010971203"
                    ),
                    "site" => null,
                    "reason_for_change" => null,
                    "stage" => array(
                        "internal_name" => "submission",
                        "stage_index" => 1,
                        "name" => "Submission",
                        "id" => "145684000000083125"
                    ),
                    "udf_fields" => array(
                        "udf_char6" => null,
                        "udf_char7" => null,
                        "udf_char8" => null,
                        "udf_date6" => null,
                        "udf_char9" => null,
                        "udf_char1" => null,
                        "udf_char13" => null,
                        "udf_char2" => null,
                        "udf_char3" => null,
                        "udf_char4" => null,
                        "udf_char5" => null,
                        "udf_char12" => null,
                        "udf_char11" => null,
                        "udf_char10" => null,
                        "udf_date1" => null,
                        "udf_date5" => null,
                        "udf_date4" => null,
                        "udf_date3" => null,
                        "udf_date2" => null
                    ),
                    "comment" => "Testing",
                    "risk" => array(
                        "name" => "Low",
                        "id" => "145684000000083080"
                    ),
                    "scheduled_start_time" => array(
                        "value" => "$finalDate"
                    ),
                    "scheduled_end_time" => array(
                        "value" => "$final_finish"
                    ),
                    "category" => null,
                    "subcategory" => null,
                    "status" => array(
                        "name" => "Requested",
                        "id" => "145684000000083260"
                    )
                )
            );

            $postdata = json_encode($data);
            curl_setopt_array($crl, array(
                CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "input_data=$postdata",
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/vnd.manageengine.sdp.v3+json',
                    'Content-Type: application/x-www-form-urlencoded',
                    "Authorization: Zoho-Oauthtoken $accessToken",
                    'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
                ),
            ));
            $hasil = curl_exec($crl);
            curl_close($crl);
            $feedback = json_decode($hasil, true);
            $comment = $feedback["change"]["display_id"]["display_value"];
            $result = preg_replace("/['']/", "", $hasil);

            if ($comment == null) {
                '';
            } else {
                $email = $_SESSION['Microservices_UserEmail'];
                $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
                $to =  "Service Desk <servicedesk@mastersystem.co.id>";
                $cc = $from;
                $bcc = "";
                $reply = $from;
                $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_number;
                $msg = "<table width='100%'";
                $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
                $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
                $msg .= "<br/>";
                $msg .= "<p>Dear Team Service Desk</p>";
                $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
                $msg .= "<p>";
                $msg .= "<table style='width:100%;'>";
                $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_number . "</td></tr>";
                $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
                $msg .= "</table>";
                $msg .= "</p>";
                $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
                $msg .= "<p>Terimakasih,<br/>";
                $msg .= $_SESSION['Microservices_UserName'] . "</p>";
                $msg .= "</td><td width='30%' rowspan='3'>";
                $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                $msg .= "</table>";

                $headers = "From: " . $from . "\r\n" .
                    "Reply-To: " . $reply . "\r\n" .
                    "Cc: " . $cc . "\r\n" .
                    "Bcc: " . $bcc . "\r\n" .
                    "MIME-Version: 1.0" . "\r\n" .
                    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

                $ALERT = new Alert();
                if (!mail(
                    $to,
                    $subject,
                    $msg,
                    $headers
                )) {
                    echo
                    "Email gagal terkirim pada jam " . date("d M Y G:i:s");
                } else {
                    echo
                    "Email terkirim pada jam " . date("d M Y G:i:s");
                }
            }

            //Backup
            $status = "Sudah";
            $condition_backup = "id = $idbackup";
            $update_backup = sprintf(
                "`output_json`=%s, `status`=%s",
                GetSQLValueString($result, "text"),
                GetSQLValueString($status, "text")

            );
            $DBCR->update_data($tblname17, $update_backup, $condition_backup);
        }
    }

    $ALERT->savedata();
} else if (isset($_POST['update_it'])) {

    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`request_date`=%s,`cr_no`=%s,`scope_of_change`=%s,`reason`=%s,`type_of_service`=%s,`project_manager`=%s,`impact_it`=%s,`impact`=%s,`change_request_status`=%s, `change_request_approval_type`=%s, `pic_leader`=%s, `pic`=%s, `pic_name`=%s, `perlu_backup`=%s, `barang_backup`=%s, `change_request_approval_type2`=%s",
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($type_of_service, "text"),
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($perlu_backup, "text"),
        GetSQLValueString($barang_backup, "text"),
        GetSQLValueString($status_approval2, "text")

    );

    $data15 = $DBCR->get_data("affected_ci", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data15[0]['serial_number'])) {
        $combine_arr_ac = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $condition_ac = "ac_id='" . $_POST['ac_id'][$i] . "'";
            $combine_arr_ac[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
            foreach ($combine_arr_ac as $value) {
                $update_ac = sprintf(
                    "`serial_number`=%s,`part_number`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                );
                $res = $DBCR->update_data($tblname14, $update_ac, $condition_ac);
            }
        }
    } else if (isset($_POST['serial_number'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['serial_number']); $i++) {
            $combine_arr[] = array($_POST['serial_number'][$i], $_POST['part_number'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`serial_number`,`part_number`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text")

            );
            $DBCR->insert_data("affected_ci", $insert_sql);
        }
    } else {
        '';
    }

    $condition_ass = "cr_no='" . $_POST['cr_no'] . "'";
    $update_assesment = sprintf(
        "`cr_no`=%s,`riskassesment`=%s,`risk_pic`=%s,`pic_ket_ta`=%s,`pic_ket_bb`=%s,`pic_ket_ra`=%s,`technical_assesment`=%s,`business_benefit`=%s,`technical_assesment_pic`=%s,`business_benefit_pic`=%s",
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($riskassesment, "text"),
        GetSQLValueString($risk_pic, "text"),
        GetSQLValueString($pic_ket_ta, "text"),
        GetSQLValueString($pic_ket_bb, "text"),
        GetSQLValueString($pic_ket_ra, "text"),
        GetSQLValueString($technical_assesment, "text"),
        GetSQLValueString($business_benefit, "text"),
        GetSQLValueString($ta_pic, "text"),
        GetSQLValueString($bb_pic, "text")
    );

    $condition_ccp = "cr_no='" . $_POST['cr_no'] . "'";
    $update_ccp = sprintf(
        "`cost_type`=%s,`responsibility`=%s, `nomor_po`=%s, `change_reason`=%s, `detail_reason`=%s",
        GetSQLValueString($cost_type, "text"),
        GetSQLValueString($nccr, "text"),
        GetSQLValueString($nomorpo_chargeable, "text"),
        GetSQLValueString($change_reason, "text"),
        GetSQLValueString($detail_reason, "text"),

    );
    $condition_implementation = "cr_no='" . $_POST['cr_no'] . "'";
    $update_implementation = sprintf(
        "`start_date`=%s,`finish_date`=%s",
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );

    // $detailplancek = ;
    $data4 = $DBCR->get_data("detail_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data4[0]['working_detail'])) {
        $combine_arr_detailplan = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $condition_detailplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_detailplan[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
            foreach ($combine_arr_detailplan as $value) {
                $update_detailplan = sprintf(
                    "`working_detail`=%s,`time`=%s, `finish_time`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "date"),
                    GetSQLValueString($value[2], "date"),
                    GetSQLValueString($value[3], "text"),
                );
                $res = $DBCR->update_data($tblname8, $update_detailplan, $condition_detailplan);
            }
        }
    } else if (isset($_POST['working_detail_plan'])) {
        $db = $DBCR->get_data("detail_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_plan']); $i++) {
            $combine_arr[] = array($_POST['working_detail_plan'][$i], $_POST['time'][$i], $_POST['finish_time'][$i], $_POST['dp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`,`time`, `finish_time`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "date"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($_POST['cr_no'], "text")
            );
            $DBCR->insert_data("detail_plans", $insert_sql);
        }
    } else {
        '';
    }

    $data5 = $DBCR->get_data("fallback_plans", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data5[0]['working_detail'])) {
        $combine_arr_fallbackplan = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $condition_fallbackplan = "dp_id='" . $_POST['dp_id'][$i] . "'";
            $combine_arr_fallbackplan[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
            foreach ($combine_arr_fallbackplan as $value) {
                $update_fallbackplan = sprintf(
                    "`working_detail`=%s, `pic`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                );
                $res = $DBCR->update_data($tblname9, $update_fallbackplan, $condition_fallbackplan);
            }
        }
    } else if (isset($_POST['working_detail'])) {
        $db = $DBCR->get_data("fallback_plans", "", "dp_id desc");
        $id = $db[0]['ip_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['working_detail_fallback']); $i++) {
            $combine_arr[] = array($_POST['working_detail_fallback'][$i], $_POST['fp_pic'][$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`ip_id`,`working_detail`, `pic`, `cr_no`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($id, "int"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($_POST['cr_no'], "text")

            );
            $DBCR->insert_data("fallback_plans", $insert_sql);
        }
    } else {
        '';
    }

    $condition_prerequisite = "cr_no='" . $_POST['cr_no'] . "'";
    $update_prerequisite = sprintf("");
    $update_prerequisite = sprintf(
        "`description`=%s",
        GetSQLValueString($customer_requirement_description, "text")
    );
    $res = $DBCR->update_data($tblname10, $update_prerequisite, $condition_prerequisite);

    $condition_mpic = "cr_no='" . $_POST['cr_no'] . "'";
    $update_mpic = sprintf("");
    $update_mpic = sprintf(
        "`name`=%s",
        GetSQLValueString($_POST['pic_apr'], "text"),
    );

    $data9 = $DBCR->get_data("customer_pic", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data9[0]['name'])) {
        $combine_arr_cpic = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $condition_cpic = "cp_id='" . $_POST['cp_id'][$i] . "'";
            $combine_arr_cpic[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
            foreach ($combine_arr_cpic as $value) {
                $update_cpic = sprintf(
                    "`name`=%s,`position`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text")

                );
                $res = $DBCR->update_data($tblname12, $update_cpic, $condition_cpic);
            }
        }
    } else if (isset($_POST['customer_pic_name'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['customer_pic_name']); $i++) {
            $combine_arr[] = array($_POST['customer_pic_name'][$i], $_POST['customer_pic_position'][$i]);
        }
        foreach ($combine_arr as $value) {
            $insert = sprintf(
                "(`cr_no`, `name`,`position`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date")
            );
            $DBCR->insert_data("customer_pic", $insert);
        }
    } else {
        '';
    }

    $condition_crc = "cr_no='" . $_POST['cr_no'] . "'";
    $update_crc = sprintf(
        "`reason_request`=%s",
        GetSQLValueString($reason_request, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $res = $DBCR->update_data($tblname3, $update_assesment, $condition_ass);
    $res = $DBCR->update_data($tblname6, $update_ccp, $condition_ccp);
    $res = $DBCR->update_data($tblname7, $update_implementation, $condition_implementation);
    $res = $DBCR->update_data($tblname11, $update_mpic, $condition_mpic);
    $res = $DBCR->update_data($tblname13, $update_crc, $condition_crc);

    $msg1 = "<p>Saya telah approved change request:" . "</p>";
    $to = '';
    $checkapproval = isset($_POST['status_approval']);
    if ($checkapproval == null) {
        '';
    } else {
        $checkapproval = $_POST['status_approval'];
    }
    if ($checkapproval == 'submission_approved') {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $_SESSION['Microservices_UserEmail'];
        $owner = $_SESSION['Microservices_UserEmail'];
        $downer = $_POST['requested_by'];
        $to = $requested_name[0]['employee_name'] . "<" . $_POST['requested_by'] . ">;";
        $to_name = $requested_name[0]['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $reason_request . "</p>";
        $notes = $reason_request;
        if ($email == $downer) {
            '';
        } else {
            $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] " . ucwords($status) . " Change Request : CR Number = " . $_POST['cr_no'];
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear " . $to_name . "</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $_POST['cr_no'] . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p>" . $msg2 . "</p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }
        }
    } else {
        '';
    }

    $databackup = $DBCR->get_data("backup", "cr_no='" . $_POST['cr_no'] . "'");
    $cekbackup = isset($databackup[0]['status']);
    if ($cekbackup == null) {
        '';
    } else if ($cekbackup == "Sudah") {
        '';
    } else if ($cekbackup == "Belum") {
        $cr_number = $databackup[0]['cr_no'];
        $idbackup = $databackup[0]['id'];
        if ($perlu_backup == "Ya" && $cekbackup == "Belum") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $dataToken = json_decode($response, true);
            $accessToken = $dataToken["access_token"];

            $crl = curl_init();
            $nowDate = $start_date;
            $stamp = strtotime($nowDate);
            $finalDate = $stamp * 1000;
            $Date = $finish_date;
            $stampdate = strtotime($Date);
            $final_finish = $stampdate * 1000;
            $data = array(
                "change" => array(
                    "template" => array(
                        "inactive" => false,
                        "name" => "General Template",
                        "id" => "145684000000084294"
                    ),
                    "description" => "$barang_backup, Requested By : " .  $requested_name[0]['employee_name'] . "",
                    "urgency" => array(
                        "name" => "Low",
                        "id" => "145684000000007923"
                    ),
                    "services" => [array(
                        "inactive" => false,
                        "name" => "Hardware",
                        "id" => "145684000001014121",
                        "sort_index" => 0
                    )],
                    "change_type" => array(
                        "color" => "#ffff66",
                        "pre_approved" => false,
                        "name" => "Minor",
                        "id" => "145684000000007955"
                    ),
                    "title" => "Request Barang Backup $cr_number, KP " . $_POST['project_code'] . "",
                    "change_owner" => null,
                    "assets" => null,
                    "configuration_items" => null,
                    "group" => array(
                        "deleted" => false,
                        "name" => "Service Desk",
                        "id" => "145684000000369105"
                    ),
                    "workflow" => array(
                        "name" => "General Change Workflow",
                        "id" => "145684000000083981"
                    ),
                    "change_manager" => null,
                    "impact" => array(
                        "name" => "Kurang dari 24 Users",
                        "id" => "145684000000008039"
                    ),
                    "retrospective" => false,
                    "priority" => array(
                        "color" => "#f40080",
                        "name" => "P4",
                        "id" => "145684000010971203"
                    ),
                    "site" => null,
                    "reason_for_change" => null,
                    "stage" => array(
                        "internal_name" => "submission",
                        "stage_index" => 1,
                        "name" => "Submission",
                        "id" => "145684000000083125"
                    ),
                    "udf_fields" => array(
                        "udf_char6" => null,
                        "udf_char7" => null,
                        "udf_char8" => null,
                        "udf_date6" => null,
                        "udf_char9" => null,
                        "udf_char1" => null,
                        "udf_char13" => null,
                        "udf_char2" => null,
                        "udf_char3" => null,
                        "udf_char4" => null,
                        "udf_char5" => null,
                        "udf_char12" => null,
                        "udf_char11" => null,
                        "udf_char10" => null,
                        "udf_date1" => null,
                        "udf_date5" => null,
                        "udf_date4" => null,
                        "udf_date3" => null,
                        "udf_date2" => null
                    ),
                    "comment" => "Testing",
                    "risk" => array(
                        "name" => "Low",
                        "id" => "145684000000083080"
                    ),
                    "scheduled_start_time" => array(
                        "value" => "$finalDate"
                    ),
                    "scheduled_end_time" => array(
                        "value" => "$final_finish"
                    ),
                    "category" => null,
                    "subcategory" => null,
                    "status" => array(
                        "name" => "Requested",
                        "id" => "145684000000083260"
                    )
                )
            );

            $postdata = json_encode($data);
            curl_setopt_array($crl, array(
                CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "input_data=$postdata",
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/vnd.manageengine.sdp.v3+json',
                    'Content-Type: application/x-www-form-urlencoded',
                    "Authorization: Zoho-Oauthtoken $accessToken",
                    'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
                ),
            ));
            $hasil = curl_exec($crl);
            curl_close($crl);
            $feedback = json_decode($hasil, true);
            $comment = $feedback["change"]["display_id"]["display_value"];
            $result = preg_replace("/['']/", "", $hasil);

            if ($comment == null) {
                '';
            } else {
                $email = $_SESSION['Microservices_UserEmail'];
                $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
                $to =  "Service Desk <servicedesk@mastersystem.co.id>";
                $cc = $from;
                $bcc = "";
                $reply = $from;
                $subject = "[MSIZone] Request Barang Backup CR Number = " . $cr_number;
                $msg = "<table width='100%'";
                $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
                $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
                $msg .= "<br/>";
                $msg .= "<p>Dear Team Service Desk</p>";
                $msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
                $msg .= "<p>";
                $msg .= "<table style='width:100%;'>";
                $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $cr_number . "</td></tr>";
                $msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>" . $comment . "</td></tr>";
                $msg .= "</table>";
                $msg .= "</p>";
                $msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
                $msg .= "<p>Terimakasih,<br/>";
                $msg .= $_SESSION['Microservices_UserName'] . "</p>";
                $msg .= "</td><td width='30%' rowspan='3'>";
                $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                $msg .= "</table>";

                $headers = "From: " . $from . "\r\n" .
                    "Reply-To: " . $reply . "\r\n" .
                    "Cc: " . $cc . "\r\n" .
                    "Bcc: " . $bcc . "\r\n" .
                    "MIME-Version: 1.0" . "\r\n" .
                    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

                $ALERT = new Alert();
                if (!mail(
                    $to,
                    $subject,
                    $msg,
                    $headers
                )) {
                    echo
                    "Email gagal terkirim pada jam " . date("d M Y G:i:s");
                } else {
                    echo
                    "Email terkirim pada jam " . date("d M Y G:i:s");
                }
            }

            //Backup
            $status = "Sudah";
            $condition_backup = "id = $idbackup";
            $update_backup = sprintf(
                "`output_json`=%s, `status`=%s",
                GetSQLValueString($result, "text"),
                GetSQLValueString($status, "text")

            );
            $DBCR->update_data($tblname17, $update_backup, $condition_backup);
        }
    }

    $ALERT->savedata();
} elseif (isset($_POST['update_sb'])) {

    $type_of_serviceimp = "";
    if (isset($_POST['type_of_service'])) {
        foreach ($_POST['type_of_service'] as $type_of_serviceimp0) {
            $type_of_serviceimp .= $type_of_serviceimp0 . " ; ";
        }
    }

    $condition = "gi_id=" . $_POST['gi_id'];
    $update = sprintf(
        "`request_date`=%s,`cr_no`=%s,`scope_of_change`=%s,`reason`=%s,`type_of_service`=%s,`project_manager`=%s,`impact_it`=%s,`impact`=%s,`change_request_status`=%s, `change_request_approval_type`=%s, `pic_leader`=%s, `pic`=%s, `pic_name`=%s, `change_request_approval_type2`=%s",
        GetSQLValueString($_POST['request_date'], "date"),
        GetSQLValueString($_POST['cr_no'], "text"),
        GetSQLValueString($_POST['scope_of_change'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($type_of_serviceimp, "text"),
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($impact_it, "text"),
        GetSQLValueString($change_impact, "text"),
        GetSQLValueString($status_request, "text"),
        GetSQLValueString($status_approval, "text"),
        GetSQLValueString($pic_apr, "text"),
        GetSQLValueString($pic_apr2, "text"),
        GetSQLValueString($pic_name[0]['employee_name'], "text"),
        GetSQLValueString($status_approval2, "text")

    );

    $data1 = $DBCR->get_data("detail_of_change", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data1[0]['detail'])) {
        $combine_arr_detailchange = array();
        for ($i = 0; $i < count($_POST['detail']); $i++) {
            $condition_detailchange = "no='" . $_POST['no'][$i] . "'";
            $combine_arr_detailchange[] = array($_POST['detail'][$i], $_POST['item'][$i], $_POST['perubahan'][$i]);
            foreach ($combine_arr_detailchange as $value) {
                $update_detailchange = sprintf(
                    "`detail`=%s,`item`=%s, `perubahan`=%s, `schedule`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                    GetSQLValueString($schedule, "text"),
                );
                $res = $DBCR->update_data($tblname16, $update_detailchange, $condition_detailchange);
            }
        }
    } else if (isset($_POST['detail'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($detail); $i++) {
            $combine_arr[] = array($detail[$i], $item[$i], $perubahan[$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`detail`,`item`, `perubahan`, `schedule`) VALUES (%s,%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
                GetSQLValueString($schedule, "text"),

            );
            $DBCR->insert_data("detail_of_change", $insert_sql);
        }
    } else {
        '';
    }

    $data12 = $DBCR->get_data("mandays", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data12[0]['type_of_resources'])) {
        $combine_arr_mandays = array();
        for ($i = 0; $i < count($_POST['mandays_tor']); $i++) {
            $condition_mandays = "mandays_id='" . $_POST['mandays_id'][$i] . "'";
            $combine_arr_mandays[] = array($_POST['mandays_tor'][$i], $_POST['mandays_tm'][$i], $_POST['mandays_value'][$i]);
            foreach ($combine_arr_mandays as $value) {
                $update_mandays = sprintf(
                    "`type_of_resources`=%s,`mandays_total`=%s, `mandays_value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname4, $update_mandays, $condition_mandays);
            }
        }
    } else if (isset($_POST['mandays_tor'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($mandays_tor); $i++) {
            $combine_arr[] = array($mandays_tor[$i], $mandays_tm[$i], $mandays_value[$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`type_of_resources`,`mandays_total`, `mandays_value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),

            );
            $DBCR->insert_data("mandays", $insert_sql);
        }
    } else {
        '';
    }

    $data11 = $DBCR->get_data("financial_others", "cr_no='" . $_POST['cr_no'] . "'");
    if (isset($data11[0]['item'])) {
        $combine_arr_others = array();
        for ($i = 0; $i < count($_POST['others_item']); $i++) {
            $condition_others = "fo_id='" . $_POST['fo_id'][$i] . "'";
            $combine_arr_others[] = array($_POST['others_item'][$i], $_POST['others_detail'][$i], $_POST['others_price'][$i]);
            foreach ($combine_arr_others as $value) {
                $update_others = sprintf(
                    "`item`=%s,`detail_item`=%s,`value`=%s",
                    GetSQLValueString($value[0], "text"),
                    GetSQLValueString($value[1], "text"),
                    GetSQLValueString($value[2], "text"),
                );
                $res = $DBCR->update_data($tblname5, $update_others, $condition_others);
            }
        }
    } else if (isset($_POST['others_item'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($others_item); $i++) {
            $combine_arr[] = array($others_item[$i], $others_detail[$i], $others_price[$i]);
        }

        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`cr_no`,`item`,`detail_item`,`value`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['cr_no'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
            );
            $DBCR->insert_data("financial_others", $insert_sql);
        }
    } else {
        '';
    }

    $condition_implementation = "cr_no='" . $_POST['cr_no'] . "'";
    $update_implementation = sprintf(
        "`start_date`=%s,`finish_date`=%s",
        GetSQLValueString($start_date, "date"),
        GetSQLValueString($finish_date, "date")
    );

    $condition_crc = "cr_no='" . $_POST['cr_no'] . "'";
    $update_crc = sprintf(
        "`reason_request`=%s",
        GetSQLValueString($reason_request, "text")
    );
    $res = $DBCR->update_data($tblname, $update, $condition);
    $res = $DBCR->update_data($tblname7, $update_implementation, $condition_implementation);
    $res = $DBCR->update_data($tblname13, $update_crc, $condition_crc);

    $msg1 = "<p>Saya telah approved change request:" . "</p>";
    $to = '';
    $checkapproval = isset($_POST['status_approval']);
    if ($checkapproval == null) {
        '';
    } else {
        $checkapproval = $_POST['status_approval'];
    }
    if ($checkapproval == 'submission_approved') {
        // APPROVED
        // $approved = '2';
        $status = 'submission_approved';
        $email = $_SESSION['Microservices_UserEmail'];
        $owner = $_SESSION['Microservices_UserEmail'];
        $downer = $_POST['requested_by'];
        $to = $requested_name[0]['employee_name'] . "<" . $_POST['requested_by'] . ">;";
        $to_name = $requested_name[0]['employee_name'];
        $msg2 = "<p>Saya memberikan persetujuan atas change request ini dengan catatan: </p><p>" . $reason_request . "</p>";
        $notes = $reason_request;
        if ($email == $downer) {
            '';
        } else {
            $from = $_SESSION['Microservices_UserName'] . "<" . $email . ">; ";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] " . ucwords($status) . " Change Request : CR Number = " . $_POST['cr_no'];
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear " . $to_name . "</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $_POST['cr_no'] . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p>" . $msg2 . "</p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }
        }
    } else {
        '';
    }
} elseif (isset($_POST['approve'])) {
    $condition2 = "cr_no='" . $_POST['cr_no'] . "' AND sequence=" . $_POST['sequence'];
    $condition = "gi_id=" . $_POST['gi_id'];


    $update = "status='approved'";
    $update2 = "change_request_status='submission_approved'";
    $update_completion = "status='complete'";
    $update_completion2 = "change_request_status='all_done'";
    $validation = $DBCR->get_data("approvals", "cr_no='" . $_POST['cr_no'] . "'");
    $validation2_complete = $DBCR->get_data("approvals", "cr_no='" . $_POST['cr_no'] . "'" . " AND review_status_complete='done'");
    $validation2 = $DBCR->get_data("approvals", "cr_no='" . $_POST['cr_no'] . "'" . " AND review_status='done'");

    $sequence = $_POST['sequence'];
    $next_seq = $sequence + 1;
    $last_sequence = $DBCR->get_data("approvals", "cr_no='" . $_POST['cr_no'] . "'");
    $update_comment = sprintf("`comment_appr`=%s", GetSQLValueString($_POST['approve_reason'], "text"));
    $update_comment2 = sprintf("`comment_complete_appr`=%s", GetSQLValueString($_POST['approve_reason'], "text"));



    if ($_POST['change_request_status'] == "submission_to_be_reviewed") {
        if ($validation[1]->num_rows == $validation2[1]->num_rows + 1) {
            $DBCR->update_data("general_informations", $update2, $condition);
            $DBCR->update_data("approvals", "review_status='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", $update_comment, $condition2);
        } else {
            $DBCR->update_data("approvals", "review_status='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review='yes'", "sequence='" . $next_seq . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", $update_comment, $condition2);
        }




        // $res = $DBCR->update_data("approval_statuses", $update, $condition);
        // $res = $DBCR->update_data("general_informations", $update2, $condition);
        // $update_comment = sprintf("`approval_comment`=%s",
        //     GetSQLValueString($_POST['approve_reason'], "text"),
        // );
        // $res = $DBCR->update_data("approval_statuses", $update_comment, $condition);
    } else if ($_POST['change_request_status'] == "completion_to_be_reviewed" || $_POST['change_request_status'] == "incomplete") {
        if ($validation[1]->num_rows == $validation2_complete[1]->num_rows + 1) {
            $DBCR->update_data("general_informations", $update_completion2, $condition);
            $DBCR->update_data("approvals", "review_status_complete='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", $update_comment2, $condition2);
        } else {
            $DBCR->update_data("approvals", "review_status_complete='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", "on_review='yes'", "sequence='" . $next_seq . "' AND cr_no='" . $_POST['cr_no'] . "'");
            $DBCR->update_data("approvals", $update_comment2, $condition2);
        }
    }



    $ALERT->savedata();
} else if (isset($_POST['reject'])) {
    $condition = "gi_id=" . $_POST['gi_id'];
    $update = "status='rejected'";
    $update2 = "change_request_status='submission_rejected'";
    $update_completion = "status='incomplete'";
    $update_completion2 = "change_request_status='incomplete'";

    if ($_POST['change_request_status'] == "submission_to_be_reviewed") {
        $res = $DBCR->update_data("approval_statuses", $update, $condition);
        $res = $DBCR->update_data("general_informations", $update2, $condition);
        $DBCR->update_data("approvals", "review_status='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
        $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
    } else if ($_POST['change_request_status'] == "completion_to_be_reviewed") {
        $res = $DBCR->update_data("completion_statuses", $update_completion, $condition);
        $res = $DBCR->update_data("general_informations", $update_completion2, $condition);
        $DBCR->update_data("approvals", "review_status='done'", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
        $DBCR->update_data("approvals", "on_review=null", "sequence='" . $_POST['sequence'] . "' AND cr_no='" . $_POST['cr_no'] . "'");
    }

    $ALERT->savedata();
} else if (isset($_POST['complete'])) {
    $condition = "gi_id=" . $_POST['gi_id'];
    $update = "status='complete'";
    $update2 = "change_request_status='completion_to_be_reviewed'";
    $file_name = $_FILES['sd_file']['name'];
    $cr_no = $_POST['cr_no'];
    $complete_reason = $_POST['complete_reason'];

    $file_dir = "uploads/change_request/" . $_POST['cr_no'] . "/" . $file_name;

    $res = $DBCR->update_data("approval_statuses", $update, $condition);
    $res = $DBCR->update_data("general_informations", $update2, $condition);
    $DBCR->update_data("general_informations", "complete_reason='$complete_reason'", "cr_no='$cr_no'");

    $insert_file = sprintf(
        "(`title`, `http_loc`, `server_loc`, `cr_no`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($file_name, "text"),
        GetSQLValueString($file_dir, "text"),
        GetSQLValueString(null, "text"),
        GetSQLValueString($cr_no, "text"),

    );
    $DBCR->insert_data("supporting_documents", $insert_file);


    $DBCR->update_data("approvals", "on_review='yes'", "sequence=1 AND cr_no='" . $_POST['cr_no'] . "'");
    if (!file_exists("uploads/change_request/$cr_no")) {
        mkdir("uploads/change_request/$cr_no", 0777);
    } else {
        echo "<script>alert('File is already exist'):</script>";
    }
    move_uploaded_file($_FILES["sd_file"]["tmp_name"], $file_dir);

    $ALERT->savedata();
}
