<?php
global $DTSB;

// echo $_SESSION['Microservices_UserEmail'] . "<br/>";
// echo $_SESSION['Microservices_UserName'];

    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1667208841";
        $author = 'Syamsul Arham';
    }else{
        $modulename = "Resource Assignment";
        $userpermission = useraccess($modulename);

        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == '0162bce636a63c3ae499224203e06ed0'){
            ?>
            <script>
                $(document).ready(function() {
                    var table = $('#view_resource_assignment').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                                extend: 'colvis',
                                text: "<i class='fa fa-columns'></i>",
                                collectionLayout: 'fixed four-column'
                            },
                            {
                                text: "<i class='fa fa-eye'></i>",
                                action: function () {
                                    var rownumber = table.rows({selected: true}).indexes();
                                    var id = table.cell( rownumber,0 ).data();
                                    window.location.href = "index.php?mod=resource_assignment&act=view&id="+id+"&submit=Submit";
                                },
                                enabled: false
                            },
                            //{
                              //  text: "<i class='fa fa-pen'></i>",
                                //action: function () {
                                  //  var rownumber = table.rows({selected: true}).indexes();
                                    //var id = table.cell( rownumber,0 ).data();
                                    //var project_code = table.cell( rownumber,1 ).data();
                                    //var no_so = table.cell( rownumber,2).data();
                                    //window.location.href = "index.php?mod=resource_assignment&act=edit&project_code="+project_code+"&no_so="+no_so+"&id="+id+"&submit=Submit";
                                //}
                            //},
                            {
                                text: "<i class='fa fa-plus'></i>",
                                action: function () {
                                    window.location.href = "index.php?mod=resource_assignment&act=add";
                                },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                            {
                                "targets": [0],
                                "visible": false,
                            }
                        ],
                        } );
                    } );
                </script>
            <?php 
            // Function
            // if($_SESSION['Microservices_UserLevel'] == "Administrator") {
            function view_data($tblname) {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DTSB;
                $primarykey = "id";
                $condition ='';
                $order = "";
                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                    global $ALERT;
                    $ALERT->datanotfound();
                } 
                
                view_table($DTSB, $tblname, $primarykey, $condition, $order);
            }

            function view_data_approval($tblname, $condition) {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DTSB;
                $primarykey = "id";
                $order = "";
                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                    global $ALERT;
                    $ALERT->datanotfound();
                } 
                
                view_table($DTSB, $tblname, $primarykey, $condition, $order);
            }

            function form_data($tblname) {
                global $DTSB;
                include("components/modules/resource_assignment/form_resource_assignment.php"); 
            } 
    
            // End Function
    
            //   $database = 'sa_wrike_integrate';
            //   include("components/modules/resource_assignment/connection.php");
            //   $DB = new Databases($hostname, $username, $userpassword, $database);
            //   $tblname = 'resource_assignment';

            $tblname = 'cfg_web';
            $condition = 'config_key="MODULE_RESOURCE_ASSIGNMENT"';
            $setupDB = $DB->get_data($tblname, $condition);
            $dsetupDB = $setupDB[0];
            if($setupDB[2]>0) {
                $params = get_params($dsetupDB['params']);
                $hostname = $params['database']['hostname'];
                $username = $params['database']['username'];
                $userpassword = $params['database']['userpassword'];
                $database = $params['database']['database_name'];

                $DTSB = new Databases($hostname, $username, $userpassword, $database);
                $tblname = 'view_resource_assignment';

                include("components/modules/resource_assignment/func_resource_assignment.php");
                // include("components/classes/func_hcm.php");
                // Bodyyy
            ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Project Charter</h6>
                    </div>
                    <div class="card-body">  
                    <?php if (!isset($_GET['act'])) { ?>
                            <select name="" id="assignment_status">
                                <option value="my_assignment">My Assignment</option>
                                <option value="pending_assignment">Pending Assignment</option>
                                <option value="approved_assignment">Approved Assignment</option>
                                <option value="rejected_assignment">Rejected Assignment</option>
                                <?php
                                    if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b"){
                                ?>
                                    <option value="temporary_assignment">Temporary Assignment</option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    <?php
                        if(!isset($_GET['act']) && !isset($_GET['status'])) {
                            if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b"){
                                view_data($tblname);
                            }else{
                                $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%'";
                                view_data_approval($tblname, $condition);
                            }
                        } elseif(!isset($_GET['act']) && $_GET['mod'] == "resource_assignment" && $_GET['status'] == 'pending_assignment') {
                            if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b"){
                                $condition = "approval_status = 'pending'";
                                view_data_approval($tblname, $condition);
                            }else{
                                // $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'pending'";
                                // view_data_approval($tblname, $condition);
                    ?>
                        <div class="col-12 mt-3">
                                <hr/>
                                <form method="POST" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                        <th>Checklist</th>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
					<th>Approval To</th>
                                        <th>Approval Status</th>
                                        <th>Created In</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sqlViewData = "SELECT * from sa_resource_assignment WHERE approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'pending'";
                                            $getData = $DTSB->get_sql($sqlViewData);
                                            $rowData = $getData[0];
                                            $resData = $getData[1];
                                            $totalRowData = $getData[2];

                                            if($totalRowData > 0){
                                                do{
                                                    $assignmentId = $rowData['id'];
                                                    $projectCode = $rowData['project_code'];
                                                    $soNumber = $rowData['no_so'];
                                                    $customerName = $rowData['customer_name'];
                                                    $projectName = $rowData['project_name'];
                                                    $rawResourceEmail = $rowData['resource_email'];
                                                    $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                                    $resourceEmail = str_replace(">","",$rawResourceEmail2[1]);
                                                    $rawRoles = $rowData['roles'];
                                                    $roles = str_replace(" - "," ","$rawRoles");
                                                    $status = $rowData['status'];
                                                    $startProgress = $rowData['start_progress'];
                                                    $endProgress = $rowData['end_progress'];
                                                    $finalStatus = "$status $startProgress% - $endProgress%";
                                                    $createdBy = $rowData['created_by'];
						    $approvalTo = $rowData['approval_to'];
                                                    $approvalStatus = $rowData['approval_status'];
                                                    $createdIn = $rowData['created_in_msizone'];

                                                    $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                        ?>
                                        <tr>
                                        <td><input type="checkbox" name="id[]"  class="ml-auto form-check-input chk_boxes1" value="<?php echo $assignmentId?>"></td>
                                        <td><?php echo $projectCode;?></td>
                                        <td><?php echo $soNumber;?></td>
                                        <td><?php echo $customerName; ?></td>
                                        <td><?php echo $projectName; ?></td>
                                        <td><?php echo $resourceEmail;?></td>
                                        <td><?php echo $roles; ?></td>
                                        <td><?php echo $finalStatus; ?></td>
                                        <td><?php echo $createdBy; ?></td>
					<td><?php echo $approvalTo; ?></td>
                                        <td><?php echo $approvalStatus; ?></td>
                                        <td><?php echo $createdIn; ?></td>
                                        </tr>
                                        <?php 
                                                }while($rowData = $resData->fetch_assoc());
                                            }else{
                                        ?>
                                            <td colspan="11">Belum ada approval</td>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th><input type="checkbox" class="check_all"/> Checklist All</th>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
					<th>Approval To</th>
                                        <th>Approval Status</th>
                                        <th>Created In</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr/>
                                <input type="hidden" name="userSession" value="<?php echo $userSession; ?>">
                                <button type="submit" name="btn_approve" id="btn_approve" class="btn btn-primary mt-1" onclick="javascript: return confirm('Apakah Anda yakin ingin approve data ini?')">Approve</button>
                                </form>
                        </div>
                                
                    <?php
                            }
                        } elseif(!isset($_GET['act']) && $_GET['status'] == 'approved_assignment') {
                            if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b"){
                                $condition = "approval_status = 'approved'";
                                view_data_approval($tblname, $condition);
                            }else{
                                $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'approved'";
                                view_data_approval($tblname, $condition);
                            }
                        } elseif(!isset($_GET['act']) && $_GET['status'] == 'rejected_assignment') {
                            if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b"){
                                $condition = "approval_status = 'rejected'";
                                view_data_approval($tblname, $condition);
                            }else{
                                $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'rejected'";
                                view_data_approval($tblname, $condition);
                            }
                        }elseif($_GET['act'] == 'add') {
                            form_data($tblname);
                        } elseif($_GET['act'] == 'new') {
                            new_projects($tblname);
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
            // }else { 
            //   $ALERT->notpermission();
            // } 
          // End Body
        }else{
            $ALERT->notpermission();
        } 
    }
    ?>

    <script>
        $(document).on('change', '#assignment_status', function() {
            var sta = $('#assignment_status').val();
            if (sta == "my_assignment") {
                window.location = window.location.pathname + "?mod=resource_assignment";
            } else {
                window.location = window.location.pathname + "?mod=resource_assignment&status=" + sta;
            }
        });

    <?php 
        if (isset($_GET['status'])) { ?>
            $('#assignment_status option[value=<?php echo $_GET['status']; ?>]').attr('selected', 'selected');
    <?php 
        } 
    ?>

    $(document).ready(function () {
        $('#example').DataTable({
            scrollX: true,
        });
    });
    </script>

    <script type="text/javascript">
    $(function() {
        $('.check_all').click(function() {
            $('.chk_boxes1').prop('checked', this.checked);
        });
    });
    </script>
    