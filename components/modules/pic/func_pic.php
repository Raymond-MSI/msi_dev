<?php
    if(isset($_POST['add'])) {
        // print_r($_POST);
        // exit();
        $insert = sprintf("(`pic_name`,`pic_email`,`pic_address`,`pic_city`,`pic_phone`, `title`,`customer_code`,`survey_count`,`created_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,0,%s)",
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_address'], "text"),
            GetSQLValueString($_POST['pic_city'], "text"),
            GetSQLValueString($_POST['pic_phone'], "text"),
            GetSQLValueString($_POST['pic_title'], "text"),
            GetSQLValueString($_POST['customer_code'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res = $DB1->insert_data($tblname, $insert);
        $insertlog = sprintf("(`pic_id`,`description`,`entry_by`) VALUES (%s,%s,%s)",
            GetSQLValueString($res, "text"),
            GetSQLValueString("New PIC Added <".$_POST['pic_name'].">", "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res3 = $DB1->insert_data('logs', $insertlog);

        $checkcust = 'select * from sa_customer where customer_code = "'.$_POST['customer_code'].'"';
        $checkcust2 = $DB1->get_sql($checkcust);
        $checkcust3 = $checkcust2[2];
        if($checkcust3 <=0){
            $insert2 = sprintf("(`customer_code`,`customer_company_name`,`category`, `created_by`) VALUES (%s,%s,'Customer',%s)",
                GetSQLValueString($_POST['customer_code'], "text"),
                GetSQLValueString($_POST['customer_name'], "text"),
                GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
            );
            $res2 = $DB1->insert_data('customer', $insert2);
        }
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $query = "select pic_name, pic_address, pic_city, customer_code, title, pic_phone from sa_pic where pic_id = ".$_POST['pic_id'];
        //$condition = "pic_id=" . $_POST['pic_id'];
        //$data = $DB1->get_data($tblname, $condition);
        $data = $DB1->get_sql($query);
        $ddata = $data[0];
        foreach($ddata as $key => $value){
            if($ddata[$key] != $_POST[$key] && $_POST[$key] != ''){
                $insertlog = sprintf("(`pic_id`,`description`,`entry_by`) VALUES (%s,%s,%s)",
                GetSQLValueString($_POST['pic_id'], "text"),
                GetSQLValueString("Update $key from <".$value."> to <".$_POST[$key].">", "text"),
                GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
            );
            $res3 = $DB1->insert_data('logs', $insertlog);
            }
        }
        $condition = "pic_id=" . $_POST['pic_id'];
        $update = sprintf("`pic_name`=%s,`pic_email`=%s,`pic_address`=%s,`pic_city`=%s,`pic_phone`=%s,`title`= %s, `customer_code` = %s",
            GetSQLValueString($_POST['pic_name'], "text"),
            GetSQLValueString($_POST['pic_email'], "text"),
            GetSQLValueString($_POST['pic_address'], "text"),
            GetSQLValueString($_POST['pic_city'], "text"),
            GetSQLValueString($_POST['pic_phone'], "text"),
            GetSQLValueString($_POST['title'], "text"),
            GetSQLValueString($_POST['customer_code'], "text")
    );
        $res = $DB1->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>