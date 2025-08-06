<?php

use Google\Service\Dataflow\ConcatPosition;

    if(isset($_POST['add'])) {
        $insert = sprintf("(`log_id`,`created_date`,`author_id`,`text_comment`) VALUES (%s,%s,%s,%s)",
            GetSQLValueString($_POST['log_id'], "int"),
            GetSQLValueString($_POST['created_date'], "text"),
            GetSQLValueString($_POST['author_id'], "text"),
            GetSQLValueString($_POST['text_comment'], "text")
        );
        $res = $DBWR->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "log_id=" . $_POST['log_id'];
        $update = sprintf("`log_id`=%s,`created_date`=%s,`author_id`=%s,`text_comment`=%s",
            GetSQLValueString($_POST['log_id'], "int"),
            GetSQLValueString($_POST['created_date'], "text"),
            GetSQLValueString($_POST['author_id'], "text"),
            GetSQLValueString($_POST['text_comment'], "text")
    );
        $res = $DBWR->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }

    function contactSave(){
        global $DBWR;
        $tblname1 = 'wrike_contact_user';
        $tbl_log = 'log_activity';
        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://www.wrike.com/api/v4/contacts');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result2 = json_decode($result, true);

        //GET Customer Name
        $result1 = $result2['data'];

        for ($i = 0; $i < count($result1); $i++) {
            $author_id = $result1[$i]['id'];
            $first_name = $result1[$i]['firstName'];
            $last_name= $result1[$i]['lastName'];
            $role= $result1[$i]['profiles'][0]['role'];


            //Jika comment = time entry, maka jalankan function
            if(strpos($role, 'Collaborator') !== false){    
                $condition = "author_id = '$author_id'";
                $testdata = $DBWR->get_data($tblname1, $condition);

                    if($testdata[2] > 0){
                        //Query Update
                        $update = sprintf("`first_name`='$first_name',`last_name`='$last_name',`role`='$role'",
                        GetSQLValueString($author_id, "text"),
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($role, "text")
                    );

                    $res1 = $DBWR->update_data($tblname1, $update, $condition);

                    for($k=0; $k<count(array($res1)); $k++){
                        $update_log = sprintf("(`activity`) VALUES ('$first_name $last_name data updated')",
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($role, "text")
                    );
                    $insert_log = $DBWR->insert_data($tbl_log, $update_log);
                    }

                    }else{
                        //Query Insert
                    $insert = sprintf("(`author_id`,`first_name`,`last_name`,`role`) VALUES ('$author_id', '$first_name', '$last_name', '$role')",
                        GetSQLValueString($author_id, "text"),
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($role, "text")
                    );
                    $res = $DBWR->insert_data($tblname1, $insert);

                    for($j=0; $j<count(array($res)); $j++){
                        $insert_log = sprintf("(`activity`) VALUES ('$first_name $last_name data inserted')",
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($role, "text")
                    );
                    $insert_log1 = $DBWR->insert_data($tbl_log, $insert_log);
                    }
                    }
            }
        }
    }



    function commentLog(){
        global $DBWR;
        $tbl_log = 'log_activity';
        $tblname = 'wrike_get_comment';

        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.wrike.com/api/v4/comments');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result2 = json_decode($result, true);

        //GET Customer Name
        $result1 = $result2['data'];

        for($i = 0; $i < count($result1); $i++) {
            $comment_id = $result1[$i]['id'];
            $created_date = $result1[$i]['createdDate'];
            $author_id = $result1[$i]['authorId'];
            $text_comment = $result1[$i]['text'];
            $text_comment = str_replace(array('%','&#61;'), array('','='), $text_comment);
            $folder_task_id = $result1[$i]['taskId'];

            //Jika comment = time entry, maka jalankan function
            if(strpos($text_comment, 'Timeentry') !== false){
                $condition = "comment_id = '$comment_id'";
                $testdata = $DBWR->get_data($tblname, $condition);
                
                if($testdata[2] > 0){
                    //Query Update
                    $update = sprintf("`created_date`='$created_date',`author_id`='$author_id',`text_comment`='$text_comment', `folder_task_id`='$folder_task_id'",
                    GetSQLValueString($comment_id, "text",),
                    GetSQLValueString($created_date, "text"),
                    GetSQLValueString($author_id, "text"),
                    GetSQLValueString($text_comment, "text"),
                    GetSQLValueString($folder_task_id, "text")
                    );
                $res1 = $DBWR->update_data($tblname, $update, $condition);
        
                for($k=0; $k<count(array($res1)); $k++){
                    $update_log = sprintf("(`activity`) VALUES ('$comment_id updated with $author_id')",
                    GetSQLValueString($comment_id, "text"),
                    GetSQLValueString($author_id, "text")
                );
                $update_log1 = $DBWR->insert_data($tbl_log, $update_log);
                }

                }else{
                    $insert = sprintf("(`comment_id`,`created_date`,`author_id`,`text_comment`,`folder_task_id`) VALUES ('$comment_id', '$created_date', '$author_id', '$text_comment', '$folder_task_id')",
                    GetSQLValueString($comment_id, "text",),
                    GetSQLValueString($created_date, "text"),
                    GetSQLValueString($author_id, "text"),
                    GetSQLValueString(strip_tags($text_comment), "text"),
                    GetSQLValueString($folder_task_id, "text")
                );

                $res = $DBWR->insert_data($tblname, $insert);

                for($j=0; $j<count(array($res)); $j++){
                    $insert_log = sprintf("(`activity`) VALUES ('$comment_id created with $author_id')",
                    GetSQLValueString($comment_id, "text"),
                    GetSQLValueString($author_id, "text")
                );
                $insert_log1 = $DBWR->insert_data($tbl_log, $insert_log);
                }
                
                }
            }
        }
    }
    
    function getComment(){
        global $DBWR;
        $tblname = 'wrike_get_comment';
        $tblname1 = 'wrike_contact_user';
        $tbl_log = 'log_activity';

        $db = $DBWR->get_data($tblname);
        $db1 = $DBWR->get_data($tblname1);

        $row=$db[0];
        $res=$db[1];
        $totalRow=$db[2];

        $row1=$db1[0];
        $res1=$db1[1];
        $totalRow1=$db1[2];

        do{
            $comment_id = $row['comment_id'];
            $task_id = $row['folder_task_id'];
            $author_id = $row['author_id'];
            $text_comment1 = (float) substr($row['text_comment'],10,2);
            $text_comment2 = ((float) substr($row['text_comment'],13,2))/60;
            $text_comment = $text_comment1 + $text_comment2;
            $comment = substr($row['text_comment'],32);
            //$comment = "Time Entry";
            $tracked_date = substr($row['text_comment'],21,10);
            //$tracked_date = date('Y-m-d');
            $status = $row['status'];

            echo "$task_id <br/>" . "$author_id <br/>" . "$text_comment1 <br/>" . "$text_comment2 <br/>" . "$comment <br/>" . "$tracked_date <br/>" . "$status <br/>";

            if($status == 'Open'){

            //GET Role User
            global $DBWR;
            $tblname1 = 'wrike_contact_user';
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/users/$author_id");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $result2 = json_decode($result, true);

            //GET User Name
            $role_check = $result2['data'][0]['profiles'][0]['role'];
            $account_id = $result2['data'][0]['profiles'][0]['accountId'];

            if($role_check == 'Collaborator'){
                //Collaborator to Regular User
                $url = "https://www.wrike.com/api/v4/users/$author_id"; 
                $data = array('profile' => "{'accountId': '$account_id','role':'User'}");
                $post_data1 = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);
                print_r($response);
                echo "<br/>";

                //Query Update Regular User
                $condition = "author_id = '$author_id'";
                $update = sprintf("`role`='Regular User'");
                $res1 = $DBWR->update_data($tblname1, $update, $condition);
                $res2 = $DBWR->get_data($tblname1,$condition);

                for($k=0; $k < $res2[2]; $k++){
                    echo $res2[0]['author_id'] . " " . $res2[0]['first_name'] . " " . $res2[0]['last_name'] . " " . $res2[0]['role'] . " berhasil di update ! <br/>";
                }

                for($i=0; $i<count(array($res2)); $i++){
                    $insert_log = sprintf("(`activity`) VALUES ('$author_id promoted to Regular User')",
                    GetSQLValueString($comment_id, "text"),
                    GetSQLValueString($author_id, "text")
                );
                $insert_log1 = $DBWR->insert_data($tbl_log, $insert_log);
                }

                // Post Timelogs
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $url = "https://www.wrike.com/api/v4/tasks/$task_id/timelogs";
                $data = array('hours' => "$text_comment", 'onBehalfOf' => "$author_id", 'comment' => "$comment %", 'trackedDate' => "$tracked_date");
                $postdata = json_encode($data); 
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $result = curl_exec($ch);
                curl_close($ch);
                print_r ($result);
                echo "<br/>";
                
                //Update Status Comment
                $tblname2 = 'wrike_get_comment';
                $condition = "comment_id = '$comment_id'";
                $update = sprintf("`status`='Close'");
                $res1 = $DBWR->update_data($tblname2, $update, $condition);

                for($i=0; $i<count(array($res1)); $i++){
                    $comment_log = sprintf("(`activity`) VALUES ('$author_id posted $comment_id to wrike')",
                    GetSQLValueString($author_id, "text"),
                    GetSQLValueString($comment_id, "text")
                );
                $post_comment = $DBWR->insert_data($tbl_log, $comment_log);
                echo "$author_id posted $comment_id to wrike </br>";
                }
                

                //Regular User to Collaborator
                $url = "https://www.wrike.com/api/v4/users/$author_id"; 
                $data = array('profile' => "{'accountId': '$account_id','role':'Collaborator'}");
                $post_data1 = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);
                print_r($response);
                echo "<br/>";

                

                //Query Update Collaborator
                $condition = "author_id = '$author_id'";
                $update = sprintf("`role`='Collaborator'");
                $res1 = $DBWR->update_data($tblname1, $update, $condition);
                $res2 = $DBWR->get_data($tblname1,$condition);
        
                for($j=0; $j<count(array($res1)); $j++){
                    $insert_log_demote = sprintf("(`activity`) VALUES ('$author_id demoted to Collaborator')",
                    GetSQLValueString($comment_id, "text"),
                    GetSQLValueString($author_id, "text")
                );
                $insert_log_demote_1 = $DBWR->insert_data($tbl_log, $insert_log_demote);
                }

                for($k=0; $k < $res2[2]; $k++){
                    echo $res2[0]['author_id'] . $res2[0]['first_name'] . $res2[0]['last_name'] . $res2[0]['role'] . "berhasil di update ! <br/> <br/>";
                }

            }
            else if ($role_check != 'Collaborator'){
                // Post Timelogs
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $url = "https://www.wrike.com/api/v4/tasks/$task_id/timelogs";
                $data = array('hours' => "$text_comment", 'onBehalfOf' => "$author_id", 'comment' => "$comment %", 'trackedDate' => "$tracked_date");
                $postdata = json_encode($data); 
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $result = curl_exec($ch);
                curl_close($ch);
                print_r ($result);
                echo "<br/>";

                $condition = "comment_id = '$comment_id'";
                $update = sprintf("`status`='Close'");
                $res1 = $DBWR->update_data($tblname, $update, $condition);

                for($i=0; $i<count(array($res1)); $i++){
                    $comment_log = sprintf("(`activity`) VALUES ('$author_id posted $comment_id to wrike')",
                    GetSQLValueString($author_id, "text"),
                    GetSQLValueString($comment_id, "text")
                );
                $post_comment = $DBWR->insert_data($tbl_log, $comment_log);
                echo "$author_id posted $comment_id to wrike <br/> <br/>";
                }
            }   
            }else{
                for($i=0; $i<count(array($res1)); $i++){
                    $comment_log_failed = sprintf("(`activity`) VALUES ('$author_id already posted $comment_id to wrike')",
                    GetSQLValueString($author_id, "text"),
                    GetSQLValueString($comment_id, "text")
                );
                $comment_log_failed1 = $DBWR->insert_data($tbl_log, $comment_log_failed);
                echo "$author_id already posted $comment_id to wrike <br/> <br/>" ;
                }
            }
        }while($row=$res->fetch_assoc());
    }
?>