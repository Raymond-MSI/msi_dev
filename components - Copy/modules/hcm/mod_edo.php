<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1659682252";
        $author = 'Syamsul Arham';
    } else {
        $modulename = "trx_edo_request";
        $mdlname = "EDO";
        $mdl_permission = useraccess_v2($mdlname);
        if(USERPERMISSION_V2=="9df0aa97b66fdfd315d2a972ef4a3b65") {
            ?>
            <script>
                $(document).ready(function() {
                    var tableDraft = $('#trx_edo_request').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=add";
                               },
                               enabled: true
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = tableDraft.rows({selected: true}).indexes();
                                   var edo_id = tableDraft.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=edit&edo_id="+edo_id+"&submit=Submit";
                                },
                               enabled: true
                            },
                            {
                               text: "<i class='fa fa-eye'></i>",
                               action: function () {
                                   var rownumber = tableDraft.rows({selected: true}).indexes();
                                   var edo_id = tableDraft.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=view&edo_id="+edo_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               extend: 'excelHtml5',
                               text: "<i class='fa fa-file-pdf'></i>",
                               title: 'Edo_'+<?php echo date("YmdGis"); ?>
                            },
                        ],
                        "columnDefs": [
                           {
                              "targets": [0,10,12,13],
                               "visible": false,
                           },
                           {
                            "targets": [4,5,13,15],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY hh:mm:ss'),
                           },
                           {
                            "targets": [6,7],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY'),
                           },
                           {
                            "targets": [8],
                            "className": 'dt-body-center',
                           },
                       ],
                       "order": [
                            [15, "desc"]
                        ]
                     } );

                     var tableSubmit = $('#trx_edo_request1').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=add";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = tableSubmit.rows({selected: true}).indexes();
                                   var edo_id = tableSubmit.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=edit&edo_id="+edo_id+"&submit=Submit";
                                },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-eye'></i>",
                               action: function () {
                                   var rownumber = tableSubmit.rows({selected: true}).indexes();
                                   var edo_id = tableSubmit.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=view&edo_id="+edo_id+"&submit=Submit";
                               },
                               enabled: true
                            },
                            {
                               extend: 'excelHtml5',
                               text: "<i class='fa fa-file-pdf'></i>",
                               title: 'Edo_'+<?php echo date("YmdGis"); ?>
                            },
                        ],
                        "columnDefs": [
                           {
                              "targets": [0,10,12,13],
                               "visible": false,
                           },
                           {
                            "targets": [4,5,13,15],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY hh:mm:ss'),
                           },
                           {
                            "targets": [6,7],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY'),
                           },
                           {
                            "targets": [8],
                            "className": 'dt-body-center',
                           },
                        ],
                        "order": [
                            [15, "desc"]
                        ]
                     } );

                     var tableCompleted = $('#trx_edo_request3').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=add";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = tableCompleted.rows({selected: true}).indexes();
                                   var edo_id = tableCompleted.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=edit&edo_id="+edo_id+"&submit=Submit";
                                },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-eye'></i>",
                               action: function () {
                                   var rownumber = tableCompleted.rows({selected: true}).indexes();
                                   var edo_id = tableCompleted.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm&sub=edo&act=view&edo_id="+edo_id+"&submit=Submit";
                               },
                               enabled: true
                            },
                            {
                               extend: 'excelHtml5',
                               text: "<i class='fa fa-file-pdf'></i>",
                               title: 'Edo_'+<?php echo date("YmdGis"); ?>
                            },
                        ],
                        "columnDefs": [
                           {
                              "targets": [0,10,12,13],
                               "visible": false,
                           },
                           {
                            "targets": [4,5,13,15],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY hh:mm:ss'),
                           },
                           {
                            "targets": [6,7],
                            "className": 'dt-body-right',
                            "render": DataTable.render.datetime('DD MMM YYYY'),
                           },
                           {
                            "targets": [8],
                            "className": 'dt-body-center',
                           },
                       ],
                       "order": [
                            [15, "desc"]
                        ]
                     } );

                  } );
               </script>
           <?php 
    
            // Function
            // if($_SESSION['Microservices_UserLevel'] == "Administrator") {
                function view_data($tblname) {
                    global $mdl_permission;
                    ?>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-body" id="draft-tab" data-bs-toggle="tab" data-bs-target="#EDO-Draft" type="button" role="tab" aria-controls="EDODraft" aria-selected="true" title='EDO Draft'>EDO Request</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-body" id="submit-tab" data-bs-toggle="tab" data-bs-target="#EDO-Submit" type="button" role="tab" aria-controls="EDOSubmit" aria-selected="false" title='EDO Submit'>EDO Submit</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-body" id="approval-tab" data-bs-toggle="tab" data-bs-target="#EDO-Completed" type="button" role="tab" aria-controls="EDOCompleted" aria-selected="false" title='EDO Completed'>EDO Complete</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="EDO-Draft" role="tabpanel" aria-labelledby="draft-tab">
                            <div class="card  mb-4">
                                <?php
                                global $DBHCM;

                                if($_SESSION['Microservices_UserLevel']=="Administrator" || $_SESSION['Microservices_UserLevel']=="Super Admin") {
                                    $conditionUser = "";
                                } else {
                                    $tblnamex = "view_employees";
                                    $condition = "employee_email = '" . $_SESSION['Microservices_UserEmail'] . "' AND `resign_date` IS NULL";
                                    $employee_info = $DBHCM->get_data($tblnamex, $condition);

                                    // $jobs = explode(" ", $employee_info[0]['job_structure']);
                                    // $job = "";
                                    // for($i=0; $i<$employee_info[0]['job_level']; $i++) {
                                    //     $job .= $jobs[$i] . " ";
                                    // }
                                    // $conditionUser = " AND (`employee_name` IN (SELECT CONCAT(`sa_view_employees`.`employee_name`, '<', `sa_view_employees`.`employee_email`, '>') FROM `sa_view_employees` WHERE `sa_view_employees`.`job_structure` LIKE '%" . trim($job) . "%'))";

                                    // $xxx = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
                                    // $conditionUser = "";
                                    // $sambung = " AND (";
                                    // foreach($xxx[2] as $zzz)
                                    // {
                                    //     $conditionUser .= $sambung . "`employee_name`='" . trim($zzz) . "'";
                                    //     $sambung = " OR ";
                                    // }
                                    // $conditionUser .= ")";

                                    $xxx = $employee_info[0]['job_name'];
                                    $job_name = str_replace("0", "", $xxx);
                                    $conditionUser = " AND ((`employee_name` IN (SELECT CONCAT(`sa_view_employees`.`employee_name`, '<', `sa_view_employees`.`employee_email`, '>') FROM `sa_view_employees` WHERE `sa_view_employees`.`job_name` LIKE '%" . trim($job_name) . "%')) OR `entry_by`='" . $_SESSION['Microservices_UserName'] . "<" . trim($_SESSION['Microservices_UserEmail']) . ">')";
                                }

                                if($mdl_permission['mdllevel']=="Member")
                                {
                                    $conditionUser = " AND `employee_name` = '" . $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">'";
                                }

                                $primarykey = "edo_id";
                                $condition = "(status = 'drafted' OR status = 'edo rejected' OR status = 'request approved' OR status = 'leave rejected') " . $conditionUser;
                                if(isset($employee_info) && strpos($employee_info[0]['organization_name'], "Generalist")!==false)
                                { 
                                    $condition = "(status = 'drafted' OR status = 'edo rejected' OR status = 'request approved' OR status = 'leave rejected') "; 
                                }
                                $order = "";
                                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                                    global $ALERT;
                                    $ALERT->datanotfound();
                                } 
                                view_table($DBHCM, $tblname, $primarykey, $condition, $order, "", "");
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="EDO-Submit" role="tabpanel" aria-labelledby="submit-tab">
                            <div class="card  mb-4">
                                <?php
                                // global $DBHCM;
                                // $primarykey = "edo_id";
                                $condition = "(status = 'edo submitted' || status = 'leave submitted') " . $conditionUser;
                                if(isset($employee_info) && strpos($employee_info[0]['organization_name'], "Generalist")!==false)
                                {
                                    $condition = "(status = 'edo submitted' || status = 'leave submitted) "; 
                                }
                                // $order = "";
                                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                                    global $ALERT;
                                    $ALERT->datanotfound();
                                } 
                                view_table($DBHCM, $tblname, $primarykey, $condition, $order, "", "", 1);
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="EDO-Completed" role="tabpanel" aria-labelledby="completed-tab">
                            <div class="card  mb-4">
                                <?php
                                $condition = "(status = 'completed' OR status='expired') " . $conditionUser;
                                if(isset($employee_info) && strpos($employee_info[0]['organization_name'], "Generalist")!==false)
                                {
                                    $condition = "(status = 'completed' OR status = 'expired') "; 
                                }
                                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                                    global $ALERT;
                                    $ALERT->datanotfound();
                                } 
                                view_table($DBHCM, $tblname, $primarykey, $condition, $order, "", "", 3);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
    
                function form_data($tblname) {
                    global $mdl_permission;
                    include("components/modules/hcm/form_edo.php"); 
                } 
        
                // End Function
        
                $tblname = "cfg_web";
                $condition = 'config_key="MODULE_HCM"';
                $setupDB = $DB->get_data($tblname, $condition);
                $dsetupDB = $setupDB[0];
                if($setupDB[2]>0) {
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
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-secondary"><?php echo $mdl_permission["mdltitle"]; ?></h6>
                            </div>
                            <div class="card-body">
                                <?php
                                if(!isset($_GET['act'])) {
                                    view_data($tblname);
                                } elseif($_GET['act'] == 'add') {
                                    form_data($tblname);
                                } elseif($_GET['act'] == 'view') {
                                    form_data($tblname);
                                } elseif($_GET['act'] == 'edit') {
                                    form_data($tblname);
                                } elseif($_GET['act'] == 'del') {
                                    echo 'Delete Data';
                                } elseif($_GET['act'] == 'save') {
                                    form_data($tblname);
                                }
                                ?>
                            </div>
                        </div>
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
    