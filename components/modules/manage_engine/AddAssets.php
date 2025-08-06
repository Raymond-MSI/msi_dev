<?php
if(!isset($_SESSION['Microservices_UserEmail']))
{
    $_SESSION['Microservices_UserEmail'] = "sdadmin@mastersystem.co.id";
}
include("components/classes/func_api_manage_engine.php");
include("components/classes/func_databases_sqlsrv.php");

$API_ME = new API_Manage_Engine(true,"",10,0,true);
$accessToken = $API_ME->get_token();

$mdlname = "CIDB";
$DBCIDB = get_conn_sqlsrv($mdlname);

$sql = "SELECT 
        [CI_ID]
        ,[CI_ID_V]
        ,[CI_ID_D]
        ,[CI_ID_RM]
        ,[CI_ID_R]
        ,[CI_ID_M]
        ,[CI_ID_I]
        ,[Manufacturer] AS [Product Manufacturer]
        ,[Part Number] AS [Product Part Number]
        ,[Serial Number] AS [Product Serial Number]
        ,[Product Category] AS [Product Category] 
        ,[Architecture Group] AS [Product Architecture Group]
        ,[Architecture Sub Group] AS [Product Architecture Sub Group]
        ,[Customer code] AS [Customer Customer Code]
        ,[Customer Name] AS [Customer Customer Name]
        ,[Project Code] AS [Project Project Code]
        ,[DO Project Name] AS [Project Project Name]
        ,[Location Category] AS [Project Location Category]
        ,[Location Address] AS [Project Location Address]
        ,[DO No.] AS [Delivery DO No.]
        ,[DO Notes] AS [Delivery Notes]
        ,CONVERT(varchar(32), [Warranty Start], 120) AS [Delivery Warranty Start]
        ,CONVERT(varchar(32), [Warranty End], 120) AS [Delivery Warranty End]
        ,[Firmware DO] AS [Delivery Firmware]
        ,CONVERT(varchar(32), [LastModifiedD], 120) AS [LastModifiedD]
        ,[Implementation Project Code]
        ,[Implementation Contract No]
        ,[Implementation Project Name]
        ,[Type of Services Implementation] AS [Implementation Type of Services]
        ,CONVERT(varchar(32), [Implementation Start], 120) AS [Implementation Implementation Start]
        ,CONVERT(varchar(32), [Implementation End], 120) AS [Implementation Implementation End]
        ,[Notes Implementation] AS [Implementation Notes]
        ,[Firmware Implementation] AS [Implementation Firmware]
        ,CONVERT(varchar(32), [LastModifiedI], 120) AS [LastModifiedI]
        ,[Maintenance Project Code]
        ,[Maintenance Contract No]
        ,[Maintenance Project Name]
        ,[Type of Services Maintenance] AS [Maintenance Type of Services]
        ,CONVERT(varchar(32), [Maintenance Start], 120) AS [Maintenance Maintenance Start]
        ,CONVERT(varchar(32), [Maintenance End], 120) AS [Maintenance Maintenance End]
        ,[Notes Maintenance] AS [Maintenance Notes]
        ,[Firmware Maintenance] AS [Maintenance Firmware]
        ,CONVERT(varchar(32), [LastModifiedM], 120) AS [LastModifiedM]
        ,[Rental Project Code]
        ,[Rental Contract No]
        ,[Rental Project Name]
        ,[Type of Service Rental] AS [Rental Type of Service]
        ,CONVERT(varchar(32), [Rental Warranty Start], 120) AS [Rental Rental Start]
        ,CONVERT(varchar(32), [Rental Warranty End], 120) AS [Rental Rental End]
        ,[Notes Rental] AS [Rental Notes]
        ,[Firmware Rental] AS [Rental Firmware]
        ,CONVERT(varchar(32), [LastModifiedR], 120) AS [LastModifiedR]
        ,[Rental Maintenance Code] AS [Rental-Maintenance Code]
        ,[Rental Maintenance Contract No] AS [Rental-Maintenance Contract No]
        ,[Rental Maintenance Project Name] AS [Rental-Maintenance Project Name]
        ,[Type of Service Rental Maintenance] AS [Rental-Maintenance Type of Service Rental]
        ,CONVERT(varchar(32), [Rental Maintenance Warranty Start], 120) AS [Rental-Maintenance Maintenance Start]
        ,CONVERT(varchar(32), [Rental Maintenance Warranty End], 120) AS [Rental-Maintenance Maintenance End]
        ,[Notes Rental Maintenance] AS [Rental-Maintenance Notes]
        ,[Firmware Rental Maintenance] AS [Rental-Maintenance Firmware]
        ,CONVERT(varchar(32), [LastModifiedRM], 120) AS [LastModifiedRM]
        ,[Vendor Project Code]
        ,CONVERT(varchar(32), [Warranty Start Vendor], 120) AS [Vendor Warranty Start]
        ,CONVERT(varchar(32), [Warranty End Vendor], 120) AS [Vendor Warranty End]
        ,[Contract No] AS [Vendor Contract No]
        ,[Vendor Project Name]
        ,[Parent Instance Number] AS [Vendor Parent Instance Number]
        ,[Instance Number] AS [Vendor Instance Number]
        ,[Service Level] AS [Vendor Service Level]
        ,[Service Level Description] AS [Vendor Service Level Description]
        ,[Firmware Vendor] AS [Vendor Firmware]
        ,CONVERT(varchar(32), [End of Product Sales], 120) AS [Lifecycle End of Product Sales]
        ,CONVERT(varchar(32), [End of New Service Attachment Date:HW], 120) AS [Lifecycle End of New Service Attachment Date:HW]
        ,CONVERT(varchar(32), [Last Date of Renew:HW], 120) AS [Lifecycle Last Date of Renew:HW]
        ,CONVERT(varchar(32), [End of Software Maintenance Date], 120) AS [Lifecycle End of Software Maintenance Date]
        ,CONVERT(varchar(32), [Ldos], 120) AS [Lifecycle Ldos]
        ,[End of Life Product Bulletin] AS [Lifecycle End of Life Product Bulletin]
        ,[Default Service List Price $] AS [Price Default Service List Price $]
        ,[Existing Coverage Level List Price $] AS [Price Existing Coverage Level List Price $]
        ,[PO Number] AS [ PO Number]
        ,CONVERT(varchar(32), [LastModifiedV], 120) AS [LastModifiedV]
    FROM [CIDB].[dbo].[ReportMatrixCI]
    WHERE
        ([LastModifiedM] BETWEEN '2022-08-02' AND '2022-08-03') OR 
        ([LastModifiedV] BETWEEN '2022-08-02' AND '2022-08-03') OR 
        ([LastModifiedD] BETWEEN '2022-08-02' AND '2022-08-03') OR 
        ([LastModifiedI] BETWEEN '2022-08-02' AND '2022-08-03')
    ORDER BY [Part Number] DESC
    OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY;
    ";

$cidb = $DBCIDB->get_sql($sql);

if($cidb[2]>0)
{
    while($row = sqlsrv_fetch_array( $cidb[1], SQLSRV_FETCH_ASSOC) )
    {
        if($row['Product Serial Number']!="")
        {
            $startIndex = 0;
            $row_count = 1;
            $criteria = '"search_criteria":
                {
                    "field":"serial_number",
                    "condition":"is",
                    "value":"' . $row['Product Serial Number'] . '"
                }';
            // $criteria = '"search_criteria":
            //     {
            //         "field":"serial_number",
            //         "condition":"is",
            //         "value":"asddfg"
            //     }';
            $json_list_info = '
{
    "list_info": 
    {
        "start_index" : ' . $startIndex . ',
        "row_count": ' . $row_count . ',
        ' . $criteria . '
    }'; 
            $json = $json_list_info . " }";
            $response = $API_ME->get_assets($accessToken, $json);
            // $API_ME->print_json($response);
            $count_asset = $response['list_info']['row_count'];
            if($count_asset>0)
            {
                // Update Assets
                // $xxx = json_encode($response, JSON_PRETTY_PRINT);
                // echo "<p><pre>" . $xxx . "</pre></p>";
                $post_type = "PUT";
                $assetsx = $response['assets'];
                foreach($assetsx as $assetx)
                {
                    $json_asset_id = '{"id":"' . $assetx['id'] . '"}';
                    $response = $API_ME->get_assets($accessToken, $json_asset_id);
                    $asset = $response['asset'];
                    $department_id = $asset['department']['id'];
                }
                $json_put = '
    "asset":
    {
        "department": 
        {
            "name": "' . $row['Customer Customer Name'] . '",
            "id": "' . $department_id . '"
        },
        "udf_fields": 
        {
            ';




            $udf_char8 = null;                              // Firmware
            $udf_char6 = "Hardware";                        // Hardware / Software
            $udf_char7 = null;                              // Warranty Sales
            $udf_char9 = null;                              // Warranty ToS
            $udf_char18 = null;                             // Implementation PM
            $udf_char26 = null;                             // Vendor ToS
            $site = $row['Project Location Category'];      // 17 - PROJECT LOCATION CATEGORY
            $udf_char4 = null;                              // Notes
            $udf_char29 = null;                             // Old Part Number
            $udf_char28 = null;                             // Old Serial Number
            $sambung = ',
            ';
            if($row['Customer Customer Code']!="")
            {
                $json_put .= '"udf_char1": "' . $row['Customer Customer Code'] . '"';
            }
            if($udf_char8!="")
            {
                $json_put .= $sambung . '"udf_char8": "' . $udf_char8 . '"';
            }
            if($udf_char6!="")
            {
                $json_put .= $sambung . '"udf_char6": "' . $udf_char6 . '"';
            }
            if($row['Implementation Project Code']!="")
            {
                $json_put .= $sambung . '"udf_char10": "' . $row['Implementation Project Code'] . '"';
            }
            if($row['Implementation Project Name']!="")
            {
                $json_put .= $sambung . '"udf_char12": "' . $row['Implementation Project Name'] . '"';
            }
            if($row['Implementation Contract No']!="")
            {
                $json_put .= $sambung . '"udf_char11": "' . $row['Implementation Contract No'] . '"';
            }
            if($udf_char18!="")
            {
                $json_put .= $sambung . '"udf_char18": "' . $udf_char18 . '"';
            }
            if($row['Implementation Type of Services']!="")
            {
                $json_put .= $sambung . '"udf_char17": "' . $row['Implementation Type of Services'] . '"';
            }
            if($row['Implementation Implementation Start']!="")
            {
                $json_put .= $sambung . '"udf_date3": {"value": "' . strtotime($row['Implementation Implementation Start'])*1000 . '"}';
            }
            if($row['Implementation Implementation End']!="")
            {
                $json_put .= $sambung . '"udf_date5": {"value": "' . strtotime($row['Implementation Implementation End'])*1000 . '"}';
            }
            if($row['Maintenance Project Code']!="")
            {
                $json_put .= $sambung . '"udf_char19": "' . $row['Maintenance Project Code'] . '"';
            }
            if($row['Maintenance Project Name']!="")
            {
                $json_put .= $sambung . '"udf_char21": "' . $row['Maintenance Project Name'] . '"';
            }
            if($row['Maintenance Contract No']!="")
            {
                $json_put .= $sambung . '"udf_char20": "' . $row['Maintenance Contract No'] . '"';
            }
            if($row['Maintenance Notes']!="")
            {
                $json_put .= $sambung . '"udf_char27": "' . $row['Maintenance Notes'] . '"';
            }
            if($row['Maintenance Type of Services']!="")
            {
                $json_put .= $sambung . '"udf_char22": "' . $row['Maintenance Type of Services'] . '"';
            }
            if($row['Maintenance Maintenance Start']!="")
            {
                $json_put .= $sambung . '"udf_date4": {"value": "' . strtotime($row['Maintenance Maintenance Start'])*1000 . '"}';
            }
            if($row['Maintenance Maintenance End']!="")
            {
                $json_put .= $sambung . '"udf_date6": {"value": "' . strtotime($row['Maintenance Maintenance End'])*1000 . '"}';
            }
            if($row['Vendor Project Code']!="")
            {
                $json_put .= $sambung . '"udf_char23": "' . $row['Vendor Project Code'] . '"';
            }
            if($row['Vendor Project Name']!="")
            {
                $json_put .= $sambung . '"udf_char25": "' . $row['Vendor Project Name'] . '"';
            }
            if($row['Vendor Contract No']!="")
            {
                $json_put .= $sambung . '"udf_char24": "' . $row['Vendor Contract No'] . '"';
            }
            if($udf_char26!="")
            {
                $json_put .= $sambung . '"udf_char26": "' . $udf_char26 . '"';
            }
            if($row['Vendor Instance Number']!="")
            {
                $json_put .= $sambung . '"udf_char14": "' . $row['Vendor Instance Number'] . '"';
            }
            if($row['Vendor Parent Instance Number']!="")
            {
                $json_put .= $sambung . '"udf_char13": "' . $row['Vendor Parent Instance Number'] . '"';
            }
            if($row['Vendor Service Level']!="")
            {
                $json_put .= $sambung . '"udf_char15": "' . $row['Vendor Service Level'] . '"';
            }
            if($row['Vendor Service Level Description']!="")
            {
                $json_put .= $sambung . '"udf_char16": "' . $row['Vendor Service Level Description'] . '"';
            }
            if($row['Vendor Warranty Start']!="")
            {
                $json_put .= $sambung . '"udf_date7": {"value": "' . strtotime($row['Vendor Warranty Start'])*1000 . '"}';
            }
            if($row['Vendor Warranty End']!="")
            {
                $json_put .= $sambung . '"udf_date8": {"value": "' . strtotime($row['Vendor Warranty End'])*1000 . '"}';
            }
            if($row['Project Project Code']!="")
            {
                $json_put .= $sambung . '"udf_char5": "' . $row['Project Project Code'] . '"';
            }
            if($row['Project Project Name']!="")
            {
                $json_put .= $sambung . '"udf_char2": "' . $row['Project Project Name'] . '"';
            }
            if($row['Delivery Notes']!="")
            {
                $json_put .= $sambung . '"udf_char3": "' . $row['Delivery Notes'] . '"';
            }
            if($udf_char7!="")
            {
                $json_put .= $sambung . '"udf_char7": "' . $udf_char7 . '"';
            }
            if($row['Delivery DO No.']!="")
            {
                $json_put .= $sambung . '"udf_char30": "' . $row['Delivery DO No.'] . '"';
            }
            if($udf_char9!="")
            {
                $json_put .= $sambung . '"udf_char9": "' . $udf_char9 . '"';
            }
            if($row['Delivery Warranty Start']!="")
            {
                $json_put .= $sambung . '"udf_date1": {"value": "' . strtotime($row['Delivery Warranty Start'])*1000 . '"}';
            }
            if($row['Delivery Warranty End']!="")
            {
                $json_put .= $sambung . '"udf_date2": {"value": "' . strtotime($row['Delivery Warranty End'])*1000 . '"}';
            }
            if($udf_char4!="")
            {
                $json_put .= $sambung . '"udf_char4": "' . $udf_char4 . '"';
            }
            if($udf_char29!="")
            {
                $json_put .= $sambung . '"udf_char29": "' . $udf_char29 . '"';
            }
            if($udf_char28!="")
            {
                $json_put .= $sambung . '"udf_char28": "' . $udf_char28 . '"';
            }
        $json_put .='
        }
    }';

    $response = $API_ME->put_asset($accessToken, "{" . $json_put . "}", $json_asset_id);
    $status_code = $response['response_status']['status_code'];
    $status =  $response['response_status']['status'];
    $json_status = '
    "response_status": 
    {
        "status_code": ' . $status_code . ',
        "status": "' . $status . '"
    }
}';

    $json = $json_list_info . $sambung . $json_put . $sambung . $json_status;


            } else
            {
                // Insert Assets
                $post_type = "POST";
                $json_put = '
    "asset":
    {
        "retain_user_site": false,
        "state": 
        {
          "name": "In Use",
          "id": "145684000000006137"
        },
        "product": 
        {
          "name": "' . $row['Product Part Number'] . '"
        },
        "serial_number": "' . $row['Product Serial Number'] . '",
        "name": "' . $row['Product Serial Number'] . '",
        "is_loanable": false,';

        if($row['Customer Customer Name'])
        {
            $json_put .= '
        "department": 
        {
            "site": null,
            "name": "PT BANK CENTRAL ASIA TBK.",
            "id": "145684000001434029"
        },';
        }

        if($row['Delivery Warranty End'])
        {
            $json_put .= '
        "warranty_expiry": 
        {
            "value": "' . strtotime($row['Delivery Warranty End'])*1000 . '"
        },';
        }
  
        if($row['Project Location Category'])
        {
            $json_put .= '
        "site": 
        {
            "name": "' . $row['Project Location Category'] . '"
        },';
        }

        if($row['Delivery Warranty Start']!="")
        {
            $json_put .= '
        "acquisition_date": 
        {
          "value": "' . strtotime($row['Delivery Warranty Start'])*1000 . '"
        },';
        }

        if($row['Project Location Address']!="")
        {
            $json_put .= '
        "location": "' . $row['Project Location Address'] . '"';
        }

        // $json_put .= '
        // "udf_fields": 
        // {
        //     ';

                if(strpos($row['CI_ID_D'], "_HW_")>0 || strpos($row['CI_ID_I'], "_HW_")>0 || strpos($row['CI_ID_M'], "_HW_")>0 || strpos($row['CI_ID_R'], "_HW_")>0 || strpos($row['CI_ID_RM'], "_HW_")>0 || strpos($row['CI_ID_V'], "_HW_")>0)
                {
                    $CIHWSW = "Hardware";
                } else
                {
                    $CIHWSW = "Software";
                }
                $mysql = sprintf("(product_type, product, product_manufacturer, asset_name, serial_number, acquisition_date, warranty_expiry_date, expiry_date, location, asset_state, assign_to_department, site, loanable, customer_code, hardware_software, implementation_contract_number, implementation_end, implementation_pm_name, implementation_project_code, implementation_project_name, implementation_start, implementation_tos, maintenance_end, maintenance_pm_name, maintenance_project_code, maintenance_project_name, maintenance_start, maintenance_tos, notes, vendor_contract_number, vendor_end, vendor_instance_number, vendor_parent_instance_number, vendor_project_code, vendor_project_name, vendor_service_level, vendor_service_level_description, vendor_tos, warranty_contract_no, warranty_do_number, warranty_end, warranty_project_code, warranty_project_name, warranty_sales_name, warranty_start, warranty_tos) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                    GetSQLValueString($row['Product Manufacturer'], "text"),
                    GetSQLValueString($row['Product Part Number'], "text"),
                    GetSQLValueString($row['Product Manufacturer'], "text"),
                    GetSQLValueString($row['Product Serial Number'], "text"),
                    GetSQLValueString($row['Product Serial Number'], "text"),
                    GetSQLValueString($row['Delivery Warranty Start'], "date"),
                    GetSQLValueString($row['Delivery Warranty End'], "date"),
                    GetSQLValueString($row['Lifecycle Ldos'], "text"),
                    GetSQLValueString($row['Project Location Address'], "text"),
                    GetSQLValueString("In Use", "text"),
                    GetSQLValueString($row['Customer Customer Name'], "text"),
                    GetSQLValueString($row['Project Location Category'], "text"),
                    GetSQLValueString(FALSE, "text"),
                    GetSQLValueString($row['Customer Customer Code'], "text"),
                    GetSQLValueString($CIHWSW, "text"),
                    GetSQLValueString($row['Implementation Contract No'], "text"),
                    GetSQLValueString($row['Implementation Implementation End'], "text"),
                    GetSQLValueString($row['Implementation Notes'], "text"),
                    GetSQLValueString($row['Implementation Project Code'], "text"),
                    GetSQLValueString($row['Implementation Project Name'], "text"),
                    GetSQLValueString($row['Implementation Implementation Start'], "text"),
                    GetSQLValueString($row['Implementation Type of Services'], "text"),
                    GetSQLValueString($row['Maintenance Contract No'], "text"),
                    GetSQLValueString($row['Maintenance Maintenance End'], "text"),
                    GetSQLValueString($row['Maintenance Notes'], "text"),
                    GetSQLValueString($row['Maintenance Project Code'], "text"),
                    GetSQLValueString($row['Maintenance Project Name'], "text"),
                    GetSQLValueString($row['Maintenance Maintenance Start'], "text"),
                    GetSQLValueString($row['Maintenance Type of Services'], "text"),
                    GetSQLValueString($row['Vendor Contract No'], "text"),
                    GetSQLValueString($row['Vendor Warranty End'], "text"),
                    GetSQLValueString($row['Vendor Instance Number'], "text"),
                    GetSQLValueString($row['Vendor Parent Instance Number'], "text"),
                    GetSQLValueString($row['Vendor Project Code'], "text"),
                    GetSQLValueString($row['Vendor Project Name'], "text"),
                    GetSQLValueString($row['Vendor Service Level'], "text"),
                    GetSQLValueString($row['Vendor Service Level Description'], "text"),
                    GetSQLValueString($row['Vendor Warranty Start'], "text"),
                    GetSQLValueString("Warranty", "text"),
                    GetSQLValueString($row['Delivery Notes'], "text"),
                    GetSQLValueString($row['Delivery DO No.'], "text"),
                    GetSQLValueString($row['Delivery Warranty End'], "text"),
                    GetSQLValueString($row['Project Project Code'], "text"),
                    GetSQLValueString($row['Project Project Name'], "text"),
                    GetSQLValueString($row['Delivery Notes'], "text"),
                    GetSQLValueString($row['Delivery Warranty Start'], "text"),
                    GetSQLValueString("Warranty", "text")
                );
                $mdlname = "MANAGE_ENGINE";
                $DBME = get_conn($mdlname);
                $tblname = "trx_asset_me";
                $res = $DBME->insert_data($tblname, $mysql);

                $status_code = '"NEW"';
                $status = '"successed"';
                $post_type = "NEW";
                $json = '{
    '. $json_put . '
    },
    "response_status": 
    {
        "status_code": "NEW",
        "status": "successed"
    }
}';

            }


            // if($count_asset>0)
            // {
            //     $response = $API_ME->put_asset($accessToken, "{" . $json_put . "}", $json_asset_id);
            // } else
            // {
            //     $response = $API_ME->post_asset($accessToken, "{" . $json_put . "}");
            // }

//             $json_status = '
//     "response_status": 
//     {
//         "status_code": ' . $status_code . ',
//         "status": "' . $status . '"
//     }
// }';
            // $xxx = json_encode($response, JSON_PRETTY_PRINT);
            // echo "<p><pre>" . $xxx . "</pre></p>";

            $mdlname = "MANAGE_ENGINE";
            $DBME = get_conn($mdlname);

            $tblname = "trx_asset_logs";
            // $json = $json_list_info . $sambung . $json_put . $sambung . $json_status;
            echo "<pre>" . $json . "</pre>";
            $mysql = sprintf("(`asset_request_type`, `asset_data`, `status`) VALUES (%s, %s, %s)",
                GetSQLValueString($post_type, "text"),
                GetSQLValueString($json, "longtext"),
                GetSQLValueString($status, "text")
            );
            $res = $DBME->insert_data($tblname, $mysql);
        } else
        {
            echo "Data Manage Engine not available.";
        }
    }
} else
{
    echo "Data CIDB not available.";
}
?>
