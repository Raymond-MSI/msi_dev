<?php
if(isset($_POST['btn_save']))
{
    $tblname = "cfg_menus";
    if($_POST['id']=='')
    {
        $insert = sprintf("(`title`, `link`, `fontawesome`, `published`, `parent`, `ordering`, `params`) VALUES (%s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString($_POST['title'], "text"),
            GetSQLValueString($_POST['link'], "text"),
            GetSQLValueString($_POST['fontawesome'], "text"),
            GetSQLValueString(isset($_POST['published']) ? "1" : "0", "text"),
            GetSQLValueString($_POST['parent'], "text"),
            GetSQLValueString($_POST['ordering'], "text"),
            GetSQLValueString($_POST['params'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
    } else
    {
        $update = sprintf("`title`=%s,`link`=%s,`fontawesome`=%s,`published`=%s,`parent`=%s,`ordering`=%s,`params`=%s",
            GetSQLValueString($_POST['title'], "text"),
            GetSQLValueString($_POST['link'], "text"),
            GetSQLValueString($_POST['fontawesome'], "text"),
            GetSQLValueString(isset($_POST['published']) ? "1" : "0", "text"),
            GetSQLValueString($_POST['parent'], "text"),
            GetSQLValueString($_POST['ordering'], "text"),
            GetSQLValueString($_POST['params'], "text")
        );
        $condition = "id=" . $_POST['id'];
        $res = $DB->update_data($tblname, $update, $condition);
    }
}
?>

<div class="card shadow">
    <div class="card-header fw-bold mb-3 d-flex flex-row align-items-center justify-content-between">
        Menu Management
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">Menu Properties:</div>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mdlProperties">Properties</a>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mdlHistory">History</a>
                <!-- <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a> -->
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="border-bottom-danger mb-3 d-flex flex-row align-items-center justify-content-between">
            <div class="fs-3 align-items-left">
                Users <span style="font-size:11px">All users can be access.</span>
            </div>
            <div class="align-items-right">
                <i class="fa-solid fa-plus btn btn-light" onclick="showModal('','','','','1','5','','', 'Add Menu Groups');"></i>
            </div>
        </div>

        <?php
        $condition = 'parent=5 AND published=1';
        $order = 'ordering ASC, title ASC';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        if($menusidebar[2]>0)
        {
            ?>
            <div class="row">
                <?php do { ?>
                    <div class="col-lg-3 mb-3">
                        <div class="card text-light-emphasis bg-light-subtle">
                            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                                <div class="align-items-left fw-bold">
                                    <i class="fa-solid <?php echo $menusidebar[0]['fontawesome']; ?>"></i>
                                    <?php echo $menusidebar[0]['title']; ?>
                                </div>
                                <div class="align-items-right">
                                    <i class=" fa-solid fa-plus btn btn-light btn-sm" onclick="showModal('','','','','<?php echo $menusidebar[0]['published']; ?>','<?php echo $menusidebar[0]['id']; ?>','','','Add Menu');"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                $condition = 'parent=' . $menusidebar[0]['id'] . ' AND published=1';
                                $tblname = 'cfg_menus';
                                $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                                if($menusubsidebar[2]>0)
                                {
                                    do {
                                    ?>
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        <li>
                                            <a class="collapse-item text-decoration-none text-reset" href="<?php echo $menusubsidebar[0]['link']; ?>" target="<?php echo $menusubsidebar[0]['params']; ?>">
                                                <?php echo $menusubsidebar[0]['title']; ?>
                                            </a>
                                        </li>
                                        <div class="align-items-right" style="font-size:11px">
                                            <i class="fa-solid fa-pen" onclick="showModal(<?php echo $menusubsidebar[0]['id']; ?>,'<?php echo $menusubsidebar[0]['title']; ?>','<?php echo $menusubsidebar[0]['link']; ?>','<?php echo $menusubsidebar[0]['fontawesome']; ?>','<?php echo $menusubsidebar[0]['published']; ?>','<?php echo $menusubsidebar[0]['parent']; ?>','<?php echo $menusubsidebar[0]['ordering']; ?>','<?php echo $menusubsidebar[0]['params']; ?>','Edit Menu');"></i>
                                        </div>
                                    </div>
                                    <?php
                                    } while($menusubsidebar[0]=$menusubsidebar[1]->fetch_assoc());
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                <?php } while($menusidebar[0]=$menusidebar[1]->fetch_assoc()); ?>
            </div>
            <?php
        }
        ?>


        <div class="border-bottom-danger mb-3 d-flex flex-row align-items-center justify-content-between">
            <div class="fs-3 align-items-left">
                Admin Users <span style="font-size:11px">Only for Admin User and Administrator.</span>
            </div>
            <div class="align-items-right">
                <i class=" fa-solid fa-plus btn btn-light btn-sm" onclick="showModal('','','','','1','77','','','Add Menu Groups');"></i>
            </div>
        </div>
        <?php
        $condition = 'parent=77 AND published=1';
        $order = 'ordering ASC, title ASC';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        if($menusidebar[2]>0)
        {
            ?>
            <div class="row">
                <?php do { ?>
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                                <div class="align-items-left fw-bold">
                                    <i class="fa-solid <?php echo $menusidebar[0]['fontawesome']; ?>"></i>
                                    <?php echo $menusidebar[0]['title']; ?>
                                </div>
                                <div class="align-items-right">
                                    <i class=" fa-solid fa-plus btn btn-light btn-sm" onclick="showModal('','','','','<?php echo $menusidebar[0]['published']; ?>','<?php echo $menusidebar[0]['id']; ?>','','','Add Menu');"></i>
                                </div>
                            </div>
                            <div class="card-body text-light-emphasis bg-light-subtle">
                                <?php
                                $condition = 'parent=' . $menusidebar[0]['id'] . ' AND published=1';
                                $tblname = 'cfg_menus';
                                $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                                if($menusubsidebar[2]>0)
                                {
                                    do {
                                    ?>
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        <li>
                                            <a class="collapse-item text-decoration-none text-reset" href="<?php echo $menusubsidebar[0]['link']; ?>" target="<?php echo $menusubsidebar[0]['params']; ?>">
                                                <?php echo $menusubsidebar[0]['title']; ?>
                                            </a>
                                        </li>
                                        <div class="align-items-right" style="font-size:11px">
                                            <i class="fa-solid fa-pen" onclick="showModal(<?php echo $menusubsidebar[0]['id']; ?>,'<?php echo $menusubsidebar[0]['title']; ?>','<?php echo $menusubsidebar[0]['link']; ?>','<?php echo $menusubsidebar[0]['fontawesome']; ?>','<?php echo $menusubsidebar[0]['published']; ?>','<?php echo $menusubsidebar[0]['parent']; ?>','<?php echo $menusubsidebar[0]['ordering']; ?>','<?php echo $menusubsidebar[0]['params']; ?>','Edit Menu');"></i>
                                        </div>
                                    </div>
                                    <?php
                                    } while($menusubsidebar[0]=$menusubsidebar[1]->fetch_assoc());
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                <?php } while($menusidebar[0]=$menusidebar[1]->fetch_assoc()); ?>
            </div>
            <?php
        }
        ?>

        <div class="border-bottom-danger mb-3 d-flex flex-row align-items-center justify-content-between">
            <div class="fs-3 align-items-left">
                Administrator <span style="font-size:11px">Only for administrator.</span>
            </div>
            <div class="align-items-right">
                <i class=" fa-solid fa-plus btn btn-light btn-sm" onclick="showModal('','','','','1','47','','','Add Menu Groups');"></i>
            </div>
        </div>
        <?php
        $condition = 'parent=47 AND published=1';
        $order = 'ordering ASC, title ASC';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        if($menusidebar[2]>0)
        {
            ?>
            <div class="row">
                <?php do { ?>
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                                <div class="align-items-left fw-bold">
                                    <i class="fa-solid <?php echo $menusidebar[0]['fontawesome']; ?>"></i>
                                    <?php echo $menusidebar[0]['title']; ?>
                                </div>
                                <div class="align-items-right">
                                    <i class=" fa-solid fa-plus btn btn-light btn-sm" onclick="showModal('','','','','<?php echo $menusidebar[0]['published']; ?>','<?php echo $menusidebar[0]['id']; ?>','','','Add Menu');"></i>
                                </div>
                            </div>
                            <div class="card-body text-light-emphasis bg-light-subtle">
                                <?php
                                $condition = 'parent=' . $menusidebar[0]['id'] . ' AND published=1';
                                $tblname = 'cfg_menus';
                                $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                                if($menusubsidebar[2]>0)
                                {
                                    do {
                                    ?>
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        <li>
                                            <a class="collapse-item text-decoration-none text-reset" href="<?php echo $menusubsidebar[0]['link']; ?>" target="<?php echo $menusubsidebar[0]['params']; ?>">
                                                <?php echo $menusubsidebar[0]['title']; ?>
                                            </a>
                                        </li>
                                        <div class="align-items-right" style="font-size:11px">
                                            <i class="fa-solid fa-pen" onclick="showModal(<?php echo $menusubsidebar[0]['id']; ?>,'<?php echo $menusubsidebar[0]['title']; ?>','<?php echo $menusubsidebar[0]['link']; ?>','<?php echo $menusubsidebar[0]['fontawesome']; ?>','<?php echo $menusubsidebar[0]['published']; ?>','<?php echo $menusubsidebar[0]['parent']; ?>','<?php echo $menusubsidebar[0]['ordering']; ?>','<?php echo $menusubsidebar[0]['params']; ?>','Edit Menu');"></i>
                                        </div>
                                    </div>
                                    <?php
                                    } while($menusubsidebar[0]=$menusubsidebar[1]->fetch_assoc());
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                <?php } while($menusidebar[0]=$menusidebar[1]->fetch_assoc()); ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="card-footer text-right" style="font-size:11px">
        Menu Version 2.0.0
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><span id="modal_title">Menu Setup</span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="form" method="post" action="">
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">ID</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="id" name="id" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Menu Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="title" name="title">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Parent</label>
                                        <div class="col-sm-8">
                                            <?php 
                                                $tblname = "cfg_menus";
                                                $condition = "parent=5 OR parent=1 OR parent=0 OR parent=47 OR parent=77";
                                                $order = "parent ASC, title ASC";
                                                $menulist = $DB->get_data($tblname, $condition, $order);
                                            ?>
                                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="parent" id="parent">
                                                <?php do { ?>
                                                    <option value="<?php echo $menulist[0]['id']; ?>" <?php if(isset($_GET['act']) && $_GET['act']=='edit') { if($menulist[0]['id'] == $menu[0]['parent']) { echo 'selected'; }} ?> >
                                                        <?php echo $menulist[0]['title']; ?>
                                                    </option>
                                                <?php } while($menulist[0]=$menulist[1]->fetch_assoc()); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Link</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="link" name="link">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Font Awesome</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="fontawesome" name="fontawesome">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Order</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm" id="ordering" name="ordering">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Published</label>
                                        <div class="col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="published" name="published">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Params</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control form-control-sm" id="params" name="params"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="btn_save" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showModal(id="", title="", link="", fontawesome="", publish=0, parent="", ordering="", note="", groupTitle="")
    {
        if(publish==1)
        {
            check = true
        }
        document.getElementById("id").value = id;
        document.getElementById("title").value = title;
        document.getElementById("parent").value = parent;
        document.getElementById("link").value = link;
        document.getElementById("fontawesome").value = fontawesome;
        document.getElementById("ordering").value = ordering;
        document.getElementById("published").checked = check;
        document.getElementById("params").value = note;
        document.getElementById("modal_title").innerHTML = groupTitle;
        $("#exampleModal").modal("show");
    }
</script>

<!-- Modal -->
<?php 
$mdlname = "MENU";
$mdlcategory = "Module";
include("components/classes/func_mod_history.php"); 
include("components/classes/func_mod_properties.php"); 
?>