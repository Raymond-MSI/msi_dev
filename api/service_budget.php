<?php
date_default_timezone_set( "Asia/Jakarta" );

include( "../applications/connections/connections.php" );
include( "../components/classes/func_databases_v3.php" );
include( "../components/classes/func_functions.php" );
include( "../components/classes/func_modules.php" );

$DB = new Databases($hostname, $username, $password, "sa_microservices");

$DBSB = get_conn("SERVICE_BUDGET");

if(isset($_GET['data']) && $_GET['data']=='project') {
    $tblname = "trx_project_list";
    $condition = "";
    $sambung = "";
    if(isset($_GET['project_id'])) {
        $condition = "project_id=" . $_GET['project_id'];
        $sambung = " AND ";
    }
    if(isset($_GET['status'])) {
        $condition .= $sambung . " status = '" . $_GET['status'] . "'";
        $sambung = " AND ";
    }
    $projects = $DBSB->get_data($tblname, $condition);
    $dprojects = $projects[0];
    $qprojects = $projects[1];
    $tproject = $projects[2];

    if($tproject>0) {
        do {
            $data[] = $dprojects;
        } while($dprojects=$qprojects->fetch_assoc());

        $response = array(
            'status' => 200,
            'message' => 'Action completed successfully',
            'errors' => "None",
            'data' => $data
        );

    } else {
        $response = array(
            'status' => 204,
            'message' => 'Data not available',
            'errors' => 'None',
            'data' => "None"
        );
    }

} else {
    $response = array(
        'status' => 204,
        'message' => 'The data to be retrieved has not been determined.',
        'errors' => 'None',
        'data' => "None"
    );
}

$json = json_encode($response);
if(isset($_GET['view']) && $_GET['view']=='true') {
    header('Content-Type: application/json');
    echo $json;
}

?>
