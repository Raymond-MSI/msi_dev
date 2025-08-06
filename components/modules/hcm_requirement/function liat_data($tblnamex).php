function liat_data($tblnamex)
    {
        global $DBHCM;
        $tblnamex = "hcm_requirement";
        $condition = "status_request = 'Pending Approval'";
        $employee_info = $DBHCM->get_data($tblnamex, $condition);

    ?>
        <div class="table-responsive">
            <table class="table" id="tblApproval">
                <thead class="text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle">
                    <tr>
                        <th class="align-middle text-center" rowspan="2"><input class="selectAll chk_boxes1" type="checkbox"></th>
                        <th class="align-middle" rowspan="2">Request Info</th>
                        <th class="align-middle" rowspan="2">Requirement</th>
                        <th class="align-middle" rowspan="2">Background</th>
                        <th class="align-middle" rowspan="2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($employee_info[2] > 0) {
                        $i = 0;
                        do {
                    ?>
                            <tr>
                                <td>
                                    <div class="form-check text-center">
                                        <input class="form-check-input chk_boxes1" type="checkbox" name="select[]" id="select[]" value="<?php echo $employee_info[0]['id']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <?php echo $employee_info[0]['id_fpkb']; ?>
                                    <span style="font-size:12px">
                                        [<?php echo $employee_info[0]['divisi']; ?>]<br />
                                        <span class="text-nowrap">Posisi : <?php echo $employee_info[0]['posisi']; ?> | </span>
                                        <span class="text-nowrap">Jumlah Dibutuhkan : <?php echo $employee_info[0]['jumlah_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Tanggal Dibutuhkan : <?php echo $employee_info[0]['tanggal_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Deskripsi : <?php echo $employee_info[0]['deskripsi']; ?></span>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $employee_info[0]['id_fpkb']; ?>
                                    <span style="font-size:12px">
                                        [<?php echo $employee_info[0]['divisi']; ?>]<br />
                                        <span class="text-nowrap">Posisi : <?php echo $employee_info[0]['posisi']; ?> | </span>
                                        <span class="text-nowrap">Jumlah Dibutuhkan : <?php echo $employee_info[0]['jumlah_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Tanggal Dibutuhkan : <?php echo $employee_info[0]['tanggal_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Deskripsi : <?php echo $employee_info[0]['deskripsi']; ?></span>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $employee_info[0]['id_fpkb']; ?>
                                    <span style="font-size:12px">
                                        [<?php echo $employee_info[0]['divisi']; ?>]<br />
                                        <span class="text-nowrap">Posisi : <?php echo $employee_info[0]['posisi']; ?> | </span>
                                        <span class="text-nowrap">Jumlah Dibutuhkan : <?php echo $employee_info[0]['jumlah_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Tanggal Dibutuhkan : <?php echo $employee_info[0]['tanggal_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Deskripsi : <?php echo $employee_info[0]['deskripsi']; ?></span>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $employee_info[0]['id_fpkb']; ?>
                                    <span style="font-size:12px">
                                        [<?php echo $employee_info[0]['divisi']; ?>]<br />
                                        <span class="text-nowrap">Posisi : <?php echo $employee_info[0]['posisi']; ?> | </span>
                                        <span class="text-nowrap">Jumlah Dibutuhkan : <?php echo $employee_info[0]['jumlah_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Tanggal Dibutuhkan : <?php echo $employee_info[0]['tanggal_dibutuhkan']; ?> | </span>
                                        <span class="text-nowrap">Deskripsi : <?php echo $employee_info[0]['deskripsi']; ?></span>
                                    </span>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } while ($employee_info[0] = $employee_info[1]->fetch_assoc());
                    } else {
                        ?>
                        <tr>
                            <td colspan="8">Data not ready</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle">
                        <th class="align-middle text-center"><input class="selectAll chk_boxes1" type="checkbox"></th>
                        <th>Request Info</th>
                        <th class="align-middle text-center">Requirement</th>
                        <th class="align-middle text-center">Background</th>
                        <th class="align-middle text-center">Status</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php
    }