<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`email_id`,`email`,`tanggal_interview`,`link_webex`,`pic`,`project_code`,`catatan`,`interview_user`,`interview_hcm`,`status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['email_id'], "int"),
            GetSQLValueString($_POST['email'], "text"),
            GetSQLValueString($_POST['tanggal_interview'], "date"),
            GetSQLValueString($_POST['link_webex'], "text"),
            GetSQLValueString($_POST['pic'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($_POST['interview_user'], "text"),
            GetSQLValueString($_POST['interview_hcm'], "text"),
            GetSQLValueString($_POST['status'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "email_id=" . $_POST['email_id'];
        $update = sprintf("`email_id`=%s,`email`=%s,`tanggal_interview`=%s,`link_webex`=%s,`pic`=%s,`project_code`=%s,`catatan`=%s,`interview_user`=%s,`interview_hcm`=%s,`status`=%s",
            GetSQLValueString($_POST['email_id'], "int"),
            GetSQLValueString($_POST['email'], "text"),
            GetSQLValueString($_POST['tanggal_interview'], "date"),
            GetSQLValueString($_POST['link_webex'], "text"),
            GetSQLValueString($_POST['pic'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($_POST['interview_user'], "text"),
            GetSQLValueString($_POST['interview_hcm'], "text"),
            GetSQLValueString($_POST['status'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>