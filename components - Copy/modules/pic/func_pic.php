<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`pic_name`,`pic_email`,`pic_address`,`pic_city`,`pic_phone`, `customer_id`,`created_by`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_address'], "text"),
            GetSQLValueString($_POST['pic_city'], "text"),
            GetSQLValueString($_POST['pic_phone'], "text"),
            GetSQLValueString($_POST['customer_id'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "pic_id=" . $_POST['pic_id'];
        $update = sprintf("`pic_id`=%s,`pic_name`=%s,`pic_address`=%s,`pic_city`=%s",
            GetSQLValueString($_POST['pic_id'], "int"),
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_address'], "text"),
            GetSQLValueString($_POST['pic_city'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>