<?php
    if(isset($_GET['projCode'])){
        $tblname = 'trx_project_list';
        $projCode = $_GET['projCode'];
        $condition = "project_code like '%$condition%'";
        $proj = $DB->get_data($tblname, $condition);
        $array;
        $projlist = $proj[0];
        $projnext = $proj[1];
        do{
            array_push($array, $projlist);
        }while($projlist == $projnext->fetch_assoc());
    var_dump($array);
//        return $array;
    }
    if(isset($_POST['add'])) {
        $insert = sprintf("(`customer_id`,`customer_company_name`,`pic_name`,`pic_email`,`pic_phone`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['customer_id'], "int"),
            GetSQLValueString($_POST['customer_company_name'], "text"),
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_phone'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "customer_id=" . $_POST['customer_id'];
        $update = sprintf("`customer_id`=%s,`customer_company_name`=%s,`pic_name`=%s,`pic_email`=%s,`pic_phone`=%s",
            GetSQLValueString($_POST['customer_id'], "int"),
            GetSQLValueString($_POST['customer_company_name'], "text"),
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_phone'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>