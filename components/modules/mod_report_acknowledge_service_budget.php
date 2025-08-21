<?php

include "components/modules/service_budget/func_report_acknowledge_service_budget.php";
$result = getReportAcknowledgeServiceBudget();

if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1754015077";
    $author = 'Syamsul Arham';
} else {

    $modulename = "md_repot_acknowledge_service_budget";
    $userpermission = useraccess($modulename);
    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
    //     // var_dump($_SESSION);
    // }
}

?>
<div class="row mt-3 ps-3 pe-3 mb-5" id="showTaskList">
    <div class="hstack gap-3 border-top border-bottom">
        <div class="p-2">
            <div class="fs-3">Report Acknowledge Service Budget</div>
        </div>
        <div class="p-2">
        </div>
        <div class="p-2 ms-auto">&nbsp;</div>
        <div class="p-2">
            <div class="fs-3"><span class="fs-5"><?php //echo date("F Y", strtotime($start)); 
                                                    ?></span></div>
        </div>
    </div>
    <div class="table-responsive ps-0 pe-0">
        <table id="Datatable" class="display compact">
            <thead class="  text-center">
                <tr>
                    <th>Project Info</th>
                    <th>Customer Name</th>
                    <th>PO Number</th>
                    <th>Project Name</th>
                    <th>Amount IDR</th>
                    <th>Bundling</th>
                    <th>Status</th>
                    <th>Create Date</th>
                </tr>

            </thead>
            <tbody class="text-left">
                <?php if (!empty($result)): ?>
                    <?php do { ?>
                        <tr>
                            <td>
                                <span class="text-nowrap"><b>Project Code: </b><?= htmlspecialchars($result[0]['project_code'] ?? '') ?></span> <br>
                                <span class="text-nowrap" style="font-size: 10px;"><b>So Number: </b><?= htmlspecialchars($result[0]['so_number'] ?? '') ?> | </span>
                                <span class="text-nowrap" style="font-size: 10px;"><b>Sales Name: </b><?= htmlspecialchars($result[0]['sales_name'] ?? '') ?> | </span>
                                <span class="text-nowrap" style="font-size: 10px;"><b>Created by: </b><?= htmlspecialchars($result[0]['create_by'] ?? '') ?></span>
                            </td>
                            <td><?= htmlspecialchars($result[0]['customer_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['po_number'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['project_name'] ?? '') ?></td>

                            <td class="text-right"><?= number_format($result[0]['amount_idr'] ?? '', 2) ?></td>
                            <td><?= (convertBundling($result[0]['bundling'] ?? '')) ?></td>
                            <td class="text-right"><?= htmlspecialchars($result[0]['status_ack'] ?? '') ?></td>
                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['create_date'] ?? '')) ?></td>


                        </tr>
                    <?php } while ($result[0] = $result[1]->fetch_assoc()) ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#Datatable').DataTable({

            select: {
                style: 'single'
            },
        });
    });
</script>