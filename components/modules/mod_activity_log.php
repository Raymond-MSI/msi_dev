<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>

<script> 
    $(document).ready(function() {
        var table = $('#log_database').DataTable( {
            // dom: 'Blfrtip',
            "order": [
                [ 0 , "desc"]
            ],
            select: {
                style: 'single'
            },
            "columnDefs": [
                {
                    // project_id
                    "targets": [ 0,1 ],
                    "visible": false
                }
            ]
        });
    });
</script>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <?php 
                    if(isset($_POST['modact'])) {
                        $dbname = $_POST['modact'];
                    } else {
                        $dbname = "sa_microservices";
                    }
                    $lognameexp = explode("_",$dbname);
                    $logname = "";
                    for($i=1;$i<count($lognameexp);$i++) {
                        $logname .= $lognameexp[$i]. " ";
                    }
                    ?>
                    <h6 class="m-0 font-weight-bold text-primary">Logs <?php echo $logname; ?></h6>
                </div>
                <?php
                $tblname = 'cfg_web';
                $condition = 'parent=10';
                $dbsetup = $DB->get_data($tblname, $condition);
                $ddbsetup = $dbsetup[0];
                $qdbsetup = $dbsetup[1];
                ?>
                <div class="card-body">
                    <form method="post" action="index.php?mod=activity_log">
                        <div class="row">
                            <div class="col-lg-9"></div>
                            <div class="col-lg-2">
                                <select class="form-select" name="modact" id="modact">
                                    <?php 
                                    do { 
                                        $params = get_params($ddbsetup['params']);
                                        if($params['log_database']['visibility']==1) {
                                            // $titleexp = explode(" ",$ddbsetup['title']);
                                            // $title = "";
                                            // for($i=1;$i<count($titleexp);$i++) {
                                            //     $title .= $titleexp[$i] . " ";
                                            // }
                                            $title = $ddbsetup['title'];
                                            if($dbname==$params['database']['database_name']) {
                                                $hostname = $params['database']['hostname'];
                                                $username = $params['database']['username'];
                                                $userpassword = $params['database']['userpassword'];
                                            }
                                            ?>
                                            <option value="<?php echo $params['database']['database_name']; ?>" <?php if($dbname == $params['database']['database_name']) { echo "selected"; } ?>><?php echo $title; ?></option>
                                            <?php
                                        } 
                                    } while($ddbsetup=$qdbsetup->fetch_assoc()) ?>
                                </select>
                            </div>
                            <div class="col-lg-1 text-center">
                                <input type="submit" class="btn btn-primary" value="submit">
                            </div>
                        </div>
                    </form>
                    <?php 
                    // $username = "root";
                    // $password = "";
                    $DBLOG = new Databases($hostname, $username, $userpassword, $dbname);
                    $tblname = "log_database";
                    $primarykey = "log_id";
                    $condition = "entry_by='" . $_SESSION['Microservices_UserEmail'] . "'";
                    $order = "log_id DESC";
                    view_table($DBLOG, $tblname, $primarykey, $condition, $order, $firstRow = 0, $totalRow = 100);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>