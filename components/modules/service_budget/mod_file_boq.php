<script>
$(document).ready(function() {
    // SB Draft
    var tblBOQ = $('#tblBOQ').DataTable({
        dom: 'Blfrtip',
        select: 
            {
            style: 'single'
            },
        buttons: [
            {
                text: "<span class='d-inline-block' data-bs-toggle='modal' data-bs-target='#exampleModal'><i class='fa-solid fa-cart-shopping'></i></span>",
                enabled: true,
                action: function() {
                    var rownumber = tblBOQ.rows({
                        selected: true
                    }).indexes();
                    var project_implementation_id = tblBOQ.cell(rownumber, 1).data();
                    if (project_implementation_id == null) {
                        alert("Please select the data.");
                    } else {
                        document.getElementById("id").value = project_implementation_id;
                        // $('#exampleModal').Modal();
                    }
                },
            },
            {
                extend: 'excelHtml5',
                text: "<i class='fa-solid fa-file-excel'></i>",
                title: 'File_BOQ_' + <?php echo date("YmdGis"); ?>
            }
        ],
        columnDefs: [
            {
            targets: [1,2,3,4],
            visible: false
            }
        ]
    });

    // tblBOQ.on( 'select', function () {
    //     tblBOQ.button(0).disable();
    //     var selectedRows = tblBOQ.rows( { selected: true } ).count();
    //     console.log(selectedRows);
    //     if(selectedRows>0)
    //     {
    //         tblBOQ.button(0).enable();
    //     }
    // });

});
</script>
<?php
$DBBOQ = get_conn("SERVICE_BUDGET");

if(isset($_POST['btn_submit']))
{
    $mysql = 
        "UPDATE `sa_trx_project_implementations` 
        SET `order_backup`='" . $_POST['order_backup'] . "' 
        WHERE `project_implementation_id`=" . $_POST['id'];
    $rs = $DBBOQ->get_sql($mysql, false);
}

// $mysql = 
//     "SELECT 
//         a.project_code, 
//         a.so_number, 
//         a.order_number, 
//         a.customer_code, 
//         a.customer_name, 
//         a.project_name, 
//         b.project_implementation_id,
//         b.file_backup, 
//         b.dedicate_backup,
//         b.order_backup,
//         a.status, 
//         a.create_by, 
//         a.create_date 
//     FROM sa_trx_project_list a 
//     LEFT JOIN sa_trx_project_implementations b 
//         ON a.project_id = b.project_id 
//     WHERE a.bundling LIKE '%2%' 
//         AND b.file_backup IS NOT NULL
//         AND b.service_type=2
//         AND a.status != 'deleted'
//         AND a.status != 'draft'
//         AND a.status != 'rejected'
//         AND a.status != 'reopen'
//         AND a.status != 'submited'
//     ";
$mysql = 'SELECT a.project_id, a.project_code, a.so_number, a.order_number, a.customer_code, a.customer_name, a.project_name, a.create_date, a.create_by, IF(b.dedicate_backup="0", "No", "Yes") AS dedicate_backup, b.order_backup, b.file_backup, IF(b.service_type=2, "Maintenance","") AS service_type, a.status
FROM sa_trx_project_list a 
LEFT JOIN sa_trx_project_implementations b ON a.project_id = b.project_id 
LEFT JOIN sa_trx_addon c ON a.project_id = c.project_id
WHERE (b.dedicate_backup > 0 OR order_backup IS NOT NULL OR file_backup IS NOT NULL) AND b.service_type = 2 AND (a.status = "approved" OR a.status = "acknowledge") AND c.service_type = 5 AND bundling LIKE "%2%"
';
$rsBOQs = $DBBOQ->get_sql($mysql);
?>
<div class="card">
    <div class="card-header fw-bold">BOQ Investment Backup Unit</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="tblBOQ" style="width:100%">
                <thead>
                    <tr>
                        <th>Project Information</th>
                        <th>ID</th>
                        <th>Project Code</th>
                        <th>SO Number</th>
                        <th>Order Number</th>
                        <th>File Name</th>
                        <th class="text-center">Dedicate</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($rsBOQs[2]>0)
                    {
                        do {
                            ?>
                            <tr>
                                <td class="col-lg-6">
                                    <?php
                                    echo "<b>". $rsBOQs[0]['project_code'] . " | " . ($rsBOQs[0]['so_number']!=null ? $rsBOQs[0]['so_number'] : "SO Number Not Yet") . " | " . ($rsBOQs[0]['order_number']!=null ? $rsBOQs[0]['order_number'] : "Order Number Not Yet") . "</b><br/>";
                                    echo "<span style='font-size:12px'><span class='text-nowrap'><b>Customer : </b>" . $rsBOQs[0]['customer_name'] . " |</span> <span class='text-nowrap'><b>Created Date :</b> " . $rsBOQs[0]['create_date'] . " |</span> <span class='text-nowrap'><b>Created By :</b> " . $rsBOQs[0]['create_by'] . " |</span> <span class='text-nowrap'><b>Status :</b> " . $rsBOQs[0]['status'] . " |</span> ";
                                    echo "<b>Project Name :</b> ". $rsBOQs[0]['project_name'] . "</span>";
                                    ?>
                                </td>
                                <td><?php echo $rsBOQs[0]['project_implementation_id']; ?></td>
                                <td><?php echo $rsBOQs[0]['project_code']; ?></td>
                                <td><?php echo $rsBOQs[0]['so_number']; ?></td>
                                <td><?php echo $rsBOQs[0]['order_number']; ?></td>
                                <td>
                                    <?php 
                                    $tblname = 'cfg_web';
                                    $condition = 'config_key="MEDIA_SERVICE_BUDGET" AND parent=8';
                                    $folders = $DB->get_data($tblname, $condition);
                                    $FolderName = 'service_budget';
                                    $sFolderTarget = $folders[0]['config_value'] . '/' . $rsBOQs[0]['customer_code'] . '_' . str_replace(".", "", str_replace(' ', '_', $rsBOQs[0]['customer_name'])) . '/' . $rsBOQs[0]['project_code'] . '/' . $FolderName . '/';
                                    ?>
                                    <a href="<?php echo $sFolderTarget . $rsBOQs[0]['file_backup']; ?>" target="_blank" class="text-body text-decoration-none"><?php echo $rsBOQs[0]['file_backup']; ?></a>
                                </td>
                                <td class="text-center">
                                    <?php
                                    // if($rsBOQs[0]['dedicate_backup']==null)
                                    // {
                                    //     echo "None";
                                    // } else
                                    // if($rsBOQs[0]['dedicate_backup']=="0")
                                    // {
                                    //     echo "NO";
                                    // } else
                                    // if($rsBOQs[0]['dedicate_backup']=="1")
                                    // {
                                    //     echo "YES";
                                    // }
                                    echo $rsBOQs[0]['dedicate_backup'];
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $rsBOQs[0]['status']; ?>
                                </td>
                            </tr>
                            <?php
                        } while($rsBOQs[0]=$rsBOQs[1]->fetch_assoc());
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Project Information</th>
                        <th>ID</th>
                        <th>Project Code</th>
                        <th>SO Number</th>
                        <th>Order Number</th>
                        <th>File Name</th>
                        <th class="text-center">Dedicate</th>
                        <th class="text-center">Status</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Order Backup</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="index.php?mod=service_budget&sub=list_boq">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="user_email" name="user_email" value="<?php echo $_SESSION['Microservices_UserEmail']; ?>">
                    <select class="form-select" name="order_backup" aria-label="Default select example">
                        <option value="Open">Open</option>
                        <option value="Order">Order</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancel">Cancel</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name=btn_submit>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>