<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`complain_id`,`order_number`,`project_leader`,`customer`,`pic_customer`,`complain`,`category`,`status`,`solution`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['complain_id'], "int"),
            GetSQLValueString($_POST['order_number'], "text"),
            GetSQLValueString($_POST['project_leader'], "text"),
            GetSQLValueString($_POST['customer'], "text"),
            GetSQLValueString($_POST['pic_customer'], "text"),
            GetSQLValueString($_POST['complain'], "text"),
            GetSQLValueString($_POST['category'], "text"),
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['solution'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "complain_id=" . $_POST['complain_id'];
        $update = sprintf("`complain_id`=%s,`order_number`=%s,`project_leader`=%s,`customer`=%s,`pic_customer`=%s,`complain`=%s,`category`=%s,`status`=%s,`solution`=%s",
            GetSQLValueString($_POST['complain_id'], "int"),
            GetSQLValueString($_POST['order_number'], "text"),
            GetSQLValueString($_POST['project_leader'], "text"),
            GetSQLValueString($_POST['customer'], "text"),
            GetSQLValueString($_POST['pic_customer'], "text"),
            GetSQLValueString($_POST['complain'], "text"),
            GetSQLValueString($_POST['category'], "text"),
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['solution'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>