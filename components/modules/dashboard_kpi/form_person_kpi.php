<?php
// include 'conn2.php';
global $DBKPI;
$nama = $_GET['nama'];
$idaja = str_replace("&20", " ", $nama);
$id = preg_replace("/[']/", "", $idaja);
$idorang = str_replace("[_]", " ", $id);
$hobi = explode("<", $idorang);
?>
<html>

<head>
    <title>Export to Excel</title>
</head>

<body>
    <label>Export :</label>
    <a href="export.php?nama='<?php echo $hobi[0]; ?>'"><button>Export to Excel</button></a><br />
    <hr />
    <?php
    include 'data_orang.php';
    ?>