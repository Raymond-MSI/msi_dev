<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $users = get_leader($_SESSION['Microservices_UserEmail'], 1);
    ?>

    <input type="hidden" id="organization_name" name="organization_name" value="<?php echo $users[0]['organization_name']; ?>">
    <input type="hidden" id="user_level" name="user_level" value="<?php echo $_SESSION['Microservices_UserLevel']; ?>">
    <input type="hidden" id="user_name" name="user_name" value="<?php echo $_SESSION['Microservices_UserLogin']; ?>">
    
    <?php 
    $tblname = 'cfg_web';
    $condition = 'config_key="MODULE_NAVISION"';
    $setupDB = $DB->get_data($tblname, $condition);
    $dsetupDB = $setupDB[0];
    if($setupDB[2]>0) {
        $params = get_params($dsetupDB['params']);
        $hostname = $params['database']['hostname'];
        $username = $params['database']['username'];
        $userpassword = $params['database']['userpassword'];
        $database = $params['database']['database_name'];

        $DPNAV = new Databases($hostname, $username, $userpassword, $database);
        $tblname = "view_order_number";
        $_SESSION['Microservices_UserPermit']="boleh";
        ?>
        <script> 
            $(document).ready(function() {
                var table = $('#<?php echo $tblname; ?>').DataTable( {
                    dom: 'Blrtip',
                    "order": [
                        [ 3 , "DESC"]
                    ],
                    select: {
                        style: 'single'
                    },
                    buttons: [
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function () {
                                var rownumber = table.rows({selected: true}).indexes();
                                var project_code = table.cell( rownumber,1 ).data();
                                var order_number = table.cell( rownumber,3 ).data();
                                if(order_number!="") {
                                    window.location.href = "index.php?mod=service_budget&act=order&project_code="+project_code+"&order_number="+order_number+"&submit=Submit";
                                } else {
                                    alert("Order Number kosong. Silahkan pilih yang ada Order Number-nya.");
                                }
                            }
                        }
                    ],
                    "columnDefs": [
                        {
                            // project_id
                            "targets": [0,4,11],
                            // "targets": [],
                            "visible": false
                        },
                        {
                            "targets": [8],
                            className: 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number( '.', ',', 2, '').display(data);
            
                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        },
                        {
                            "targets": [9],
                            className: 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number( '.', ',', 2, '').display(data);
            
                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 14000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        },
                    ],
                    "order": [
                        [0, "desc"],
                    ]
                });
                table.on( 'select deselect', function () {
                    var selectedRows = table.rows( { selected: true } ).count();
                    var rownumber = table.rows({selected: true}).indexes();
                    var amount_idr = table.cell( rownumber,8 ).data();
                    var amount_usd = table.cell( rownumber,9 ).data();
                    // var so_value = table.cell( rownumber,13 ).data();
                    var sales_type = table.cell( rownumber,10 ).data();
                    var organization_name = document.getElementById('organization_name').value;
                    var user_level = document.getElementById('user_level').value;
                    var user_name = document.getElementById('user_name').value;

                    table.button().disable();
                    // if((amount_idr >= 200000000 || amount_usd >= 14000 || sales_type.search('Installation') >= 0 || sales_type.search('Maintenance') >= 0 || sales_type.search('Implementation') >= 0) && (organization_name.search('Presales') >=0 || organization_name.search('Solution Architect') >=0)) {
                    if((organization_name.search('Presales') >=0 || organization_name.search('Solution Architect') >=0 || organization_name.search('Service Provider') >=0 || user_name.search('yongki') >=0)) {
                        table.button(0).enable();
                    } else if((sales_type=='Trade' || sales_type.search("Lease")>=0 || sales_type.search("Rent")>=0 || sales_type=='') && (organization_name.search("Marketing")>=0 || organization_name.search("Sales")>=0 || organization_name.search("Telesales")>=0 || organization_name.search("Account Manage")>=0) && (amount_idr<200000000 || amount_usd<14000)) {
                        table.button().enable();
                    } else if(user_level=='Administrator') {
                        table.button().enable();
                    } 
                } );
            } );
        </script>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-secondary">Select Order Number</h6>
                <?php spinner(); ?>
                <div class="align-items-right">
                <a href="index.php?mod=service_budget" class="btn btn-light border-secondary" title='Back to Service Budget' style="font-size:10px; background-color:#ddd"><i class='fa fa-arrow-left'></i></a>
                    <button type="button" class="btn btn-light border-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px; background-color:#ddd"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <?php 
                    $tblname = "view_order_number";
                    $primarykey = "order_id";

                    $leader = get_leader($_SESSION['Microservices_UserEmail'],1);
                    $dleader = $leader[0];
                    $condition = 'status_order=0';
                    $conditionsb = '';
                    $sambung = ' AND ';
                    $totalRows=100;
                    if(isset($_GET['project_code']) && $_GET['project_code']!='') {
                        $condition .= $sambung . "project_code LIKE '%" . $_GET['project_code'] . "%'";
                        $conditionsb = "project_code LIKE '%" . $_GET['project_code'] . "%'";
                        $sambung=" AND ";
                        $totalRows=0;
                    }
                    if(isset($_GET['so_number']) && $_GET['so_number']!='') {
                        $condition .= $sambung . "so_number LIKE '%" . $_GET['so_number'] . "%'";
                        $conditionsb = "so_number LIKE '%" . $_GET['so_number'] . "%'";
                        $sambung=" AND ";
                        $totalRows=0;
                    }
                    if(isset($_GET['order_number']) && $_GET['order_number']!='') {
                        $condition .= $sambung . "order_number LIKE '%" . $_GET['order_number'] . "%'";
                        $conditionsb = "order_number LIKE '%" . $_GET['order_number'] . "%'";
                        $sambung=" AND ";
                        $totalRows=0;
                    }
                    if(isset($_GET['customer_name']) && $_GET['customer_name']!='') {
                        $condition .= $sambung . " customer_name LIKE '%" . $_GET['customer_name'] . "%'";
                        $totalRows=0;
                    }
                    $condition .= " AND project_code!='' AND (so_number LIKE '%SO%' OR so_number IS NULL OR so_number = '')";
                    $order = "order_id DESC";

                    view_table($DPNAV, $tblname, $primarykey, $condition, $order, $firstRow = 0, $totalRows);

                    $projects = $DPNAV->get_data($tblname, $conditionsb); 
                    if($projects[2]==0) {
                        ?>
                        <script>alert("Order Number di CRM belum dibuat. Silahkan menghubungi AM!");</script>
                        <?php
                    } elseif($projects[2]==1) {
                        $DBSB = get_conn("SERVICE_BUDGET");
                        $tblname = "trx_project_list";
                        $order = "project_id DESC";
                        $dtsb = $DBSB->get_data($tblname, $conditionsb, $order);
                        ?>
                        <script>alert("Sudah pernah dibuatkan Service Budget : \r\nCreated_by : <?php echo $DBHCM->get_profile($dtsb[0]['create_by'], "employee_name"); ?> \r\nCreated_date : <?php echo date('d/m/Y G:i:s' , strtotime($dtsb[0]['create_date'])); ?>\r\nStatus : <?php echo strtoupper($dtsb[0]['status']); ?>");</script>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
 
        <!-- Modal -->
        <div class="modal fade" id="filter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
            <div class="modal-dialog">    
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Order Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form name="form" method="get" action="index.php">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-lg-12">Project Code:</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control form-control-sm" name="project_code" id="project_code" value="<?php if(isset($_GET['project_code'])) { echo $_GET['project_code']; } ?>" placeholder="Project Code">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">Order Number:</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control form-control-sm" name="order_number" id="order_number" value="<?php if(isset($_GET['order_number'])) { echo $_GET['order_number']; } ?>" placeholder="Order Number">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">SO Number:</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control form-control-sm" name="so_number" id="so_number" value="<?php if(isset($_GET['so_number'])) { echo $_GET['so_number']; } ?>" placeholder="SO Number">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">Customer Name:</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" value="<?php if(isset($_GET['customer_name'])) { echo $_GET['customer_name']; } ?>" placeholder="Customer Name">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="mod" value="order_list">
                            <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Search">
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php 
    } else {
        echo "Aplikasi belum disetup";            
    }

} 
?>
