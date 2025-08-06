<?php
if(isset($property) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    function view_table($DTBL, $tblname, $primarykey="", $condition = "", $order = "", $firstRow = 0, $totalRow = 100, $index = "") {
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
                                    $header .="<th class='text-center align-middle'>" . $headerx . "</th>";
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
                            if($tdatatable > 0) {
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

    function view_table_sqlsrv($params) {
        // $params['connection'];
        // $params['tblname']; 
        // $params['condition'];
        // $params['order'];
        // $params['firstRow'];
        // $params['totalRowa'];
        // $params['nameView'];
        // $params['dom'];

        global $ALERT;
        $tblStatus = true;
        if(isset($params['tblname'])) {
            $condition = "";
            if(isset($params['condition'])) {
                $condition = $params['condition'];
            }
            $order = "";
            if(isset($params['order'])) {
                $order = $params['order'];
            }
            $firstRow = 0;
            if(isset($params['firstRow'])) {
                $firstRow = $params['firstRow'];
            }
            $totalRows = 100;
            if(isset($params['totalRows'])) {
                $totalRows = $params['TtotalRows'];
            }
            $data = $params['connection']->get_data($params['tblname'], $condition, $order, $firstRow, $totalRows);
        } elseif(isset($params['sql'])) {
            $data = $params['connection']->get_sql($params['sql']);
        } else {
            echo $ALERT->datatable_error("Parameter \$params['tblname'] or \$params['sql'] not defined.");
            $tblStatus = false;
        }
        if(!isset($params['connection'])) {
            echo $ALERT->datatable_error("Parameter \$params['connection'] not defined.");
        }
        if($tblStatus == true) {
            ?>
            <script>
                $(document).ready(function() {
                    <?php if(isset($params['dom0'])) { echo $params['dom0']; } ?>
                    var table = $('#<?php echo $params['nameView']; ?>').DataTable( {
                        <?php echo $params['dom']; ?>
                    } );
                    <?php if(isset($params['dom2'])) { echo $params['dom2']; } ?>
                });
            </script>
            <style>
                tr.group,
                tr.group:hover {
                    background-color: #ddd !important;
                }
            </style>
            <div class="table-responsive">
                <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $params['nameView']; ?>" width="100%" cellspacing="0">
                    <thead>
                        <?php
                        $stmt = $params['connection']->get_fields($params['sql']);

                        if(isset($params['header']) && $params['header']=="splite") {
                            $header0 = "<tr class='bg-light'>";
                            $header1 = "<tr class='bg-light'>";
                            $i=0;
                            $prefix0="";
                            foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) 
                            {
                                $title = explode(" ", str_replace("_", " ", strtoupper($fieldMetadata['Name'])));
                                $prefix1 = "";
                                for ($j=0; $j<count($title); $j++) 
                                {
                                    if($j>0) 
                                    {
                                        $prefix1 .= $title[$j] . " ";
                                    }
                                }
                                if($i==0)
                                {
                                    $prefix0 = $title[0];
                                }
                                if($prefix0!=$title[0]) 
                                {
                                    $prefix0!="" ? $caption=$prefix0 : $caption=$title[0] ;
                                    $header0 .= "<th class='text-center align-middle' colspan='" . $i . "'>" . $caption . "</th>";
                                    $prefix0 = $title[0];
                                    $i=0;
                                }
                                $header1 .= "<th class='text-center align-middle'>" . $prefix1 . "</th>";
                                $i++;
                            }
                            $header0 .= "</tr>";
                            $header1 .= "</tr>";
                            echo $header0;
                            echo $header1;
                        } else {
                            $header1 = "<tr>";
                            foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
                                $header1 .=  "<th class='text-center align-middle'>" . str_replace("_", " ", strtoupper($fieldMetadata['Name'])) . "</th>";
                            }
                            $header1 .= "</tr>";
                            echo $header1;
                        }
                        ?>
                    </thead>
                    <tfoot>
                        <?php 
                        echo $header1;
                        ?>
                    </tfoot>
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
            <?php 
        }
    } 
} 
?>