<?php
    // if(isset($_POST['add'])) {
    //     $insert = sprintf("(`user_id`,`name`,`username`,`email`,`password`) VALUES (%s,%s,%s,%s,%s)",
    //         GetSQLValueString($_POST['user_id'], "bigint"),
    //         GetSQLValueString($_POST['name'], "text"),
    //         GetSQLValueString($_POST['username'], "text"),
    //         GetSQLValueString($_POST['email'], "text"),
    //         GetSQLValueString($_POST['password'], "text")
    //     );
    //     $res = $DB->insert_data($tblname, $insert);
    //     $ALERT->savedata();
    // } else
    if(isset($_POST['save'])) {
        if($_POST['newpassword']==$_POST['repeatpassword']) {
            $condition = "user_id=" . $_POST['user_id'];
            $update = sprintf("`name`=%s,`password`=%s",
                GetSQLValueString($_POST['name'], "text"),
                GetSQLValueString(MD5($_POST['newpassword']), "text")
            );
            $res = $DB->update_data($tblname, $update, $condition);
            $ALERT->savedata();
            $from = $_SESSION["Microservices_UserEmail"];
            $to = $_POST['email'];
            $subject = "[MSIZone] Reset Password pada MSIZone";
            $msg = "Dear, ";
            $msg .= "<p>Anda telah melakukan penggantian password melalui aplikasi <a href='https://msizone.mastersystem.co.id'>MSIZone</a>.</p>";
            $msg .= "<p>Bila Anda merasa tidak melakukannya, silahkan menghubungi IT/MIS.</p>";
            $msg .= "<p>Dikirim secara otomatis oleh sistem <a href='https://msizone.mastersystem.co.id'>MSIZone</a>.<br/>";
            $msg .= "Jangan membalas/reply email ini.</p>";
            
            $headers = "From: " . $from . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
            
            if(mail($to, $subject, $msg, $headers)) {
                echo "Email terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            }
    
            echo '<script>window.location.href = "index.php";</script>';
        } else {
            $ALERT->password();
        }
    }
    ?>