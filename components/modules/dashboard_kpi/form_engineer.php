<?php
$dbdb = "DASHBOARD_KPI";
$DBKPI = get_conn($dbdb);

$sbsb = "SERVICE_BUDGET";
$DBSB1 = get_conn($sbsb);

$wrwr = "WRIKE_INTEGRATE";
$DBWR = get_conn($wrwr);

$crcr = "CHANGE_REQUEST";
$DBCR = get_conn($crcr);

$hcm = "HCM";
$DBHCM = get_conn($hcm);

$name = $_GET['nama'];
$idaja = str_replace("&20", " ", $name);
$id = preg_replace("/[']/", "", $idaja);
$idorang = str_replace("[_]", " ", $id);
$hobi = explode("<", $idorang);
$nama = $hobi[0];

$sql = $DBKPI->get_sql("SELECT * FROM sa_summary_user WHERE Nama LIKE '%$nama%'");
// $data3 = mysqli_fetch_assoc($retval);
$sqlq = $DBKPI->get_sql("SELECT * FROM sa_user_kpi WHERE Nama LIKE '%$nama%'");
// $data4 = mysqli_fetch_assoc($retvalq);
$hcm = $DBHCM->get_sql("SELECT department_name FROM sa_view_employees_v2 WHERE employee_name='$nama'");
// $hcm[0] = mysqli_fetch_assoc($retval_hcm);
$row = $sqlq[0]['end_assignment'];
$periode = explode("-", $row);
if ($sql[0]['Nama'] == NULL) {
    echo 'Data kamu tidak ada';
} else {
    $nilai_project = number_format($sql[0]['nilai_project'], 5, ",", ".");
    $nilai_personal_assignment = number_format($sql[0]['nilai_personal_assignment'], 5, ",", ".");
    $total_nilai = number_format($sql[0]['total_nilai'], 5, ",", ".");
    $project = number_format($sql[0]['project'], 2);
    $personal_assignment = number_format($sql[0]['personal_assignment'], 2);
?>
    <?php if (!isset($_GET['act'])) { ?>
        <div class="col-lg-6">
            <label>Periode : </label>
            <select name="" id="periode_kpi">
                <option value="All">All</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
            </select>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <div class="col-sm-10">
                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Nama : </label>
                    <label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm"><?php echo $sql[0]['Nama']; ?></label><br>
                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Produktifitas :</label>
                    <label for="inputCID3" class="col-sm-5 col-form-label col-form-label-sm"><?php echo $sql[0]['produktifitas'] . " Project"; ?></label><br>
                    <!-- <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Periode : </label>
                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"><?php echo $periode[0]; ?></label><br> -->
                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Department : </label>
                    <label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm"><?php echo $hcm[0]['department_name']; ?></label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Project</label>
                    <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"><?php echo $project * 100 . "%"; ?></label>
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"><?php echo $nilai_project; ?></label>
                    <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Personal Assignment</label>
                    <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"><?php echo $personal_assignment * 100 . "%"; ?></label>
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"><?php echo $nilai_personal_assignment; ?></label><br>
                    <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total </label>
                    <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">:</label>
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"><?php echo $total_nilai; ?></label>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="inputCID3" class="col-sm-22 col-form-label col-form-label-sm">Data User</label>
            </div>
        </div>
    </div>
<?php
}
function lihat_data($tblname, $condition)
{
    // Definisikan tabel yang akan ditampilkan dalam DataTable
    global $DBKPI;
    $primarykey = "id";
    $order = "";
    if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
        global $ALERT;
        $ALERT->datanotfound();
    }
    view_table($DBKPI, $tblname, $primarykey, $condition, $order);
}
$condition = "Nama LIKE '%$nama%'";
lihat_data("user", $condition);
?>
<br>
<br>
<div class="col-lg-6">
    <div class="row mb-3">
        <div class="col-sm-12">
            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm">Data Project</label>
        </div>
    </div>
</div>
<?php
function lihat_data_project($tblname, $condition)
{
    // Definisikan tabel yang akan ditampilkan dalam DataTable
    global $DBKPI;
    $primarykey = "id";
    $order = "";
    if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
        global $ALERT;
        $ALERT->datanotfound();
    }
    view_table($DBKPI, $tblname, $primarykey, $condition, $order);
}
$condition = "Nama LIKE '%$nama%'";
lihat_data_project("view_project_kpi", $condition);

?>
<!-- <input type="submit" class="btn btn-primary" name="export" id="export" value="Export"> -->
<input type="button" class="btn btn-primary" value="Back" onclick="history.back(-1)" />
<a href="components/modules/get_data/export_data_engineer.php?nama='<?php echo $nama; ?>'"><button class="btn btn-primary">Export to Excel</button></a><br />

<script>
    $(document).on('change', '#periode_kpi', function() {
        var sta = $('#periode_kpi').val();
        var nama = $_GET['nama'];
        if (sta == "All") {
            window.location = window.location.pathname + "?mod=dashboard_kpi&nama=" + nama;
        } else {
            window.location = window.location.pathname + "?mod=dashboard_kpi&nama" + nama + "&periode_kpi=" + sta;
        }
    });
    <?php if (isset($_GET['periode_kpi'])) { ?>
        $('#periode_kpi option[value=<?php echo $_GET['periode_kpi']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>