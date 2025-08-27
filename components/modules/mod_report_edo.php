<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1754015077";
    $author = 'Syamsul Arham';
} else {

    $modulename = "mod_report_edo";
    $userpermission = useraccess($modulename);
    if (
        USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" ||  USERPERMISSION == "0162bce636a63c3ae499224203e06ed0"
        || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793"
    ) {
        include "components/modules/hcm/func_edo.php";
        $result = getReportEdo();

?>
        <style>
            #Datatable tfoot select {
                width: 100%;
                box-sizing: border-box;

            }
        </style>

        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Edo</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="Datatable" class="display compact">
                            <thead class="text-center">
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Jabatan</th>
                                    <th>Division</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actual Start </th>
                                    <th>Actual End </th>
                                    <th>Duration </th>
                                    <th>Status </th>
                                    <th>Leave Start </th>
                                    <th>Leave End </th>
                                    <th>Leave Status </th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                <?php if (!empty($result)): ?>
                                    <?php do { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($result[0]['employee_name'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($result[0]['jabatan'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($result[0]['division'] ?? '') ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['start_date'] ?? '')) ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['end_date'] ?? '')) ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['actual_start'] ?? '')) ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['actual_end'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars($result[0]['duration'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($result[0]['status'] ?? '') ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['leave_start'] ?? '')) ?></td>
                                            <td class="text-right text-nowrap"><?= date('j-M-Y', strtotime($result[0]['leave_end'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars($result[0]['leave_status'] ?? '') ?></td>
                                        </tr>
                                    <?php } while ($result[0] = $result[1]->fetch_assoc()) ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class=" text-center">
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Jabatan</th>
                                    <th>Division</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actual Start </th>
                                    <th>Actual End </th>
                                    <th>Duration </th>
                                    <th>Status </th>
                                    <th>Leave Start </th>
                                    <th>Leave End </th>
                                    <th>Leave Status </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#Datatable').DataTable({
                    select: {
                        style: 'single'
                    }

                });


            });
            $(document).ready(function() {
                var table = $('#Datatable').DataTable();

                // Loop through each column to add a dropdown filter
                table.columns().every(function() {
                    var column = this;
                    var select = $('<select><option value="">All</option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {

                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            if (val === "") {
                                column.search('', true, false).draw(); // Jika nilai "All" dipilih, tampilkan semua data
                            } else {
                                column.search('^' + val + '$', true, false).draw(); // Jika nilai lain dipilih, filter data
                            }

                        });


                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });
            });
        </script>
<?php
    } else {
        $ALERT->notpermission();
    }
}
?>