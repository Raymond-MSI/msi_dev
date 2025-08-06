<div class="card shadow mb-4">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-secondary">Employee</h6>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <?php
            $mdlname = "HCM";
            $DBLD = get_conn($mdlname);
            $tblname = "view_employees";
            $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND (job_structure LIKE 'EA%' OR job_structure LIKE 'KB%' OR job_structure LIKE 'RW%' OR job_structure LIKE 'THK%')";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Sales & Business</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND job_structure LIKE 'JW%'";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Corporate Service</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND (job_structure LIKE 'JG%' OR job_structure LIKE 'LWW%' OR job_structure LIKE 'RBC%')";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tech. & Solution</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $mysql = "SELECT COUNT('employee_id') AS `lastyear` FROM `sa_view_employees` WHERE `join_date` < '" . date("Y-m-d", mktime(0,0,0,1,1,date("Y"))) . "' AND isnull(`resign_date`)";
            $lastyear = $DBHCM->get_sql($mysql);
            $mysql = "SELECT COUNT('employee_id') AS `now` FROM `sa_view_employees` WHERE `join_date` <= '" . date("Y-m-d", mktime(0,0,0,31,12,date("Y"))) . "' AND isnull(`resign_date`)";
            $now = $DBHCM->get_sql($mysql);
            $mysql = "SELECT COUNT('employee_id') AS `resign` FROM `sa_view_employees` WHERE `resign_date` >= '" . date("Y-m-d", mktime(0,0,0,1,1,date("Y"))) . "' AND `resign_date` <= '" . date("Y-m-d", mktime(0,0,0,31,12,date("Y"))) . "'";
            $resign = $DBHCM->get_sql($mysql);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Turnover</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo number_format($resign[0]['resign']/($lastyear[0]['lastyear'] + $now[0]['now'])/2*100, 2, ".", ",") . "%"; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $condition = "join_date >= '" . date("Y-m-d", strtotime("-1 day"), ) . "'";
            $order = "join_date DESC, nik DESC";
            $newEmp = $DBHCM->get_data($tblname, $condition, $order);
            ?>
            <div class="col-xl-12 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today Update</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $newEmp[2]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="text-xs mb-1 table-responsive">
                                            <table class="table table-sm">
                                                <tr class="bg-light"><th>NIK</th><th>Employee Name</th><th>Organization</th></tr>
                                                <?php
                                                if($newEmp[2]>0) {
                                                    // $i=0;
                                                    do {
                                                        // if($i<4) {
                                                            echo "<tr><td class='align-text-top'>" . $newEmp[0]['nik'] . "</td><td class='align-text-top'>" . $newEmp[0]['employee_name'] . "</td><td class='align-text-top'>" . $newEmp[0]['organization_name'] . "</td></tr>";
                                                        // }
                                                        // $i++;
                                                    } while($newEmp[0]=$newEmp[1]->fetch_assoc());
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>