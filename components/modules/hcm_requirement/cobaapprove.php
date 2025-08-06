<?php

// $_SESSION{
//     'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';


// echo "==========";
// echo "Execution module : Alert email 2024";
// echo "Started : " . date("d-M-Y G:i:s");
// echo "==========<br/>";
// $time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();


global $DBHCM;
$mdlname = "REQUIREMENT_HCM";
$DBHCM = get_conn($mdlname);
$idrequirement = $_GET['id'];
$query = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement where id = '" . $idrequirement . "'");
$data = mysqli_fetch_array($query[1]);
if ($data) {
    echo "<table width='100%'>
            <tr>
                <td width='20%' rowspan='4'></td>
                <td style='width:60%; padding:20px; border:thin solid #dadada'>
                <td width='20%' rowspan='4'>
            </tr>
            <tr>
                <td style='padding:20px; border:thin solid #dadada'>
                    <table width='80%'>
                        <tr>
                            <td>Divisi</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['divisi']) . "</td>
                        </tr>
                        <tr>
                            <td>Posisi</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['posisi']) . "</td>
                        </tr>
                        <tr>
                            <td>Kode Project</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['project_code']) . "</td>
                        </tr>
                        <tr>
                            <td>Status Requirement</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['status_rekrutmen']) . "</td>
                        </tr>
                        <tr>
                            <td>Status Karyawan</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['status_karyawan']) . "</td>
                        </tr>
                        <tr>
                            <td>Kandidat</td>
                            <td>:</td>
                            <td>" . htmlspecialchars($data['kandidat']) . "</td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td>:</td>
                            <td>
                            <textarea type='text' class='form-control' id='deskripsi' name='deskripsi' rows='2' required style='width: 465px; height: 100px;'></textarea>
                            </td>
                        </tr>
                    </table>
                    <form method='post' action=''>
                        <input type='hidden' name='requirement_id' value='" . htmlspecialchars($data['id']) . "' />
                        <div style='text-align: right;'>
                        <input type='submit' name='dissaprove' value='Dissaprove' style='background-color: #d9534f; color: white; border: none; padding: 10px 20px; cursor: pointer;' />
                            <input type='submit' name='approve' value='Approve' style='background-color: #007bff; color: white; border: none; padding: 10px 20px; cursor: pointer;' />
                        </div>
                    </form>
                </td>
            </tr>
        </table>";
} else {
    echo "No requirement found.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
    $requirement_id = $_POST['requirement_id'];
    // Here, add your logic to handle the approval
    // For example, update the database to mark this requirement as approved
    $update_query = "UPDATE sa_hcm_requirement SET status='approved' WHERE id=" . intval($requirement_id);
    if ($DBHCM->execute($update_query)) {
        echo "Requirement ID $requirement_id has been approved.";
    } else {
        echo "Error approving requirement ID $requirement_id.";
    }
}
// $time_end = microtime(true);
// $time = $time_end - $time_start;
// echo "<br/>==========";
// echo "Finished : " . date("d-M-Y G:i:s");
// echo "The time used to run this module $time seconds";
// echo "==========";
// $DBCRON->ending($descErr);
