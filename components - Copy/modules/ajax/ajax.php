<?php
session_start();

$rootfolder = $_SERVER['DOCUMENT_ROOT'] . "/microservices/";
include( $rootfolder."components/classes/func_databases_v3.php" ); 
include( $rootfolder."components/classes/func_component.php");

if(isset($_POST['act']) && $_POST['act'] == 'getPIC'){
    $database = 'sa_survey';
    $DB1 = new Databases('localhost', 'root', '', $database);
    $query = 'select pic_id, pic_name, pic_email, pic_address, pic_city, pic_phone, customer_company_name, customer_code
    from sa_pic pic join sa_customer cust on pic.customer_id = cust.customer_id where pic_id = '.$_POST['pic_id'];
    $data = $DB1->get_sql($query);
    $data1 = json_encode($data[0]);
    echo $data1;
}

if(isset($_POST['act']) && $_POST['act'] == 'getListSO'){
    $database = 'sa_ps_service_budgets';
    $DB1 = new Databases('localhost', 'root', '', $database);
    $query = 'select distinct(so_number), project_code from sa_trx_project_list where customer_code = "'.$_POST['cust_id'].'"';
    $data = $DB1->get_sql($query);
    $data1 = $data[0];
    $data2 = $data[1];
    $listsonumber = array();
    do {
        array_push($listsonumber, "<option value='".$data1['so_number']."'>".$data1['project_code']." ( ".$data1['so_number']." )" );
    } while ($data1 = $data2->fetch_assoc());

    $listsonumber = json_encode($listsonumber);
    echo $listsonumber;
}

if(isset($_POST['act']) && $_POST['act'] == 'getProjectName'){
    $database = 'sa_ps_service_budgets';
    $DB1 = new Databases('localhost', 'root', '', $database);
    $query = 'select project_name from sa_trx_project_list where so_number = "'.$_POST['so_number'].'"';
    $data = $DB1->get_sql($query);
    $data1 = $data[0];
    $data2 = $data[1];
    echo $data1['project_name'];
}

if(isset($_POST['act']) && $_POST['act'] == 'cancelSurvey'){
    $database = 'sa_survey';
    $DB1 = new Databases('localhost', 'root', '', $database);
    $condition = "survey_id = ".$_POST['survey_id'];
    $update = "status='inactive' ";
    $tblname = 'survey';
    $update = $DB1->update_data($tblname, $update, $condition);
    $query = "select status from sa_survey where survey_id = '".$_POST['survey_id']."'";
    $data = $DB1->get_sql($query);
    if($data[0]['status'] == 'inactive'){
        echo "Success";
    }else{
        echo "Update Fail";
    }
}

if(isset($_POST['act']) && $_POST['act'] == 'fillSurvey'){
    $database = 'sa_survey';
    $DB1 = new Databases('localhost', 'root', '', $database);
    //print_r($_POST['data']);
    //exit();
    $answer = $_POST['data'];
    $survey_link = $answer[0]['value'];
    $survey_id = $answer[1]['value'];
    $i = 2;
    $temp_answer = array();
    $main_answer = array();
    $engineer = array('question_id' => 'engineer', 'value' => array());
    while($answer[$i]['name'] != 'main_question_count' && $answer[$i]['value']!=['start_of_main']){
        $question = explode('_',$answer[$i]['name']);
        $question_id = $question[2];
        if($question_id == 'engineer'){
            do {
                $question = explode('_',$answer[$i]['name']);
                array_push($engineer['value'], array('email' => $question[3], 'value' => $answer[$i]['value']));
                $i++;
            } while (strpos($answer[$i]['name'], 'engineer') !== false);
            array_push($temp_answer, $engineer);
        } else {
            if($answer[$i]['value'] != '')
            array_push($temp_answer, array('question_id' => $question_id, 'value' => $answer[$i]['value']));
        }
        $i++;
    }
    if($answer[$i]['name'] == 'main_question_count' && $answer[$i]['value'] == 'start_of_main'){
        $i += 1;
        while($answer[$i]['name'] != '' && $answer[$i]['name'] != 'main_question_count' && $answer[$i]['value']!=['end_of_main']){
            $question = explode('_',$answer[$i]['name']);
            $question_id = $question[3];
            if($question_id == 'engineer'){
                do {
                    $question = explode('_',$answer[$i]['name']);
                    array_push($engineer, array('email' => $question[4], 'value' => $answer[$i]['value']));
                    $i++;
                } while (strpos($answer[$i]['name'], 'engineer') !== false);
                array_push($main_answer, $engineer);
            } else {
                if($answer[$i]['value'] != '')
                array_push($main_answer, array('question_id' => $question_id, 'value' => $answer[$i]['value']));
            }
            $i++;
        }
    }
    $temp_answer = json_encode($temp_answer);
    $main_answer = json_encode($main_answer);
    $insert = sprintf("(`survey_id`, `survey_link`, `template_survey_answer`, `main_survey_answer`) VALUES (%s,%s, %s, %s)",
            GetSQLValueString($survey_id, "int"),
            GetSQLValueString($survey_link, "text"),
            GetSQLValueString($temp_answer, "text"),
            GetSQLValueString($main_answer, "text"),
        );
    $res = $DB1->insert_data('trx_survey', $insert);
    echo 1;
}