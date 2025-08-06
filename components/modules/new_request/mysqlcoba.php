<?php
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';
echo "==========";
echo "Execution module : trx_request_requirement";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);

global $DBREC;
$mdlname = "new_request";
$DBREC = get_conn($mdlname);
$narik = $DBREC->get_sqlV2("SELECT DISTINCT id_assign,assign FROM sa_assign");

var_dump($narik);


$id_assign = $narik[0]['id_assign'];
$assign = $narik[0]['assign'];
$update_assign = sprintf("assign = 'malik'");
$assign_kondisi = "id_assign =" . $id_assign;

// for ($i = 1; $i <= 5; $i++) {
//     $value = "coba" . $i;
//     $insert = sprintf(
//         "(`assign`) VALUES (%s)",
//         GetSQLValueString("coba", "text")
//     );
//     $inserrtttt = $DBREC->insert_data('assign', $insert);
//     if ($insert) {
//         echo "berhasil kok";
//     } else {
//         echo " gagal";
//     }
// }

// } else {
// if ($assign == "Assign Recruitment") {
//     $update = $DBREC->get_res("UPDATE sa_assign SET assign = 'malik' WHERE assign NOT LIKE '%Assign Recruitment%'");
// } else {
//     $delete = $DBREC->get_res("DELETE FROM sa_assign where assign NOT LIKE '%Assign Recruitment%'");
// }

// $alter_table = $DBREC->get_res("ALTER TABLE sa_assign ADD malik text null default null after assign");
// $alter_table = $DBREC->get_res("ALTER TABLE sa_assign DROP malik");
// }
