<?php
if ($_GET['act'] == 'edit') {
    global $DBHCM;
    $condition = "id=" . $_GET['id'];
    $data = $DBHCM->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}

?>

<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['act']) && $_GET['act'] == 'edit') {
                                                        echo htmlspecialchars($ddata['id']);
                                                    } ?>">
    <div id="holiday-group">
        <?php if ($_GET['act'] == 'edit') { ?>
            <!-- Edit mode: single row -->
            <div class="row">
                <div class="col-lg-6">

                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Date</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" id="holiday_date" name="holiday_date" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                        echo $ddata['holiday_date'];
                                                                                                                                    } ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Descriptions</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="Descriptions" name="Descriptions" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                        echo $ddata['Descriptions'];
                                                                                                                                    } ?>">
                        </div>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <!-- Add mode: allow multiple inputs -->
            <div class="row mb-3 holiday-row">
                <div class="col-lg-5">
                    <input type="date" class="form-control form-control-sm" name="holiday_date[]">
                </div>
                <div class="col-lg-5">
                    <input type="text" class="form-control form-control-sm" name="Descriptions[]" placeholder="Description">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if ($_GET['act'] == 'add') { ?>
        <button type="button" class="btn btn-secondary " id="add-holiday">Add More</button>
    <?php } ?>


    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'del') { ?>
        <input type="submit" class="btn btn-primary" name="del" value="Save">
    <?php } ?>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addBtn = document.getElementById("add-holiday");
        const group = document.getElementById("holiday-group");

        if (addBtn) {
            addBtn.addEventListener("click", function() {
                const row = document.querySelector(".holiday-row").cloneNode(true);
                row.querySelectorAll("input").forEach(input => input.value = "");
                group.appendChild(row);
            });
        }

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-btn")) {
                const rows = document.querySelectorAll(".holiday-row");
                if (rows.length > 1) {
                    e.target.closest(".holiday-row").remove();
                }
            }
        });
    });



</script>