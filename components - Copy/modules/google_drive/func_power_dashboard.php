<?php 

function getProjectWrike() {
    $modulename = 'POWER_DASHBOARD';
    $DBPW = get_conn($modulename);
    $modulename = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($modulename);

    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/IEAEOPF5I4U6PGE7/folders?project=true&fields=['customFields']");
    //&updatedDate={'start':'" . date('Y-m-d', strtotime('-1 days')) . "T00:00:00Z','end':'" . date('Y-m-d', strtotime('-1 days')) . "T23:59:59Z'}
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result2 = json_decode($result, true);

    //GET Customer Name
    $result1 = $result2['data'];

    for ($i = 0; $i < count($result1); $i++) {
        $id = $result1[$i]['id'];
        $title = $result1[$i]['title'];
        $customStatus = $result1[$i]['project']['customStatusId'];
        $createdDate = $result1[$i]['createdDate'];
        $permalink = $result1[$i]['permalink'];
        $ownerId = $result1[$i]['project']['ownerIds'];
        $updatedDate = $result1[$i]['updatedDate'];
        $cf = $result1[$i]['customFields'];

        for ($j = 0; $j < count($cf); $j++) {
            $id = $cf[$j]["id"];
            $value = $cf[$j]["value"];

            if (strpos($id, 'IEAEOPF5JUACAVJN') !== false) {
                $projectCode = $value;
            }
        }

        //Lookup Project Status
        $queryLookup = "SELECT * FROM sa_wrike_config WHERE object = 'CustomStatus' and object_id = '$customStatus'";
        $dataLookup = $DBWR->get_sql($queryLookup);
        $customStatusName = $dataLookup[0]['title'];

        echo "$id - $title - $projectCode - $customStatusName<br/>";

        
    }
}