<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`survey_id`,`so_number`,`project_type`,`type_of_service`,`customer_name`,`project_name`,`pic_name`,`pic_email`,`pic_phone`,`template_type`,`survey_link`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['so_number'], "text"),
            GetSQLValueString($_POST['project_type'], "text"),
            GetSQLValueString($_POST['type_of_service'], "text"),
            GetSQLValueString($_POST['customer_name'], "text"),
            GetSQLValueString($_POST['project_name'], "text"),
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_phone'], "int"),
            GetSQLValueString($_POST['template_type'], "text"),
            GetSQLValueString($_POST['survey_link'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "survey_id=" . $_POST['survey_id'];
        $update = sprintf("`survey_id`=%s,`so_number`=%s,`project_type`=%s,`type_of_service`=%s,`customer_name`=%s,`project_name`=%s,`pic_name`=%s,`pic_email`=%s,`pic_phone`=%s,`template_type`=%s,`survey_link`=%s",
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['so_number'], "text"),
            GetSQLValueString($_POST['project_type'], "text"),
            GetSQLValueString($_POST['type_of_service'], "text"),
            GetSQLValueString($_POST['customer_name'], "text"),
            GetSQLValueString($_POST['project_name'], "text"),
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_phone'], "int"),
            GetSQLValueString($_POST['template_type'], "text"),
            GetSQLValueString($_POST['survey_link'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>