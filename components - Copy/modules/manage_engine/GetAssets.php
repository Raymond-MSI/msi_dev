<?php include("components/classes/func_api_manage_engine.php"); ?>
<script>
$(document).ready(function () {
    var tableCompleted = $('#table').DataTable( {
        dom: 'Btip',
        select: {
            style: 'single'
        },
        buttons: [
            {
                extend: 'excelHtml5',
                text: "<i class='fa fa-file-pdf'></i>",
                title: 'Asset_Manage_Engine_'+<?php echo date("YmdGis"); ?>
            },
        ],
        "columnDefs": [
            {
                "targets": [],
                "visible": false,
            },
            // {
            // "targets": [4,5],
            // "className": 'dt-body-right',
            // "render": DataTable.render.datetime('DD MMM YYYY'),
            // },
            // {
            //     "targets": [15],
            //     "className": 'dt-body-right',
            //     "render": DataTable.render.datetime('DD MMM YYYY HH:MM:SS'),
            // },
            // {
            // "targets": [6],
            // "className": 'dt-body-center',
            // },
        ],
    } );
});
</script>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-secondary">Configuration Item Database</h6>
        <?php spinner(); ?>
        <div class="align-items-right">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <!-- <div class="col-lg-12"> -->
                <?php
                $API_ME = new API_Manage_Engine(true,"",10,0,true);
                $accessToken = $API_ME->get_token();

                // $status = json_decode($accessToken, true);
                // $field = "product.name"; // Part Number
                // $field = "product.manufacturer"; // Manufacturer
                // $field = "name"; // Serial Number
                // $value = "AGA1416N7DK";

                $row_count = 100;

                $criteria = "";
                if(isset($_GET['project_code']) && $_GET['project_code']!="")
                {
                    $criteria = '"search_criteria":{"field":"product.name","condition":"like","value":"' . $_GET['project_code'] . '"},';
                    echo "Project Code : " . $_GET['project_code'] . "<br/>";
                }
                if(isset($_GET['customer_name']) && $_GET['customer_name']!="")
                {
                    $criteria = '"search_criteria":{"field":"department.name","condition":"like","value":"' . $_GET['customer_name'] . '"},';
                    echo "Customer Name : " . $_GET['customer_name'] . "<br/>";
                }
                if(isset($_GET['part_number']) && $_GET['part_number']!="")
                {
                    $criteria = '"search_criteria":{"field":"product.name","condition":"like","value":"' . $_GET['part_number'] . '"},';
                    echo "Part Number : " . $_GET['part_number'] . "<br/>";
                }
                if(isset($_GET['serial_number']) && $_GET['serial_number']!="")
                {
                    $criteria = '"search_criteria":{"field":"name","condition":"like","value":"' . $_GET['serial_number'] . '"},';
                    echo "Serial Number : " . $_GET['serial_number'] . "<br/>";
                }
                $startIndex = 0;
                if(isset($_GET['index_number']) && $_GET['index_number']!="")
                {
                    $startIndex = $_GET['index_number'];
                    echo "Index Number : " . $_GET['index_number'] . "<br/>";
                }

                $json = '
                {
                    "list_info": 
                    {
                        "start_index" : ' . $startIndex . ',
                        "row_count": ' . $row_count . ',
                        "get_total_count": true,' .
                        $criteria . '
                    }
                }';
                $response = $API_ME->get_assets($accessToken, $json);

                if(isset($response['error']))
                {
                    $json_pretty = json_encode($response, JSON_PRETTY_PRINT);
                    echo "<pre>Get List :<br/>" . $json_pretty . "<pre/>";
                } else 
                {
                    $assets = $response['assets'];
                    $table = "
                        <table class='table-bordered hover stripe display compact dataTableMulti' id='table'>
                            <thead>";
                    $tblheader = "
                                <tr class='text-center'>
                                    <th>ID</td>
                                    <th>Manufacturer</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Hardware / Software</th>
                                    <th>Warranty Project Code</th>
                                    <th>Warranty Project Name</th>
                                    <th>Warranty Contract Number</th>
                                    <th>Warranty DO Number</th>
                                    <th>Warranty Sales Name</th>
                                    <th>Warranty Type of Service</th>
                                    <th>Warranty Start</th>
                                    <th>Warranty End</th>
                                    <th>Implementation Project Code</th>
                                    <th>Implementation Project Name</th>
                                    <th>Implementation Contract Number</th>
                                    <th>Implementation PM Name</th>
                                    <th>Implementation Type of Service</th>
                                    <th>Implementation Start</th>
                                    <th>Implementation End</th>
                                    <th>Maintenance Project Code</th>
                                    <th>Maintenance Project Name</th>
                                    <th>Maintenance Contract Number</th>
                                    <th>Maintenance PM Name</th>
                                    <th>Maintenance Type of Service</th>
                                    <th>Maintenance Start</th>
                                    <th>Maintenance End</th>
                                    <th>Vendor</th>
                                    <th>Vendor Project Code</th>
                                    <th>Vendor Project Name</th>
                                    <th>Vendor Contract Number</th>
                                    <th>Vendor Instance Number</th>
                                    <th>Vendor Parent Instance Number</th>
                                    <th>Vendor Service Level</th>
                                    <th>Vendor Service Level Description</th>
                                    <th>Vendor Type of Service</th>
                                    <th>Vendor Start</th>
                                    <th>Vendor End</th>
                                </tr>";
                    $table .= $tblheader . "
                            </thead>
                            <tbody>
                            ";
                    foreach($assets as $asset)
                    {
                        $json = '{"id": "' . $asset['id'] .'"}';
                        $result = $API_ME->get_assets($accessToken, $json);

                        if(is_null($result['asset']['udf_fields']['udf_date1'])) 
                        {
                            $warrantyStart = "";
                        } else
                        {
                            $warrantyStart = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date1']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date2'])) 
                        {
                            $warrantyEnd = "";
                        } else
                        {
                            $warrantyEnd = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date2']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date3'])) 
                        {
                            $impStart = "";
                        } else
                        {
                            $impStart = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date3']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date5'])) 
                        {
                            $impEnd = "";
                        } else
                        {
                            $impEnd = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date5']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date4'])) 
                        {
                            $mntStart = "";
                        } else
                        {
                            $mntStart = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date4']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date6'])) 
                        {
                            $mntEnd = "";
                        } else
                        {
                            $mntEnd = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date6']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date7'])) 
                        {
                            $vendorStart = "";
                        } else
                        {
                            $vendorStart = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date7']['display_value']));
                        }
                        if(is_null($result['asset']['udf_fields']['udf_date8'])) 
                        {
                            $vendorEnd = "";
                        } else
                        {
                            $vendorEnd = date("d-M-Y", strtotime($result['asset']['udf_fields']['udf_date8']['display_value']));
                        }
                        $table .= "
                            <tr>
                                <td>" . $result['asset']['id'] . "</td>
                                <td>" . $result['asset']['product']['product_type']['name'] . "</td>
                                <td>" . $result['asset']['product']['name'] . "</td>
                                <td>" . $result['asset']['serial_number'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char1'] . "</td>
                                <td>" . $result['asset']['department']['name'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char6'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char5'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char2'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char3'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char30'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char7'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char9'] . "</td>
                                <td>" . $warrantyStart . "</td>
                                <td>" . $warrantyEnd . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char10'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char12'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char11'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char18'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char17'] . "</td>
                                <td>" . $impStart . "</td>
                                <td>" . $impEnd . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char19'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char27'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char20'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char27'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char22'] . "</td>
                                <td>" . $mntStart . "</td>
                                <td>" . $mntEnd . "</td>
                                <td>" . $result['asset']['vendor'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char23'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char25'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char24'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char14'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char13'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char15'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char16'] . "</td>
                                <td>" . $result['asset']['udf_fields']['udf_char26'] . "</td>
                                <td>" . $vendorStart . "</td>
                                <td>" . $vendorEnd . "</td>
                            </tr>";
                    }
                    $table .= "</tbody><tfoot>" . $tblheader . "</tfoot></table>";
                    echo $table;
                }
                ?>
            <!-- </div> -->
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="filter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
    <div class="modal-dialog">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Order Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="form" method="get" action="index.php">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">Project Code:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="text" class="form-control form-control-sm" name="project_code" id="project_code" value="<?php if(isset($_GET['project_code'])) { echo $_GET['project_code']; } ?>" placeholder="Project Code">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">Customer Name:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" value="<?php if(isset($_GET['customer_name'])) { echo $_GET['customer_name']; } ?>" placeholder="Customer Name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">Part Number:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="text" class="form-control form-control-sm" name="part_number" id="part_number" value="<?php if(isset($_GET['part_number'])) { echo $_GET['part_number']; } ?>" placeholder="Part Number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">Serial Number:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="text" class="form-control form-control-sm" name="serial_number" id="serial_number" value="<?php if(isset($_GET['serial_number'])) { echo $_GET['serial_number']; } ?>" placeholder="Serial Number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">Index Number:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="text" class="form-control form-control-sm" name="index_number" id="index_number" value="<?php if(isset($_GET['index_number'])) { echo $_GET['index_number']; } ?>" placeholder="Index Number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="mod" value="manage_engine">
                    <input type="hidden" name="sub" value="GetAssets">
                    <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Search">
                </div>
            </form>
        </div>
    </div>
</div>
