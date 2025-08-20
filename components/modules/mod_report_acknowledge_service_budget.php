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
            <thead class="bg-danger-subtle text-danger-emphasis text-center">
                <tr>
                    <th>Project Code</th>
                    <th>Project Name</th>
                    <th>Transaction Number</th>
                    <th>Amount IDR</th>
                    <th>Bundling</th>

                </tr>

            </thead>
            <tbody class="text-left">
                <?php if (!empty($result)): ?>
                    <?php do { ?>
                        <tr>
                            <td><?= htmlspecialchars($result[0]['project_code'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['project_name'] ?? '') ?></td>
                            <td style="font-size: 11px;">
                                <span><b>PO Number: </b><?= htmlspecialchars($result[0]['po_number'] ?? '') ?></span>
                                <span><b>Order Number: </b><?= htmlspecialchars($result[0]['order_number'] ?? '') ?></span>
                                <span><b>SO Number: </b><?= htmlspecialchars($result[0]['so_number'] ?? '') ?></span>
                            </td>
                            <td><?= htmlspecialchars($result[0]['amount_idr'] ?? '') ?></td>
                            <td><?= htmlspecialchars(convertBundling($result[0]['bundling'] ?? '')) ?></td>

                        </tr>
                    <?php } while ($result[0] = $result[1]->fetch_assoc()) ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#Datatable').DataTable();
    });
</script>