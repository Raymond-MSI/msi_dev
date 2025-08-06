<?php

global $DB1;
global $DBSendSurvey;

$mdlnamesb = "SERVICE_BUDGET";
$DBSB = get_conn($mdlnamesb);

$mdlnamekpi = "KPI_BOARD";
$DBKPI = get_conn($mdlnamekpi);


?>
<?php

if ($_GET['act'] == 'add') {
    $so_sb = $DBSB->get_sqlV2("select distinct(so_number), project_code, bundling from sa_trx_project_list where status in ('acknowledge','approved') and bundling not like '0;0;0;'");
    $so_kpi = $DBKPI->get_sqlV2("select so_number, project_code_kpi from sa_kpi_board kpi left join sa_data_so so on kpi.so_number = so.so_number_kpi where kpi.verif_status = 'Completed;'");
    $project_type = $DBSB->get_sqlV2("SELECT DISTINCT(so_number),CASE WHEN bundling = 1 THEN 'implementation' WHEN bundling = 2 THEN 'maintenance' ELSE 'other' END AS bundling_alias FROM sa_trx_project_list;");

?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Id</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control form-control-sm" id="survey_id" name="survey_id" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Source From</label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" name='project_source' id='project_source'>
                            <option value="kpi">KPI Board</option>
                            <option value="sbf">Service Budgets</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number<b>*</b></label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" name="so_number" id="so_number">
                            <?php if ($_GET['project_source'] == "sbf") { ?>
                                <?php while ($row = $so_sb[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['so_number']; ?>"><?php echo $row['project_code'] . " ( " . $row['so_number'] . " ) "; ?></option>
                                <?php } ?>
                            <?php } elseif ($_GET['project_source'] == "kpi") { ?>
                                <?php while ($row = $so_kpi[1]->fetch_assoc()) { ?>
                                    <option></option>
                                    <option value="<?php echo $row['so_number']; ?>"><?php echo $row['project_code_kpi'] . " ( " . $row['so_number'] . " ) "; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Type</label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" name="project_type" id="project_type">
                            <?php if ($_GET['project_source'] == 'sbf' && $_GET['so_number']) {
                                $narik_project_type_sb = $DBSB->get_sqlV2("SELECT DISTINCT(so_number), CASE WHEN bundling = 1 THEN 'MSI Project Implementation' WHEN bundling = 2 THEN 'MSI Project Maintenance' END AS bundling_alias FROM sa_trx_project_list WHERE bundling IN (1,2) AND so_number LIKE '%" . $_GET['so_number'] . "%' GROUP BY so_number, bundling"); ?>
                                <?php while ($riw = $narik_project_type_sb[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $riw['bundling_alias']; ?>"><?php echo $riw['bundling_alias']; ?></option>
                                <?php } ?>
                            <?php } elseif ($_GET['project_source'] == 'kpi' && $_GET['so_number']) {
                                $narik_project_type_kpi = $DBSB->get_sqlV2("SELECT DISTINCT(so_number), CASE WHEN bundling = 1 THEN 'MSI Project Implementation' WHEN bundling = 2 THEN 'MSI Project Maintenance' END AS bundling_alias FROM sa_trx_project_list WHERE bundling IN (1,2) AND so_number LIKE '%" . $_GET['so_number'] . "%' GROUP BY so_number, bundling"); ?>
                                <?php while ($riw = $narik_project_type_kpi[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $riw['bundling_alias']; ?>"><?php echo $riw['bundling_alias']; ?></option>
                                <?php } ?>
                            <?php } ?>
                            <!-- <option value="MSI Implementation">MSI Implementation</option>
                            <option value="MSI Maintenance">MSI Maintenance</option> -->
                        </select>
                    </div>
                </div>
                <!-- <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Type Of Service</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="type_of_service" name="type_of_service" value="">
                    </div>
                </div> -->
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="customer_name" name="customer_name" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="pic_name" name="pic_name" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="pic_email" name="pic_email" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="pic_phone" name="pic_phone" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template Type</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="template_type" name="template_type" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Link</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="survey_link" name="survey_link" value="">
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
    if ($_GET['act'] == 'edit') {
        global $DB;
        $condition = "survey_id=" . $_GET['survey_id'];
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">

        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).on('change', '#project_source', function() {
            var sta = $('#project_source').val();
            if (sta == "") {
                window.location = window.location.pathname + "?mod=survey_send&act=add";
            } else {
                window.location = window.location.pathname + "?mod=survey_send&act=add&project_source=" + sta;
            }
        });

        <?php if (isset($_GET['project_source'])) { ?>
            $('#project_source option[value="<?php echo $_GET['project_source']; ?>"]').attr('selected', 'selected');
        <?php } ?>

        $(document).on('change', '#so_number', function() {
            var project_source = $('#project_source').val(); // Uncomment this line to get project_source value
            var so_number = $('#so_number').val();
            if (so_number == "") {
                window.location = window.location.pathname + "?mod=survey_send&act=add&project_source=" + project_source;
            } else {
                window.location = window.location.pathname + "?mod=survey_send&act=add&project_source=" + project_source + "&so_number=" + so_number;
            }
        });

        <?php if (isset($_GET['so_number'])) { ?>
            $('#so_number option[value="<?php echo $_GET['so_number']; ?>"]').attr('selected', 'selected');
        <?php } ?>
    </script>



    <script>
        $(document).ready(function() {
            // Initialize Select2 on the SO Number dropdown
            $('#so_number').select2({
                placeholder: 'Search for SO Number',
                allowClear: true, // Optional: Adds a clear button to the dropdown
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Event listener for the so_number dropdown change
            $('#so_number').change(function() {
                // Get the selected so_number value
                var selectedSoNumber = $(this).val();

                // Check if bundling is equal to 1
                var bundling = getBundlingValue(selectedSoNumber);

                // Set project_type based on bundling value
                if (bundling == 1) {
                    $('#project_type').val('MSI Implementation');
                } else {
                    // Set default value or handle other cases if needed
                    // $('#project_type').val('Default');
                }
            });

            // Function to get bundling value based on so_number
            function getBundlingValue(soNumber) {
                // You may need to implement the logic to get bundling value
                // This is a placeholder, replace it with your actual logic
                var bundlingValue = 0;

                // Add your logic here to get the bundling value based on so_number
                // For example, you might fetch it from a database or use some other method

                return bundlingValue;
            }
        });
    </script>