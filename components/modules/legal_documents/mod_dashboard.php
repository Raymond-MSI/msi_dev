<div class="card shadow mb-4">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-secondary">Legal Document</h6>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <?php
            $mdlname = "LEGAL_DOCUMENTS";
            $DBLD = get_conn($mdlname);
            $tblname = "documents";
            $condition = "date_expired >=  '" . date("Y-m-d") . "'";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Actived</div>
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
            $condition = "date_expired <  '" . date("Y-m-d") . "'";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Expired</div>
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
            $tdoc = $DBLD->get_data($tblname);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total</div>
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
            $condition = "date_released >= '" . date("Y-m-d", strtotime("-1 day"), ) . "'";
            $order = "date_released DESC";
            $released = $DBLD->get_data($tblname, $condition, $order);
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
                                                <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $released[2]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="text-xs mb-1 table-responsive">
                                            <table class="table table-sm">
                                                <tr class="bg-light"><th>Doc. Number</th><th>Released</th><th>Expired</th></tr>
                                                <?php
                                                if($released[2]>0) {
                                                    // $i=0;
                                                    do {
                                                        // if($i<4) {
                                                            echo "<tr><td class='align-text-top'>" . $released[0]['doc_number'] . "</td><td class='align-text-top'>" . $released[0]['date_released'] . "</td><td class='align-text-top'>" . $released[0]['date_expired'] . "</td></tr>";
                                                        // }
                                                        // $i++;
                                                    } while($released[0]=$released[1]->fetch_assoc());
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