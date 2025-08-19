<?php
function save_mandaysx($project_id, $brand, $resource_level, $catalog_id, $service_type, $mans, $mandays, $rate, $catalogType=0)
{
    global $DTSB;
    $xxx = ($resource_level - fmod($resource_level,10))/10;
    $mysql = 
        "SELECT
            `project_mandays_id`, `project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `catalog_type`, `service_type`, `modified_by`, `modified_date`
        FROM
            `sa_trx_project_mandays`
        WHERE
            `project_id` = " . $_POST['project_id'] ."
            AND (`resource_level` - (`resource_level` % 10))/10 = " . $xxx . "
            AND `service_type` = " . $service_type . "
            AND `brand` = '" . $brand . "'";
    $rsExisting = $DTSB->get_sql($mysql);
    if($mans!=NULL || $mandays!=NULL)
    {
        if($rsExisting[2]==0)
        {
            $mysql = sprintf("INSERT INTO `sa_trx_project_mandays` (`project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `catalog_type`, `service_type`, `modified_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", GetSQLValueString($project_id, "int"), GetSQLValueString($resource_level, "int"), GetSQLValueString($catalog_id, "int"), GetSQLValueString($mans, "int"), GetSQLValueString($mandays, "int"), GetSQLValueString($brand, "text"), GetSQLValueString($rate, "int"), GetSQLValueString($catalogType, "int"), GetSQLValueString($service_type, "int"), GetSQLValueString(MYFULLNAME, "text"));
            $res = $DTSB->get_sql($mysql, false);
            echo '<script>console.log("save_mandaysx :");</script>';
            echo '<script>console.log("    '.$mysql.'");</script>';
        } else
        if($mans==$rsExisting[0]['mantotal'] && $mandays==$rsExisting[0]['mandays'] && $brand==$rsExisting[0]['brand'] && $resource_level==$rsExisting[0]['resource_level'] && $service_type==$rsExisting[0]['service_type'] && $catalogType==$rsExisting[0]['catalog_type'])
        {
            // echo '<script>console.log("'.$catalogType." - ".$rsExisting[0]['catalog_type'].'");</script>';
        } else
        {
            $mysql = sprintf("UPDATE `sa_trx_project_mandays` SET `mantotal` = %s, `mandays` = %s, `brand` = %s, `modified_by` = %s, `value` = %s, `catalog_type` = %s WHERE `project_id` = %s AND `resource_level` = %s AND `service_type`= %s", GetSQLValueString($mans, "int"), GetSQLValueString($mandays, "int"), GetSQLValueString($brand, "text"), GetSQLValueString(MYFULLNAME, "text"), GetSQLValueString($rate, "int"), GetSQLValueString($catalogType, "int"), GetSQLValueString($_POST['project_id'], "int"), GetSQLValueString($resource_level, "int"), GetSQLValueString($service_type, "int"));
            $rs = $DTSB->get_sql($mysql, false);
            echo '<script>console.log("save_mandaysx :");</script>';
            echo '<script>console.log("    '.$mysql.'");</script>';
        }
    } else
    if($rsExisting[2]>0)
    {
        delete_mandays($resource_level, $service_type);
    }
}

function delete_mandays($resource_level, $service_type)
{
    global $DTSB;
    $mysql = sprintf("DELETE FROM `sa_trx_project_mandays` WHERE `project_id` = %s AND `service_type` = %s AND `resource_level` = %s", GetSQLValueString($_POST['project_id'], "int"), GetSQLValueString($service_type, "int"), GetSQLValueString($resource_level, "int"));
    $res = $DTSB->get_sql($mysql, false);
    echo '<script>console.log("save_mandaysx :");</script>';
    echo '<script>console.log("    '.$mysql.'");</script>';
}

// Function save Mandays
// if(isset($_POST['save_service_budget']))
if(isset($_POST['mandays']))
{
    $brandx = 1;
    foreach($_POST['mandays'] AS $brand)
    {
        $service_type = 1;
        if(isset($brand["'PDMans'"]) && isset($brand["'PDMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "1$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PDMans'"], $brand["'PDMandays'"], $brand["'PDRate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("1$brandx", $service_type);
        }
        if(isset($brand["'PMMans'"]) && isset($brand["'PMMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "2$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PMMans'"], $brand["'PMMandays'"], $brand["'PMRate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("2$brandx", $service_type);
        }
        if(isset($brand["'PCMans'"]) && isset($brand["'PCMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "3$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PCMans'"], $brand["'PCMandays'"], $brand["'PCRate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("3$brandx", $service_type);
        }
        if(isset($brand["'PAMans'"]) && isset($brand["'PAMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "4$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PAMans'"], $brand["'PAMandays'"], $brand["'PARate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("4$brandx", $service_type);
        }
        if(isset($brand["'EEMans'"]) && isset($brand["'EEMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "5$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EEMans'"], $brand["'EEMandays'"], $brand["'EERate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("5$brandx", $service_type);
        }
        if(isset($brand["'EPMans'"]) && isset($brand["'EPMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "6$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EPMans'"], $brand["'EPMandays'"], $brand["'EPRate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("6$brandx", $service_type);
        }
        if(isset($brand["'EAMans'"]) && isset($brand["'EAMandays'"]))
        {
            save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "7$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EAMans'"], $brand["'EAMandays'"], $brand["'EARate'"], $brand["'type'"]);
        } else
        {
            delete_mandays("7$brandx", $service_type);
        }
        $brandx++;
    }
} else
{
    $mysql = sprintf(
        "DELETE
        FROM
            `sa_trx_project_mandays`
        WHERE
            `project_id` = %s AND `service_type` = 1",
        GetSQLValueString($_POST['project_id'], "int")
    );
    $res = $DTSB->get_sql($mysql, false);
}
?>