<?php
$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';
$mdlname = "MICROSERVICES";
$update = get_conn($mdlname);
$query = $update->get_sqlV2("select * from sa_mst_users a join sa_md_hcm.sa_view_employees_v2 b on a.email = b.employee_email where a.organization_name = 'System Engineer' and b.resign_date is null");
while ($row = $query[1]->fetch_assoc()) {
    $email = $row['email'];
    $test = array(
        "MODULE_CHANGE_REQUEST" => array(
            "title" => "Change Request",
            "user_level" => "Read"
        ),
        "MODULE_DASHBOARD_KPI" => array(
            "title" => "Dashboard KPI",
            "user_level" => "Read"
        ),
        "MODULE_EDO" => array(
            "title" => "Extra Day Off",
            "user_level" => "Read"
        ),
        "MODULE_PRODUCTIVITY" => array(
            "title" => "Productivity",
            "user_level" => "Read"
        ),
    );
    $permission = json_encode($test);
    $query_update = $update->get_res("UPDATE sa_mst_users SET permission = '$permission' where email = '$email'");
}
