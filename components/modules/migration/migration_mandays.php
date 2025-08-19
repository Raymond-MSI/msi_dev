<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
// $txt = "John Doe\n";
// fwrite($myfile, $txt);

$DBSB = get_conn("SERVICE_BUDGET");
echo "Migration Mandays data to Addon for <br/>";
fwrite($myfile, "Migration Mandays data to Addon for \r\n");
echo "1. Existing Backup Unit <br/>";
fwrite($myfile, "1. Existing Backup Unit \r\n");
echo "2. Investment Backup Unit <br/>";
fwrite($myfile, "2. Investment Backup Unit \r\n");
echo "3. Extended Warranty Cisco <br/>";
fwrite($myfile, "3. Extended Warranty Cisco \r\n");
echo "4. Extended Warranty Non-Cisco <br/>";
fwrite($myfile, "4. Extended Warranty Non-Cisco \r\n");
$mysql = "SELECT
`project_id`,
`brand` AS `addon_title`,
`mandays` AS `addon_price`,
IF(
    LEFT(`resource_level`, 1) = 1 AND `service_type` = 2,
    4,
    IF(
        LEFT(`resource_level`, 1) = 2 AND `service_type` = 2,
        5,
        IF(
            LEFT(`resource_level`, 1) = 1 AND `service_type` = 3,
            6,
            IF(
                LEFT(`resource_level`, 1) = 2 AND `service_type` = 3,
                7,
                8
            )
        )
    )
) AS `service_type`,
`modified_by`,
`modified_date`
FROM
`sa_trx_project_mandays`
WHERE
`service_type` > 1 AND `mandays` > 0 AND
    IF(
    LEFT(`resource_level`, 1) = 1 AND `service_type` = 2,
    4,
    IF(
        LEFT(`resource_level`, 1) = 2 AND `service_type` = 2,
        5,
        IF(
            LEFT(`resource_level`, 1) = 1 AND `service_type` = 3,
            6,
            IF(
                LEFT(`resource_level`, 1) = 2 AND `service_type` = 3,
                7,
                8
            )
        )
    )
) < 8
ORDER BY
`project_id`;";

$rsMandays = $DBSB->get_sql($mysql);
echo "Total Records : " . $rsMandays[2] . "<br/><br/>"; 
if($rsMandays[2]>0)
{
    $i = 1;
    do {
        echo $i . ". " . $rsMandays[0]['project_id'] . " - " . $rsMandays[0]['addon_title'] . " - " . $rsMandays[0]['addon_price'] . " - " . $rsMandays[0]['service_type'] . " - ";
        $mysql = sprintf(
            "INSERT INTO `sa_trx_addon`(
                `project_id`,
                `addon_title`,
                `addon_price`,
                `service_type`,
                `modified_by`,
                `modified_date`
            )
            VALUES(
                %s,
                %s,
                %s,
                %s,
                %s,
                %s
            )",
            GetSQLValueString($rsMandays[0]['project_id'], "int"),
            GetSQLValueString($rsMandays[0]['addon_title'], "text"),
            GetSQLValueString($rsMandays[0]['addon_price'], "double"),
            GetSQLValueString($rsMandays[0]['service_type'], "int"),
            GetSQLValueString($rsMandays[0]['modified_by']!=NULL ? $rsMandays[0]['modified_by'] : "Migration", "text"),
            GetSQLValueString($rsMandays[0]['modified_date'], "date"),
        );
        $rs = $DBSB->get_sql($mysql, false);
        $txt .= "Successed\r\n";
        echo "Successed<br/>";
        fwrite($myfile, $txt);
        $i++;
    } while($rsMandays[0]=$rsMandays[1]->fetch_assoc());
}
echo "<br/><br/>";


echo "Migration Mandays data to Warranty Price for Implementation <br/>";
fwrite($myfile, "Migration Mandays data to Warranty Price for Implementation <br/>");
$mysql = "SELECT
`project_id`,
`brand` AS `addon_title`,
`mandays` AS `addon_price`,
IF(
    LEFT(`resource_level`, 1) = 1 AND `service_type` = 2,
    4,
    IF(
        LEFT(`resource_level`, 1) = 2 AND `service_type` = 2,
        5,
        IF(
            LEFT(`resource_level`, 1) = 1 AND `service_type` = 3,
            6,
            IF(
                LEFT(`resource_level`, 1) = 2 AND `service_type` = 3,
                7,
                8
            )
        )
    )
) AS `service_type`,
`modified_by`,
`modified_date`
FROM
`sa_trx_project_mandays`
WHERE
`service_type` > 1 AND `mandays` > 0 AND
    IF(
    LEFT(`resource_level`, 1) = 1 AND `service_type` = 2,
    4,
    IF(
        LEFT(`resource_level`, 1) = 2 AND `service_type` = 2,
        5,
        IF(
            LEFT(`resource_level`, 1) = 1 AND `service_type` = 3,
            6,
            IF(
                LEFT(`resource_level`, 1) = 2 AND `service_type` = 3,
                7,
                8
            )
        )
    )
) = 8
ORDER BY
`project_id`;";
$rsWPrice = $DBSB->get_sql($mysql);
echo "Total records : " . $rsWPrice[2] . "<br/><br/>";

if($rsWPrice[2]>0)
{
    $i = 1;
    do {
        $txt = $i . ". " . $rsWPrice[0]['project_id'] . " - " . $rsWPrice[0]['addon_price'] . " - ";
        echo $txt;
        $mysql = sprintf(
            "UPDATE
            `sa_trx_project_implementations`
        SET
            `implementation_price` = %s,
            `implementation_price_list` = 0
        WHERE
            `project_id` = %s AND `tos_id` = 9 AND `service_type` = 3",
            GetSQLValueString($rsWPrice[0]['addon_price'], "double"),
            GetSQLValueString($rsWPrice[0]['project_id'], "int")
        );
        $rs = $DBSB->get_sql($mysql, false);
        $txt .= "Successed\r\n";
        echo "Successed<br/>";
        fwrite($myfile, $txt);
        $i++;
    } while($rsWPrice[0]=$rsWPrice[1]->fetch_assoc());
}

fclose($myfile);

?>