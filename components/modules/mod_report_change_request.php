<?php

include "components/modules/change_request/func_report_change_request.php";
$result = getReportChangeRequest();

if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1754015077";
    $author = 'Syamsul Arham';
} else {

    $modulename = "md_report_change_request";
    $userpermission = useraccess($modulename);
    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
    //     // var_dump($_SESSION);
    // }
}

?>
<style>
    /* Mengatur gaya dasar untuk baris group header */
    tr.group {
        background-color: #e2e6ea !important;
        /* Warna latar belakang abu-abu terang */
        font-weight: bold;
        color: #495057;
        /* Warna teks gelap */

        /* Mengaktifkan ikon pointer */
    }

    /* Mengubah warna saat kursor hover di atas group header */
    tr.group:hover {
        background-color: #d1d5db !important;
        /* Warna abu-abu yang sedikit lebih gelap saat di-hover */
    }

    /* Mengatur gaya untuk mode gelap jika ada */
    body.dark-mode tr.group {
        background-color: #343a40 !important;
        color: #f8f9fa;
    }

    body.dark-mode tr.group:hover {
        background-color: #495057 !important;
    }
</style>

<div class="row mt-3 ps-3 pe-3 mb-5" id="showTaskList">
    <div class="hstack gap-3 border-top border-bottom">
        <div class="p-2">
            <div class="fs-3">Report Change Request Maintenance</div>
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
            <thead class="bg-danger-subtle text-danger-emphasis">
                <tr>
                    <th class="d-none text-wrap">Project Name </th>
                    <th rowspan="2">CR Number</th>
                    <th rowspan="2">Requested By</th>
                    <th colspan="3" class="text-center">Ticket</th>
                    <th rowspan="2">Requested Date</th>
                    <th rowspan="2">Modified Date</th>
                </tr>
                <tr>
                    <th class="d-none"></th>
                    <th>Ticket Allocation</th>
                    <th>Used Ticket</th>
                    <th class="text-center">Remaining Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($result)): ?>
                    <?php do { ?>
                        <tr>
                            <td class="d-none">[<?= htmlspecialchars($result[0]['project_code'] ?? '') ?>] [<?= htmlspecialchars($result[0]['type_of_service'] ?? '') ?>] <?= htmlspecialchars($result[0]['project_name'] ?? '') ?></td>
                            <td>

                                <!-- <span class="text-nowrap"><b>Project Code: </b> <?= htmlspecialchars($result[0]['project_code'] ?? '') ?> | </span> -->
                                <span class="text-nowrap"> <?= htmlspecialchars($result[0]['cr_no'] ?? '') ?> </span>
                                <!-- <span class="text-nowrap"><b>Type Of Service: </b> <?= htmlspecialchars($result[0]['type_of_service'] ?? '') ?> </span> -->


                            </td>
                            <td><?= htmlspecialchars($result[0]['requested_by_email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['ticket_allocation'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['used_ticket'] ?? '') ?></td>
                            <td><?= htmlspecialchars($result[0]['ticket_allocation_sisa'] ?? '') ?></td>
                            <td><?= date('j-M-Y', strtotime($result[0]['request_date'] ?? '')) ?></td>
                            <td><?= date('j-M-Y', strtotime($result[0]['modified_date'] ?? '')) ?></td>
                        </tr>
                    <?php } while ($result[0] = $result[1]->fetch_assoc()) ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#Datatable').DataTable({
            order: [
                [0, 'asc']
            ],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var lastGroup = null;

                api.column(0, {
                    page: 'current'
                }).data().each(function(data, i) {
                    // "data" sekarang langsung berisi nama proyek dari kolom tersembunyi
                    var currentGroup = data.trim();
                    if (lastGroup !== currentGroup) {
                        var colCount = $('#Datatable thead th').length;

                        if (currentGroup) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="' + colCount + '">' +
                                currentGroup + '</td></tr>'
                            );
                        }
                        lastGroup = currentGroup;
                    }
                });
            }
        });

        // Handle klik pada group header
        $('#Datatable tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 0 && currentOrder[1] === 'asc') {
                table.order([0, 'desc']).draw();
            } else {
                table.order([0, 'asc']).draw();
            }
        });
    });
</script>