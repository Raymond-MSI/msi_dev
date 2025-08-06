<?php
// global $DBMENU;
// include_once('components/classes/func_databases_v3.php');
$tblname = "cfg_menus";
if(isset($_POST['update'])) { 
    $condition = "id=" . $_POST['id'];
    $update = sprintf("`title`=%s,`link`=%s,`fontawesome`=%s,`published`=%s,`parent`=%s,`ordering`=%s,`params`=%s",
        GetSQLValueString($_POST['title'], "text"),
        GetSQLValueString($_POST['link'], "text"),
        GetSQLValueString($_POST['fontawesome'], "text"),
        GetSQLValueString($_POST['published'], "int"),
        GetSQLValueString($_POST['parent'], "int"),
        GetSQLValueString($_POST['ordering'], "int"),
        GetSQLValueString($_POST['params'], "text")
    );

    $res = $DBMENU->update_data($tblname, $update, $condition);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Horeee!</strong> Data has been successfully update.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif(isset($_POST['insert'])) {
    $insert = sprintf("(`title`, `link`, `fontawesome`, `published`, `parent`, `ordering`, `params`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['title'], "text"),
        GetSQLValueString($_POST['link'], "text"),
        GetSQLValueString($_POST['fontawesome'], "text"),
        GetSQLValueString($_POST['published'], "int"),
        GetSQLValueString($_POST['parent'], "int"),
        GetSQLValueString($_POST['ordering'], "int"),
        GetSQLValueString($_POST['params'], "text")
    );
    $res = $DBMENU->insert_data($tblname, $insert);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Horeee!</strong> Data has been successfully added.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>