<?php


// // Ambil filter dari request (opsional)
$filters = [];

include "components/modules/change_request/func_report_change_request.php";
$result = getReportChangeRequest($filters);
if (!empty($_GET['cr_status'])) {
    $filters['cr_status'] = $_GET['cr_status'];
}

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Report Change Request</h1>

    <!-- Filter Form -->
    <form method="GET" class="mb-3">
        <select name="cr_status" id="cr_status">
            <option value="cr_pending" <?= ($_GET['cr_status'] ?? '') == 'cr_pending' ? 'selected' : '' ?>>My CR</option>
            <option value="pending_review" <?= ($_GET['cr_status'] ?? '') == 'submission_to_be_reviewed' ? 'selected' : '' ?>>Pending Review</option>
            <option value="approved" <?= ($_GET['cr_status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="submission_approved" <?= ($_GET['cr_status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed/Closed</option>
            <option value="rejected" <?= ($_GET['cr_status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="open" <?= ($_GET['cr_status'] ?? '') == 'open' ? 'selected' : '' ?>>Open</option>
            <option value="cancel" <?= ($_GET['cr_status'] ?? '') == 'cancel' ? 'selected' : '' ?>>Cancel</option>
        </select>
    </form>


    <!-- Table Result -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="Datatable">
            <thead class="thead-dark">
                <tr>
                    <!-- <th>GI ID</th> -->
                    <th>Project Code</th>
                    <th>Project Name</th>
                    <th>CR No</th>
                    <th>Type of Service</th>
                    <th>SO Number</th>
                    <th>Order Number</th>
                    <th>Requested By</th>
                    <th>Used Ticket</th>
                    <th>Ticket Allocation</th>
                    <th>Remaining Ticket</th>
                    <th>Request Date</th>
                    <th>Modified Date</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($result)): ?>
                    <?php do { ?>

                        <tr>
                            <!-- <td><?= htmlspecialchars($result[0]['gi_id']) ?></td> -->
                            <td><?= htmlspecialchars($result[0]['project_code']) ?></td>
                            <td><?= htmlspecialchars($result[0]['project_name']) ?></td>
                            <td><?= htmlspecialchars($result[0]['cr_no']) ?></td>
                            <td><?= htmlspecialchars($result[0]['type_of_service']) ?></td>
                            <td><?= htmlspecialchars($result[0]['so_number']) ?></td>
                            <td><?= htmlspecialchars($result[0]['order_number']) ?></td>
                            <td><?= htmlspecialchars($result[0]['requested_by_email']) ?></td>
                            <td><?= htmlspecialchars($result[0]['used_ticket']) ?></td>
                            <td><?= htmlspecialchars($result[0]['ticket_allocation']) ?></td>
                            <td><?= htmlspecialchars($result[0]['ticket_allocation_sisa']) ?></td>
                            <td><?= htmlspecialchars($result[0]['request_date']) ?></td>
                            <td><?= htmlspecialchars($result[0]['modified_date']) ?></td>
                        </tr>
                    <?php } while ($result[0] = $result[1]->fetch_assoc())

                    ?>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#Datatable').DataTable({
            dom: 'Blfrtip',
            select: {
                style: 'single'
            },
            buttons: [

            ]

        });
    });
</script>
<script>
    $(document).on('change', '#cr_status', function() {
        var sta = $('#cr_status').val();
        if (sta == "cr_pending") {
            window.location = window.location.pathname + "?mod=report_change_request";
        } else {
            window.location = window.location.pathname + "?mod=report_change_request&cr_status=" + sta;
        }
    });
    <?php if (isset($_GET['cr_status'])) { ?>
        $('#cr_status option[value=<?php echo $_GET['cr_status']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>