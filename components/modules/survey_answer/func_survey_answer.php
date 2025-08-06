<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`answer_id`,`survey_id`,`survey_link`) VALUES (%s,%s,%s)",
            GetSQLValueString($_POST['answer_id'], "int"),
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['survey_link'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "answer_id=" . $_POST['answer_id'];
        $update = sprintf("`answer_id`=%s,`survey_id`=%s,`survey_link`=%s",
            GetSQLValueString($_POST['answer_id'], "int"),
            GetSQLValueString($_POST['survey_id'], "int"),
            GetSQLValueString($_POST['survey_link'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>