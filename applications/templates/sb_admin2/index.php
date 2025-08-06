<?php
if ((isset($properties)) && ($properties == 1)) {
    // collected module information
    $module = 'index.php';
    $version = '1.0';
    $released = '20210614';
    $author = 'Syamsul Arham';
    $fcreated = '09 February 2020 03:15:52';
    $fstat = stat('applications/templates/sb_admin2/index.php');
    $fmodified = date("d F Y H:i:s", $fstat['mtime']);

    $properties = array("module" => $module, "version" => $version, "released" => $released, "author" => $author, "created" => $fcreated, "modified" => $fmodified);
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo TITLEOFWEBSITE; ?></title>
        <?php $favicon = $DB->getConfig("FAVICON_OF_WEBSITE"); ?>
        <link href="<?php echo $favicon; ?>" rel="icon" type="image/x-icon" />

        <script src="components/vendor/bootstrap-5.1.0/js/bootstrap.bundle.min.js"></script>

        <!-- Custom fonts for this template-->
        <!-- <link href="applications/templates/sb_admin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> -->
        <link href="components/vendor/fontawesome-free-6.1.1-web/css/all.min.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template-->
        <link href="applications/templates/sb_admin2/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Custom styles for this page -->
        <script src="components/vendor/jquery/jquery-3.5.1.js"></script>

        <script src="components/js/java.microservices.js"></script>

        <link href="components/vendor/datatables-1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
        <link href="components/vendor/datatable/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
        <link href="components/vendor/datatable/select.dataTables.min.css" rel="stylesheet" type="text/css">

        <script src="components/vendor/cloudflare/moment-2.29.2/moment.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script> -->
        <script src="components/vendor/DataTables-1.12.1/js/jquery.dataTables.min.js"></script>
        <!-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> -->
        <script src="components/vendor/datatable/dataTables.buttons.min.js"></script>
        <script src="components/vendor/datatable/buttons.colVis.min.js"></script>
        <script src="components/vendor/datatable/dataTables.select.min.js"></script>
        <link href="components/vendor/bootstrap-5.1.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <script src="components/vendor/jszip-2.5.0/jszip.min.js"></script>
        <script src="components/vendor/pdfmake-0.1.36/pdfmake.min.js"></script>
        <script src="components/vendor/pdfmake-0.1.36/vfs_fonts.js"></script>
        <script src="components/vendor/buttons-2.2.1/js/buttons.html5.min.js"></script>
        <script src="components/vendor/buttons-2.2.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

        <link href="components/vendor/jquery-ui-1.13.0/jquery-ui.css" rel="stylesheet">
        <link href="components/vendor/jdatetimepicker-2.5.21/jquery.datetimepicker.css" rel="stylesheet">

    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Menu Sidebar -->
            <?php include("components/modules/menu/mod_menu_sidebar.php"); ?>
            <!-- End Menu Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <?php include("components/modules/menu/mod_menu_topbar.php"); ?>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Project Card Example -->
                        <?php
                        if (isset($_GET['mod'])) {
                            include('components/modules/mod_' . $_GET['mod'] . '.php');
                        } else {
                            include('components/modules/mod_dashboard.php');
                        }
                        ?>
                    </div>
                    <!-- /.container-fluid -->
                    <!-- End Page Content -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include("components/modules/menu/mod_footer.php"); ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="index.php?logout=1">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="components/vendor/bootstrap-5.1.0/js/bootstrap.min.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="applications/templates/sb_admin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


        <!-- Core plugin JavaScript-->
        <script src="applications/templates/sb_admin2/vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="components/vendor/jquery-ui-1.13.0/jquery-ui.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="applications/templates/sb_admin2/js/sb-admin-2.min.js"></script>

        <!-- Chart -->
        <!-- <script src="applications/templates/sb_admin2/vendor/chart.js/Chart.min.js"></script>
    <script src="components/modules/asset/chart-pie-demo.js"></script> -->

        <script src="components/vendor/jdatetimepicker-2.5.21/build/jquery.datetimepicker.full.js"></script>

    </body>

    </html>

<?php
}
?>
<script type="text/javascript">
    $("#datepicker").datepicker({
        inline: true,
    });
    $("#datepicker1").datepicker({
        inline: true
    });
    $(".selector").datepicker({
        altFormat: "yy-mm-dd"
    });
</script>

<script>
    /*jslint browser:true*/
    /*global jQuery, document*/

    jQuery(document).ready(function() {
        'use strict';

        jQuery('#filter-date, #search-from-date, #search-to-date').datetimepicker();
    });
</script>