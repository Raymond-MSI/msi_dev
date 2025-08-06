<?php

if ($_GET['act'] == 'add') {
    global $DBPCMT;
    global $DBSB;

    $DBSB = get_conn("SERVICE_BUDGET");

    $DBWRIKE = get_conn("WRIKE_INTEGRATE");

    $get_kp = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE bundling not like '%0;0;0%' AND project_code IS NOT NULL AND so_number IS NOT NULL ORDER BY project_code ASC");
    $get_ordernumber = $DBSB->get_sql(" SELECT * FROM sa_trx_project_list WHERE order_number IS NOT NULL ORDER BY order_number ASC");
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#Assignment" type="button" role="tab" aria-controls="Assignment" aria-selected="true">Assignment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#mtreport" type="button" role="tab" aria-controls="mtreport" aria-selected="false">Maintenance Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#History" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="Assignment" role="tabpanel" aria-labelledby="assignment-tab">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="mb-3">Information Project</h3>

                                <!-- Row untuk dua kolom -->
                                <div class="row">
                                    <!-- 🔹 KOLOM KIRI: Daftar Project Code (Radio Button) -->
                                    <div class="col-lg-6 mb-5" style="height: auto;">
                                        <div class="card">
                                            <div class="card-header">Project Code</div>
                                            <div class="card-body d-flex flex-column p-0">
                                                <div class="overflow-auto" style="max-height: 140px; flex-grow: 1;">
                                                    <?php
                                                    $get_all_kp = $DBSB->get_sqlV2("SELECT `project_code`, `project_name` FROM `sa_trx_project_list` WHERE `status` = 'approved' OR `status` = 'acknowledge' GROUP BY `project_code`, `project_name` ORDER BY `project_id` DESC");
                                                    ?>
                                                    <div class="list-group list-group-flush">
                                                        <?php if (!isset($_GET['project_code']) || $_GET['project_code'] !== "Non-Project") { ?>
                                                            <label class="list-group-item d-flex align-items-center form-check">
                                                                <input class="form-check-input me-2" type="radio" name="projectCodeRadio" value="Non-Project">
                                                                <div style="flex-shrink: 1;">
                                                                    <strong>Non-Project</strong>
                                                                </div>
                                                            </label>
                                                        <?php } ?>

                                                        <?php
                                                        if ($get_all_kp[1] && $get_all_kp[1]->num_rows > 0) {
                                                            $get_all_kp[1]->data_seek(0);
                                                            while ($row = $get_all_kp[1]->fetch_assoc()) {
                                                                $projectCode = htmlspecialchars($row['project_code']);
                                                                $projectName = htmlspecialchars($row['project_name']);
                                                        ?>
                                                                <label class="list-group-item d-flex align-items-center form-check">
                                                                    <div>
                                                                        <input
                                                                            class="form-check-input me-2"
                                                                            type="radio"
                                                                            name="projectCodeRadio"
                                                                            value="<?php echo $projectCode; ?>"
                                                                            <?php echo (isset($_GET['project_code']) && $_GET['project_code'] == $projectCode) ? 'checked' : ''; ?>>
                                                                        <div style="flex-shrink: 1;">
                                                                            <strong><?php echo $projectCode; ?></strong><br>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <div class="list-group-item text-muted">Tidak ada data project</div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 🔹 KOLOM KANAN: Form Input (SO Number, Project Type, dll) -->
                                    <div class="col-lg-6 mb-5">
                                        <div class="card h-100 d-flex flex-column">
                                            <div class="card-body d-flex flex-column">
                                                <!-- SO Number -->
                                                <label for="orderNumber" class="col-form-label col-form-label-sm">SO Number / Order Number</label>
                                                <div class="mb-2">
                                                    <select id="orderNumber" class="form-control form-control-sm" name="orderNumber">
                                                        <option value="SO-2021-001">2021/SO/2079 (ORD-25287-S9Z1V5)</option>
                                                    </select>
                                                </div>

                                                <!-- Project Type -->
                                                <label for="projectType" class="col-form-label col-form-label-sm">Project Type</label>
                                                <div class="mb-2">
                                                    <select id="projectType" class="form-control form-control-sm" name="projectType">
                                                        <option value="Implementation">Implementation</option>
                                                        <option value="Maintenance">Maintenance</option>
                                                    </select>
                                                </div>

                                                <!-- Customer Name -->
                                                <label for="customer" class="col-form-label col-form-label-sm">Customer Name</label>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="customer" value="AK JASA RAHARJA" readonly>
                                                </div>

                                                <!-- Project Name -->
                                                <label for="projectName" class="col-form-label col-form-label-sm">Project Name</label>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" id="projectName" value="Pengadaan Pengembangan Perangkat Jaringan..." readonly>
                                                </div>

                                                <!-- Hidden Input -->
                                                <input type="hidden" id="projectId" name="projectId" value="123">

                                                <!-- Flex Spacer agar konten tidak menempel ke bawah -->
                                                <div class="mt-auto"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <h3>Resource Assignment</h3>
                                <div class="control-group after-add-more">
                                    <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm mt-3">Resource
                                        Email* (WAJIB DIISI)</label>
                                    <div class="col-sm-10 mb-2">
                                        <select id="email" class="form-control form-control-sm" name="email[]" onchange="searchTest(this.value)">
                                            <option value="#" selected="selected" disabled>--Choose Resource--</option>
                                        </select>
                                        <div class="button" id="buttonSearch"></div>
                                    </div>
                                    <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm ">Roles
                                        Project* (WAJIB DIISI)</label>
                                    <div class="col-sm-10 mb-2">
                                        <select class="form-control form-control-sm" name="projectRoles[]" id="projectRoles" required>
                                            <?php if (isset($_GET['project_code'])) {
                                                if ($_GET['project_type'] == "Implementation") {
                                            ?>
                                                    <option value="#" selected="selected" disabled>--Choose Roles--</option>
                                                    <option value="Co - Project Leader">Co - Project Leader</option>
                                                    <option value="Project Admin">Project Admin</option>
                                                    <option value="Project Leader">Project Leader</option>
                                                    <option value="Project Orchestrator">Project Orchestrator</option>
                                                    <option value="Team Member (PM)">Team Member (PM)</option>
                                                    <option value="Team Member (Technical)">Team Member (Technical)</option>
                                                    <option value="Technical Leader">Technical Leader</option>
                                                <?php
                                                } else if ($_GET['project_type'] == "Maintenance") { ?>
                                                    <option value="#" selected="selected" disabled>--Choose Roles--</option>
                                                    <option value="Project Leader">Project Leader</option>
                                                    <option value="Project Co-Leader">Project Co-Leader</option>
                                                    <option value="PIC Maintenance">PIC Maintenance</option>
                                                    <option value="Technical Expert">Technical Expert</option>
                                                    <option value="Technical Leader">Technical Leader</option>
                                                    <option value="Technical Team Member">Technical Team Member</option>
                                                    <option value="Project Admin">Project Admin</option>

                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                    <div class="col-sm-10 mb-2">
                                        <select class="form-control form-control-sm" name="status[]" id="status" onchange="onChangeStatusFunction();" required>
                                            <option value="#" selected="selected" disabled>--Choose Status--</option>
                                            <option value="Penuh">Penuh</option>
                                            <option value="Mutasi">Mutasi</option>
                                        </select>
                                    </div>
                                    <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress
                                        (in Percent %)</label>
                                    <div class="col-md-5 mb-2">
                                        <input type="number" name="startProgress[]" id="startProgress" class="form-control form-control-sm" style="display: inline-block;">
                                    </div>
                                    <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress
                                        (in Percent %)</label>
                                    <div class="col-md-5 mb-2">
                                        <input type="number" name="endProgress[]" id="endProgress" class="form-control form-control-sm" style="display: inline-block;">
                                    </div>
                                    <div class="col-sm-10 mb-2">
                                        <label for="exampleFormControlTextarea1">Description (optional)</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-10 mb-2">
                                        <button type="button" class="btn btn-success" value="Save to database" id="butsave"><i class="glyphicon glyphicon-plus"></i>Add Resource</button>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <!-- Daftar Resource Sementara -->
                                <h3 class="mb-4">Daftar resource sementara</h3>

                                <!-- Tabel -->
                                <div class="table-responsive">
                                    <table class="table table-hover" id="temporaryResourceTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Project Code</th>
                                                <th scope="col">Resource Email</th>
                                                <th scope="col">Roles</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Created In</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Baris 1 -->
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>TA010121G0001</td>
                                                <td>budi.santoso@company.com</td>
                                                <td>Engineer - Cisco</td>
                                                <td>Penuh 0% - 100%</td>
                                                <td>2025-07-29 10:30</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#konfirmasi">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Baris 2 -->
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>TA010121G0001</td>
                                                <td>rina.wijaya@company.com</td>
                                                <td>Project Leader</td>
                                                <td>Mutasi 20% - 100%</td>
                                                <td>2025-07-29 11:15</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#konfirmasi">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Baris 3 -->
                                            <tr>
                                                <th scope="row">3</th>
                                                <td>TA010121G0001</td>
                                                <td>agus.pratama@company.com</td>
                                                <td>Technical Leader</td>
                                                <td>Penuh 0% - 100%</td>
                                                <td>2025-07-29 12:00</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#konfirmasi">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Modal Konfirmasi Delete (diluar tabel) -->
                                <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="exampleModalLabel">
                                                    <b>Delete Confirmation</b>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p>Apakah yakin ingin menghapus data ini?</p>
                                                <img src="https://i.gifer.com/7yh2.gif" width="100" height="100" alt="Delete animation" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit - Dipindahkan ke kanan bawah -->
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" class="btn btn-success" id="butsave">
                                        <i class="glyphicon glyphicon-plus"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <?php
                            // Dummy data untuk ditampilkan
                            $dummyData = [
                                [
                                    'project_code' => 'PROJ-2023-001',
                                    'resource_email' => 'john.doe@company.com',
                                    'roles' => 'Developer - Backend',
                                    'status' => 'Active',
                                    'start_progress' => 30,
                                    'end_progress' => 70,
                                    'approval_status' => 'Approved',
                                    'created_in_msizone' => '2023-10-05 14:23:11'
                                ],
                                [
                                    'project_code' => 'PROJ-2023-001',
                                    'resource_email' => 'jane.smith@company.com',
                                    'roles' => 'Project Manager',
                                    'status' => 'Active',
                                    'start_progress' => 10,
                                    'end_progress' => 45,
                                    'approval_status' => 'Pending',
                                    'created_in_msizone' => '2023-10-04 09:15:33'
                                ],
                                [
                                    'project_code' => 'PROJ-2023-001',
                                    'resource_email' => 'alex.wong@company.com',
                                    'roles' => 'QA Tester - ',
                                    'status' => 'Inactive',
                                    'start_progress' => 0,
                                    'end_progress' => 0,
                                    'approval_status' => 'Rejected',
                                    'created_in_msizone' => '2023-10-03 16:40:02'
                                ]
                            ];

                            // Data untuk mode edit (bisa sama atau berbeda)
                            $dummyEditData = [
                                [
                                    'id' => 101,
                                    'project_code' => 'PROJ-EDIT-001',
                                    'resource_email' => 'sarah.connor@company.com',
                                    'roles' => 'System Analyst - ',
                                    'status' => 'On Hold',
                                    'start_progress' => 20,
                                    'end_progress' => 20,
                                    'approval_status' => 'Approved',
                                    'created_in_msizone' => '2023-11-01 10:05:44'
                                ],
                                [
                                    'id' => 102,
                                    'project_code' => 'PROJ-EDIT-001',
                                    'resource_email' => 'michael.brown@company.com',
                                    'roles' => 'UI/UX Designer - Frontend',
                                    'status' => 'Active',
                                    'start_progress' => 60,
                                    'end_progress' => 85,
                                    'approval_status' => 'Approved',
                                    'created_in_msizone' => '2023-11-02 13:11:22'
                                ]
                            ];

                            // Simulasi nilai dari $_GET
                            $_GET['act'] = $_GET['act'] ?? ''; // Default kosong jika tidak ada
                            $_GET['project_code'] = $_GET['project_code'] ?? 'PROJ-2023-001';
                            $ddata['project_code'] = 'PROJ-EDIT-001'; // Simulasi data edit
                            ?>

                            <?php if ($_GET['act'] != 'edit') { ?>
                                <!-- Mode View: Tampilkan data dummy untuk project_code -->
                                <h3 class="mb-3">Daftar resource pada <?php echo htmlspecialchars($_GET['project_code']); ?></h3>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Project Code</th>
                                            <th scope="col">Resource Email</th>
                                            <th scope="col">Roles</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Approval</th>
                                            <th scope="col">Created In</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($dummyData as $rowDataPC): ?>
                                            <?php
                                            $projectCodeTable = $rowDataPC['project_code'];
                                            $resourceEmailTable = $rowDataPC['resource_email'];
                                            $rolesTable = $rowDataPC['roles'];
                                            $statusTable = $rowDataPC['status'];
                                            $approvalStatusTable = $rowDataPC['approval_status'];
                                            $startProgress = $rowDataPC['start_progress'];
                                            $endProgress = $rowDataPC['end_progress'];
                                            $timestamp = $rowDataPC['created_in_msizone'];

                                            // Hapus bagian setelah " - " jika kosong
                                            $explodeRolesTable = explode(' - ', $rolesTable);
                                            if (isset($explodeRolesTable[1]) && $explodeRolesTable[1] === "") {
                                                $rolesTable = $explodeRolesTable[0];
                                            }
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $i; ?></th>
                                                <td><?php echo htmlspecialchars($projectCodeTable); ?></td>
                                                <td><?php echo htmlspecialchars($resourceEmailTable); ?></td>
                                                <td><?php echo htmlspecialchars($rolesTable); ?></td>
                                                <td><?php echo htmlspecialchars($statusTable) . " $startProgress% - $endProgress%"; ?></td>
                                                <td><?php echo htmlspecialchars($approvalStatusTable); ?></td>
                                                <td><?php echo htmlspecialchars($timestamp); ?></td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php } else if ($_GET['act'] == 'edit') { ?>
                                <!-- Mode Edit: Tampilkan data dummy edit -->
                                <h3 class="mb-4">Daftar resource pada <?php echo htmlspecialchars($ddata['project_code']); ?></h3>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Project Code</th>
                                            <th scope="col">Resource Email</th>
                                            <th scope="col">Roles</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Approval</th>
                                            <th scope="col">Created In</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($dummyEditData as $rowCheckTable): ?>
                                            <?php
                                            $projectCode = $rowCheckTable['project_code'];
                                            $resourceEmail = $rowCheckTable['resource_email'];
                                            $roles = $rowCheckTable['roles'];
                                            $status = $rowCheckTable['status'];
                                            $approvalStatus = $rowCheckTable['approval_status'];
                                            $startProgress = $rowCheckTable['start_progress'];
                                            $endProgress = $rowCheckTable['end_progress'];
                                            $timestamp = $rowCheckTable['created_in_msizone'];

                                            $explodeRolesTable = explode(' - ', $roles);
                                            if (isset($explodeRolesTable[1]) && $explodeRolesTable[1] === "") {
                                                $roles = $explodeRolesTable[0];
                                            }
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $i; ?></th>
                                                <td><?php echo htmlspecialchars($projectCode); ?></td>
                                                <td><?php echo htmlspecialchars($resourceEmail); ?></td>
                                                <td><?php echo htmlspecialchars($roles); ?></td>
                                                <td><?php echo htmlspecialchars($status) . " $startProgress% - $endProgress%"; ?></td>
                                                <td><?php echo htmlspecialchars($approvalStatus); ?></td>
                                                <td><?php echo htmlspecialchars($timestamp); ?></td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="mtreport" role="tabpanel" aria-labelledby="mtreport-tab">
            <div class="card shadow mb-4">
                <?php
                // === SIMULASI DUMMY DATA ===
                $project_code = $_GET['project_code'] ?? 'PROJ-MT-2023';
                $order_number = $_GET['order_number'] ?? 'ORD-12345';

                // Dummy data untuk form
                $checking = [
                    [
                        'total_mt_report_date' => 3,
                        'total_preventive_mt_date' => 2,
                        'total_backup_mt_date' => 2,
                        'total_pmr_date' => 1,
                        'ontime' => 1,
                        'ontime_notes' => 'implementation',
                        'notes' => 'No delay',
                        'gabungan' => 0,
                        'gabungan_parent' => null,
                        'openitem' => 0,
                        'notes_openitem' => '',
                        'total_ticket_allocation' => 5
                    ]
                ];

                // Dummy dates from database (simulasi $data_result_date)
                $data_result_date = [
                    'mt_date_start' => '2023-10-01',
                    'mt_date_end' => '2023-12-15',
                    'ikom' => '2023-10-10',
                    'kom' => '2023-10-20',
                    'assignment_date' => '2023-09-25',
                    'mt_report_date_1' => '2023-10-05',
                    'mt_report_date_2' => '2023-11-01',
                    'mt_report_date_3' => '2023-11-15',
                    'preventive_mt_date_1' => '2023-10-12',
                    'preventive_mt_date_2' => '2023-11-12',
                    'backup_mt_date_1' => '2023-10-03',
                    'backup_mt_date_2' => '2023-11-03',
                    'pmr_date_1' => '2023-10-07'
                ];

                // Simulasi mode: edit atau add
                $datatype = 'edit'; // Ubah jadi 'add' untuk tes mode tambah

                // Simulasi nilai lain
                $mt_date_start = $data_result_date['mt_date_start'] ?? null;
                $mt_date_end = $data_result_date['mt_date_end'] ?? null;
                $mt_date_ikom = $data_result_date['ikom'] ?? null;
                $mt_date_kom = $data_result_date['kom'] ?? null;
                $mt_date_assignment = $data_result_date['assignment_date'] ?? null;

                $tiket_reporting = $checking[0]['total_mt_report_date'];
                $tiket_preventive = $checking[0]['total_preventive_mt_date'];
                $backup_config = $checking[0]['total_backup_mt_date'];
                $total_pmr = $checking[0]['total_pmr_date'];

                // Simulasi renewal & parent
                $dataRenewal = "Bukan Project Renewal";
                $dataSub = "Bukan Sub-Project";
                ?>

                <!-- Form Maintenance Date -->
                <form method="post" action="index.php?mod=<?php echo $_GET['mod'] ?? 'maintenance'; ?>">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="addkp" role="tabpanel">
                            <div class="card shadow mb-4">
                                <div class="card-body">

                                    <!-- Bagian: Maintenance Start & End -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="mt_date_start" class="col-sm-4 col-form-label col-form-label-sm">Maintenance Start</label>
                                                <div class="col-sm-8">
                                                    <?php if ($datatype == "edit"): ?>
                                                        <input type="date" class="form-control form-control-sm" id="mt_date_start" name="date[mt_date_start]"
                                                            value="<?= htmlspecialchars($mt_date_start) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="date" class="form-control form-control-sm" id="mt_date_start" name="date[mt_date_start]" required>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="mt_date_end" class="col-sm-4 col-form-label col-form-label-sm">Maintenance End</label>
                                                <div class="col-sm-8">
                                                    <?php if ($datatype == "edit"): ?>
                                                        <input type="date" class="form-control form-control-sm" id="mt_date_end" name="date[mt_date_end]"
                                                            value="<?= htmlspecialchars($mt_date_end) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="date" class="form-control form-control-sm" id="mt_date_end" name="date[mt_date_end]" required>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="duration_actual" class="col-sm-4 col-form-label col-form-label-sm">Project Duration Actual</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control form-control-sm" id="diff_duration" value="75 Hari" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="ikom_date" class="col-sm-4 col-form-label col-form-label-sm">IKOM Date</label>
                                                <div class="col-sm-8">
                                                    <?php if ($datatype == "edit"): ?>
                                                        <input type="date" class="form-control form-control-sm" id="ikom_date" name="date[ikom]"
                                                            value="<?= htmlspecialchars($mt_date_ikom) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="date" class="form-control form-control-sm" id="ikom_date" name="date[ikom]">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="kom_date" class="col-sm-4 col-form-label col-form-label-sm">KOM Date</label>
                                                <div class="col-sm-8">
                                                    <?php if ($datatype == "edit"): ?>
                                                        <input type="date" class="form-control form-control-sm" id="kom_date" name="date[kom]"
                                                            value="<?= htmlspecialchars($mt_date_kom) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="date" class="form-control form-control-sm" id="kom_date" name="date[kom]">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="assignment_date" class="col-sm-4 col-form-label col-form-label-sm">Assignment Date</label>
                                                <div class="col-sm-8">
                                                    <?php if ($datatype == "edit"): ?>
                                                        <input type="date" class="form-control form-control-sm" id="assignment_date" name="date[assignment_date]"
                                                            value="<?= htmlspecialchars($mt_date_assignment) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="date" class="form-control form-control-sm" id="assignment_date" name="date[assignment_date]">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Maintenance Report Date -->
                                    <div class="row mt-4">
                                        <div class="col-md-4 border-right">
                                            <div class="row mb-3">
                                                <div class="col-sm-6"><b>Maintenance Report</b></div>
                                                <div class="col-sm-6"><b>Date</b></div>
                                            </div>
                                            <?php for ($i = 1; $i <= $checking[0]['total_mt_report_date']; $i++): ?>
                                                <div class="row mb-3" id="input_mt_report_date">
                                                    <div class="col-sm-1">
                                                        <label class="col-form-label"><?= $i ?></label>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <input type="date" class="form-control form-control-sm" name="date[mt_report_date][]"
                                                            value="<?= $data_result_date['mt_report_date_' . $i] ?? '' ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control form-control-sm ajax-upload" data-type="mt_report_date" data-row="<?= $i ?>">
                                                        <div class="upload-status"></div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                        <!-- Preventive Maintenance Date -->
                                        <div class="col-md-4 border-right">
                                            <div class="row mb-3">
                                                <div class="col-sm-6"><b>Preventive Maintenance</b></div>
                                                <div class="col-sm-6"><b>Date</b></div>
                                            </div>
                                            <?php for ($i = 1; $i <= $checking[0]['total_preventive_mt_date']; $i++): ?>
                                                <div class="row mb-3" id="input_preventive_mt_date">
                                                    <div class="col-sm-6">
                                                        <input type="date" class="form-control form-control-sm" name="date[preventive_mt_date][]"
                                                            value="<?= $data_result_date['preventive_mt_date_' . $i] ?? '' ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control form-control-sm ajax-upload" data-type="preventive_mt_date" data-row="<?= $i ?>">
                                                        <div class="upload-status"></div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                        <!-- Backup Config Date -->
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-sm-6"><b>Backup Config</b></div>
                                                <div class="col-sm-6"><b>Date</b></div>
                                            </div>
                                            <?php for ($i = 1; $i <= $checking[0]['total_backup_mt_date']; $i++): ?>
                                                <div class="row mb-3" id="input_backup_mt_date">
                                                    <div class="col-sm-6">
                                                        <input type="date" class="form-control form-control-sm" name="date[backup_mt_date][]"
                                                            value="<?= $data_result_date['backup_mt_date_' . $i] ?? '' ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control form-control-sm ajax-upload" data-type="backup_mt_date" data-row="<?= $i ?>">
                                                        <div class="upload-status"></div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <!-- Preventive & Maintenance Report (PMR) -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="row mb-3">
                                                <div class="col-sm-6"><b>Preventive & Maintenance Report</b></div>
                                                <div class="col-sm-6"><b>Date</b></div>
                                            </div>
                                            <?php for ($i = 1; $i <= $checking[0]['total_pmr_date']; $i++): ?>
                                                <div class="row mb-3" id="input_pmr_date">
                                                    <div class="col-sm-6">
                                                        <input type="date" class="form-control form-control-sm" name="date[pmr_date][]"
                                                            value="<?= $data_result_date['pmr_date_' . $i] ?? '' ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control form-control-sm ajax-upload" data-type="pmr_date" data-row="<?= $i ?>">
                                                        <div class="upload-status"></div>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <!-- Informasi Tambahan -->
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="ontime_notes" class="col-sm-4 col-form-label col-form-label-sm">Ontime</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control form-control-sm" name="master[ontime_notes]" id="ontime_notes" <?= $datatype == 'edit' ? 'disabled' : '' ?>>
                                                        <option value="implementation" selected>Implementation</option>
                                                        <option value="sales">Sales</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="renewal" class="col-sm-4 col-form-label col-form-label-sm">Project Renewal</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($dataRenewal) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="subproject" class="col-sm-4 col-form-label col-form-label-sm">Sub-Project</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" readonly><?= htmlspecialchars($dataSub) ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="row mt-5">
                                        <div class="col-sm-12">
                                            <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                                            <?php if ($datatype == 'edit'): ?>
                                                <input type="submit" class="btn btn-primary" name="saveReport" value="Save">
                                                <button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#submitModal">Submit</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Modal Submit (Contoh) -->
                <div class="modal fade" id="submitModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Submit</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin submit data ini? Data tidak dapat diubah setelah disubmit.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" name="submitResource" value="Submit">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- jQuery & Script Upload (Tetap dibutuhkan) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $(".ajax-upload").on("change", function() {
                            var fileInput = this;
                            var file = fileInput.files[0];
                            var fileType = $(this).data("type");
                            var rowNumber = $(this).data("row");
                            var statusDiv = $(this).closest(".row").find(".upload-status");

                            if (file) {
                                statusDiv.html("Uploading...");
                                // Simulasi upload sukses
                                setTimeout(function() {
                                    statusDiv.html("<span style='color: green;'>Upload sukses: " + file.name + "</span>");
                                }, 1000);
                            }
                        });
                    });

                    function totalReportDate(type) {
                        // Dummy function untuk simulasi kalkulasi
                        console.log("Calculating total for: " + type);
                    }

                    function updateDurationActual(mode) {
                        // Dummy function untuk update durasi
                        document.getElementById('diff_duration').value = "75 Hari";
                    }
                </script>
            </div>
        </div>

        <div class="tab-pane fade" id="History" role="tabpanel" aria-labelledby="history-tab">
            <div class="card shadow mb-4">
                malik
            </div>
        </div>
    </div>



<?php
}
?>
<input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
<?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
    <input type="submit" class="btn btn-primary" name="save" value="Save">
<?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
    <input type="submit" class="btn btn-primary" name="add" value="Save">
<?php } ?>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#project_code').select2({
            placeholder: 'Pilih project_code',
            allowClear: true,
            width: '100%' // pastikan lebar responsif
        });
    });
</script>