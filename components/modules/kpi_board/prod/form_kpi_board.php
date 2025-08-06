<?php
$dbdb = "DASHBOARD_KPI";
$DBKPI = get_conn($dbdb);

$sbsb = "SERVICE_BUDGET";
$DBSB = get_conn($sbsb);

$wrwr = "WRIKE_INTEGRATE";
$DBWR = get_conn($wrwr);

$survey = "SURVEY";
$DBSV = get_conn($survey);

$db_name = "HCM";
$DBHCM = get_conn($db_name);

$crcr = "CHANGE_REQUEST";
$DBCR = get_conn($crcr);

$check_approval = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE job_level LIKE '%2%' AND resign_date IS NULL ORDER BY employee_name");
$check_data = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number ='" . $_GET['so_number'] . "'");
$get_data = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi = '" . $_GET['so_number'] . "'");
$history = $DBKPI->get_sql("SELECT * FROM sa_log_board WHERE so_number ='" . $_GET['so_number'] . "'");
$pic_apr = $DBHCM->get_data("view_employees", "((job_structure LIKE '%JG%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_structure LIKE '%LWW%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_structure LIKE '%RBC%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_title LIKE '%Direktur%' AND resign_date IS NULL)) ORDER BY employee_name");
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <?php
    if ($_GET['act'] == 'edit') {
    ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Information</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#historyx" type="button" role="tab" aria-controls="history" aria-selected="false" style="color: black;">History</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- TAB Change Request Type -->
            <div class="tab-pane fade show active" id="informationx" role="tabpanel" aria-labelledby="crtype-tab">
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
        
        <div class="row">
            <div class="col-lg-6">
                <?php
                $check_error = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='" . $_GET['so_number'] . "'");
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "' AND project_type='MSI Project Implementation'");
                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
                $bast_no = $DBWR->get_sql("SELECT a.document_bast_no FROM sa_wrike_project_detail a left join sa_wrike_project_list b ON a.project_id=b.id WHERE b.no_so='" . $_GET['so_number'] . "'");
                $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
                $so_number = $_GET['so_number'];
                $implementation_price = $get_data[0]['SB_amount_idr'];
                $po_maintenance = $get_data[0]['SB_maintenance_price'];
                $po_warranty = $get_data[0]['SB_warranty_price'];
                $value = $implementation_price - $po_maintenance - $po_warranty;
                ?>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Owner Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_owner[0]['owner_email'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_owner[0]['owner_email']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Belum ada di wrike" readonly>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="project_code" value="<?php echo $get_data[0]['project_code_kpi']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">So Number</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="so_number" value="<?php echo $_GET['so_number']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['customer_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['WR_status_project']; ?>" readonly>
                    </div>
                </div>
                <?php
                if (isset($check_error[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_error[0]['no_bast']; ?>">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nilai Project</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="nilai_project" value="<?php echo "Rp. " . number_format($value, 0, ".", "."); ?>" readonly>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <?php if (isset($bast_no[0]['document_bast_no'])) { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $bast_no[0]['document_bast_no']; ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nilai Project</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="nilai_project" value="<?php echo "Rp. " . number_format($value, 0, ".", "."); ?>" readonly>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-lg-6">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['project_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resources</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
                        <?php if (isset($get_engineer[0]['resource_email'])) { ?>
                            <div class="name-list">
                                <ul>
                                    <?php
                                    do {
                                        $get_name = $DBHCM->get_sql("SELECT employee_name, employee_email from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                                    ?>
                                        <li type="text" value="<?php echo $get_name[0]['employee_name']; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
                                        <!-- <input type="hidden" value="<?php echo $get_name[0]['employee_email']; ?>" name="resources" id="resources">
                                        <input type="text" value="<?php echo $get_name[0]['employee_email']; ?>" name="resources" id="resources"> -->
                                    <?php
                                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                                    ?>
                                </ul>
                            </div>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control" value="Belum Ada" readonly>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $get_survey_id = $DBSV->get_sql("SELECT * FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                        if (isset($get_survey_id[0]['survey_id'])){
                            $get_survey = $DBSV->get_sql("SELECT rating_average FROM sa_trx_survey WHERE survey_id='" . $get_survey_id[0]['survey_id'] . "'");
                            if (isset($get_survey[0]['rating_average'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php $get_survey[0]['rating_average']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        } else { ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <?php if (isset($check_error[0]['pic'])) { ?>
                            <textarea cols="35" name="pic" value="<?php echo $check_error[0]['pic']; ?>" readonly><?php echo $check_error[0]['pic']; ?></textarea>
                        <?php } else if (isset($check_data[0]['pic'])) { ?>
                            <textarea name="pic" cols="35" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
                                <option value="mega.dwi@mastersystem.co.id">mega.dwi@mastersystem.co.id</option>
                                <option value="matias.jepri@mastersystem.co.id">matias.jepri@mastersystem.co.id</option>
                                <?php do { ?>
                                    <option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
                                <?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
                            <?php } ?>
                            </select>
                    </div>
                </div>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($check_data[0]['note'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['note']; ?>" readonly><?php echo $check_data[0]['note']; ?></textarea>
                        <?php
                        } else {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" ></textarea>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
            </div>
            <?php $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE no_so='$so_number' AND project_type='MSI Project Implementation'"); 
                                      $idproject = isset($id_project[0]['id']);
                                      if (empty($idproject)) {
                                        $id = null;
                                      } else {
                                        $id = $id_project[0]['id'];
                                      }
                                      $get_jumlah_task = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id'"); 
                                      $get_updated_task = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id'"); 
                                      $jumlah_task = $get_jumlah_task[0]['jumlah_task']; 
                                      $updated_task = $get_updated_task[0]['updated_task']; 
                                      $get_data_kpi = $DBKPI->get_sql("SELECT * from sa_kpi_so_wr WHERE so_number='$so_number'"); 
                                      $weighted_val = $get_data_kpi[0]['weighted_value'];
                                      $get_sb_mandays = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi='$so_number' AND SB_service_type_implementation=1"); 
                                      $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
                                      $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
                                      if(empty($bast_planing)) { 
                                        $bast_plan = 'Empty';
                                        } else {
                                            $bast_plan = $bast_planing;
                                        } 
                                      $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
                                      if(empty($bast_actualing)) {
                                        $bast_actual = 'Empty';
                                      } else {
                                        $bast_actual = $bast_actualing;
                                      } 
                                      $cek_error = $DBKPI->get_sql("SELECT * from sa_kpi_board WHERE so_number='$so_number'"); 
                                      $nilai = isset($cek_error[0]['nilai_error']);
                                      if(empty($nilai)) {
                                        $nilaierror = 0;
                                        $persen_error = $nilaierror * 100; 
                                      } else {
                                        $nilaierror = $cek_error[0]['nilai_error'];
                                        $persen_error = $nilaierror * 100; 
                                      } 
                                      if($nilaierror >= 0.06 && $nilaierror < 0.12) {
                                        $error_result = 'Minor';
                                      } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
                                        $error_result = 'Major';
                                      } else if ($nilaierror >= 0.2) {
                                        $error_result = 'Critical';
                                      } else {
                                        $error_result = 'Normal';
                                      } 
                                      $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
                                      $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
                                      ?>
                                
                                <div class="col-lg-12">
                                    <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col">Productivity</th>
                                        <th scope="col">Cost (%)</th>
                                        <th scope="col">Time (%)</th>
                                        <th scope="col">Error (%)</th>
                                        <th scope="col">Total KPI (%)</th>
                                        <th scope="col">Weighted Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td scope="row"><?php echo $jumlah_task . " | " . $updated_task ?></td>
                                        <td>
                                            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm"><?php echo $persen_cost . "% | " . $get_data_kpi[0]['commercial_category']; ?></label>
                                            <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-10 col-form-label col-form-label-sm"><?php echo $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']); ?></label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm"><?php echo $persen_time . "% | " . $get_data_kpi[0]['time_category']; ?></label>
                                            <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-10 col-form-label col-form-label-sm"><?php echo $bast_plan . " | " . $bast_actual; ?></label>
                                            </div>
                                        </td>
                                        <td><label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm"><?php echo $persen_error . "% | " . $error_result; ?></label></td>
                                        <td><label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $total_kpi . "%"; ?></label></td>
                                        <td><label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm"><?php echo $weighted_val ?></label></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
            <div class="col-lg-12">
                <div class="row mb-3">
                    <div class="row mb-2">
                        <div class="col-6 col-form-label-sm">Error</div>
                        <div class="col-1 col-form-label-sm">Ada ?</div>
                        <div class="col-1 col-form-label-sm">Impact</div>
                        <div class="col-1 col-form-label-sm">Nilai</div>
                        <div class="col-3 col-form-label-sm">Note</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="1">Komplain lisan Customer</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category'])) { ?>
                                <select class="form-control form-control-sm" name="impact" id="impact">
                                    <option value="<?php echo $check_error[0]['error_category']; ?>"><?php echo $check_error[0]['error_category']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact" id="impact">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category'])) { ?>
                                <?php if ($check_error[0]['error_category'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes'])) { ?>
                                <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes']; ?>"><?php echo $check_error[0]['notes']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="2">Post Project Review dengan nilai rata-rata kurang dari 7</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list2'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category2'])) { ?>
                                <select class="form-control form-control-sm" name="impact2" id="impact2">
                                    <option value="<?php echo $check_error[0]['error_category2']; ?>"><?php echo $check_error[0]['error_category2']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact2" id="impact2">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category2'])) { ?>
                                <?php if ($check_error[0]['error_category2'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category2'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category2'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes2'])) { ?>
                                <textarea name="notes2" id="notes2" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes2']; ?>"><?php echo $check_error[0]['notes2']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes2" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="3">Kesalahan dokumentasi project (contoh logo, nama perusahaan, kesalahan penulisan yang menyebabkan pengertian berubah, non typo)</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list3'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category3'])) { ?>
                                <select class="form-control form-control-sm" name="impact3" id="impact3">
                                    <option value="<?php echo $check_error[0]['error_category3']; ?>"><?php echo $check_error[0]['error_category3']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact3" id="impact3">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category3'])) { ?>
                                <?php if ($check_error[0]['error_category3'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category3'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category3'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes3'])) { ?>
                                <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes3']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="4">
                                    Dokumentasi project tidak lengkap
                                    (contoh: MoM, PSR,
                                    CR (PM/PC); LLD,
                                    Migration Plan,
                                    System
                                    Impelementation
                                    (Engineer)
                                </option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list4'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category4'])) { ?>
                                <select class="form-control form-control-sm" name="impact4" id="impact4">
                                    <option value="<?php echo $check_error[0]['error_category4']; ?>"><?php echo $check_error[0]['error_category4']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact4" id="impact4">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category4'])) { ?>
                                <?php if ($check_error[0]['error_category4'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category4'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category4'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes4'])) { ?>
                                <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes4']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="5">Komplain tertulis atau Surat Peringatan dari Customer</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list5'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category5'])) { ?>
                                <select class="form-control form-control-sm" name="impact5" id="impact5">
                                    <option value="<?php echo $check_error[0]['error_category5']; ?>"><?php echo $check_error[0]['error_category5']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact5" id="impact5">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category5'])) { ?>
                                <?php if ($check_error[0]['error_category5'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category5'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category5'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes5'])) { ?>
                                <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes5']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="6">Migrasi gagal (fail) yang menyebabkan customer tidak dapat beroperasi secara normal, dengan penyebab kegagalan adalah kurangnya koordinasi dan data collection dari team project MSI</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list6'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category6'])) { ?>
                                <select class="form-control form-control-sm" name="impact6" id="impact6">
                                    <option value="<?php echo $check_error[0]['error_category6']; ?>"><?php echo $check_error[0]['error_category6']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact6" id="impact6">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category6'])) { ?>
                                <?php if ($check_error[0]['error_category6'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category6'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category6'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes6'])) { ?>
                                <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes6']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="7">Tidak melakukan eskalasi jika diperlukan (internal dan eksternal)</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list7'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category7'])) { ?>
                                <select class="form-control form-control-sm" name="impact7" id="impact7">
                                    <option value="<?php echo $check_error[0]['error_category7']; ?>"><?php echo $check_error[0]['error_category7']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact7" id="impact7">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category7'])) { ?>
                                <?php if ($check_error[0]['error_category7'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category7'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category7'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes7'])) { ?>
                                <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes7']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="8">Mastersystem dikenakan Penalty, tanpa ada eskalasi sebelumnya dan tidak ada recovery action / plan</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list8'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category8'])) { ?>
                                <select class="form-control form-control-sm" name="impact8" id="impact8">
                                    <option value="<?php echo $check_error[0]['error_category8']; ?>"><?php echo $check_error[0]['error_category8']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact8" id="impact8">
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category8'])) { ?>
                                <?php if ($check_error[0]['error_category8'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category8'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category8'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes8'])) { ?>
                                <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes8']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
	    </div>
              </div>
                </div>
            </div>
            <div class="tab-pane fade" id="historyx" role="tabpanel" aria-labelledby="history-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5>History</h5>
                        <table class="table">
                            <thead class="bg-light">
                                <th class="col-lg-2">Date</th>
                                <th class="col-lg-2">Time</th>
                                <th class="col-lg-8">Description</th>
                            </thead>
                            </thead>
                            <tbody>
                                <?php if (isset($history[0]['date'])) {
                                    do {
                                        $date = explode("-", $history[0]['date']);
                                        $tahun = $date[0];
                                        $bulan = $date[1];
                                        $tanggal = $date[2];
                                        $time = $history[0]['time'];
                                        $status = $history[0]['status'];
                                ?>
                                        <tr>
                                            <td style="font-size: 12px">
                                                <table class="table table-sm table-light table-striped">
                                                    <tr>
                                                        <td class="text-center fw-bold" colspan="2"><?php echo $tahun; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center"><?php echo $bulan; ?></td>
                                                        <td class="text-center"><?php echo $tanggal; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="font-size: 12px"><?php echo $time; ?></td>
                                            <td style="font-size: 12px"><?php echo $status; ?></td>
                                        </tr>
                                    <?php } while ($history[0] = $history[1]->fetch_assoc()); ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    if ($_GET['act'] == 'add') {
    ?>
        <?php
        $completed = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Completed%' AND so_number ='" . $_GET['so_number'] . "'");
        $hbarang = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Outstanding Barang%' AND so_number ='" . $_GET['so_number'] . "'");
        $hso = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Revisi administrasi SO%' AND so_number ='" . $_GET['so_number'] . "'");
        $openitm = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Open Item%' AND so_number ='" . $_GET['so_number'] . "'");
        $others = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Others%' AND so_number ='" . $_GET['so_number'] . "'");
        $cekcr_so = $DBCR->get_sql("SELECT * FROM sa_general_informations WHERE project_code='" . $_GET['so_number'] . "'");
        $cekcr_kp = $DBCR->get_sql("SELECT * FROM sa_general_informations WHERE project_code='" . $_GET['project_code'] . "'");
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Information</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#historyx" type="button" role="tab" aria-controls="history" aria-selected="false" style="color: black;">History</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- TAB Change Request Type -->
            <div class="tab-pane fade show active" id="informationx" role="tabpanel" aria-labelledby="crtype-tab">
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
        
        <div class="row">
            <div class="col-lg-6">
                <?php
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "' AND project_type='MSI Project Implementation'");
                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
                $bast_no = $DBWR->get_sql("SELECT a.document_bast_no FROM sa_wrike_project_detail a left join sa_wrike_project_list b ON a.project_id=b.id WHERE b.no_so='" . $_GET['so_number'] . "'");
                $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
                ?>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Owner Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_owner[0]['owner_email'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_owner[0]['owner_email']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Belum ada di wrike" readonly>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="project_code" value="<?php echo $get_data[0]['project_code_kpi']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">So Number</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="so_number" value="<?php echo $_GET['so_number']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['customer_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['WR_status_project']; ?>" readonly>
                    </div>
                </div>
                <?php
                if (isset($check_data[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_data[0]['no_bast']; ?>">
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <?php if (isset($bast_no[0]['document_bast_no'])) { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $bast_no[0]['document_bast_no']; ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast">
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <?php if (isset($check_data[0]['pic'])) { ?>
                            <textarea cols="35" name="pic" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
                                <option value="mega.dwi@mastersystem.co.id">mega.dwi@mastersystem.co.id</option>
                                <option value="matias.jepri@mastersystem.co.id">matias.jepri@mastersystem.co.id</option>
                                <?php do { ?>
                                    <option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
                                <?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
                            <?php } ?>
                            </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['project_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resources</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
			
                        <?php 
				if (isset($get_engineer[0]['resource_email'])) { ?>
                            <div class="name-list">
                                <ul>
                                    <?php
                                    do {
                                        $get_name = $DBHCM->get_sql("SELECT employee_name,employee_email from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                                    ?>
                                        <li type="text" name="resources" id="resources" value="<?php echo $get_name[0]['employee_name']; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
                                        <input type="hidden" name="resources[]" id="resources[]" value="<?php echo $get_name[0]['employee_email'];?>">
                                    <?php
                                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                                    ?>
                                </ul>
                            </div>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control" value="Belum Ada" readonly>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $get_survey_id = $DBSV->get_sql("SELECT * FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                        if (isset($get_survey_id[0]['survey_id'])){
                            $get_survey = $DBSV->get_sql("SELECT rating_average FROM sa_trx_survey WHERE survey_id='" . $get_survey_id[0]['survey_id'] . "'");
                            if (isset($get_survey[0]['rating_average'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php $get_survey[0]['rating_average']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        } else { ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php $check1 = isset($completed[0]['verif_status']); ?>
                        <?php if ($check1 == NULL) { ?>
                            <div class="form-check form-check-inline" id="completed">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed">
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <?php if (isset($hbarang[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="1">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "checked"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="1">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang">
                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($hso[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="2">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "checked"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="2">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO">
                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($openitm[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="3">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "checked"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="3">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item">
                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($others[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="4">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "checked"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="4">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others">
                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="form-check form-check-inline" id="completed">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed" <?php echo "checked"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <div class="form-check form-check-inline" id="1">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                            </div>
                            <div class="form-check form-check-inline" id="2">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                            </div>
                            <div class="form-check form-check-inline" id="3">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Open Item</label>
                            </div>
                            <div class="form-check form-check-inline" id="4">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Others</label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($check_data[0]['note'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['note']; ?>"><?php echo $check_data[0]['note']; ?></textarea>
                        <?php
                        } else {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes"></textarea>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">List Change Request</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
                        <?php if (isset($cekcr_so[0]['cr_no'])) { ?>
                            <div class="name-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">CR-No</th>
                                            <th scope="col">Classification</th>
                                            <th scope="col">Scope of Change</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Button</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        do {
                                            $i++;
                                            $cr_no = $cekcr_so[0]['cr_no'];
                                            $class = $cekcr_so[0]['classification'];
                                            $scope = $cekcr_so[0]['scope_of_change'];
                                            $status1 = $cekcr_so[0]['change_request_status'];
                                            if ($status1 == "submission_to_be_reviewed") {
                                                $status = "Open";
                                            } else if ($status1 == "Canceled") {
                                                $status = "Cancel";
                                            } else {
                                                $status = "Closed";
                                            }
                                            echo "<tr><td><input type='text' name='cr_no[]' value='$cr_no'></td><td>$class</td><td>$scope</td><td>$status</td>"; ?>
                                            <?php if ($status == "Open") { 
                                                $cr = $cr_no;
                                                ?>
                                                <td><input type="submit" id='cr_no' class="btn btn-primary" name="Cancel_CR<?php echo $i ?>" value="Cancel"></td>
                                            <?php } else {
                                                '';
                                            } ?>
                                            </tr>
                                        <?php
                                        } while ($cekcr_so[0] = $cekcr_so[1]->fetch_assoc());
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } elseif (isset($cekcr_kp[0]['cr_no'])) {
                        ?>
                            <div class="name-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">CR-No
                                            </th>
                                            <th scope="col">Classification</th>
                                            <th scope="col">Scope of Change</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Button</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        do {
                                            $i++;
                                            $cr_no = $cekcr_kp[0]['cr_no'];
                                            $class = $cekcr_kp[0]['classification'];
                                            $scope = $cekcr_kp[0]['scope_of_change'];
                                            $status1 = $cekcr_kp[0]['change_request_status'];
                                            if ($status1 == "submission_to_be_reviewed") {
                                                $status = "Open";
                                            } else if ($status1 == "Canceled") {
                                                $status = "Cancel";
                                            } else {
                                                $status = "Closed";
                                            }
                                            echo "<tr><td><input type='text' name='cr_no[]' value='$cr_no'></td><td>$class</td><td>$scope</td><td>$status</td>"; ?>
                                            <?php if ($status == "Open") { 
                                                $cr = $cr_no;
                                                ?>
                                                <td><input type="submit" id='cr_no' class="btn btn-primary" name="Cancel_CR<?php echo $i ?>" value="Cancel"></td>
                                            <?php } else {
                                                '';
                                            } ?>
                                            </tr>
                                        <?php
                                            // $j++;
                                        } while ($cekcr_kp[0] = $cekcr_kp[1]->fetch_assoc());
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else {
                        ?>
                            <input type="text" class="form-control" value="Tidak Ada CR" readonly>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
                </div>
            </div>
            <div class="tab-pane fade" id="historyx" role="tabpanel" aria-labelledby="history-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5>History</h5>
                        <table class="table">
                            <thead class="bg-light">
                                <th class="col-lg-2">Date</th>
                                <th class="col-lg-2">Time</th>
                                <th class="col-lg-8">Description</th>
                            </thead>
                            </thead>
                            <tbody>
                                <?php if (isset($history[0]['date'])) {
                                    do {
                                        $date = explode("-", $history[0]['date']);
                                        $tahun = $date[0];
                                        $bulan = $date[1];
                                        $tanggal = $date[2];
                                        $time = $history[0]['time'];
                                        $status = $history[0]['status'];
                                ?>
                                        <tr>
                                            <td style="font-size: 12px">
                                                <table class="table table-sm table-light table-striped">
                                                    <tr>
                                                        <td class="text-center fw-bold" colspan="2"><?php echo $tahun; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center"><?php echo $bulan; ?></td>
                                                        <td class="text-center"><?php echo $tanggal; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="font-size: 12px"><?php echo $time; ?></td>
                                            <td style="font-size: 12px"><?php echo $status; ?></td>
                                        </tr>
                                    <?php } while ($history[0] = $history[1]->fetch_assoc()); ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#pic_board').multiselect({
                    nonSelectedText: 'Select PIC',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth: '314px'
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', e => {
                document.querySelector('input[type="checkbox"][value="Completed"]').addEventListener('click', function(e) { //find all checkboxes that are NOT the key checkbox
                    let col = document.querySelectorAll('input[ type="checkbox" ]:not( [ value="Completed" ] )');
                    col.forEach(chk => { //iterate through collection
                        chk.disabled = this.checked; //disable current checkbox if key is checked and enable if not checked
                        if (this.checked) chk.checked = false; //if key checkbox is checked, all others cannot be checked
                    });
                });
            });
        </script>

        <script>
            function cancel(cr_no) {
                console.log(cr_no);
            }
        </script>

        

    <?php
    }
    if ($_GET['act'] == 'review') {
    ?>
        <?php
        $data_kpi = $DBKPI->get_sql("SELECT * FROM sa_kpi_so_wr WHERE so_number='" . $_GET['so_number'] . "'");
        $completed = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Completed%' AND so_number ='" . $_GET['so_number'] . "'");
        $hbarang = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Outstanding Barang%' AND so_number ='" . $_GET['so_number'] . "'");
        $hso = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Revisi administrasi SO%' AND so_number ='" . $_GET['so_number'] . "'");
        $openitm = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Open Item%' AND so_number ='" . $_GET['so_number'] . "'");
        $others = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Others%' AND so_number ='" . $_GET['so_number'] . "'");
        $check_error = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='" . $_GET['so_number'] . "'");
        $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
        $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
        $bast_no = $DBWR->get_sql("SELECT a.document_bast_no FROM sa_wrike_project_detail a left join sa_wrike_project_list b ON a.project_id=b.id WHERE b.no_so='" . $_GET['so_number'] . "'");
        $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
        $so_number = $_GET['so_number'];
        $implementation_price = $get_data[0]['SB_amount_idr'];
        $po_maintenance = $get_data[0]['SB_maintenance_price'];
        $po_warranty = $get_data[0]['SB_warranty_price'];
        $value = $implementation_price - $po_maintenance - $po_warranty;
        $weighted_val = $data_kpi[0]['weighted_value'];
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Information</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#historyx" type="button" role="tab" aria-controls="history" aria-selected="false" style="color: black;">History</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- TAB Change Request Type -->
            <div class="tab-pane fade show active" id="informationx" role="tabpanel" aria-labelledby="crtype-tab">
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "' AND project_type='MSI Project Implementation'");
                                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
                                $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
                                ?>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Owner Project</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php
                                        if (isset($get_owner[0]['owner_email'])) {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_owner[0]['owner_email']; ?>" readonly>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" value="Belum ada di wrike" readonly>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $get_data[0]['project_code_kpi']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">So Number</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="so_number" value="<?php echo $_GET['so_number']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['customer_name']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['WR_status_project']; ?>" readonly>
                                    </div>
                                </div>
                                <?php if (isset($check_data[0]['no_bast'])) { ?>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_data[0]['no_bast']; ?>" readonly>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="no_bast" readonly>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if (isset($check_data[0]['pic'])) { ?>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="form-group col-sm-8">
                                        <textarea cols="35" <?php echo "disabled"; ?> readonly><?php echo $check_data[0]['pic']; ?></textarea>
                                    </div>
                                </div>
                                <?php } else if (isset($check_error[0]['pic'])) { ?>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="form-group col-sm-8">
                                        <textarea cols="35" <?php echo "disabled"; ?> readonly><?php echo $check_error[0]['pic']; ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nilai Project</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="nilai_project" value="<?php echo "Rp. " . number_format($value, 0, ".", "."); ?>" readonly>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="form-group col-sm-8">
                                        <textarea cols="35" <?php echo "disabled"; ?> readonly></textarea>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nilai Project</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="nilai_project" value="<?php echo "Rp. " . number_format($value, 0, ".", "."); ?>" readonly>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['project_name']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resources</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <style>
                                        .name-list {
                                            height: 130px;
                                            overflow: scroll;
                                        }
                                    </style>
                                    <div class="col-sm-8">
                                        <?php if (isset($get_engineer[0]['resource_email'])) { ?>
                                            <div class="name-list">
                                                <ul>
                                                    <?php
                                                    do {
                                                        $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                                                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                                                    ?>
                                                        <li type="text" value="<?php echo $get_name[0]['employee_name']; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
                                                    <?php
                                                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="text" class="form-control" value="Belum Ada" readonly>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php
                                        $get_survey_id = $DBSV->get_sql("SELECT * FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                                        if (isset($get_survey_id[0]['survey_id'])){
                                            $get_survey = $DBSV->get_sql("SELECT rating_average FROM sa_trx_survey WHERE survey_id='" . $get_survey_id[0]['survey_id'] . "'");
                                            if (isset($get_survey[0]['rating_average'])) {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" value="<?php $get_survey[0]['rating_average']; ?>" readonly>
                                        <?php
                                        } else {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                                        <?php
                                        }
                                        } else { ?>
                                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php $check2 = isset($completed[0]['verif_status']); ?>
                                        <?php if ($check2 == NULL) { ?>
                                            <div class="form-check form-check-inline" id="completed">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed" <?php echo "disabled"; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                                            </div>
                                            <?php if (isset($hbarang[0]['verif_status'])) { ?>
                                                <div class="form-check form-check-inline" id="1">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "checked"; ?> disabled>
                                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline" id="1">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "disabled"; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                                </div>
                                            <?php } ?>
                                            <?php if (isset($hso[0]['verif_status'])) { ?>
                                                <div class="form-check form-check-inline" id="2">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "checked"; ?> disabled>
                                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline" id="2">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "disabled"; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                                </div>
                                            <?php } ?>
                                            <?php if (isset($openitm[0]['verif_status'])) { ?>
                                                <div class="form-check form-check-inline" id="3">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "checked"; ?> disabled>
                                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline" id="3">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "disabled"; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                                </div>
                                            <?php } ?>
                                            <?php if (isset($others[0]['verif_status'])) { ?>
                                                <div class="form-check form-check-inline" id="4">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "checked"; ?> disabled>
                                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline" id="4">
                                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "disabled"; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline" id="completed">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed" <?php echo "checked"; ?> disabled>
                                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                                            </div>
                                            <div class="form-check form-check-inline" id="1">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "disabled"; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                            </div>
                                            <div class="form-check form-check-inline" id="2">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "disabled"; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                            </div>
                                            <div class="form-check form-check-inline" id="3">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "disabled"; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                            </div>
                                            <div class="form-check form-check-inline" id="4">
                                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "disabled"; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Others</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if (isset($check_data[0]['note'])) { ?>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['note']; ?>" readonly><?php echo $check_data[0]['note']; ?></textarea>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control form-control-sm" name="notes" readonly></textarea>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div>
                                <?php $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE no_so='$so_number' AND project_type='MSI Project Implementation'"); 
                                      $idproject = isset($id_project[0]['id']);
                                      if (empty($idproject)) {
                                        $id = null;
                                      } else {
                                        $id = $id_project[0]['id'];
                                      }
                                      $get_jumlah_task = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id'"); 
                                      $get_updated_task = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id'"); 
                                      $jumlah_task = $get_jumlah_task[0]['jumlah_task']; 
                                      $updated_task = $get_updated_task[0]['updated_task']; 
                                      $get_data_kpi = $DBKPI->get_sql("SELECT * from sa_kpi_so_wr WHERE so_number='$so_number'"); 
                                      $weighted_val = $get_data_kpi[0]['weighted_value'];
                                      $get_sb_mandays = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi='$so_number' AND SB_service_type_implementation=1"); 
                                      $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
                                      $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
                                      if(empty($bast_planing)) { 
                                        $bast_plan = 'Empty';
                                        } else {
                                            $bast_plan = $bast_planing;
                                        } 
                                      $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
                                      if(empty($bast_actualing)) {
                                        $bast_actual = 'Empty';
                                      } else {
                                        $bast_actual = $bast_actualing;
                                      } 
                                      $cek_error = $DBKPI->get_sql("SELECT * from sa_kpi_board WHERE so_number='$so_number'"); 
                                      $nilai = isset($cek_error[0]['nilai_error']);
                                      if(empty($nilai)) {
                                        $nilaierror = 0;
                                        $persen_error = $nilaierror * 100; 
                                      } else {
                                        $nilaierror = $cek_error[0]['nilai_error'];
                                        $persen_error = $nilaierror * 100; 
                                      } 
                                      if($nilaierror >= 0.06 && $nilaierror < 0.12) {
                                        $error_result = 'Minor';
                                      } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
                                        $error_result = 'Major';
                                      } else if ($nilaierror >= 0.2) {
                                        $error_result = 'Critical';
                                      } else {
                                        $error_result = 'Normal';
                                      } 
                                      $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
                                      $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
                                      ?>
                                
                                <div class="col-lg-12">
                                    <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col">Productivity</th>
                                        <th scope="col">Cost (%)</th>
                                        <th scope="col">Time (%)</th>
                                        <th scope="col">Error (%)</th>
                                        <th scope="col">Total KPI (%)</th>
                                        <th scope="col">Weighted Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td scope="row"><?php echo $jumlah_task . " | " . $updated_task ?></td>
                                        <td>
                                            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm"><?php echo $persen_cost . "% | " . $get_data_kpi[0]['commercial_category']; ?></label>
                                            <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-10 col-form-label col-form-label-sm"><?php echo $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']); ?></label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm"><?php echo $persen_time . "% | " . $get_data_kpi[0]['time_category']; ?></label>
                                            <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-10 col-form-label col-form-label-sm"><?php echo $bast_plan . " | " . $bast_actual; ?></label>
                                            </div>
                                        </td>
                                        <td><label for="inputCID3" class="col-sm-10 col-form-label col-form-label-sm"><?php echo $persen_error . "% | " . $error_result; ?></label></td>
                                        <td><label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $total_kpi . "%"; ?></label></td>
                                        <td><label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm"><?php echo $weighted_val ?></label></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                                <!-- <div class="col-lg-12">
                                    <div class="row mb-3">
                                        <div class="row mb-2">
                                            <div class="col-3 col-form-label-sm">Productivity</div>
                                            <div class="col-3 col-form-label-sm">  Cost (%)</div>
                                            <div class="col-3 col-form-label-sm">Time (%)</div>
                                            <div class="col-3 col-form-label-sm">Error (%)</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"><?php echo $jumlah_task . " | " . $updated_task ?></label>
                                            </div>
                                            <div class="col-3">
                                                <label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $persen_cost . "% | " . $get_data_kpi[0]['commercial_category']; ?></label>
                                                <div class="row mb-1">
                                                <label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']); ?></label>
                                                </div>                                               
                                            </div>
                                            <div class="col-3">
                                                <label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $persen_time . "% | " . $get_data_kpi[0]['time_category']; ?></label>
                                                <div class="row mb-1">
                                                <label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $bast_plan . " | " . $bast_actual; ?></label>
                                                </div>                                               
                                            </div>
                                            <div class="col-3">
                                                <label for="inputCID3" class="col-sm-8 col-form-label col-form-label-sm"><?php echo $persen_error . "% | " . $error_result; ?></label>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row mb-3">

                                </div>
                                <div class="col-lg-12">
                                    <div class="row mb-3">
                                        <div class="row mb-2">
                                            <div class="col-6 col-form-label-sm">Error</div>
                                            <div class="col-1 col-form-label-sm">Ada ?</div>
                                            <div class="col-1 col-form-label-sm">Impact</div>
                                            <div class="col-1 col-form-label-sm">Nilai</div>
                                            <div class="col-3 col-form-label-sm">Note</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <select class="form-control form-control-sm" name="" id="">
                                                    <option value="1">Komplain lisan Customer</option>
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact" id="impact" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category']; ?>"><?php echo $check_error[0]['error_category']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact" id="impact" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                                <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category'])) { ?>
                                                        <?php if ($check_error[0]['error_category'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes'])) { ?>
                                                    <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes']; ?>" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <select class="form-control form-control-sm" name="" id="">
                                                    <option value="2">Post Project Review dengan nilai rata-rata kurang dari 7</option>
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list2'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category2'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact2" id="impact2" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category2']; ?>"><?php echo $check_error[0]['error_category2']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact2" id="impact2" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category2'])) { ?>
                                                        <?php if ($check_error[0]['error_category2'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category2'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category2'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes2'])) { ?>
                                                    <textarea name="notes2" id="notes2" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes2']; ?>" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes2']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes2" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="3">Kesalahan dokumentasi project (contoh logo, nama perusahaan, kesalahan penulisan yang menyebabkan pengertian berubah, non typo)</option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list3'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category3'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact3" id="impact3" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category3']; ?>"><?php echo $check_error[0]['error_category3']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact3" id="impact3" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category3'])) { ?>
                                                        <?php if ($check_error[0]['error_category3'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category3'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category3'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes3'])) { ?>
                                                    <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes3']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="4">
                                                        Dokumentasi project tidak lengkap
                                                        (contoh: MoM, PSR,
                                                        CR (PM/PC); LLD,
                                                        Migration Plan,
                                                        System
                                                        Impelementation
                                                        (Engineer)
                                                    </option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list4'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category4'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact4" id="impact4" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category4']; ?>"><?php echo $check_error[0]['error_category4']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact4" id="impact4" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category4'])) { ?>
                                                        <?php if ($check_error[0]['error_category4'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category4'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category4'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes4'])) { ?>
                                                    <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes4']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="5">Komplain tertulis atau Surat Peringatan dari Customer</option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list5'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category5'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact5" id="impact5" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category5']; ?>"><?php echo $check_error[0]['error_category5']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact5" id="impact5" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category5'])) { ?>
                                                        <?php if ($check_error[0]['error_category5'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category5'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category5'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes5'])) { ?>
                                                    <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes5']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="6">Migrasi gagal (fail) yang menyebabkan customer tidak dapat beroperasi secara normal, dengan penyebab kegagalan adalah kurangnya koordinasi dan data collection dari team project MSI</option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list6'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category6'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact6" id="impact6" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category6']; ?>"><?php echo $check_error[0]['error_category6']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact6" id="impact6" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category6'])) { ?>
                                                        <?php if ($check_error[0]['error_category6'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category6'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category6'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes6'])) { ?>
                                                    <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes6']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="7">Tidak melakukan eskalasi jika diperlukan (internal dan eksternal)</option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list7'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category7'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact7" id="impact7" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category7']; ?>"><?php echo $check_error[0]['error_category7']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact7" id="impact7" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category7'])) { ?>
                                                        <?php if ($check_error[0]['error_category7'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category7'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category7'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes7'])) { ?>
                                                    <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes7']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                                    <option value="8">Mastersystem dikenakan Penalty, tanpa ada eskalasi sebelumnya dan tidak ada recovery action / plan</option>
                                                </select></div>
                                            <div class="col-1">
                                                <div class="form-check form-switch">
                                                    <?php if (isset($check_error[0]['error_list8'])) { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                                    <?php } else { ?>
                                                        <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "disabled"; ?>>
                                                    <?php } ?>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($check_error[0]['error_category8'])) { ?>
                                                    <select class="form-control form-control-sm" name="impact8" id="impact8" <?php echo "disabled"; ?>>
                                                        <option value="<?php echo $check_error[0]['error_category8']; ?>"><?php echo $check_error[0]['error_category8']; ?></option>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <select class="form-control form-control-sm" name="impact8" id="impact8" <?php echo "disabled"; ?>>
                                                        <option value="Normal">N/A</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Major">Major</option>
                                                        <option value="Critical">Critical</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <div class="col-1">
                                                        <?php if (isset($check_error[0]['error_category8'])) { ?>
                                                        <?php if ($check_error[0]['error_category8'] == "Minor") { ?>
                                                            <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                                        <?php } else if ($check_error[0]['error_category8'] == "Major") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                                        <?php } else if ($check_error[0]['error_category8'] == "Critical") { ?> 
                                                            <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <input class="form-control form-control-sm" type="text" value="0" readonly>
                                                        <?php } ?>
                                                </div>
                                            <div class="col-3">
                                                <?php if (isset($check_error[0]['notes8'])) { ?>
                                                    <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes8']; ?></textarea>
                                                <?php } else { ?>
                                                    <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="tab-pane fade" id="historyx" role="tabpanel" aria-labelledby="history-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5>History</h5>
                        <table class="table">
                            <thead class="bg-light">
                                <th class="col-lg-2">Date</th>
                                <th class="col-lg-2">Time</th>
                                <th class="col-lg-8">Description</th>
                            </thead>
                            </thead>
                            <tbody>
                                <?php if (isset($history[0]['date'])) {
                                    do {
                                        $date = explode("-", $history[0]['date']);
                                        $tahun = $date[0];
                                        $bulan = $date[1];
                                        $tanggal = $date[2];
                                        $time = $history[0]['time'];
                                        $status = $history[0]['status'];
                                ?>
                                        <tr>
                                            <td style="font-size: 12px">
                                                <table class="table table-sm table-light table-striped">
                                                    <tr>
                                                        <td class="text-center fw-bold" colspan="2"><?php echo $tahun; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center"><?php echo $bulan; ?></td>
                                                        <td class="text-center"><?php echo $tanggal; ?></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="font-size: 12px"><?php echo $time; ?></td>
                                            <td style="font-size: 12px"><?php echo $status; ?></td>
                                        </tr>
                                    <?php } while ($history[0] = $history[1]->fetch_assoc()); ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    if ($_GET['act'] == 'read_add') {
    ?>
        <?php
        $completed = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Completed%' AND so_number ='" . $_GET['so_number'] . "'");
        $hbarang = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Outstanding Barang%' AND so_number ='" . $_GET['so_number'] . "'");
        $hso = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Revisi administrasi SO%' AND so_number ='" . $_GET['so_number'] . "'");
        $openitm = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Open Item%' AND so_number ='" . $_GET['so_number'] . "'");
        $others = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Others%' AND so_number ='" . $_GET['so_number'] . "'");
        $cekcr_so = $DBCR->get_sql("SELECT * FROM sa_general_informations WHERE project_code='" . $_GET['so_number'] . "'");
        $cekcr_kp = $DBCR->get_sql("SELECT * FROM sa_general_informations WHERE project_code='" . $_GET['project_code'] . "'");
        ?>
        <div class="row">
            <div class="col-lg-6">
                <?php
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "' AND project_type='MSI Project Implementation'");
                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
                $bast_no = $DBWR->get_sql("SELECT a.document_bast_no FROM sa_wrike_project_detail a left join sa_wrike_project_list b ON a.project_id=b.id WHERE b.no_so='" . $_GET['so_number'] . "'");
                $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
                ?>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Owner Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_owner[0]['owner_email'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_owner[0]['owner_email']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Belum ada di wrike" readonly>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="project_code" value="<?php echo $get_data[0]['project_code_kpi']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">So Number</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="so_number" value="<?php echo $_GET['so_number']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['customer_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['WR_status_project']; ?>" readonly>
                    </div>
                </div>
                <?php
                if (isset($check_data[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_data[0]['no_bast']; ?>" readonly>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <?php if (isset($bast_no[0]['document_bast_no'])) { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $bast_no[0]['document_bast_no']; ?>" readonly>
                            <?php } else { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" readonly>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <?php if (isset($check_data[0]['pic'])) { ?>
                            <textarea cols="35" name="pic" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
                                <option value="mega.dwi@mastersystem.co.id">mega.dwi@mastersystem.co.id</option>
                                <option value="matias.jepri@mastersystem.co.id">matias.jepri@mastersystem.co.id</option>
                                <?php do { ?>
                                    <option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
                                <?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
                            <?php } ?>
                            </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['project_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resources</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
                        <?php if (isset($get_engineer[0]['resource_email'])) { ?>
                            <div class="name-list">
                                <ul>
                                    <?php
                                    do {
                                        $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                                    ?>
                                        <li type="text" value="<?php echo $get_name[0]['employee_name']; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
                                    <?php
                                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                                    ?>
                                </ul>
                            </div>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control" value="Belum Ada" readonly>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php $check1 = isset($completed[0]['verif_status']); ?>
                        <?php if ($check1 == NULL) { ?>
                            <div class="form-check form-check-inline" id="completed">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed" <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <?php if (isset($hbarang[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="1">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="1">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($hso[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="2">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="2">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($openitm[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="3">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="3">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Open Item</label>
                                </div>
                            <?php } ?>
                            <?php if (isset($others[0]['verif_status'])) { ?>
                                <div class="form-check form-check-inline" id="4">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                </div>
                            <?php } else { ?>
                                <div class="form-check form-check-inline" id="4">
                                    <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "disabled"; ?>>
                                    <label class="form-check-label" for="inlineRadio1">Others</label>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="form-check form-check-inline" id="completed">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <div class="form-check form-check-inline" id="1">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang" <?php echo "disabled"; ?> <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                            </div>
                            <div class="form-check form-check-inline" id="2">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO" <?php echo "disabled"; ?> <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                            </div>
                            <div class="form-check form-check-inline" id="3">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item" <?php echo "disabled"; ?> <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Open Item</label>
                            </div>
                            <div class="form-check form-check-inline" id="4">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others" <?php echo "disabled"; ?> <?php echo "disabled"; ?>>
                                <label class="form-check-label" for="inlineRadio1">Others</label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($check_data[0]['note'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['note']; ?>" <?php echo "disabled"; ?>><?php echo $check_data[0]['note']; ?></textarea>
                        <?php
                        } else {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" <?php echo "disabled"; ?>></textarea>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">List Change Request</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
                        <?php if (isset($cekcr_so[0]['cr_no'])) { ?>
                            <div class="name-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">CR-No</th>
                                            <th scope="col">Classification</th>
                                            <th scope="col">Scope of Change</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Button</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        do {
                                            $cr_no = $cekcr_so[0]['cr_no'];
                                            $class = $cekcr_so[0]['classification'];
                                            $scope = $cekcr_so[0]['scope_of_change'];
                                            $status1 = $cekcr_so[0]['change_request_status'];
                                            if ($status1 == "submission_to_be_reviewed") {
                                                $status = "Open";
                                            } else if ($status1 == "canceled") {
                                                $status = "Cancel";
                                            } else {
                                                $status = "Closed";
                                            }
                                            echo "<tr><td><input type='text' name='cr_no' value='$cr_no'></td><td>$class</td><td>$scope</td><td>$status</td>"; ?>
                                            <?php if ($status == "Open") { ?>
                                                <td><input type="submit" class="btn btn-primary" name="Cancel_CR" value="Cancel"></td>
                                            <?php } else {
                                                '';
                                            } ?>
                                            </tr>
                                        <?php
                                            // $j++;
                                            //     //echo $user[0]['email'] . "|" . $user[0]['name'];
                                            //echo $user[0]['name'];
                                        } while ($cekcr_so[0] = $cekcr_so[1]->fetch_assoc());
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } elseif (isset($cekcr_kp[0]['cr_no'])) {
                        ?>
                            <div class="name-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">CR-No
                                            </th>
                                            <th scope="col">Classification</th>
                                            <th scope="col">Scope of Change</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Button</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        do {
                                            $cr_no = $cekcr_kp[0]['cr_no'];
                                            $class = $cekcr_kp[0]['classification'];
                                            $scope = $cekcr_kp[0]['scope_of_change'];
                                            $status1 = $cekcr_kp[0]['change_request_status'];
                                            if ($status1 == "submission_to_be_reviewed") {
                                                $status = "Open";
                                            } else if ($status1 == "canceled") {
                                                $status = "Cancel";
                                            } else {
                                                $status = "Closed";
                                            }
                                            echo "<tr><td><input type='text' name='cr_no' value='$cr_no'></td><td>$class</td><td>$scope</td><td>$status</td>"; ?>
                                            <?php if ($status == "Open") { ?>
                                                <td><input type="submit" class="btn btn-primary" name="Cancel_CR" value="Cancel"></td>
                                            <?php } else {
                                                '';
                                            } ?>
                                            </tr>
                                        <?php
                                            // $j++;
                                        } while ($cekcr_kp[0] = $cekcr_kp[1]->fetch_assoc());
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else {
                        ?>
                            <input type="text" class="form-control" value="Tidak Ada CR" readonly>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#pic_board').multiselect({
                    nonSelectedText: 'Select PIC',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth: '314px'
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', e => {
                document.querySelector('input[type="checkbox"][value="Completed"]').addEventListener('click', function(e) { //find all checkboxes that are NOT the key checkbox
                    let col = document.querySelectorAll('input[ type="checkbox" ]:not( [ value="Completed" ] )');
                    col.forEach(chk => { //iterate through collection
                        chk.disabled = this.checked; //disable current checkbox if key is checked and enable if not checked
                        if (this.checked) chk.checked = false; //if key checkbox is checked, all others cannot be checked
                    });
                });
            });
        </script>
    <?php
    }
    ?>
    <?php
    if ($_GET['act'] == 'read_edit') {
    ?>
        <div class="row">
            <div class="col-lg-6">
                <?php
                $check_error = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='" . $_GET['so_number'] . "'");
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "' AND project_type='MSI Project Implementation'");
                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
                $bast_no = $DBWR->get_sql("SELECT a.document_bast_no FROM sa_wrike_project_detail a left join sa_wrike_project_list b ON a.project_id=b.id WHERE b.no_so='" . $_GET['so_number'] . "'");
                $query_kpi = $DBKPI->get_sql("SELECT customer_name,project_name,WR_status_project FROM sa_data_so WHERE so_number_kpi='" . $_GET['so_number'] . "'");
                ?>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Owner Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_owner[0]['owner_email'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_owner[0]['owner_email']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Belum ada di wrike" readonly>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="project_code" value="<?php echo $get_data[0]['project_code_kpi']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">So Number</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" name="so_number" value="<?php echo $_GET['so_number']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['customer_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['WR_status_project']; ?>" readonly>
                    </div>
                </div>
                <?php
                if (isset($check_error[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_error[0]['no_bast']; ?>" readonly>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <?php if (isset($bast_no[0]['document_bast_no'])) { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $bast_no[0]['document_bast_no']; ?>" readonly>
                            <?php } else { ?>
                                <input type="text" class="form-control form-control-sm" name="no_bast" readonly>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-lg-6">
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" value="<?php echo $query_kpi[0]['project_name']; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resources</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <style>
                        .name-list {
                            height: 130px;
                            overflow: scroll;
                        }
                    </style>
                    <div class="col-sm-8">
                        <?php if (isset($get_engineer[0]['resource_email'])) { ?>
                            <div class="name-list">
                                <ul>
                                    <?php
                                    do {
                                        $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                                    ?>
                                        <li type="text" value="<?php echo $get_name[0]['employee_name']; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
                                    <?php
                                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                                    ?>
                                </ul>
                            </div>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control" value="Belum Ada" readonly>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <?php if (isset($check_error[0]['pic'])) { ?>
                            <textarea cols="35" name="pic" value="<?php echo $check_error[0]['pic']; ?>" readonly><?php echo $check_error[0]['pic']; ?></textarea>
                        <?php } else if (isset($check_data[0]['pic'])) { ?>
                            <textarea name="pic" cols="35" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
                                <option value="mega.dwi@mastersystem.co.id">mega.dwi@mastersystem.co.id</option>
                                <option value="matias.jepri@mastersystem.co.id">matias.jepri@mastersystem.co.id</option>
                                <?php do { ?>
                                    <option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
                                <?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
                            <?php } ?>
                            </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
            </div>
            <div class="col-lg-12">
                <div class="row mb-3">
                    <div class="row mb-2">
                        <div class="col-6 col-form-label-sm">Error</div>
                        <div class="col-1 col-form-label-sm">Ada ?</div>
                        <div class="col-1 col-form-label-sm">Impact</div>
                        <div class="col-1 col-form-label-sm">Nilai</div>
                        <div class="col-3 col-form-label-sm">Note</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="1">Komplain lisan Customer</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category'])) { ?>
                                <select class="form-control form-control-sm" name="impact" id="impact" readonly>
                                    <option value="<?php echo $check_error[0]['error_category']; ?>"><?php echo $check_error[0]['error_category']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact" id="impact" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category'])) { ?>
                                <?php if ($check_error[0]['error_category'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes'])) { ?>
                                <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes']; ?>" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <select class="form-control form-control-sm" name="" id="">
                                <option value="2">Post Project Review dengan nilai rata-rata kurang dari 7</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list2'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type2" id="flexSwitchCheckDefault" value="2" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category2'])) { ?>
                                <select class="form-control form-control-sm" name="impact2" id="impact2" readonly>
                                    <option value="<?php echo $check_error[0]['error_category2']; ?>"><?php echo $check_error[0]['error_category2']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact2" id="impact2" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category2'])) { ?>
                                <?php if ($check_error[0]['error_category2'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category2'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category2'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes2'])) { ?>
                                <textarea name="notes2" id="notes2" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes2']; ?>" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes2']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes2" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="3">Kesalahan dokumentasi project (contoh logo, nama perusahaan, kesalahan penulisan yang menyebabkan pengertian berubah, non typo)</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list3'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category3'])) { ?>
                                <select class="form-control form-control-sm" name="impact3" id="impact3" readonly>
                                    <option value="<?php echo $check_error[0]['error_category3']; ?>"><?php echo $check_error[0]['error_category3']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact3" id="impact3" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category3'])) { ?>
                                <?php if ($check_error[0]['error_category3'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category3'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category3'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes3'])) { ?>
                                <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes3']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="4">
                                    Dokumentasi project tidak lengkap
                                    (contoh: MoM, PSR,
                                    CR (PM/PC); LLD,
                                    Migration Plan,
                                    System
                                    Impelementation
                                    (Engineer)
                                </option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list4'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category4'])) { ?>
                                <select class="form-control form-control-sm" name="impact4" id="impact4" readonly>
                                    <option value="<?php echo $check_error[0]['error_category4']; ?>"><?php echo $check_error[0]['error_category4']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact4" id="impact4" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category4'])) { ?>
                                <?php if ($check_error[0]['error_category4'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category4'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category4'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes4'])) { ?>
                                <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes4']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="5">Komplain tertulis atau Surat Peringatan dari Customer</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list5'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category5'])) { ?>
                                <select class="form-control form-control-sm" name="impact5" id="impact5" readonly>
                                    <option value="<?php echo $check_error[0]['error_category5']; ?>"><?php echo $check_error[0]['error_category5']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact5" id="impact5" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category5'])) { ?>
                                <?php if ($check_error[0]['error_category5'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category5'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category5'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes5'])) { ?>
                                <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes5']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="6">Migrasi gagal (fail) yang menyebabkan customer tidak dapat beroperasi secara normal, dengan penyebab kegagalan adalah kurangnya koordinasi dan data collection dari team project MSI</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list6'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category6'])) { ?>
                                <select class="form-control form-control-sm" name="impact6" id="impact6" readonly>
                                    <option value="<?php echo $check_error[0]['error_category6']; ?>"><?php echo $check_error[0]['error_category6']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact6" id="impact6" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category6'])) { ?>
                                <?php if ($check_error[0]['error_category6'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category6'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category6'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes6'])) { ?>
                                <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes6']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="7">Tidak melakukan eskalasi jika diperlukan (internal dan eksternal)</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list7'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category7'])) { ?>
                                <select class="form-control form-control-sm" name="impact7" id="impact7" readonly>
                                    <option value="<?php echo $check_error[0]['error_category7']; ?>"><?php echo $check_error[0]['error_category7']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact7" id="impact7" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category7'])) { ?>
                                <?php if ($check_error[0]['error_category7'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category7'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category7'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes7'])) { ?>
                                <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes7']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><select class="form-control form-control-sm" name="" id="">
                                <option value="8">Mastersystem dikenakan Penalty, tanpa ada eskalasi sebelumnya dan tidak ada recovery action / plan</option>
                            </select></div>
                        <div class="col-1">
                            <div class="form-check form-switch">
                                <?php if (isset($check_error[0]['error_list8'])) { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "checked"; ?> <?php echo "disabled"; ?>>
                                <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "disabled"; ?>>
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-1">
                            <?php if (isset($check_error[0]['error_category8'])) { ?>
                                <select class="form-control form-control-sm" name="impact8" id="impact8" readonly>
                                    <option value="<?php echo $check_error[0]['error_category8']; ?>"><?php echo $check_error[0]['error_category8']; ?></option>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } else { ?>
                                <select class="form-control form-control-sm" name="impact8" id="impact8" readonly>
                                    <option value="Normal">N/A</option>
                                    <option value="Minor">Minor</option>
                                    <option value="Major">Major</option>
                                    <option value="Critical">Critical</option>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-1">
                                <?php if (isset($check_error[0]['error_category8'])) { ?>
                                <?php if ($check_error[0]['error_category8'] == "Minor") { ?>
                                    <input class="form-control form-control-sm" type="text" value="6%" readonly>
                                <?php } else if ($check_error[0]['error_category8'] == "Major") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="12%" readonly>
                                <?php } else if ($check_error[0]['error_category8'] == "Critical") { ?> 
                                    <input class="form-control form-control-sm" type="text" value="20%" readonly>
                                <?php } else { ?>
                                    <input class="form-control form-control-sm" type="text" value="0" readonly>
                                <?php } ?>
                                <?php } ?>
                        </div>
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes8'])) { ?>
                                <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>><?php echo $check_error[0]['notes8']; ?></textarea>
                            <?php } else { ?>
                                <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1" <?php echo "disabled"; ?>></textarea>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <?php
    if ($_GET['act'] == 'test') {
    ?>
<?php $so_number = $_GET['so_number']; ?>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Cancel</button> -->
<!-- <a href='#' data-toggle='modal' data-target='#exampleModal' data-so_number=" . $so_number . ">Cancel</a> -->
<!-- <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Employee</span></a> -->
<a href="#addEmployeeModal" class="btn btn-primary" data-toggle="modal"><span>Cancel</span></a>
<?php $check = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='$so_number'"); ?>

    <div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" action="index.php">
					<div class="modal-header">						
						<h4 class="modal-title">Add Employee</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>SO Number</label>
							<input type="text" class="form-control" name="so_number" value="<?php echo $so_number; ?>" required>
						</div>
						<div class="form-group">
							<label>Catatan</label>
							<input type="text" class="form-control" name="catatan_cancel" placeholder="Catatan" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" name="submitess" value="submitess">
					</div>
				</form>
			</div>
		</div>
	</div>
    <?php
    }
    ?>
    <button type="cancel" class="btn btn-secondary" onclick="window.location='https://msizone.mastersystem.co.id/index.php?mod=kpi_board';return false;"><<</button>
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
        <input type="submit" class="btn btn-primary" name="submit_error" value="Submit">
        <a href="#cancel_modal" class="btn btn-primary" data-toggle="modal"><span>Cancel</span></a>
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
        <input type="submit" class="btn btn-primary" name="submit_review" value="Submit">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'review') { ?>
    <!-- <input type="submit" class="btn btn-primary" name="review" value="Approve">
	<input type="submit" class="btn btn-primary" name="reject" value="Reject"> -->
    <a href="#reject_modal" class="btn btn-primary" data-toggle="modal"><span>Reject</span></a>
    <a href="#approve_modal" class="btn btn-primary" data-toggle="modal"><span>Approve</span></a>
    <?php } //elseif (isset($_GET['act']) && $_GET['act'] == 'error') { 
    ?>
    <!-- <input type="submit" class="btn btn-primary" name="error" value="Approve"> -->
    <?php //} 
    ?>
</form>

<?php $check = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='" . $_GET['so_number'] . "'"); ?>
    <div id="cancel_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
					<div class="modal-header">						
						<h4 class="modal-title">Cancel Project</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>SO Number</label>
							<input type="text" class="form-control" name="so_number" value="<?php echo $so_number; ?>" required>
						</div>
						<div class="form-group">
							<label>Catatan</label>
							<input type="text" class="form-control" name="catatan_cancel" placeholder="Catatan" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-secondary" data-dismiss="modal" value="<<">
						<input type="submit" class="btn btn-success" name="submitess" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>

    <div id="approve_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
					<div class="modal-header">						
						<h4 class="modal-title">Approve Project</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>SO Number</label>
							<input type="text" class="form-control" name="so_number" value="<?php echo $so_number; ?>" required>
						</div>
						<div class="form-group">
							<label>Catatan</label>
							<input type="text" class="form-control" name="catatan_approval" placeholder="Catatan" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-secondary" data-dismiss="modal" value="<<">
						<input type="submit" class="btn btn-success" name="review" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>

    <div id="reject_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
					<div class="modal-header">						
						<h4 class="modal-title">Reject Project</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>SO Number</label>
							<input type="text" class="form-control" name="so_number" value="<?php echo $so_number; ?>" required>
						</div>
						<div class="form-group">
							<label>Catatan</label>
							<input type="text" class="form-control" name="catatan_reject" placeholder="Catatan" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-secondary" data-dismiss="modal" value="<<">
						<input type="submit" class="btn btn-success" name="reject" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>