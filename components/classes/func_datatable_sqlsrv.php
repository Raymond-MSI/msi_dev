<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {

    function view_table_sqlsrv($DBVIEW, $tblname, $condition = "", $order = "", $firstRow = 0, $totalRow = 100, $index = "") { 
        $data = $DBVIEW->get_sql($tblname);
        ?>
    
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered hover stripe display compact dataTableMulti" id="example" width="100%" cellspacing="0">
                    <!-- <thead>
                        <tr>
                            <?php 
                                // $header = ''; 
                                // do { 
                                //     // $header .="<th>" . strtoupper($ddatatable_header['Field']) . "</th>";
                                //     $headerx = str_replace("_", " ", strtoupper($ddatatable_header['Field']));
                                //     $header .="<th class='text-center align-middle'>" . $headerx . "</th>";
                                // } while ($ddatatable_header = $qdatatable_header->fetch_assoc()); 
                                // echo $header; 
                            ?>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php //echo $header; ?>
                        </tr>
                    </tfoot> -->
                    <tbody>
                        <?php
                            while( sqlsrv_fetch( $data[1] )) {
                                ?>
                                <tr>
                                    <?php
                                    // Iterate through the fields of each row.
                                    for($i = 0; $i < $data[3]; $i++) { 
                                        ?>
                                        <td><?php echo sqlsrv_get_field($data[1], $i); ?></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php 
    } 
}
?>