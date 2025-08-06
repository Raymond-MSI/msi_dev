<?php
    $tblnamex = "documents";
    if(isset($_POST['add'])) {
        $insert = sprintf("(`doc_id`,`doc_number`,`doc_title`,`provider_id`,`category_id`,`date_released`,`date_expired`,`note`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['doc_id'], "int"),
            GetSQLValueString($_POST['doc_number'], "text"),
            GetSQLValueString($_POST['doc_title'], "text"),
            GetSQLValueString($_POST['provider_id'], "int"),
            GetSQLValueString($_POST['category_id'], "int"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['date_released'])), "date"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['date_expired'])), "date"),
            GetSQLValueString($_POST['note'], "text")
        );
        $res = $DBLD->insert_data($tblnamex, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "doc_id=" . $_POST['doc_id'];
        $update = sprintf("`doc_id`=%s,`doc_number`=%s,`doc_title`=%s,`provider_id`=%s,`category_id`=%s,`date_released`=%s,`date_expired`=%s,`note`=%s",
            GetSQLValueString($_POST['doc_id'], "int"),
            GetSQLValueString($_POST['doc_number'], "text"),
            GetSQLValueString($_POST['doc_title'], "text"),
            GetSQLValueString($_POST['provider_id'], "int"),
            GetSQLValueString($_POST['category_id'], "int"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['date_released'])), "date"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['date_expired'])), "date"),
            GetSQLValueString($_POST['note'], "text")
    );
        $res = $DBLD->update_data($tblnamex, $update, $condition);
        $ALERT->savedata();
    }
    ?>