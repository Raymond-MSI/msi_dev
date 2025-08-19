<script>
    $(document).ready(function() {
        var tblSBF = $('#table_service_budget').DataTable({
            dom: 'Blrtip',
            select: {
                style: 'single'
            },
            buttons: [
                {
                    text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Delete'><i class='fa-solid fa-trash'></i></span>",
                    action: function() {
                        var rownumber = tblSBF.rows({
                            selected: true
                        }).indexes();
                        var project_id = tblSBF.cell(rownumber, 0).data();
                        if (project_id == null) {
                            alert("Please select the data.");
                        } else {
                            window.location.href = "index.php?mod=service_budget&sub=poc_service_budget&id="+project_id;
                        }
                    },
                    enabled: true
                },
                {
                    extend: 'excelHtml5',
                    text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export to Excel'><i class='fa fa-file-excel'></i></span>",
                    title: 'SBF_Temporary_Expired_' + <?php echo date("YmdGis"); ?>
                }
            ],
            "columnDefs": [{
                    "targets": [0],
                    "visible": false
                },
                {
                    "targets": [7,8],
                    "className": 'dt-body-right'
                },
            ]
        });
    });
</script>

<div class="cards">
    <div class="card-header">
        <span class="card-title fs-3 fw-bold">Expired Temporary Service Budget</span>
        <span> (Expired &gt; 1 year)</span>
    </div>
    <?php
    $DBSBF = get_conn("SERVICE_BUDGET");
    if (isset($_GET['id']) && $_GET['id'] != '') {
        $update = "status = 'expired'";
        $condition = "project_id = " . $_GET['id'];
        $rs = $DBSBF->update_data("trx_project_list", $update, $condition);
    }

    $date = date('Y-m-d', strtotime("-1 year"));
    $mysql = "SELECT a.project_id, a.project_code, a.so_number, a.order_number, a.project_name, a.po_number, a.amount_idr, b.implementation_price,
        IF(b.service_type = 1, 'Implementation', IF(b.service_type = 2, 'Maintenance', IF(b.service_type = 3, 'Warranty', IF(b.service_type = 4,'Manage Service', 'None')))) AS service_type, a.status, a.create_date
    FROM sa_trx_project_list a LEFT JOIN sa_trx_project_implementations b ON a.project_id = b.project_id 
    WHERE (a.po_number IS NULL OR a.so_number IS NULL OR a.amount_idr = 0) AND (a.`status` = 'acknowledge' OR a.status = 'approved') AND a.sbtype > 0 AND a.create_date <= '$date'";
    $rsProjects = $DBSBF->get_sql($mysql);
    if ($rsProjects[2] > 0) {
        ?>
        <div class="card-body">
            <?php
            if($_SESSION['Microservices_UserEmail'] == 'syamsul@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'malik.aulia@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'fortuna@mastersystem.co.id') {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <table id="table_service_budget" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Project Type</th>
                                    <th>Project Code</th>
                                    <th>SO Number</th>
                                    <th>Order Number</th>
                                    <th>Project Name</th>
                                    <th>PO Number</th>
                                    <th>Amount IDR</th>
                                    <th class="text-nowrap">Create Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php do { ?>
                                    <tr>
                                        <td><?php echo $rsProjects[0]['project_id']; ?></td>
                                        <td><?php echo $rsProjects[0]['service_type']; ?></td>
                                        <td><?php echo $rsProjects[0]['project_code']; ?></td>
                                        <td class="text-nowrap"><?php echo $rsProjects[0]['so_number']; ?></td>
                                        <td class="text-nowrap"><?php echo $rsProjects[0]['order_number']; ?></td>
                                        <td><?php echo $rsProjects[0]['project_name']; ?></td>
                                        <td><?php echo $rsProjects[0]['po_number']; ?></td>
                                        <td><?php echo number_format($rsProjects[0]['amount_idr'], 2, ',', '.'); ?></td>
                                        <td><?php echo date('d-M-Y', strtotime($rsProjects[0]['create_date'])); ?></td>
                                    </tr>
                                <?php } while($rsProjects[0]=$rsProjects[1]->fetch_assoc()); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div class="alert alert-danger" role="alert">
                    You do not have permission to view this page.
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>