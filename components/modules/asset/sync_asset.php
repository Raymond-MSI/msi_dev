<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-secondary">Asset Data Reconciliation<?php spinner(); ?></h6>
        <div class="align-items-right">
        <!-- <a href="index.php?mod=service_budget" class="btn btn-light border-secondary" title='Back to Service Budget' style="font-size:10px; background-color:#ddd"><i class='fa fa-arrow-left'></i></a> -->
            <!-- <button type="button" class="btn btn-light border-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px; background-color:#ddd"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button> -->
        </div>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Table</th>
                                <th>Records</th>
                                <th>Reconciliation Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Item Asset</td>
                                <td class=""><span name="asset" id="asset" value=" ">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_asset" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Total Asset</td>
                                <td class=""><span id="total_asset" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_total" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Lifecycle</td>
                                <td class=""><span id="lifecycle" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_lifecycle" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>CIDB</td>
                                <td class=""><span id="cidb" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_cidb" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td class=""><span id="price" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_price" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Sudirman Park Location</td>
                                <td class=""><span id="SudirmanPark" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_sudirman" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Wisma 7.8 Location</td>
                                <td class=""><span id="Wisma78" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_wisma" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Surabaya Location</td>
                                <td class=""><span id="Surabaya" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_surabaya" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>DC/DRC Location</td>
                                <td class=""><span id="DCDRC" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_dcdrc" value="Reconciliation"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table">
                        <thead>
                            <th>Table</th>
                            <th>Records</th>
                            <th>Reconciliation Data</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Warehouse Location</td>
                                <td class=""><span id="Warehouse" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_warehouse" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Customer Loan Location</td>
                                <td class=""><span id="Customer" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_customer" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Employee Loan Location</td>
                                <td class=""><span id="Karyawan" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_karyawan" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Damaged Location</td>
                                <td class=""><span id="Rusak" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_rusak" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Others Location</td>
                                <td class=""><span id="Others" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_others" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Proses Order Location</td>
                                <td class=""><span id="Order" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_order" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Recurrence Last Year</td>
                                <td class=""><span id="Recurrence" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_recurrence" value="Reconciliation"></td>
                            </tr>
                            <tr>
                                <td>Recurrence From Beginning</td>
                                <td class=""><span id="Recurrence_All" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_recurrence_all" value="Reconciliation"></td>
                            </tr>
                            <!-- <tr>
                                <td>Currency PO</td>
                                <td class=""><span id="Currency" value="">0/0</span></td>
                                <td class=""><input type="submit" class="btn btn-primary" name="btn_currency" value="Reconciliation"></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
if(!isset($_GET['act'])) {
    $_SESSION[ "Microservices_UserEmail" ]="System";
}
$mdlname = "ASSET";
$DBAS = get_conn($mdlname);

// Update Asset to Asset Summary
if(isset($_POST['btn_asset'])) {
    $tblname = "mst_asset";
    $assets = $DBAS->get_data($tblname);
    $i = 0;
    $t = 0;
    do {
        $tblname = "trx_asset_summary";
        $condition = "part_number = '" . $assets[0]['part_number'] . "' AND brand_name = '" . $assets[0]['brand_name'] . "' AND asset_owner = '" . $assets[0]['asset_owner'] . "'";
        $summary = $DBAS->get_data($tblname, $condition);
        if($summary[2]==0) {
            $mysql = "(`asset_owner`, `part_number`, `brand_name`) VALUES ('" . $assets[0]['asset_owner'] . "', '" . $assets[0]['part_number'] . "', '" . $assets[0]['brand_name'] . "')";
            $res = $DBAS->insert_data($tblname, $mysql);
            $i++;
        }
        $t++;
        echo "<script>document.getElementById('asset').innerHTML='" . $i . "/" . $t . "';</script>";
    } while($assets[0]=$assets[1]->fetch_assoc());
}

$tblnameS = "trx_asset_summary";
$asset_summary = $DBAS->get_data($tblnameS);
$i = 0;
// Update Total Asset
if(isset($_POST['btn_total']) and $asset_summary[2]>0) {
    do {
        $mysql = "SELECT COUNT('asset_id') AS `total_asset` from `sa_mst_asset` WHERE `asset_owner` = '" . $asset_summary[0]['asset_owner'] . "' AND `part_number` = '" . $asset_summary[0]['part_number'] . "' AND `brand_name` = '" . $asset_summary[0]['brand_name'] . "' GROUP BY `asset_owner`, `part_number`, `brand_name`";
        $totalAsset = $DBAS->get_sql($mysql);
        if($totalAsset[2]>0) {
            $condition = "`asset_owner` = '" . $asset_summary[0]['asset_owner'] . "' AND `part_number` = '" . $asset_summary[0]['part_number'] . "' AND `brand_name` = '" . $asset_summary[0]['brand_name'] . "'";
            $mysql = "`total_asset` = " . $totalAsset[0]['total_asset'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('total_asset').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Lifecycle
if(isset($_POST['btn_lifecycle'])) {
    do {
        $tblname = "mst_lifecycle";
        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "'";
        $totalAsset = $DBAS->get_data($tblname, $condition);

        if($totalAsset[2]>0) {
            $condition = "`asset_owner` = 'BACKUP' AND `part_number` = '" . $asset_summary[0]['part_number'] . "'";
            $mysql = sprintf("`end_of_product_sales`=%s, `end_of_new_service_attachment_date`=%s, `last_date_of_renew`=%s, `end_of_software_maintenance_date`=%s, `ldos`=%s,`end_of_life_product_bulletin`=%s, `new_part_number`=%s",
                GetSQLValueString($totalAsset[0]['end_of_product_sales'], "date"),
                GetSQLValueString($totalAsset[0]['end_of_new_service_attachment_date'], "date"),
                GetSQLValueString($totalAsset[0]['last_date_of_renew'], "date"),
                GetSQLValueString($totalAsset[0]['end_of_software_maintenance_date'], "date"),
                GetSQLValueString($totalAsset[0]['ldos'], "date"),
                GetSQLValueString($totalAsset[0]['end_of_life_product_bulletin'], "text"),
                GetSQLValueString($totalAsset[0]['new_part_number'], "text")
            );
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('lifecycle').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Total CIDB
if(isset($_POST['btn_cidb'])) {
    do {
        $tblname = "cidb_summary";
        $mysql = "SELECT SUM(`total_unit`) AS `total_cidb` FROM `sa_cidb_summary` WHERE `maintenance_end`>NOW() AND `part_number` = '" . $asset_summary[0]['part_number'] . "' GROUP BY `part_number`;";
        $cidbs = $DBAS->get_sql($mysql);

        if($cidbs[2]>0) {
            $condition = "`asset_owner` = 'BACKUP' AND `part_number` = '" . $asset_summary[0]['part_number'] . "'";
            if($cidbs[0]['total_cidb']>0) {
                if($cidbs[0]['total_cidb']>0) {
                    $mysql = "`total_cidb` = " . $cidbs[0]['total_cidb'];
                } else {
                    $mysql = "`total_cidb` = NULL";
                }
                $res = $DBAS->update_data($tblnameS, $mysql, $condition);
            } else {
                $mysql = "`total_cidb` = NULL";
                $res = $DBAS->update_data($tblnameS, $mysql, $condition);
            }
        }
        $i++;
        echo "<script>document.getElementById('cidb').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Price
if(isset($_POST['btn_price'])) {
    do {
        $tblname = "mst_purchases";
        // $mysql = "SELECT `sa_mst_purchases`.`currency`,`sa_mst_purchases`.`direct_unit_cost` AS `price` FROM `sa_mst_purchases` WHERE `sa_mst_purchases`.`id` IN( SELECT MAX(`sa_mst_purchases`.`id`) FROM `sa_mst_purchases` GROUP BY `sa_mst_purchases`.`part_number`) AND `part_number` = '" . $asset_summary[0]['part_number'] . "';";
        // $mysql = "SELECT `sa_mst_purchases`.`currency`,`sa_mst_purchases`.`direct_unit_cost` AS `price` FROM `sa_mst_purchases` WHERE `part_number` = '" . $asset_summary[0]['part_number'] . "' ORDER BY `id` DESC limit 0,1;";
        // $prices = $DBAS->get_sql($mysql);
        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "'";
        $order = "`po_number` DESC";
        $prices = $DBAS->get_data($tblname, $condition, $order, 0, 1);

        if($prices[2]>0) {
            $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "'";
            $mysql = "`currency` = '" . $prices[0]['currency'] . "' , `price` = " . $prices[0]['direct_unit_cost'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('price').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// UPDATE LOCATION
// Update Sudirman Park Location
if(isset($_POST['btn_sudirman'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Sudirman Park' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`sudirman_park` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`sudirman_park` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('SudirmanPark').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Wisma 7.8 Location
if(isset($_POST['btn_wisma'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Wisma 7.8' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`wisma_7.8` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`wisma_7.8` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Wisma78').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Surabaya Location
if(isset($_POST['btn_surabaya'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Surabaya' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`surabaya` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`surabaya` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Surabaya').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Warehouse Location
if(isset($_POST['btn_warehouse'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Warehouse' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`warehouse` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`warehouse` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Warehouse').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update DC/DRC Location
if(isset($_POST['btn_dcdrc'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'DC/DRC' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`dc/drc` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`dc/drc` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('DCDRC').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Pinjaman Customer Location
if(isset($_POST['btn_customer'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Pinjaman Customer' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`pinjaman_customer` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`pinjaman_customer` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Customer').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Pinjaman Karyawan Location
if(isset($_POST['btn_karyawan'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Pinjaman Karyawan' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`pinjaman_karyawan` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`pinjaman_karyawan` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Karyawan').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Other Location
if(isset($_POST['btn_others'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Other' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`other` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`other` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Others').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Barang Rusak Location
if(isset($_POST['btn_rusak'])) {
    do {
        $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Barang Rusak' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`barang_rusak` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`barang_rusak` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Rusak').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Proses Order Location
if(isset($_POST['btn_order'])) {
    do {
    $mysql = "SELECT COUNT(`part_number`) AS `total_unit` FROM `sa_mst_asset` LEFT JOIN `sa_mst_asset_locations` ON `sa_mst_asset`.`asset_location_code`=`sa_mst_asset_locations`.`location_code` WHERE `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`='" . $asset_summary[0]['brand_name'] . "' AND `part_number`='" . $asset_summary[0]['part_number'] . "' AND `location_nikname` = 'Proses Order' GROUP BY `part_number`,`brand_name`,`asset_owner`, `location_nikname`;";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`proses_order` = " . $totalbylocation[0]['total_unit'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`proses_order` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Order').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Recurrence Last Year
if(isset($_POST['btn_recurrence'])) {
    do {
    $mysql = "SELECT `sa_md_asset`.`sa_mst_asset_requests`.`asset_owner` AS `asset_owner`,`sa_md_asset`.`sa_mst_asset_requests_line`.`part_number` AS `part_number`,COUNT(`sa_md_asset`.`sa_mst_asset_requests_line`.`request_line_id`) AS `recurrence_loan` FROM (`sa_md_asset`.`sa_mst_asset_requests` LEFT JOIN `sa_md_asset`.`sa_mst_asset_requests_line` ON (`sa_md_asset`.`sa_mst_asset_requests`.`request_number` = `sa_md_asset`.`sa_mst_asset_requests_line`.`request_number`)) WHERE `sa_md_asset`.`sa_mst_asset_requests`.`document_date` >= cast(current_timestamp() AS date) - interval 1 year AND (`sa_md_asset`.`sa_mst_asset_requests_line`.`document_type` = 'Loan' OR `sa_md_asset`.`sa_mst_asset_requests_line`.`document_type` = 'Request') AND `sa_mst_asset_requests`.`asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `sa_mst_asset_requests_line`.`part_number`='" . $asset_summary[0]['part_number'] . "' GROUP BY `sa_md_asset`.`sa_mst_asset_requests`.`asset_owner`,`sa_md_asset`.`sa_mst_asset_requests_line`.`part_number`";
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`recurrence_loan` = " . $totalbylocation[0]['recurrence_loan'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`recurrence_loan` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Recurrence').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// Update Recurrence All
if(isset($_POST['btn_recurrence_all'])) {
    do {
    $mysql = "SELECT `sa_md_asset`.`sa_mst_asset_requests`.`asset_owner` AS `asset_owner`,`sa_md_asset`.`sa_mst_asset_requests_line`.`part_number` AS `part_number`,COUNT(`sa_md_asset`.`sa_mst_asset_requests_line`.`request_line_id`) AS `recurrence_all` FROM (`sa_md_asset`.`sa_mst_asset_requests` LEFT JOIN `sa_md_asset`.`sa_mst_asset_requests_line` ON (`sa_md_asset`.`sa_mst_asset_requests`.`request_number` = `sa_md_asset`.`sa_mst_asset_requests_line`.`request_number`)) WHERE (`sa_md_asset`.`sa_mst_asset_requests_line`.`document_type` = 'Loan' OR `sa_md_asset`.`sa_mst_asset_requests_line`.`document_type` = 'Request') AND `sa_mst_asset_requests`.`asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `sa_mst_asset_requests_line`.`part_number`='" . $asset_summary[0]['part_number'] . "' GROUP BY `sa_md_asset`.`sa_mst_asset_requests`.`asset_owner`,`sa_md_asset`.`sa_mst_asset_requests_line`.`part_number`";
    
        $totalbylocation = $DBAS->get_sql($mysql);

        $condition = "`part_number` = '" . $asset_summary[0]['part_number'] . "' AND `asset_owner`='" . $asset_summary[0]['asset_owner'] . "' AND `brand_name`= '" . $asset_summary[0]['brand_name'] . "'";
        if($totalbylocation[2]>0) {
            $mysql = "`recurrence_all` = " . $totalbylocation[0]['recurrence_all'];
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        } else {
            $mysql = "`recurrence_all` = NULL";
            $res = $DBAS->update_data($tblnameS, $mysql, $condition);
        }
        $i++;
        echo "<script>document.getElementById('Recurrence_All').innerHTML='" . $i . "/" . $asset_summary[2] . "';</script>";
    } while($asset_summary[0]=$asset_summary[1]->fetch_assoc());
}

// // Update Currency
// if(isset($_POST['btn_currency'])) {
//     $tblnameP = "mst_purchases";
//     $purchases = $DBAS->get_data($tblnameP);
//     do {
//         $tblname = "mst_purchases_header";
//         $condition = "`po_number`='" . $purchases[0]['po_number'] . "'";
//         $order = "";
//         $currency = $DBAS->get_data($tblname, $condition, $order, 0,1);

//         $condition = "`po_number` = '" . $purchases[0]['po_number'] . "'";
//         if($currency[2]>0) {
//             $mysql = "`currency` = '" . $currency[0]['currency'] . "'";
//             $res = $DBAS->update_data($tblnameP, $mysql, $condition);
//         } else {
//             $mysql = "`currency` = NULL";
//             $res = $DBAS->update_data($tblnameP, $mysql, $condition);
//         }
//         $i++;
//         echo "<script>document.getElementById('Currency').innerHTML='" . $i . "/" . $purchases[2] . "';</script>";
//     } while($purchases[0]=$purchases[1]->fetch_assoc());
// }
?>