<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1754015077";
    $author = 'Syamsul Arham';
} else {

    $modulename = "md_holiday";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#md_holiday').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [
                        // {
                        //     extend: 'colvis',
                        //     text: "<i class='fa fa-columns'></i>",
                        //     collectionLayout: 'fixed four-column',  
                        // },
                        {
                            text: "<i class='fa fa-eye'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                if (!id || id === 'undefined') {
                                    alert("No data selected. Please select a row before viewing.");
                                    return;
                                }
                                window.location.href = "index.php?mod=md_holiday&act=view&id=" + id + "&submit=Submit";
                            },
                            enabled: true
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                if (!id || id === 'undefined') {
                                    alert("No data selected. Please select a row before editing.");
                                    return;
                                }
                                window.location.href = "index.php?mod=md_holiday&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-trash'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                if (!id || id === 'undefined') {
                                    alert("No data selected. Please select a row before deleting.");
                                    return;
                                }
                                window.location.href = "index.php?mod=md_holiday&act=del&id=" + id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=md_holiday&act=add";
                            },
                            // enabled: false
                        },
                        {
                            extend: 'collection',
                            text: 'Select Year',
                            buttons: (function() {
                                var currentYear = new Date().getFullYear();
                                var years = [];
                                for (var y = currentYear + 1; y >= currentYear - 5; y--) {
                                    years.push({
                                        text: y.toString(),
                                        action: function(e, dt, node, config) {
                                            var year = $(node).text();
                                            window.location.href = "index.php?mod=md_holiday&year=" + year;
                                        }
                                    });
                                }
                                return years;
                            })()
                        }

                    ],
                    "columnDefs": [{
                            "targets": [0, 3, 4, 5, 6, 7],
                            "visible": false,
                        },
                        {

                            "targets": 1,
                            "render": function(data, type, row) {
                                console.log(data); // This will print the raw date string to the console
                                // Check if the type is 'display'
                                if (type === 'display' || type === 'filter') {
                                    var date = new Date(data + 'T00:00:00'); // Parse the date string
                                    var options = {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    };
                                    return date.toLocaleDateString('en-GB', options).replace(/ /g, '-');
                                }

                                return data;
                            }
                        }
                    ],
                });
            });
        </script>
        <?php

        // Function
        if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
            function view_data($tblname)
            {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DBHCM;
                $primarykey = "id";
                $year = isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : date("Y");
                $condition = "YEAR(holiday_date) = " . GetSQLValueString($year, "int") .
                    " AND (is_deleted IS NULL OR is_deleted = 0)";
                $order = "holiday_date ASC";
                if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                    global $ALERT;
                    $ALERT->datanotfound();
                }
                view_table($DBHCM, $tblname, $primarykey, $condition, $order);
            }
            function form_data($tblname)
            {
                include("components/modules/md_holiday/form_md_holiday.php");
            }

            // End Function

            // $database = 'sa_md_holiday';
            // include("components/modules/md_holiday/connection.php");
            // $DBHCM = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'md_holiday';

            include("components/modules/md_holiday/func_md_holiday.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Holiday</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        if (!isset($_GET['act'])) {
                            view_data($tblname);
                        } elseif ($_GET['act'] == 'add') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'new') {
                            new_projects($tblname);
                        } elseif ($_GET['act'] == 'edit') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'del') {
                            include("components/modules/md_holiday/delete_md_holiday.php");
                        } elseif ($_GET['act'] == 'save') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'view') {
                            include("components/modules/md_holiday/view_md_holiday.php");
                        }
                        ?>
                    </div>
                </div>
            </div>
<?php

        } else {
            $ALERT->notpermission();
        }
        // End Body
    }
}
?>