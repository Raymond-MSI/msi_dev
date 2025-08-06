<div class="card shadow mb-4">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-secondary">CRM Order</h6>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <?php
            $mdlname = "NAVISION";
            $DBLD = get_conn($mdlname);
            $tblname = "mst_order_number";
            $condition = "status_order=0";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Open Order</div>
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
            $condition = "status_order=1";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Created SB</div>
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
            $condition = "";
            $tdoc = $DBLD->get_data($tblname, $condition);
            ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Order</div>
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
            $condition = "create_date >= '" . date("Y-m-d", strtotime("-1 day"), ) . "' AND `so_number` LIKE '%/SO/%'";
            $order = "create_date DESC";
            $orders = $DBLD->get_data($tblname, $condition, $order);
            ?>
            <div class="col-xl-12 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Update Today</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $orders[2]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-9">
                                        <div class="text-xs mb-1">
                                        <table class="table table-sm">
                                            <tr class="bg-light"><th>Project Code</th><th>Order Number</th><th>SO Number</th><th>Sales Name</th></tr>
                                            <?php
                                            if($orders[2]>0) {
                                                do {
                                                    echo "<tr><td class='align-text-top'>" . $orders[0]['project_code'] . "</td><td class='align-text-top'>" . $orders[0]['order_number'] . "</td><td class='align-text-top'>" . $orders[0]['so_number'] . "</td><td class='align-text-top'>" . $orders[0]['sales_name'] . "</td></tr>";
                                                } while($orders[0]=$orders[1]->fetch_assoc());
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