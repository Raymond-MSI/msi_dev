<?php
    if($_GET['act']=='edit') {
        global $DBWH;
        $condition = "idbrg=" . $_GET['idbrg'];
        $data = $DBWH->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Idbrg</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="idbrg" name="idbrg" value="<?php if($_GET['act']=='edit') { echo $ddata['idbrg']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kdproduk</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="kdproduk" name="kdproduk" value="<?php if($_GET['act']=='edit') { echo $ddata['kdproduk']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Namabrg</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="namabrg" name="namabrg" value="<?php if($_GET['act']=='edit') { echo $ddata['namabrg']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="jumlah" name="jumlah" value="<?php if($_GET['act']=='edit') { echo $ddata['jumlah']; } ?>">
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>
    