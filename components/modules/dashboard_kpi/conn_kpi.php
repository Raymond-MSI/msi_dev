<!-- Chart with crosshair -->
<?php
// $dataPoints = array(
//     array("label" => "KP1", "y" => 25.1),
//     array("label" => "KP2", "y" => 29.1),
//     array("label" => 1999, "y" => 36),
//     array("label" => 2000, "y" => 28.9),
//     array("label" => 2001, "y" => 32.7),
//     array("label" => 2002, "y" => 25.8),
//     array("label" => 2003, "y" => 30),
//     array("label" => 2004, "y" => 33.9),
//     array("label" => 2005, "y" => 40),
//     array("label" => 2006, "y" => 30.2),
//     array("label" => 2007, "y" => 40.5),
//     array("label" => 2008, "y" => 89.2),
//     array("label" => 2009, "y" => 91.4),
//     array("label" => 2010, "y" => 35.9),
//     array("label" => 2011, "y" => 35.9),
//     array("label" => 2012, "y" => 28.5),
//     array("label" => 2013, "y" => 48.4),
//     array("label" => 2014, "y" => 32.4),
//     array("label" => 2015, "y" => 48.3),
//     array("label" => 2016, "y" => 25)
// );

?>
<!-- <!DOCTYPE HTML>
<html>

<head>
    <script>
        window.onload = function() {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                //theme: "light2",
                title: {
                    text: "Data KPI per Project tahun ini"
                },
                axisX: {
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "in Metric Tons",
                    includeZero: true,
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html> -->
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- STACKED BAR CHART -->
<?php

// $test = array(
//     array("label" => "Sachin Tendulkar", "y" => 51),
//     array("label" => "Ricky Ponting", "y" => 41),
//     array("label" => "Kumar Sangakkara", "y" => 38),
//     array("label" => "Jacques Kallis", "y" => 45),
//     array("label" => "Mahela Jayawardene", "y" => 34),
//     array("label" => "Hashim Amla", "y" => 28),
//     array("label" => "Brian Lara", "y" => 34),
//     array("label" => "Virat Kohli", "y" => 20),
//     array("label" => "Rahul Dravid", "y" => 36),
//     array("label" => "AB de Villiers", "y" => 21)
// );

// $odi = array(
//     array("label" => "Sachin Tendulkar", "y" => 49),
//     array("label" => "Ricky Ponting", "y" => 30),
//     array("label" => "Kumar Sangakkara", "y" => 25),
//     array("label" => "Jacques Kallis", "y" => 17),
//     array("label" => "Mahela Jayawardene", "y" => 19),
//     array("label" => "Hashim Amla", "y" => 26),
//     array("label" => "Brian Lara", "y" => 19),
//     array("label" => "Virat Kohli", "y" => 32),
//     array("label" => "Rahul Dravid", "y" => 12),
//     array("label" => "AB de Villiers", "y" => 25)
// );

?>
<!-- <!DOCTYPE HTML>
<html>

<head>
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "KPI Engineer This Year"
                },
                axisX: {
                    reversed: true
                },
                axisY: {
                    includeZero: true
                },
                toolTip: {
                    shared: true
                },
                data: [{
                    type: "stackedBar",
                    name: "Project",
                    dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "stackedBar",
                    name: "Personal Assignment",
                    indexLabel: "#total",
                    indexLabelPlacement: "outside",
                    indexLabelFontSize: 15,
                    indexLabelFontWeight: "bold",
                    dataPoints: <?php echo json_encode($odi, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html> -->
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<?php
if ($_GET['act'] == 'test') {
    global $DBKPI;
    $get_data = $DBKPI->get_sql("SELECT Nama,nilai_akhir_aktual,project_code FROM sa_user");
    do {
        $nama = $get_data[0]['Nama'];
        $nilai = $get_data[0]['nilai_akhir_aktual'];
        $kp = $get_data[0]['project_code'];
        $idaja = str_replace("&20", " ", $nama);
        $id = preg_replace("/[']/", "", $idaja);
        $idorang = str_replace("[_]", " ", $id);
        $hobi = explode("<", $idorang);
?>
        <form name="form" action="" method="post">
            <input type="text" id="nama[]" name="nama[]" value="<?php echo $hobi[0] . "($kp)"; ?>">
            <input type="text" id="nilai[]" name="nilai[]" value="<?php echo $nilai; ?>">
        <?php
        // echo $nama . "(" . $kp . ") = " . $nilai . "<br>";
    } while ($get_data[0] = $get_data[1]->fetch_assoc());
        ?>
        <button type="submit" name="ok">OK</button>
        </form>
    <?php
}

if (isset($_POST['ok'])) {
    $combine_arr = array();
    for ($i = 0; $i < count($_POST['nama']); $i++) {
        $combine_arr[] = array($_POST['nama'][$i], $_POST['nilai'][$i]);
        $combine_arr_sn[] = array($_POST['nama'][$i]);
        $combine_arr_pn[] = array($_POST['nilai'][$i]);
    }

    foreach ($combine_arr as $value) {
        $insert_sql = sprintf(
            "(`nama`,`nilai`) VALUES (%s,%s)",
            GetSQLValueString($value[0], "text"),
            GetSQLValueString($value[1], "text")
        );
    }
    if ($value[0] == null) {
        $sn == null;
        $pn == null;
    } else {
        $sn = json_encode($combine_arr_sn);
        $pn = json_encode($combine_arr_pn);
    }
}

// echo json_encode($sn);
$check = json_decode($sn);
$check2 = json_decode($pn);
$apa = json_encode($check2);
$t = preg_replace("/\D/", "", $apa);
$av = explode(".", $apa);
$tt = preg_replace("/\D/", "", $av[0]);
$p = json_encode($check[0]);
$o = preg_replace("/[^a-zA-Z]/", "", $p);


$odi = array(
    array("label" => json_encode($check[0]), "y" => $tt),
    array("label" => json_encode($check[1]), "y" => 30),
    array("label" => json_encode($check[2]), "y" => 25),
    array("label" => json_encode($check[3]), "y" => 17),
    array("label" => json_encode($check[4]), "y" => 19),
    array("label" => json_encode($check[5]), "y" => 26),
    array("label" => json_encode($check[6]), "y" => 19),
    array("label" => json_encode($check[7]), "y" => 32),
    array("label" => json_encode($check[8]), "y" => 12),
    array("label" => json_encode($check[9]), "y" => 25),
    array("label" => json_encode($check[10]), "y" => 25)
);
    ?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <script>
            window.onload = function() {

                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "dark1", // "light1", "light2", "dark1", "dark2"
                    title: {
                        text: "KPI Engineer This Year"
                    },
                    axisX: {
                        reversed: true
                    },
                    axisY: {
                        includeZero: true
                    },
                    toolTip: {
                        shared: true
                    },
                    data: [{
                        type: "stackedBar",
                        name: "Nilai Akhir Aktual",
                        indexLabel: "#total",
                        indexLabelPlacement: "outside",
                        indexLabelFontSize: 10,
                        indexLabelFontWeight: "bold",
                        dataPoints: <?php echo json_encode($odi, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();

            }
        </script>
    </head>

    <body>
        <div id="chartContainer" style="height: 480px; width: 100%;"></div>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </body>

    </html>