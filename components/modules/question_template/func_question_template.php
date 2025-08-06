<?php
    if(isset($_POST['add'])) {
        $questions = array();
        foreach($_POST['question'] as $question){
            array_push($questions, $question);
        }
        $questions = json_encode($questions);
        $insert = sprintf("(`template_name`,`questions`,`valid_year`,`template_type`,`created_by`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['template_name'], "text"),
            GetSQLValueString($questions, "text"),
            GetSQLValueString($_POST['valid_year'], "text"),
            GetSQLValueString($_POST['template_type'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res = $DB1->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $questions = array();
        foreach($_POST['question'] as $question){
            array_push($questions, $question);
        }
        $questions = json_encode($questions);
        $condition = "template_id=" . $_POST['template_id'];
        $update = sprintf("`template_name`=%s,`questions`=%s, `valid_year`=%s, `template_type`=%s,`modified_by`=%s",
            GetSQLValueString($_POST['template_name'], "text"),
            GetSQLValueString($questions, "text"),
            GetSQLValueString($_POST['valid_year'], "text"),
            GetSQLValueString($_POST['template_type'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
    );
        $res = $DB1->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>