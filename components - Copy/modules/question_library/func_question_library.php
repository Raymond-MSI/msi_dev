<?php
    if(isset($_POST['add'])) {
        $question = str_replace("'", "''", $_POST['question']);
        $insert = sprintf("(`question`,`category`,`weight`,`created_by`, `modified_by`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($question, "text"),
            GetSQLValueString($_POST['category'], "text"),
            GetSQLValueString($_POST['weight'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $question = str_replace("'", "''", $_POST['question']);
        $condition = "question_id=" . $_POST['question_id'];
        $update = sprintf("`question`=%s,`category`=%s,`weight`=%s,`modified_by`=%s",
            GetSQLValueString($_POST['question'], "text"),
            GetSQLValueString($_POST['category'], "text"),
            GetSQLValueString($_POST['weight'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>