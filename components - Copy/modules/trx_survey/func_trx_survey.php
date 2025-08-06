<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`survey_id`,`customer_id`,`survey_question`,`survey_answer`,`survey_weight`,`survey_date`) VALUES (%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['customer_id'], "int"),
            GetSQLValueString($_POST['survey_question'], "text"),
            GetSQLValueString($_POST['survey_answer'], "text"),
            GetSQLValueString($_POST['survey_weight'], "int"),
            GetSQLValueString($_POST['survey_date'], "date")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "survey_id=" . $_POST['survey_id'];
        $update = sprintf("`survey_id`=%s,`customer_id`=%s,`survey_question`=%s,`survey_answer`=%s,`survey_weight`=%s,`survey_date`=%s",
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['customer_id'], "int"),
            GetSQLValueString($_POST['survey_question'], "text"),
            GetSQLValueString($_POST['survey_answer'], "text"),
            GetSQLValueString($_POST['survey_weight'], "int"),
            GetSQLValueString($_POST['survey_date'], "date")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>