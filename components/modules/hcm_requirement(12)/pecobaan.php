<script>
    $(document).ready(function() {
        // Function to initialize DataTable with appropriate button actions
        function initializeDataTable(nr_stat_value) {
            var tableConfig = {
                dom: 'Blfrtip',
                select: {
                    style: 'single'
                },
                buttons: [
                    //     {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=hcm_requirement&act=add";
                    //     }
                    // }
                ],
                columnDefs: [{
                    targets: [],
                    visible: false
                }]
            };

            // Customize button actions based on nr_stat_value
            if (nr_stat_value === "Submitted") {
                tableConfig.buttons.push({
                    text: "<i class='fa fa-pen'></i>",
                    action: function() {
                        var table = $('#hcm_requirement').DataTable();
                        var rownumber = table.rows({
                            selected: true
                        }).indexes();
                        var id = table.cell(rownumber, 0).data();
                        // Add your logic for action on 'Submitted' here
                        window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&submit=Submit";
                    }
                }, {
                    text: "<i class='fa fa-plus'></i>",
                    action: function() {
                        window.location.href = "index.php?mod=hcm_requirement&act=add";
                    }
                });
                tableConfig.columnDefs.push({
                    targets: [0],
                    visible: false
                });
            } else if (nr_stat_value === "Approval") {
                tableConfig.buttons.push({
                    text: "<i class='fa fa-pen'></i>",
                    action: function() {
                        var table = $('#hcm_requirement').DataTable();
                        var rownumber = table.rows({
                            selected: true
                        }).indexes();
                        var id = table.cell(rownumber, 0).data();
                        // Add your logic for action on 'Approval' here
                        window.location.href = "index.php?mod=hcm_requirement&act=editapproval&id=" + id + "&submit=Submit";
                    }
                });
                tableConfig.columnDefs.push({
                    targets: [0],
                    visible: false
                });
            }

            // Initialize DataTable with the configured options
            var table = $('#hcm_requirement').DataTable(tableConfig);
        }

        // Get the current value of nr_stat from the URL query parameter
        var currentNrStat = "<?php echo isset($_GET['nr_stat']) ? $_GET['nr_stat'] : ''; ?>";

        // Initialize DataTable based on the current nr_stat value
        initializeDataTable(currentNrStat);

        // Handle change event on nr_stat dropdown
        $('#nr_stat').change(function() {
            var selectedValue = $(this).val();
            var url = "index.php?mod=hcm_requirement";

            // Append nr_stat value to the URL
            if (selectedValue) {
                url += "&nr_stat=" + selectedValue;
            }

            // Redirect to the updated URL
            window.location.href = url;
        });

        // Set the selected option in the dropdown based on nr_stat value
        <?php if (isset($_GET['nr_stat'])) { ?>
            $('#nr_stat').val('<?php echo $_GET['nr_stat']; ?>');
        <?php } ?>
    });
</script>