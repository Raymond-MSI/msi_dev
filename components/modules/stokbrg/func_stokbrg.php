<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`idbrg`,`kdproduk`,`namabrg`,`jumlah`) VALUES (%s,%s,%s,%s)",
            GetSQLValueString($_POST['idbrg'], "int"),
            GetSQLValueString($_POST['kdproduk'], "text"),
            GetSQLValueString($_POST['namabrg'], "text"),
            GetSQLValueString($_POST['jumlah'], "int")
        );
        $res = $DBWH->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "idbrg=" . $_POST['idbrg'];
        $update = sprintf("`idbrg`=%s,`kdproduk`=%s,`namabrg`=%s,`jumlah`=%s",
            GetSQLValueString($_POST['idbrg'], "int"),
            GetSQLValueString($_POST['kdproduk'], "text"),
            GetSQLValueString($_POST['namabrg'], "text"),
            GetSQLValueString($_POST['jumlah'], "int")
    );
        $res = $DBWH->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>