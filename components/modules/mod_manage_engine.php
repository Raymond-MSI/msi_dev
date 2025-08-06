<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';

    $properties = '
    {
        "version": 1,
        "author": "Syamsul Arham",
        "history":
        {
            "log#1":
            {
                "modified_date": "09/09/2022",
                "modified_by":
                {
                    "id": 00000000,
                    "name": "Syamsul Arham",
                    "email": "syamsul@mastersyetm.co.id"
                },
                "description": "xxxxxxxxxxxxxxxxxxxx"
            }
        }
    }';
} else {
    include("components/modules/" . $_GET['mod'] . "/" . $_GET['sub'] . ".php");
}
?>