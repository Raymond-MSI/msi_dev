<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    // $mdlname = "microservices";
// $DBX = get_conn($mdlname);
// $tblname = "mst_users";
// $dat = $DBX->get_data($tblname);
// $ddat = $dat[0];
// $qdat = $dat[1];
// do {
//     echo $ddat['username'] . "<br/>";
// } while($ddat=$qdat->fetch_assoc());

// $leader = get_leader("syamsul@mastersystem.co.id",1);
// $dleader = $leader[0];
// $qleader = $leader[1];
// do {
//     echo $dleader['leader_name'] . " : ";
//     echo $dleader['leader_email'] . "<br/>";
// } while($dleader=$qleader->fetch_assoc());

// $leader = get_leader("syamsul@mastersystem.co.id",0);
// $dleader = $leader[0];
// $qleader = $leader[1];
// do {
//     echo $dleader['employee_name'] . " : ";
//     echo $dleader['employee_email'] . "<br/>";
// } while($dleader=$qleader->fetch_assoc());
?>

<?php
// History Service Budget
// $mdlname = 'SERVICE_BUDGET';
// $DBSB = get_conn($mdlname);
// $tblname = 'logs';
// $condition = '';
// $order = 'log_id desc';
// $history = $DBSB->get_data($tblname, $condition, $order);
// $dhistory = $history[0];
// $qhistory = $history[1];
?>

<?php
// do {
?>
    <!-- <div class="card-header">
        <?php //echo date("d-M-Y G:i:s", strtotime($dhistory['entry_date'])) . " " . $dhistory['entry_by']; ?>
    </div>
    <div class="card-body">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-11">
            <?php //echo $dhistory['description']; ?>
        </div>
    </div>
<?php
//} while($dhistory=$qhistory->fetch_assoc());
?> -->


<?php
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$tblname = "view_employees";
$condition = "resign_date='0000-00-00'";
$employees = $DBHCM->get_data($tblname, $condition);
$demployees = $employees[0];
$qemployees = $employees[1];

do {
?>

<?php
} while($demployees=$qemployees->fetch_assoc());
?>

<ul>
    <li>
        <a href="#">Name1</a>
    </li>
    <li>
        <ul>
            <li>
                <a href="#">Name2</a>
            </li>
            <li>
                <a href="#">Name3</a>
            </li>
            <ul>
                <li>
                    <a href="#">Name2</a>
                </li>
                <li>
                    <a href="#">Name3</a>
                </li>
            </ul>
        </ul>
    </li>
    <li>
        <a href="#">Name4</a>
    </li>
</ul>

<?php } ?>