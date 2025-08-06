<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $modulename = "Service Budget";
    $userpermission = useraccess($modulename);
    if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533" || USERPERMISSION=="5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION=="335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0") {
        // $hostname = 'localhost';
        $database = 'sa_ps_service_budgets';
        // $username = 'root';
        // $password = '';
        $DTBL = new Databases($hostname, $username, $password, $database);
        ?>
        <script> 
            $(document).ready(function() {
                var table = $('#mst_resource_catalogs').DataTable( {
                    "columnDefs": [
                        {
                            // project_id
                            "targets": [ 0,1 ],
                            "visible": false,
                        },
                        {
                            // project_id
                            "targets": [ 4 ],
                            className: 'dt-body-center'
                        },
                        {
                            // project_id
                            "targets": [ 5,6,7 ],
                            className: 'dt-body-right'
                        },
                    ]
                });           
            });
        </script>

        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Catalogs</h6>
                </div>
                <div class="card-body">
                <?php 
                        $tblname = "mst_resource_catalogs";
                        $primarykey = "resource_catalog_id";
                        $condition = "";
                        $order = "";
                        view_table($DTBL, $tblname, $primarykey, $condition, $order, $firstRow = 0, $totalRow = 100)
                    ?>
                </div>
            </div>
        </div>

    <?php
    } else {
        $ALERT->notpermission();
    } 
} 
?>