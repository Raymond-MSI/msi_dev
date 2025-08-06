<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1631757027";
    $author = 'Syamsul Arham';
} else {
    function view_table_cr($DTBL, $tblname, $primarykey = "", $condition = "", $order = "", $firstRow = 0, $totalRow = 600, $index = "")
    {
        $datatable = $DTBL->get_data($tblname, $condition, $order, $firstRow, $totalRow);
        $ddatatable = $datatable[0];
        $qdatatable = $datatable[1];
        $tdatatable = $datatable[2];
        $modtitle = 'Catalog listing';

        $datatable_header = $DTBL->get_columns($tblname);
        $ddatatable_header = $datatable_header[0];
        $qdatatable_header = $datatable_header[1];
        $tdatatable_header = $datatable_header[2];
        $datatable_header2 = $DTBL->get_columns($tblname);
        $ddatatable_header2 = $datatable_header2[0];
        $qdatatable_header2 = $datatable_header2[1];
        $tdatatable_header2 = $datatable_header2[2];
?>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname . $index; ?>" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                            $header = '';
                            do {
                                // $header .="<th>" . strtoupper($ddatatable_header['Field']) . "</th>";
                                $headerx = str_replace("_", " ", strtoupper($ddatatable_header['Field']));
                                $header .= "<th class='text-center align-middle'>" . $headerx . "</th>";
                            } while ($ddatatable_header = $qdatatable_header->fetch_assoc());
                            echo $header;
                            ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php echo $header; ?>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if ($tdatatable > 0) {
                            do { ?>
                                <tr>
                                    <?php
                                    $datatable_header2 = $DTBL->get_columns($tblname);
                                    $ddatatable_header2 = $datatable_header2[0];
                                    $qdatatable_header2 = $datatable_header2[1];
                                    ?>
                                    <?php do { ?>
                                        <td><?php echo $ddatatable[$ddatatable_header2['Field']]; ?></td>
                                    <?php } while ($ddatatable_header2 = $qdatatable_header2->fetch_assoc()); ?>
                                </tr>
                        <?php
                            } while ($ddatatable = $qdatatable->fetch_assoc());
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    $modulename = "Change Request";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "858ba4765e53c712ef672a9570474b1d") {
        $user_mail = $_SESSION['Microservices_UserEmail'];
    ?>
        <?php if ($user_mail == "hendri@mastersystem.co.id") { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#general_informations').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                                extend: 'excelHtml5',
                                text: "<i class='fa fa-file-pdf'></i>",
                                title: 'KPI_Panel_' + <?php echo date("YmdGis"); ?>
                            },
                            // {
                            //     text: "<i class='fas fa-file-pdf'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         window.location.href = "components/modules/change_request/export_summary_cr.php";
                            //         // window.location.href = "index.php?mod=change_request&export=excel";
                            //     }
                            // },
                            {
                                text: "<i class='fa fa-plus' data-bs-toggle='popover' data-bs-trigger='focus' title='Add CR'></i>",
                                action: function() {
                                    window.location.href = "index.php?mod=change_request&act=add&type=Implementation&costimpact=Technical&project_code=&so_number=";
                                },
                                // enabled: false
                            },
                            // {
                            //     text: "<i class='fa fa-eye'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var gi_id = table.cell(rownumber, 0).data();
                            //         var type = table.cell(rownumber, 12).data();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         var project_code = table.cell(rownumber, 13).data();
                            //         var costimpact = table.cell(rownumber, 20).data();
                            //         var so_number = table.cell(rownumber, 15).data();
                            //         if (gi_id == null) {
                            //             alert("Please select the data");
                            //         } else {
                            //             window.location.href = "index.php?mod=change_request&act=review&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                            //         }
                            //     },
                            // },
                            {
                                text: "<i class='fa fa-pen' data-bs-toggle='popover' data-bs-trigger='focus' title='Edit CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else {
                                        if (requested_by_email != "<?php echo $user_mail ?>") {
                                            alert("Anda tidak bisa edit CR ini");
                                        } else {
                                            if (type == "Implementation" || type == "Maintenance" || type == "IT") {
                                                window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&status_approval=" + status_approval + "&so_number=" + so_number;
                                            } else if (type == "Sales/Presales") {
                                                window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                            }
                                        }
                                    }
                                }
                            },
                            {
                                text: "<i class='fas fa-file-pdf' data-bs-toggle='popover' data-bs-trigger='focus' title='PDF Eksternal'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var seq = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else {
                                        window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php?mod=change_request&type=" + type + "&project_code=" + project_code + "&so_number=" + so_number + "&cr_no=" + cr_no + "&so_number=" + so_number;
                                    }
                                }
                            },
                            // {
                            //     text: "<i class='fas fa-file-pdf' data-bs-toggle='popover' data-bs-trigger='focus' title='PDF Internal'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var gi_id = table.cell(rownumber, 0).data();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         var type = table.cell(rownumber, 12).data();
                            //         var seq = table.cell(rownumber, 4).data();
                            //         var project_code = table.cell(rownumber, 13).data();
                            //         var so_number = table.cell(rownumber, 15).data();
                            //         if (gi_id == null) {
                            //             alert("Please select the data");
                            //         } else if (type == 'IT' || type == 'Sales/Presales') {
                            //             alert("Mohon klik logo PDF satunya");
                            //         } else {
                            //             window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf_internal.php?mod=change_request&type=" + type + "&project_code=" + project_code + "&so_number=" + so_number + "&cr_no=" + cr_no + "&so_number=" + so_number;
                            //         }
                            //     }
                            // },
                            {
                                text: "<i class='fa-solid fa-trash'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else if (gi_id != null && type == "Implementation" || type == "Maintenance" || type == "IT") {
                                        // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                        window.location.href = "index.php?mod=change_request&act=review&try=delete&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                    } else if (gi_id != null && type == "Sales/Presales") {
                                        window.location.href = "index.php?mod=change_request&act=review&try=delete&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                    }
                                }
                            },
                            {
                                text: "<i class='fa-solid fa-check' data-bs-toggle='popover' data-bs-trigger='focus' title='Approve/Reject CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    if (requested_by_email == "<?php echo $_SESSION['Microservices_UserEmail'] ?>") {
                                        alert("Can't Approve/Reject this CR");
                                    } else {
                                        if (gi_id == null) {
                                            alert("Please select the data");
                                        } else if (gi_id != null && type == "Implementation" || type == "Maintenance" || type == "IT") {
                                            // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                            window.location.href = "index.php?mod=change_request&act=review&try=approve&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                        } else if (gi_id != null && type == "Sales/Presales") {
                                            window.location.href = "index.php?mod=change_request&act=review&try=approve&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                        }
                                    }
                                }
                            },
                            {
                                text: "<i class='fa-solid fa-rectangle-xmark' data-bs-toggle='popover' data-bs-trigger='focus' title='Close/Cancel CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else if (requested_by_email != "<?php echo $user_mail; ?>" || <?php echo $user_mail; ?> != "chrisheryanda@mastersystem.co.id") {
                                        alert("Can't Close/Cancel this CR");
                                    } else if (gi_id != null && type == "Implementation" || gi_id != null && type == "Maintenance" || gi_id != null && type == "IT") {
                                        // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                        window.location.href = "index.php?mod=change_request&act=review&try=close&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                    } else if (gi_id != null && type == "Sales/Presales") {
                                        window.location.href = "index.php?mod=change_request&act=review&try=close&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                    }
                                }
                            }
                            //components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php
                        ],
                        "columnDefs": [{
                            "targets": [3],
                            "visible": true,
                            "render": DataTable.render.datetime('DD MM YYYY'),
                        }],
                        "columnDefs": [{
                            "targets": [0, 5, 7, 8, 9, 10, 16, 18, 20, 23, 24, 25, 26, 27, 28, 29],
                            "visible": false,
                        }],
                        "order": [
                            [0, "desc"]
                        ],
                        "order": [
                            [19, "asc"]
                        ],
                    });
                    var table_review = $('#review_validations').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                                extend: 'colvis',
                                text: "<i class='fa fa-columns'></i>",
                                collectionLayout: 'fixed five-column'
                            },
                            {
                                text: "<i class='fa fa-eye'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    window.location.href = "index.php?mod=change_request&act=review&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code;
                                },
                            },
                            {
                                text: "<i class='fa fa-pen'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code;
                                }
                            },
                            {
                                text: "<i class='fas fa-file-signature'></i>",
                                action: function() {
                                    var rownumber = table_review.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table_review.cell(rownumber, 0).data();
                                    var cr_no = table_review.cell(rownumber, 2).data();
                                    var type = table_review.cell(rownumber, 7).data();
                                    var seq = table_review.cell(rownumber, 4).data();
                                    window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php";
                                }
                            },
                            {
                                text: "<i class='fa fa-plus'></i>",
                                action: function() {
                                    window.location.href = "index.php?mod=change_request&act=add&type=implementation";
                                },
                                // enabled: false
                            }
                        ],
                        "columnDefs": [{
                            "targets": [3],
                            "visible": true,
                            "render": DataTable.render.datetime('DD MM YYYY'),
                        }],
                        "order": [
                            [0, "desc"]
                        ],
                    });
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var get = url.searchParams.get("cr_status");
                    table.buttons(6).nodes().css('display', 'none');
                    table.buttons(6).nodes().css('display', 'inline');

                    if (get == "approved") {
                        table.buttons(6).nodes().css('display', 'inline');

                    }
                    if (get == "incomplete") {
                        table.buttons(6).nodes().css('display', 'inline');

                    }

                    table.buttons(2).nodes().css('display', 'none');
                    //  table_view.buttons(5).nodes().css('display', 'none');



                });
            </script>
        <?php } else { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#general_informations').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            //{
                            //extend: 'excelHtml5',
                            //text: "<i class='fa fa-file-pdf'></i>",
                            //title: 'KPI_Panel_'+<?php echo date("YmdGis"); ?>
                            //},
                            // {
                            //     text: "<i class='fas fa-file-pdf'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         window.location.href = "components/modules/change_request/export_summary_cr.php";
                            //         // window.location.href = "index.php?mod=change_request&export=excel";
                            //     }
                            // },
                            {
                                text: "<i class='fa fa-plus' data-bs-toggle='popover' data-bs-trigger='focus' title='Add CR'></i>",
                                action: function() {
                                    window.location.href = "index.php?mod=change_request&act=add&type=Implementation&costimpact=Technical&project_code=&so_number=";
                                },
                                // enabled: false
                            },
                            // {
                            //     text: "<i class='fa fa-eye'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var gi_id = table.cell(rownumber, 0).data();
                            //         var type = table.cell(rownumber, 12).data();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         var project_code = table.cell(rownumber, 13).data();
                            //         var costimpact = table.cell(rownumber, 20).data();
                            //         var so_number = table.cell(rownumber, 15).data();
                            //         if (gi_id == null) {
                            //             alert("Please select the data");
                            //         } else {
                            //             window.location.href = "index.php?mod=change_request&act=review&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                            //         }
                            //     },
                            // },
                            {
                                text: "<i class='fa fa-pen' data-bs-toggle='popover' data-bs-trigger='focus' title='Edit CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else {
                                        if (requested_by_email == "<?php echo $user_mail ?>") {
                                            alert("Anda tidak bisa edit CR ini");
                                        } else {
                                            if (type == "Implementation" || type == "Maintenance" || type == "IT") {
                                                window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&status_approval=" + status_approval + "&so_number=" + so_number;
                                            } else if (type == "Sales/Presales") {
                                                window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                            }
                                        }
                                    }
                                }
                            },
                            {
                                text: "<i class='fas fa-file-pdf' data-bs-toggle='popover' data-bs-trigger='focus' title='PDF Eksternal'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var seq = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else {
                                        window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php?mod=change_request&type=" + type + "&project_code=" + project_code + "&so_number=" + so_number + "&cr_no=" + cr_no + "&so_number=" + so_number;
                                    }
                                }
                            },
                            // {
                            //     text: "<i class='fas fa-file-pdf' data-bs-toggle='popover' data-bs-trigger='focus' title='PDF Internal'></i>",
                            //     action: function() {
                            //         var rownumber = table.rows({
                            //             selected: true
                            //         }).indexes();
                            //         var gi_id = table.cell(rownumber, 0).data();
                            //         var cr_no = table.cell(rownumber, 4).data();
                            //         var type = table.cell(rownumber, 12).data();
                            //         var seq = table.cell(rownumber, 4).data();
                            //         var project_code = table.cell(rownumber, 13).data();
                            //         var so_number = table.cell(rownumber, 15).data();
                            //         if (gi_id == null) {
                            //             alert("Please select the data");
                            //         } else if (type == 'IT' || type == 'Sales/Presales') {
                            //             alert("Mohon klik logo PDF satunya");
                            //         } else {
                            //             window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf_internal.php?mod=change_request&type=" + type + "&project_code=" + project_code + "&so_number=" + so_number + "&cr_no=" + cr_no + "&so_number=" + so_number;
                            //         }
                            //     }
                            // },
                            {
                                text: "<i class='fa-solid fa-trash'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else if (gi_id != null && type == "Implementation" || type == "Maintenance" || type == "IT") {
                                        // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                        window.location.href = "index.php?mod=change_request&act=review&try=delete&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                    } else if (gi_id != null && type == "Sales/Presales") {
                                        window.location.href = "index.php?mod=change_request&act=review&try=delete&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                    }
                                }
                            },
                            {
                                text: "<i class='fa-solid fa-check' data-bs-toggle='popover' data-bs-trigger='focus' title='Approve/Reject CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    if (requested_by_email == "<?php echo $_SESSION['Microservices_UserEmail'] ?>") {
                                        alert("Can't Approve/Reject this CR");
                                    } else {
                                        if (gi_id == null) {
                                            alert("Please select the data");
                                        } else if (gi_id != null && type == "Implementation" || type == "Maintenance" || type == "IT") {
                                            // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                            window.location.href = "index.php?mod=change_request&act=review&try=approve&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                        } else if (gi_id != null && type == "Sales/Presales") {
                                            window.location.href = "index.php?mod=change_request&act=review&try=approve&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                        }
                                    }
                                }
                            },
                            {
                                text: "<i class='fa-solid fa-rectangle-xmark' data-bs-toggle='popover' data-bs-trigger='focus' title='Close/Cancel CR'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    var costimpact = table.cell(rownumber, 20).data();
                                    var status_approval = table.cell(rownumber, 19).data();
                                    var so_number = table.cell(rownumber, 15).data();
                                    var requested_by_email = table.cell(rownumber, 17).data();
                                    var classification = table.cell(rownumber, 6).data();
                                    if (gi_id == null) {
                                        alert("Please select the data");
                                    } else if (requested_by_email != "<?php echo $user_mail; ?>") {
                                        alert("Can't Close/Cancel this CR");
                                    } else if (gi_id != null && type == "Implementation" || gi_id != null && type == "Maintenance" || gi_id != null && type == "IT") {
                                        // window.location.href = "components/modules/change_request/delete.php?mod=change_request&gi_id=" + gi_id;
                                        window.location.href = "index.php?mod=change_request&act=review&try=close&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&costimpact=" + costimpact + "&so_number=" + so_number;
                                    } else if (gi_id != null && type == "Sales/Presales") {
                                        window.location.href = "index.php?mod=change_request&act=review&try=close&&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code + "&classification=" + classification + "&status_approval=" + status_approval;
                                    }
                                }
                            }
                            //components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php
                        ],
                        "columnDefs": [{
                            "targets": [3],
                            "visible": true,
                            "render": DataTable.render.datetime('DD MM YYYY'),
                        }],
                        "columnDefs": [{
                            "targets": [0, 5, 7, 8, 9, 10, 16, 18, 20, 23, 24, 25, 26, 27, 28, 29],
                            "visible": false,
                        }],
                        "order": [
                            [0, "desc"]
                        ],
                        "order": [
                            [19, "asc"]
                        ],
                    });
                    var table_review = $('#review_validations').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                                extend: 'colvis',
                                text: "<i class='fa fa-columns'></i>",
                                collectionLayout: 'fixed five-column'
                            },
                            {
                                text: "<i class='fa fa-eye'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    window.location.href = "index.php?mod=change_request&act=review&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code;
                                },
                            },
                            {
                                text: "<i class='fa fa-pen'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table.cell(rownumber, 0).data();
                                    var type = table.cell(rownumber, 12).data();
                                    var cr_no = table.cell(rownumber, 4).data();
                                    var project_code = table.cell(rownumber, 13).data();
                                    window.location.href = "index.php?mod=change_request&act=edit&gi_id=" + gi_id + "&submit=Submit&type=" + type + "&cr_no=" + cr_no + "&project_code=" + project_code;
                                }
                            },
                            {
                                text: "<i class='fas fa-file-signature'></i>",
                                action: function() {
                                    var rownumber = table_review.rows({
                                        selected: true
                                    }).indexes();
                                    var gi_id = table_review.cell(rownumber, 0).data();
                                    var cr_no = table_review.cell(rownumber, 2).data();
                                    var type = table_review.cell(rownumber, 7).data();
                                    var seq = table_review.cell(rownumber, 4).data();
                                    window.location.href = "components/vendor/TCPDF-main/examples/rpt_change_request_pdf.php";
                                }
                            },
                            {
                                text: "<i class='fa fa-plus'></i>",
                                action: function() {
                                    window.location.href = "index.php?mod=change_request&act=add&type=implementation";
                                },
                                // enabled: false
                            }
                        ],
                        "columnDefs": [{
                            "targets": [3],
                            "visible": true,
                            "render": DataTable.render.datetime('DD MM YYYY'),
                        }],
                        "order": [
                            [0, "desc"]
                        ],
                    });
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var get = url.searchParams.get("cr_status");
                    table.buttons(6).nodes().css('display', 'none');
                    table.buttons(6).nodes().css('display', 'inline');

                    if (get == "approved") {
                        table.buttons(6).nodes().css('display', 'inline');

                    }
                    if (get == "incomplete") {
                        table.buttons(6).nodes().css('display', 'inline');

                    }

                    table.buttons(2).nodes().css('display', 'none');
                    //  table_view.buttons(5).nodes().css('display', 'none');



                });
            </script>
        <?php } ?>
        <?php

        // Function
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBCR;
            $primarykey = "gi_id";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table_cr($DBCR, $tblname, $primarykey, $condition, $order);
        }

        function form_data($tblname)
        {
            include("components/modules/change_request/form_change_request.php");
        }

        function excel_data($tblname)
        {
            include("components/modules/change_request/export_summary_cr.php");
        }

        function add_change($tblname)
        {
            include("components/modules/get_data/add_change_manageengine.php");
        }

        // End Function

        // $tblname = 'cfg_web';
        // $condition = 'config_key="MODULE_CHANGE_REQUEST"';
        // $setupDB = $DB->get_data($tblname, $condition);
        // $dsetupDB = $setupDB[0];
        // if ($setupDB[2] > 0) {
        // $params = get_params($dsetupDB['params']);
        // $hostname = $params['database']['hostname'];
        // $username = $params['database']['username'];
        // $userpassword = $params['database']['userpassword'];
        // $database = $params['database']['database_name'];

        // $json_conn = json_decode($dsetupDB['params'], true);
        // if ($json_conn['version']['connection'] == "4") {
        //     $hostname = $json_conn['connection']['hostname'];
        //     $username = $json_conn['connection']['username'];
        //     $password = $json_conn['connection']['password'];
        //     $database = $json_conn['connection']['database'];

        $DBCR = get_conn("change_request");

        // $DBCR = new Databases($hostname, $username, $password, $database);
        $tblname = 'general_informations';
        $tblname2 = 'risk_assesments';
        $tblname3 = 'assesments';
        $tblname4 = 'mandays';
        $tblname5 = 'financial_others';
        $tblname6 = 'change_cost_plans';
        $tblname7 = 'implementation_plans';
        $tblname8 = 'detail_plans';
        $tblname9 = 'fallback_plans';
        $tblname10 = 'prerequisites';
        $tblname11 = 'master_pic';
        $tblname12 = 'customer_pic';
        $tblname13 = 'change_request_closing';
        $tblname14 = 'affected_ci';
        $tblname15 = 'type_of_service';
        $tblname16 = 'detail_of_change';
        $tblname17 = 'backup';

        include("components/modules/change_request/func_change_request.php");

        // Body
        ?>

        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Change Request</h6>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href=""></a>
                    </li>
                </ul>

                <div class="card-body">
                    <?php if (!isset($_GET['act'])) { ?>
                        <select name="" id="cr_status">
                            <option value="cr_pending">My CR</option>
                            <option value="pending_review">Pending Review</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed/Closed</option>
                            <option value="rejected">Rejected</option>
                            <option value="open">Open</option>
                            <option value="cancel">Cancel</option>
                        </select>
                    <?php } ?>
                    <?php
                    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && !isset($_GET['cr_status'])) {
                        $condition = "requested_by_email='$user_mail' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "pending_review" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "open") {
                        $condition = "change_request_status='submission_to_be_reviewed' AND change_request_approval_type='submission_to_be_reviewed' OR change_request_approval_type2='submission_to_be_reviewed' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "approved") {
                        $condition = "change_request_approval_type='submission_approved' OR change_request_approval_type2='submission_approved' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "completed") {
                        $condition = "change_request_status='all_done' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "rejected") {
                        $condition = "change_request_status='submission_rejected' OR change_request_approval_type='submission_rejected' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "cancel") {
                        $condition = "change_request_status='canceled' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" && !isset($_GET['act']) && $_GET['cr_status'] == "incomplete") {
                        $condition = "change_request_status='incomplete' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && !isset($_GET['cr_status']) || $_SESSION['Microservices_UserEmail'] == "hendri@mastersystem.co.id" && !isset($_GET['act']) && !isset($_GET['cr_status'])) {
                        $condition = "requested_by_email='$user_mail' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "pending_review" || $_SESSION['Microservices_UserEmail'] == "hendri@mastersystem.co.id" && !isset($_GET['act']) && $_GET['cr_status'] == "pending_review" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "open" || $_SESSION['Microservices_UserEmail'] == "hendri@mastersystem.co.id" && !isset($_GET['act']) && $_GET['cr_status'] == "open") {
                        $condition = "change_request_status='submission_to_be_reviewed' AND change_request_approval_type='submission_to_be_reviewed' AND change_request_approval_type2='submission_to_be_reviewed' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "approved" || $_SESSION['Microservices_UserEmail'] == "hendri@mastersystem.co.id" && !isset($_GET['act']) && $_GET['cr_status'] == "approved") {
                        $condition = "change_request_approval_type='submission_approved' OR change_request_approval_type2='submission_approved' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "completed" || $_SESSION['Microservices_UserEmail'] == "hendri@mastersystem.co.id" && $_GET['cr_status'] == "completed") {
                        $condition = "change_request_status='all_done' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "rejected") {
                        $condition = "change_request_status='submission_rejected' OR change_request_approval_type='submission_rejected' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "cancel") {
                        $condition = "change_request_status='canceled'ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" && !isset($_GET['act']) && $_GET['cr_status'] == "incomplete") {
                        $condition = "change_request_status='incomplete' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && !isset($_GET['cr_status'])) {
                        $condition = "requested_by_email='$user_mail'";
                        view_data($tblname, $condition);
                        // view_data_pending();
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "cr_pending") {
                        $condition = "requested_by_email='$user_mail'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "pending_review" || !isset($_GET['act']) && $_GET['cr_status'] == "open") {
                        $condition = "pic_leader='$user_mail' AND change_request_approval_type2='submission_to_be_reviewed' OR pic='$user_mail' AND change_request_approval_type='submission_to_be_reviewed' OR pic_pmo='$user_mail' AND CR_status_approval_pmo='submission_to_be_reviewed' OR pic_sales='$user_mail' AND CR_status_approval_sales='submission_to_be_reviewed' OR pic_presales='$user_mail' AND CR_status_approval_presales='submission_to_be_reviewed' ORDER BY gi_id DESC";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "approved") {
                        $condition = "requested_by_email='$user_mail' AND change_request_approval_type='submission_approved' OR pic_leader='$user_mail' AND change_request_approval_type2='submission_approved' OR pic='$user_mail' AND change_request_approval_type='submission_approved' OR pic_pmo='$user_mail' AND CR_status_approval_pmo='submission_approved' OR pic_sales='$user_mail' AND CR_status_approval_sales='submission_approved' OR pic_presales='$user_mail' AND CR_status_approval_presales='submission_approved'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "completed") {
                        $condition = "requested_by_email='$user_mail' AND change_request_status='all_done' OR pic_leader='$user_mail' AND change_request_status='all_done' OR pic='$user_mail' AND change_request_status='all_done'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "cancel") {
                        $condition = "requested_by_email='$user_mail' AND change_request_status='canceled' OR pic_leader='$user_mail' AND change_request_status='canceled' OR pic='$user_mail' AND change_request_status='canceled'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "rejected") {
                        $condition = "requested_by_email='$user_mail' AND change_request_approval_type='submission_rejected' OR pic_leader='$user_mail' AND change_request_approval_type='submission_rejected' OR pic='$user_mail' AND change_request_approval_type='submission_rejected'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['cr_status'] == "incomplete") {
                        $condition = "requested_by_email='$user_mail' AND change_request_status='incomplete' OR pic_leader='$user_mail' AND change_request_status='incomplete' OR pic='$user_mail' AND change_request_status='incomplete'";
                        view_data($tblname, $condition);
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'new') {
                        new_projects($tblname);
                    } elseif ($_GET['act'] == 'edit') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'review' || $_GET['act'] == 'complete' || $_GET['act'] == 'view') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        echo 'Delete Data';
                    } elseif ($_GET['act'] == 'save') {
                        form_data($tblname);
                    } elseif ($_GET['tambah'] == 'manageengine') {
                        add_change($tblname);
                    } elseif ($_GET['export'] == 'excel') {
                        excel_data($tblname);
                    }

                    ?>
                </div>
            </div>
        </div>

<?php
        // End Body
        // } else {
        //     echo "Aplikasi belum disetup";
        // }
    } else {
        $ALERT->notpermission();
    }
}
// }
?>

<script>
    // $(document).on('click', '.dt-button', function(e){
    //     if(!$('.select-item').length){
    //         alert('Please select the row from table below');
    //         window.location.href = "";
    //     }
    // });
    $(document).on('change', '#cr_status', function() {
        var sta = $('#cr_status').val();
        if (sta == "cr_pending") {
            window.location = window.location.pathname + "?mod=change_request";
        } else {
            window.location = window.location.pathname + "?mod=change_request&cr_status=" + sta;
        }
    });
    <?php if (isset($_GET['cr_status'])) { ?>
        $('#cr_status option[value=<?php echo $_GET['cr_status']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>