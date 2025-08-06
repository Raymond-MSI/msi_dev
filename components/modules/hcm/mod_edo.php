<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1659682252";
    $author = 'Syamsul Arham';
} else {
    $author = "Syamsul Arham";
    $update_info = "";

    $modulename = "trx_edo_request";

    $mdlname = "EDO";
    $mdl_permission = useraccess_v3($mdlname);

    if ($mdl_permission['user_level'] != "None") {
?>
        <script>
            $(document).ready(function() {
                var tableDraft = $('#EDOList').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=hcm&sub=edo&act=add";
                            },
                            enabled: true
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = tableDraft.rows({
                                    selected: true
                                }).indexes();
                                var edo_id = tableDraft.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm&sub=edo&act=edit&edo_id=" + edo_id + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            text: "<i class='fa fa-eye'></i>",
                            action: function() {
                                var rownumber = tableDraft.rows({
                                    selected: true
                                }).indexes();
                                var edo_id = tableDraft.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm&sub=edo&act=view&edo_id=" + edo_id + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'Edo_' + <?php echo date("YmdGis"); ?>
                        },
                    ],
                    columnDefs: [{
                        targets: [0],
                        visible: false,
                    }, ],
                });
                tableDraft.on('select deselect', function() {
                    var selectedRows = tableDraft.rows({
                        selected: true
                    }).count();
                    tableDraft.button(1).enable(selectedRows > 0);
                });
            });
        </script>
        <?php

        function view_data($tblname)
        {
            global $mdl_permission;
            global $DBHCM;
            $tblnamex = "view_employees";
            $condition = "employee_email = '" . $_SESSION['Microservices_UserEmail'] . "' AND `resign_date` IS NULL";
            $employee_info = $DBHCM->get_data($tblnamex, $condition);

            $subs = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
            $subordinats = "";
            $i = 0;
            if ($subs[0] != "None") {
                foreach ($subs[2] as $namex) {
                    $i > 0 ? $sambung = " OR " : $sambung = "";
                    $subordinats .= $sambung . '`employee_name`="' . addslashes($namex) . '"';
                    $i++;
                }
                $subordinats = " OR " . $subordinats;
            }

            $xxx = $employee_info[0]['job_name'];
            $job_name = str_replace("0", "", $xxx);
            // if(isset($_GET['status']) && $_GET['status']=="waiting")
            // {
            //     $conditionUser = " AND (`employee_name` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `entry_by` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `performed_by` LIKE '" . addslashes($_SESSION['Microservices_UserName']) . "%' OR `leader` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%' " . $subordinats . ")";
            // } else
            if (isset($_GET['resource'])) {
                $conditionUser = " AND `employee_name` LIKE '%" . $_GET['resource'] . "%'";
            } else {
                $conditionUser = " AND `employee_name` LIKE '%" . addslashes($_SESSION['Microservices_UserName']) . "%'";
            }

            $primarykey = "edo_id";
            if (isset($_GET['status'])) {
                // My EDO
                $xxx = date("Y-m-d", strtotime("-1 week"));
                if ($_GET['status'] == 'draft') {
                    $condition = "`status` = 'drafted'";
                } elseif ($_GET['status'] == 'submitted') {
                    $condition = "(`status` = 'edo submitted' OR (`status` = 'request approved' AND `flag_approval` = 1))";
                } elseif ($_GET['status'] == 'approved') {
                    $condition = "(`status` = 'request approved' AND `flag_approval` = 2)";
                } elseif ($_GET['status'] == 'rejected') {
                    $condition = "(`status` = 'edo rejected')";
                } elseif ($_GET['status'] == 'completed') {
                    $condition = "(`status` = 'completed' OR `status` = 'completed with paid' OR `status` = 'cancel by cutoff')";
                } else {
                    $condition = "`employee_name` ='" . $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">'";
                }
            } else {
                $condition = "(`status` = 'edo submitted' OR (`status` = 'request approved' AND `flag_approval` = 1))";
            }

            // if(isset($employee_info) && strpos($employee_info[0]['organization_name'], "Generalist")>=0)
            // { 
            // } else
            // {
            $condition .= $conditionUser;
            // }
            $condition .= " AND `start_date` >= '" . date("Y", strtotime("-1 year")) . "-01-01'";
            $order = "";
            $rsEdoList = $DBHCM->get_data($tblname, $condition, $order);

            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }


            include("components/modules/hcm/mod_edo_menu.php");

        ?>
            <div class="table-responsive">
                <table class="display compact hover" id="EDOList" width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2">EDO ID</th>
                            <th rowspan="2" class="bg-primary-subtle text-primary-emphasis">Employee Name</th>
                            <th colspan="5" class="text-center bg-info-subtle text-info-emphasis">Overtime</th>
                            <?php
                            if (isset($_GET['status']) && $_GET['status'] == "completed") {
                            ?>
                                <th colspan="3" class="text-center bg-success-subtle text-success-emphasis">Extra Day Off</th>
                            <?php
                            }
                            ?>
                            <th class="text-center bg-primary-subtle text-primary-emphasis" rowspan="2">Approval</th>
                            <th class="bg-info-subtle text-info-emphasis" rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th class="bg-info-subtle text-info-emphasis">Start</th>
                            <th class="bg-info-subtle text-info-emphasis">End</th>
                            <th class="bg-info-subtle text-info-emphasis">Duration</th>
                            <th class="bg-info-subtle text-info-emphasis">Onsite</th>
                            <th class="bg-info-subtle text-info-emphasis">Reason</th>
                            <?php
                            if (isset($_GET['status']) && $_GET['status'] == "completed") {
                            ?>
                                <th class="bg-success-subtle text-success-emphasis">Start</th>
                                <th class="bg-success-subtle text-success-emphasis">End</th>
                                <th class="bg-success-subtle text-success-emphasis">Approval</th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($rsEdoList[2] > 0) {
                            do { ?>
                                <tr class="align-top">
                                    <td><?php echo $rsEdoList[0]['edo_id']; ?></td>
                                    <td><?php echo $rsEdoList[0]['employee_name'] . " | " . $rsEdoList[0]['jabatan']; ?></td>
                                    <td class="text-right text-nowrap"><?php echo date("d-M-Y H:i", strtotime($rsEdoList[0]['start_date'])); ?></td>
                                    <td class="text-right text-nowrap"><?php echo date("d-M-Y H:i", strtotime($rsEdoList[0]['end_date'])); ?></td>
                                    <td class="text-center"><?php echo $rsEdoList[0]['duration']; ?></td>
                                    <td class="text-center"><?php echo $rsEdoList[0]['category']; ?></td>
                                    <td>
                                        <?php
                                        if ($rsEdoList[0]['project_code'] != "") {
                                            $DBNAV = get_conn("NAVISION");
                                            $mysql = sprintf(
                                                "SELECT `project_name` 
                                                FROM `sa_mst_order_number` 
                                                WHERE `project_code` = %s",
                                                GetSQLValueString($rsEdoList[0]['project_code'], "text")
                                            );
                                            $rsProjects = $DBNAV->get_sql($mysql);
                                            echo $rsEdoList[0]['project_code'] . " - ";
                                            if ($rsProjects[2] > 0) {
                                                echo $rsProjects[0]['project_name'] . "<br/>";
                                            }
                                        } else {
                                            echo "Non-Project<br/>";
                                        }
                                        echo "Reason : " . $rsEdoList[0]['reason'];
                                        ?>
                                    </td>
                                    <td><?php echo $rsEdoList[0]['overtime_approval_by']; ?></td>
                                    <?php
                                    if (isset($_GET['status']) && $_GET['status'] == "completed") {
                                    ?>
                                        <td class="text-right"><?php echo $rsEdoList[0]['actual_start'] > 0 ? date("d-M-Y", strtotime($rsEdoList[0]['actual_start'])) : ""; ?></td>
                                        <td class="text-right"><?php echo $rsEdoList[0]['actual_end'] > 0 ? date("d-M-Y", strtotime($rsEdoList[0]['actual_end'])) : ""; ?></td>
                                        <td></td>
                                    <?php
                                    }
                                    ?>
                                    <td class="text-center">
                                        <?php
                                        if (date("Y-m-d", strtotime($rsEdoList[0]['performed_date'])) < date("Y-m-d", strtotime("-1 week")) && $rsEdoList[0]['status'] == 'edo submitted') {
                                            echo "Overdue Approval";
                                        } else {
                                            $status = get_status($rsEdoList[0]['status']);
                                            echo $status . "";
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            } while ($rsEdoList[0] = $rsEdoList[1]->fetch_assoc());
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }

        function form_data($tblname)
        {
            global $mdl_permission;
            include("components/modules/hcm/form_edo.php");
        }

        // End Function

        $tblname = "cfg_web";
        $condition = 'config_key="MODULE_HCM"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            $params = get_params($dsetupDB["params"]);
            $hostname = $params["database"]["hostname"];
            $username = $params["database"]["username"];
            $userpassword = $params["database"]["userpassword"];
            $database = $params["database"]["database_name"];
            $DBHCM = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'trx_edo_request';

            include("components/modules/hcm/func_edo.php");
            $tblname = 'trx_edo_request';

            // Body
        ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary"><?php echo $mdl_permission["mdltitle"]; ?></h6>
                    <div class="align-items-right">
                        <a href="https://msiguide.mastersystem.co.id/?cat=157" target="_blank"><i class="fa-solid fa-circle-info fs-4"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        view_data($tblname);
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'view') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'edit') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        echo 'Delete Data';
                    } elseif ($_GET['act'] == 'save') {
                        form_data($tblname);
                    }
                    ?>
                </div>
                <?php //show_footer("control", "Syamsul Arham", $msg="Testing"); 
                ?>
            </div>
<?php
        } else {
            echo "Aplikasi belum disetup";
        }
    } else {
        $ALERT->notpermission();
    }
    // End Body
    // } 
}
?>

<script src="components/modules/hcm/java_edo.js"></script>