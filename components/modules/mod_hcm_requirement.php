<?php
if (isset($_GET['sub'])) {
    include("components/modules/hcm_requirement/" . $_GET['sub'] . ".php");
} else
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1715069209";
    $author = 'Syamsul Arham';
} else {

    $mdlname = "REQUIREMENT_HCM";
    $userpermission = useraccess($mdlname);
    // var_dump($userpermission);
    // die;
    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "858ba4765e53c712ef672a9570474b1d" || USERPERMISSION == "34ac674a9e7eead3136801663c282dff" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
    $user = $_SESSION['Microservices_UserEmail'];
    $alluser = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email = '" . $user . "' AND job_level IN (1,2,3) AND resign_date is null");
    $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
        "'  AND resign_date is null AND organization_name like '%Recruitment%'");
    if (
        $alluser[2] > 0 || $HCMfull[2] > 0 ||
        $user == 'malik.aulia@mastersystem.co.id' ||
        $user == 'hanna.utami@mastersystem.co.id' ||
        $user == 'chrisheryanda@mastersystem.co.id' ||
        $user == 'aryo.bimo@mastersystem.co.id' ||
        $user == 'muhammad.febrian@mastersystem.co.id' ||
        $user == 'mulki.syahputra@mastersystem.co.id' ||
        $user == 'firmansyah@mastersystem.co.id' ||
        $user == 'lucky.andiani@mastersystem.co.id' ||
        $user == 'lukman.susanto@mastersystem.co.id' ||
        $user == 'akbar.nugraha@mastersystem.co.id'
    ) {
        $userName = addslashes($_SESSION['Microservices_UserName']);
        $entry_by = $userName . " <" . $user . ">";


        $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
            "'  AND resign_date is null AND organization_name like '%Recruitment%'");
?>
        <input type="hidden" id="userEmail" value="<?php echo $HCMfull[0]['employee_email']; ?>">

        <script>
            $(document).ready(function() {
                // Function to initialize DataTable with appropriate button actions
                function initializeDataTable(nr_stat_value) {
                    var tableConfig = {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        order: [
                            [0, 'desc']
                        ], // Assuming `request_date` is the first column
                        buttons: [],
                        columnDefs: [{
                            targets: [],
                            visible: false
                        }]
                    };

                    // Customize button actions based on nr_stat_value
                    if (nr_stat_value === "") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=hcm_requirement&act=add";
                            }
                        }, {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data();
                                var project_name = table.cell(rownumber, 7).data();
                                var gm = table.cell(rownumber, 25).data();
                                var gm_hcm = table.cell(rownumber, 28).data();
                                var gm_bod = table.cell(rownumber, 31).data();
                                var created_by = table.cell(rownumber, 24).data();
                                var userEmail = document.getElementById('userEmail').value;

                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                    // } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && gm_bod == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>" || id != null && userEmail == "<?php echo $user ?>") {
                                    //     window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&project_code=" + project_code + "&submit=Submit";
                                    // }
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&project_code=" + project_code + "&project_name=" + project_name + "&submit=Submit";
                                }
                            },
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Draft") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data();
                                var project_name = table.cell(rownumber, 7).data();
                                var gm = table.cell(rownumber, 25).data();
                                var gm_hcm = table.cell(rownumber, 28).data();
                                var gm_bod = table.cell(rownumber, 31).data();
                                var created_by = table.cell(rownumber, 24).data();
                                var userEmail = document.getElementById('userEmail').value;

                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                    // } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && gm_bod == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>" || id != null && userEmail == "<?php echo $user ?>") {
                                    //     window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&project_code=" + project_code + "&submit=Submit";
                                    // }
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=editdraft&id=" + id + "&project_code=" + project_code + "&project_name=" + project_name + "&submit=Submit";
                                }
                            },
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Submitted") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-eye'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data;
                                var gm = table.cell(rownumber, 25).data();
                                var gm_hcm = table.cell(rownumber, 28).data();
                                var gm_bod = table.cell(rownumber, 31).data();
                                var created_by = table.cell(rownumber, 24).data();
                                var userEmail = document.getElementById('userEmail').value;

                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=view&id=" + id + "&submit=Submit";
                                }
                            },
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Approval") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var table = $('#hcm_requirement').DataTable();
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data();
                                var userEmail = document.getElementById('userEmail').value;
                                // Add your logic for action on 'Approval' here
                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=editapproval&id=" + id + "&project_code=" + project_code + "&submit=Submit";
                                }
                            }
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Share") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var table = $('#hcm_requirement').DataTable();
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data();
                                var project_name = table.cell(rownumber, 7).data();
                                // Add your logic for action on 'Approval' here
                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=editshare&id=" + id + "&project_code=" + project_code + "&project_name=" + project_name + "&submit=Submit";
                                }
                            }
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Interview") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var table = $('#hcm_requirement').DataTable();
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 9).data();
                                var id_fpkb = table.cell(rownumber, 1).data();
                                // Add your logic for action on 'Approval' here
                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=editinterview&id=" + id + "&project_code=" + project_code + "&id_fpkb=" + id_fpkb + "&submit=Submit";
                                }
                            }
                        });
                        tableConfig.columnDefs.push({
                            targets: [0],
                            visible: false
                        });
                    } else if (nr_stat_value === "Offering") {
                        tableConfig.buttons.push({
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var table2 = $('#hcm_requirement_interview').DataTable();
                                var rownumber = table2.rows({
                                    selected: true
                                }).indexes();
                                var id = table2.cell(rownumber, 0).data();
                                var id_request = table2.cell(rownumber, 1).data();
                                var id_fpkb = table2.cell(rownumber, 2).data();
                                // Add your logic for action on 'Approval' here
                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else {
                                    window.location.href = "index.php?mod=hcm_requirement&act=editoffering&id=" + id + "&id_request=" + id_request + "&id_fpkb=" + id_fpkb + "&submit=Submit";
                                }
                            }
                        });
                        tableConfig.columnDefs.push({
                            targets: [0, 1, 3, 6, 7, 8],
                            visible: false
                        });
                    } else if (nr_stat_value === "Join") {
                        // tableConfig.buttons.push({
                        // text: "<i class='fa fa-pen'></i>",
                        // action: function() {
                        //     var table2 = $('#hcm_requirement_interview').DataTable();
                        //     var rownumber = table2.rows({
                        //         selected: true
                        //     }).indexes();
                        //     var id = table2.cell(rownumber, 0).data();
                        //     var id_fpkb = table2.cell(rownumber, 1).data();
                        //     // Add your logic for action on 'Approval' here
                        //     window.location.href = "index.php?mod=hcm_requirement&act=editoffering&id=" + id + "&id_fpkb=" + id_fpkb + "&submit=Submit";
                        // }
                        // });
                        tableConfig.columnDefs.push({
                            targets: [0, 2, 5, 6],
                            visible: false
                        });
                    }

                    // Initialize DataTable with the configured options
                    var table = $('#hcm_requirement').DataTable(tableConfig);
                    var table2 = $('#hcm_requirement_interview').DataTable(tableConfig);
                }

                // Get the current value of nr_stat from the URL query parameter
                var currentNrStat = "<?php echo isset($_GET['nr_stat']) ? $_GET['nr_stat'] : ''; ?>";

                // Initialize DataTable based on the current nr_stat value
                initializeDataTable(currentNrStat);

                // Handle change event on nr_stat dropdown
                $('#nr_stat').change(function() {
                    var selectedValue = $(this).val();
                    var url = "index.php?mod=hcm_requirement";

                    // If the selected value is "Dashboard", set the URL parameter to sub=dashboard_requirement
                    if (selectedValue === "Dashboard") {
                        url += "&sub=dashboard_requirement1";
                    } else {
                        // Otherwise, append nr_stat value
                        if (selectedValue) {
                            url += "&nr_stat=" + selectedValue;
                        }
                    }

                    // Redirect to the updated URL
                    window.location.href = url;
                });

                // Set the selected option in the dropdown based on nr_stat value
                <?php if (isset($_GET['nr_stat'])) { ?>
                    $('#nr_stat').val('<?php echo $_GET['nr_stat']; ?>');
                <?php } ?>
            });
        </script>
        <?php

        // Function
        //   if($_SESSION['Microservices_UserLevel'] == "Administrator") {

        function join_data($tblname)
        {

            if (!isset($_GET['year']))
                $year =  date("Y");
            else
                $year = $_GET['year'];

            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB1;
            $primarykey = "survey_id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            //view_table($DB1, $tblname, $primarykey, $condition, $order);
            $query = "SELECT template_name, pic_name, project_code, project_name, survey_link,souvenir, cust.customer_company_name, survey.created_by, survey.created_datetime from sa_survey survey join sa_question_template temp on survey.main_template_id = temp.template_id join sa_pic pic on pic.pic_id = survey.pic_id join sa_customer cust on pic.customer_code = cust.customer_code where survey.status = 'Active' AND YEAR(survey.created_datetime) = " . $year . " LIMIT 0,100";
            $datatable = $DB1->get_sql($query);
            $ddatatable = $datatable[0];
            $qdatatable = $datatable[1];
            $tdatatable = $datatable[2];
            $modtitle = 'Catalog listing';

            $queryyear = "SELECT DISTINCT(YEAR(created_datetime)) as year from sa_survey where created_datetime != '' AND status = 'active' order by created_datetime desc";
            $getyear = $DB1->get_sql($queryyear);
            $years = $getyear[0];
            $prev_year = $getyear[1];

            $header = ['Name', 'Project Code', 'Project Name', 'Customer', 'Template Name', 'Survey Link', 'Souvenir', 'Create Date'];
            $header1 = '';
            foreach ($header as $head) {
                $header1 .= "<th class='text-center align-middle'>" . $head . "</th>";
            }
        ?>
            <div class="card-body">
                <div>Tahun: <select id='selectYear'>
                        <?php
                        do {
                            $select = '';
                            if (isset($_GET['year']) && $_GET['year'] != '') {
                                $select = ($year == $years['year']) ? 'selected' : '';
                            }
                            echo "<option value='" . $years['year'] . "' $select>" . $years['year'];
                        } while ($years = $prev_year->fetch_assoc());
                        ?>
                    </select></div>
                <div class="table-responsive">
                    <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname; ?>" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <?php
                                echo $header1;
                                ?>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <?php echo $header1; ?>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if ($tdatatable > 0) {
                                do { ?>
                                    <tr>
                                        <?php
                                        $datatable_header2 = $DB1->get_columns($tblname);
                                        $ddatatable_header2 = $datatable_header2[0];
                                        $qdatatable_header2 = $datatable_header2[1];
                                        ?>
                                <?php
                                    echo "<td>" . $ddatatable['pic_name'] . "</td>";
                                    echo "<td>" . $ddatatable['project_code'] . "</td>";
                                    echo "<td>" . $ddatatable['project_name'] . "</td>";
                                    echo "<td>" . $ddatatable['customer_company_name'] . "</td>";
                                    echo "<td>" . $ddatatable['template_name'] . "</td>";
                                    echo "<td>" . $ddatatable['survey_link'] . "</td>";
                                    echo "<td>" . $ddatatable['souvenir'] . "</td>";
                                    echo "<td>" . $ddatatable['created_datetime'] . "</td>";
                                    echo "</tr>";
                                } while ($ddatatable = $qdatatable->fetch_assoc());
                            }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        }
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBHCM;
            $primarykey = "id";
            // $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBHCM, $tblname, $primarykey, $condition, $order, 0, 0);
        }
        function form_data($tblname)
        {
            include("components/modules/hcm_requirement/form_hcm_requirement.php");
        }

        // End Function

        // $database = 'sa_md_hcm';
        // include("components/modules/hcm_requirement/connection.php");
        // $DB = new Databases($hostname, $username, $userpassword, $database);
        // $DBHCM = get_conn('hcm_requirement');
        $tblname = 'hcm_requirement';
        $tblname2 = 'hcm_requirement_interview';


        include("components/modules/hcm_requirement/func_hcm_requirement.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Requirement</h6>
                </div>
                <div class="card-body">
                    <?php if (!isset($_GET['act'])) { ?>
                        <select name="" id="nr_stat">
                            <?php
                            $user_gm_bod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email = '" . $user . "' AND job_level = 2 AND resign_date is null");
                            $user_manager = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email = '" . $user . "' AND job_level = 3 AND resign_date is null");
                            $user_bod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email = '" . $user . "' AND job_level = 1 AND resign_date is null");
                            if ($HCMfull[2] > 0 || $user == 'malik.aulia@mastersystem.co.id' || $user == 'muhammad.febrian@mastersystem.co.id') {
                            ?>

                                <!-- <option value="">Requirement</option> -->
                                <option value="">Request</option>
                                <option value="Draft">Draft Request</option>
                                <option value="Submitted">Requirement Submitted</option>
                                <option value="Approval">Assign Recruiter</option>
                                <option value="Share">Upload CV</option>
                                <option value="Interview">Interview</option>
                                <option value="Offering">Offering</option>
                                <option value="Join">Join</option>
                                <option disabled>-------------------</option>
                                <option value="Hold">Hold</option>
                                <option value="Request Inactive">Request Inactive</option>
                                <option value="Dashboard">Dashboard</option>
                            <?php } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') { ?>
                                <option value="">Request</option>
                                <option value="Draft">Draft Request</option>
                                <option value="Submitted">Requirement Submitted</option>
                                <option value="Share">Upload CV</option>
                                <option value="Interview">Interview</option>
                                <option value="Offering">Offering</option>
                            <?php } elseif ($user_gm_bod[2] > 0) { ?>
                                <option value="">Request</option>
                                <option value="Draft">Draft Request</option>
                                <option value="Submitted">Requirement Submitted</option>
                                <option value="Share">Upload CV</option>
                                <option value="Interview">Interview</option>
                                <option value="Offering">Offering</option>
                            <?php } elseif ($user_bod[2] > 0) { ?>
                                <option value="">Request</option>
                                <option value="Draft">Draft Request</option>
                                <option value="Submitted">Requirement Submitted</option>
                                <option value="Share">Upload CV</option>
                                <option value="Interview">Interview</option>
                                <option value="Offering">Offering</option>
                            <?php }
                            ?>
                        </select>
                    <?php } ?>
                    <?php


                    if (!isset($_GET['act']) && !isset($_GET['nr_stat'])) {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0 || $user == 'muhammad.febrian@mastersystem.co.id') {
                            $condition = "status_request = 'Pending Approval'";
                            view_data($tblname, $condition);
                        } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') {
                            $condition = "status_request = 'Pending Approval' AND request_by LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_gm_bod[2] > 0) {
                            $condition = "status_request = 'Pending Approval' AND gm LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_bod[2] > 0) {
                            $condition = "status_request = 'Pending Approval' AND (gm LIKE '%$user%' OR gm_bod LIKE '%$user%')";
                            view_data($tblname, $condition);
                        }
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Draft") {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0 || $user == 'muhammad.febrian@mastersystem.co.id') {
                            $condition = "status_request = 'Request Draft' AND request_by LIKE '%$user%'";
                            view_data($tblname, $condition);
                        }
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Submitted") {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0 || $user == 'muhammad.febrian@mastersystem.co.id') {
                            $condition = "status_request = 'Submitted' or status_request = 'Proses Interview'";
                            view_data($tblname, $condition);
                        } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') {
                            $condition = "status_request IN ('Submitted', 'Proses Interview') AND request_by LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_gm_bod[2] > 0) {
                            $condition = "status_request IN ('Submitted', 'Proses Interview') AND gm LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_bod[2] > 0) {
                            $condition = "status_request IN ('Submitted', 'Proses Interview') AND (gm LIKE '%$user%' OR gm_bod LIKE '%$user%')";
                            view_data($tblname, $condition);
                        }
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Approval") {
                        $condition = "status_request = 'Submitted'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Share") {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0) {
                            $condition = "assign_requirement is not null";
                            view_data($tblname, $condition);
                        } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') {
                            $condition = "assign_requirement is not null AND request_by LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_gm_bod[2] > 0) {
                            $condition = "assign_requirement is not null AND divisi = '" . $user_gm_bod[0]['organization_name'] . "'";
                            view_data($tblname, $condition);
                        } elseif ($user_bod[2] > 0) {
                            $condition = "assign_requirement is not null AND (gm LIKE '%$user%' OR gm_bod LIKE '%$user%')";
                            view_data($tblname, $condition);
                        }
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Interview") {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0 || $user == 'muhammad.febrian@mastersystem.co.id') {
                            // $condition = "assign_requirement is not null";
                            $condition = "status_request = 'Proses Interview'";
                            view_data($tblname, $condition);
                        } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') {
                            $condition = "status_request = 'Proses Interview' AND request_by LIKE '%$user%'";
                            view_data($tblname, $condition);
                        } elseif ($user_gm_bod[2] > 0) {
                            $condition = "status_request = 'Proses Interview' AND divisi = '" . $user_gm_bod[0]['organization_name'] . "'";
                            view_data($tblname, $condition);
                        } elseif ($user_bod[2] > 0) {
                            $condition = "status_request = 'Proses Interview' AND (gm LIKE '%$user%' OR gm_bod LIKE '%$user%')";
                            view_data($tblname, $condition);
                        }
                        // $condition = "status_request = 'Proses Interview'";
                        // view_data($tblname, $condition);
                        // } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Hasil") {
                        //     $condition = "email_id is not null";
                        //     view_data($tblname6, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Join") {
                        $condition = "status = 'Complete Offering'";
                        join_data($tblname2, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Offering") {
                        if ($user == 'malik.aulia@mastersystem.co.id' || $HCMfull[2] > 0) {
                            $condition = "status_cv = 'Yes' AND status != 'Complete Offering'";
                            view_data($tblname2, $condition);
                        } elseif ($user_gm_bod[2] > 0) {
                            $condition = "status_cv = 'Yes' AND status != 'Complete Offering' AND gm_offering like '%$user%'";
                            view_data($tblname2, $condition);
                        } elseif ($user_bod[2] > 0) {
                            $condition = "status_cv = 'Yes' AND status != 'Complete Offering' AND (gm_offering like '%$user%' OR BOD like '%$user%')";
                            view_data($tblname2, $condition);
                        } elseif ($user_manager[2] > 0 || $user == 'hanna.utami@mastersystem.co.id' || $user == 'mulki.syahputra@mastersystem.co.id' || $user == 'akbar.nugraha@mastersystem.co.id' || $user == 'firmansyah@mastersystem.co.id' || $user == 'lukman.susanto@mastersystem.co.id' || $user == 'lucky.andiani@mastersystem.co.id') {
                            $condition = "status_cv = 'Yes' AND status != 'Complete Offering' AND interview_user LIKE '%$user%'";
                            view_data($tblname2, $condition);
                        }
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Hold") {
                        $condition = "status = 'Hold Offering'";
                        view_data($tblname2, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Request Inactive") {
                        $condition = "status_request = 'Inactive'";
                        view_data($tblname, $condition);
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'edit') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editdraft') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'view') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editapproval') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editshare') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'complete') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editinterview') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editoffering') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'editjoin') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        echo 'Delete Data';
                    } elseif ($_GET['act'] == 'save') {
                        form_data($tblname);
                    }
                    ?>
                </div>
            </div>
        </div>
<?php

    } else {
        $ALERT->notpermission();
    }
}
?>