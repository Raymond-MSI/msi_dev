<form name="form" method="post" action="index.php?mod=menu">
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-lg-6">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">ID</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="id" value="<?php if($act=='edit') { echo $dmenu['id']; } ?>" name="id" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Menu Name</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="title" value="<?php if($act=='edit') { echo $dmenu['title']; } ?>" name="title">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Parent</label>
                    <div class="col-sm-3">
                        <?php 
                            $tblname = "cfg_menus";
                            $condition = "parent=5 OR parent=1 OR parent=0 OR parent=47";
                            $order = "parent ASC, title ASC";
                            $menulist = $DBMENU->get_data($tblname, $condition, $order);
                            $dmenulist = $menulist[0];
                            $qmenulist = $menulist[1];
                            $tmenulist = $menulist[2];
                        ?>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="parent">
                            <?php do { ?>
                                <option value="<?php echo $dmenulist['id']; ?>" <?php if($act=='edit') { if($dmenulist['id'] == $dmenu['parent']) { echo 'selected'; }} ?>>
                                    <?php echo $dmenulist['title']; ?>
                                </option>
                            <?php } while($dmenulist=$qmenulist->fetch_assoc()); ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Link</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control form-control-sm" id="link" value="<?php if($act=='edit') { echo $dmenu['link']; } ?>" name="link">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Font Awesome</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="fontawesome" value="<?php if($act=='edit') { echo $dmenu['fontawesome']; } ?>" name="fontawesome">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Order</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="ordering" value="<?php if($act=='edit') { echo $dmenu['ordering']; } ?>" name="ordering">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Published</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="published" value="<?php if($act=='edit') { echo $dmenu['published']; } ?>" name="published">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Params</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm" id="params" value="<?php if($act=='edit') { echo $dmenu['params']; } ?>" name="params">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
<?php if($act=='edit') { ?>
    <input type="submit" class="btn btn-primary" name="update" value="Save">
<?php } elseif($act=='add') { ?>
    <input type="submit" class="btn btn-primary" name="insert" value="Save">
<?php } ?>
</form>