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
        <div class="row">
            <div class="col-lg-6">
                <?php
                $check_error = $DBKPI->get_sql("SELECT * FROM sa_error WHERE so_number='" . $_GET['so_number'] ."'");
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
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
                            <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_error[0]['bast_no']; ?>">
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
                            height: 100px;
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
                                        <li type="text" value="<?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
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
                            <textarea cols="40" name="pic" value="<?php echo $check_error[0]['pic']; ?>" readonly><?php echo $check_error[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
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
                        <div class="col-2 col-form-label-sm">Impact</div>
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
                                <?php if(isset($check_error[0]['error_list'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type" id="flexSwitchCheckDefault" value="1">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes'])) { ?>
                            <textarea type="text" name="notes" class="form-control form-control-sm" cols="3" rows="1" value="<?php echo $check_error[0]['notes']; ?>" ><?php echo $check_error[0]['notes']; ?></textarea>
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
                        <div class="col-2">
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes2'])) {?>
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
                                <?php if (isset($check_error[0]['error_list3'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type3" id="flexSwitchCheckDefault" value="3">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category3'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes3'])) {?>
                            <textarea type="text" name="notes3" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes3']; ?></textarea>
                            <?php } else {?>
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
                                <?php if (isset($check_error[0]['error_list4'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type4" id="flexSwitchCheckDefault" value="4">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category4'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes4'])) {?>
                            <textarea type="text" name="notes4" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes4']; ?></textarea>
                            <?php } else {?>
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
                                <?php if (isset($check_error[0]['error_list5'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type5" id="flexSwitchCheckDefault" value="5">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category5'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes5'])) {?>
                            <textarea type="text" name="notes5" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes5']; ?></textarea>
                            <?php } else {?>
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
                                <?php if (isset($check_error[0]['error_list6'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type6" id="flexSwitchCheckDefault" value="6">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category6'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes6'])) {?>
                            <textarea type="text" name="notes6" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes6']; ?></textarea>
                            <?php } else {?>
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
                                <?php if (isset($check_error[0]['error_list7'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type7" id="flexSwitchCheckDefault" value="7">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category7'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes7'])) {?>
                            <textarea type="text" name="notes7" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes7']; ?></textarea>
                            <?php } else {?>
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
                                <?php if (isset($check_error[0]['error_list8'])) {?>
                                <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8" <?php echo "checked"; ?>>
                                <?php } else { ?>
                                <input class="form-check-input" type="checkbox" name="error_type8" id="flexSwitchCheckDefault" value="8">
                                <?php } ?>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Yes</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <?php if (isset($check_error[0]['error_category8'])) {?>
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
                        <div class="col-3">
                            <?php if (isset($check_error[0]['notes8'])) {?>
                            <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1"><?php echo $check_error[0]['notes8']; ?></textarea>
                            <?php } else {?>
                            <textarea type="text" name="notes8" class="form-control form-control-sm" cols="3" rows="1"></textarea>
                            <?php } ?>
                        </div>
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
        ?>
        <div class="row">
            <div class="col-lg-6">
                <?php
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
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
                            <textarea cols="40" name="pic" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select class="form-control" name="pic_board[]" multiple>
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
                            height: 100px;
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
                                        <li type="text" value="<?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
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
                        if (isset($check_data[0]['notes'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['notes']; ?>"><?php echo $check_data[0]['notes']; ?></textarea>
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
    if ($_GET['act'] == 'review') {
    ?>
        <?php
        $completed = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Completed%' AND so_number ='" . $_GET['so_number'] . "'");
        $hbarang = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Outstanding Barang%' AND so_number ='" . $_GET['so_number'] . "'");
        $hso = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Hold - Revisi administrasi SO%' AND so_number ='" . $_GET['so_number'] . "'");
        $openitm = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Open Item%' AND so_number ='" . $_GET['so_number'] . "'");
        $others = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE verif_status LIKE '%Others%' AND so_number ='" . $_GET['so_number'] . "'");
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
                                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
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
                                <div class="row mb-2">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No BAST</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" name="no_bast" value="<?php echo $check_data[0]['no_bast']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">KPI Board</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="form-group col-sm-8">
                                        <textarea cols="40" <?php echo "disabled"; ?> readonly><?php echo $check_data[0]['pic']; ?></textarea>
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
                            height: 100px;
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
                                        <li type="text" value="<?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?>" readonly><?php echo $get_name[0]['employee_name'] . " (" . $get_leader_resource[0]['leader_name'] . ")"; ?></li>
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
                                        $get_survey = $DBSV->get_sql("SELECT survey_id FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                                        if (isset($get_survey[0]['survey_id'])) {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" value="Sudah Ada" readonly>
                                        <?php
                                        } else {
                                        ?>
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
                                <div class="row mb-5">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                    <div class="col-sm-1">
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['notes']; ?>" readonly><?php echo $check_data[0]['notes']; ?></textarea>
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
    ?>
    <?php
    if ($_GET['act'] == 'test') { ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

        <div class="row">
            <div class="col-lg-6">
                <?php
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
                $get_engineer = $DBWR->get_sql("SELECT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
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
                            <input type="text" class="form-control form-control-sm" name="no_bast">
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
                            <textarea cols="40" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <select id="pic_board" name="pic_board[]" multiple>
                                <?php do { ?>
                                    <option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
                                <?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
                            </select>
                        <?php } ?>
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
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_engineer[0]['resource_email'])) {
                            do {
                        ?>
                                <input type="text" class="form-control form-control-sm" value="<?php echo $get_engineer[0]['resource_email']; ?>" readonly>
                            <?php
                            } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                        } else {
                            ?>
                            <input type="text" class="form-control form-control-sm" value="Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey ID</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $get_survey = $DBSV->get_sql("SELECT survey_id FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                        if (isset($get_survey[0]['survey_id'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_survey[0]['survey_id']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if (isset($check_data[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-check form-check-inline" id="completed">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed">
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <div class="form-check form-check-inline" id="1">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang">
                                <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                            </div>
                            <div class="form-check form-check-inline" id="2">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO">
                                <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                            </div>
                            <div class="form-check form-check-inline" id="3">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item">
                                <label class="form-check-label" for="inlineRadio1">Open Item</label>
                            </div>
                            <div class="form-check form-check-inline" id="4">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others">
                                <label class="form-check-label" for="inlineRadio1">Others</label>
                            </div>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Completed">
                                <label class="form-check-label" for="inlineRadio1">Completed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Outstanding Barang">
                                <label class="form-check-label" for="inlineRadio1">Hold - Outstanding Barang</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Hold - Revisi administrasi SO">
                                <label class="form-check-label" for="inlineRadio1">Hold - Revisi administrasi SO</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Open Item">
                                <label class="form-check-label" for="inlineRadio1">Open Item</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="verif_status[]" id="verif_status" value="Others">
                                <label class="form-check-label" for="inlineRadio1">Others</label>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($check_data[0]['notes'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['notes']; ?>"><?php echo $check_data[0]['notes']; ?></textarea>
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
        </div>
        <script>
            $(document).ready(function() {
                $('#pic_board').multiselect({
                    nonSelectedText: 'Select PIC',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    // overflow: auto,
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
    if ($_GET['act'] == 'coba') { ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

        <div class="row">
            <div class="col-lg-6">
                <?php
                $get_owner = $DBWR->get_sql("SELECT owner_email FROM sa_wrike_project_list WHERE no_so='" . $_GET['so_number'] . "'");
                $get_engineer = $DBWR->get_sql("SELECT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='" . $_GET['so_number'] . "'");
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
                            <input type="text" class="form-control form-control-sm" name="no_bast">
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
                            <textarea cols="40" value="<?php echo $check_data[0]['pic']; ?>" readonly><?php echo $check_data[0]['pic']; ?></textarea>
                        <?php } else { ?>
                            <div id="newRow-detailchange"></div>
                            <button id="addRow-detailchange" type="button" class="btn btn-info col-12">+</button>
                        <?php } ?>
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
                    <div class="col-sm-8">
                        <?php
                        if (isset($get_engineer[0]['resource_email'])) {
                            do {
                        ?>
                                <input type="text" class="form-control form-control-sm" value="<?php echo $get_engineer[0]['resource_email']; ?>" readonly>
                            <?php
                            } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                        } else {
                            ?>
                            <input type="text" class="form-control form-control-sm" value="Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey ID</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        $get_survey = $DBSV->get_sql("SELECT survey_id FROM sa_survey WHERE so_number='" . $_GET['so_number'] . "'");
                        if (isset($get_survey[0]['survey_id'])) {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="<?php echo $get_survey[0]['survey_id']; ?>" readonly>
                        <?php
                        } else {
                        ?>
                            <input type="text" class="form-control form-control-sm" value="Survey Belum Ada" readonly>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if (isset($check_data[0]['so_number'])) {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" name="verif_status">
                                <option value="<?php echo $check_data[0]['verif_status']; ?>"><?php echo $check_data[0]['verif_status']; ?></option>
                                <option value="Completed">Completed</option>
                                <option value="Hold - Outstanding Barang">Hold - Outstanding Barang</option>
                                <option value="Hold - Revisi administrasi SO">Hold - Revisi administrasi SO</option>
                                <option value="Open Item">Open Item</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="row mb-2">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Verifikasi Status</label>
                        <div class="col-sm-1">
                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" name="verif_status">
                                <option value="Completed">Completed</option>
                                <option value="Hold - Outstanding Barang">Hold - Outstanding Barang</option>
                                <option value="Hold - Revisi administrasi SO">Hold - Revisi administrasi SO</option>
                                <option value="Open Item">Open Item</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="row mb-5">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-1">
                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">:</label>
                    </div>
                    <div class="col-sm-8">
                        <?php
                        if (isset($check_data[0]['notes'])) {
                        ?>
                            <textarea type="text" class="form-control form-control-sm" name="notes" value="<?php echo $check_data[0]['notes']; ?>"><?php echo $check_data[0]['notes']; ?></textarea>
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
        </div>
        <script>
            //Detail of Change
            $("#addRow-detailchange").click(function() {
                var html = '';
                html += '<div class="p-0" id="inputFormRow-detailchange">';
                html += '<div class="row">';
                html += '<select class="form-control">';
                <?php do { ?>
                    html += '<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>';
                <?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
                html += '</select>';
                // html += '<div class="col-38 table-bordered"><textarea class="form-control" id="pic_board" name="pic_board[]" rows="1"></textarea></div>';
                html += '<button id="removeRow-detailchange" type="button" class="btn btn-danger col-13">Remove</button>';
                html += '</div>';
                html += '</div>';
                $('#newRow-detailchange').append(html);
            });

            $(document).on('click', '#removeRow-detailchange', function() {
                $(this).closest('#inputFormRow-detailchange').remove();
            });
        </script>
    <?php
    }
    ?>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
        <input type="submit" class="btn btn-primary" name="submit_error" value="Submit">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add' || $_GET['act'] == 'test') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
        <input type="submit" class="btn btn-primary" name="submit_review" value="Submit">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'review') { ?>
        <input type="submit" class="btn btn-primary" name="review" value="Approve">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'error') { ?>
        <input type="submit" class="btn btn-primary" name="error" value="Approve">
    <?php } ?>
</form>