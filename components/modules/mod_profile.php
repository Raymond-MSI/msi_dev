<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
<?php 
// User Information
$tblname = "mst_users";
$condition = "username='" . $_SESSION['Microservices_UserLogin'] . "'";
$users = $DB->get_data($tblname, $condition);
$dusers = $users[0];
$qusers = $users[1];

// Employee Profile
$mdlname = "HCM";
$order = "job_structure, join_date";
$employee = get_leader($_SESSION['Microservices_UserEmail'], 1);
//Sub Ordinate
$employee2 = get_leader($_SESSION['Microservices_UserEmail'], 0, $order);
$totalsubordinate = $employee2[2];
?>
<div class="col-lg-12">

    <div class="row">

        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <!-- <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Photo</h6>
                </div> -->
                <div class="card-body">
                <img class='img-thumbnail' src='data:image/jpeg;base64, <?php echo base64_encode($DBHCM->get_profile($_SESSION['Microservices_UserEmail'], "unitdrawing")); ?>' width="400px" />
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <table class="" width="100%">
                        <tr><td>User Login</td><td><?php echo $dusers['username']; ?></td></tr>
                        <tr><td>Password</td><td><?php echo "********"; ?></td></tr>
                        <tr><td>User Level</td><td><?php echo $dusers['usertype']; ?></td></tr>
                        <tr><td>Register Date</td><td><?php echo date("d M Y G:i:s", strtotime($dusers['registerdate'])); ?></td></tr>
                        <tr><td>Last Visited</td><td><?php echo date("d M Y G:i:s", strtotime($dusers['logindate'])); ?></td></tr>
                        <tr><td>IP Address</td><td><?php echo $dusers['login_ip']; ?></td></tr>
                    </table>
                </div>
            </div>


        </div>

        <div class="col-lg-5">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employee Information</h6>
                </div>
                <div class="card-body">
                    <table class="" width="100%">
                        <tr><td width="30%">Name</td><td><?php echo $employee[0]['employee_name']; ?></td></tr>
                        <tr><td>NIK</td><td><?php echo $employee[0]['nik']; ?></td></tr>
                        <tr><td>Email</td><td><?php echo $employee[0]['employee_email']; ?></td></tr>
                        <tr><td>Join Date</td><td><?php echo date("d M Y", strtotime($employee[0]['join_date'])); ?></td></tr>
                        <tr><td>Organization Name</td><td><?php echo $employee[0]['organization_name']; ?></td></tr>
                        <tr><td>Job Title</td><td><?php echo $employee[0]['job_title']; ?></td></tr>
                        <tr><td>Leader Name</td><td><?php echo $employee[0]['leader_name']; ?></td></tr>
                        <tr><td>Leader Email</td><td><?php echo $employee[0]['leader_email']; ?></td></tr>
                        <tr><td>Job Structure</td><td><?php echo $employee[0]['job_structure']; ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subordinate Information (<span id="total"></span>)</h6>
                </div>
                <?php if($employee2[2]>0) { ?>
                    <?php do { ?>
                        <div class="card-body bg-light">
                            <table class="" width="100%">
                                <tr><td rowspan="7" width="25%"><img class='img-thumbnail' src='data:image/jpeg;base64, <?php echo base64_encode($DBHCM->get_profile($employee2[0]['employee_email'], "unitdrawing")); ?>' width="400px" /></td><td width="30%">Name</td><td><?php echo $employee2[0]['employee_name']; ?></td></tr>
                                <tr><td>NIK</td><td><?php echo $employee2[0]['nik']; ?></td></tr>
                                <tr><td>Email</td><td><?php echo $employee2[0]['employee_email']; ?></td></tr>
                                <tr><td>Join Date</td><td><?php echo date("d M Y", strtotime($employee2[0]['join_date'])); ?></td></tr>
                                <tr><td>Job Title</td><td><?php echo $employee2[0]['job_title']; ?></td></tr>
                                <tr><td>Job Structure</td><td><?php echo $employee2[0]['job_structure']; ?></td></tr>
                            </table>
                        </div>
                        <hr/>
                        <?php
                        $employee3 = get_leader($employee2[0]['employee_email'], 0, $order);
                        $totalsubordinate+=$employee3[2];
                        if($employee3[2]>0) {
                            do {
                                ?>
                                <div class="card-body bg-success bg-opacity-10">
                                    <table class="" width="100%">
                                        <tr></td><td rowspan="8" width="25%"><img class='img-thumbnail' src='data:image/jpeg;base64, <?php echo base64_encode($DBHCM->get_profile($employee3[0]['employee_email'], "unitdrawing")); ?>' width="400px" /></td><td width="30%">Name</td><td><?php echo $employee3[0]['employee_name']; ?></td></tr>
                                        <tr><td>NIK</td><td><?php echo $employee3[0]['nik']; ?></td></tr>
                                        <tr><td>Email</td><td><?php echo $employee3[0]['employee_email']; ?></td></tr>
                                        <tr><td>Join Date</td><td><?php echo date("d M Y", strtotime($employee3[0]['join_date'])); ?></td></tr>
                                        <tr><td>Job Title</td><td><?php echo $employee3[0]['job_title']; ?></td></tr>
                                        <tr><td>Job Structure</td><td><?php echo $employee3[0]['job_structure']; ?></td></tr>
                                    </table>
                                </div>
                            <?php } while($employee3[0]=$employee3[1]->fetch_assoc()); ?>
                        <?php } ?>
                        <hr/>

                    <?php } while($employee2[0]=$employee2[1]->fetch_assoc()); ?>
                <?php } ?>
            </div>
            <script>document.getElementById("total").innerHTML=<?php echo $totalsubordinate; ?>;</script>

        </div>
        
        <div class="col-lg-4">

            <div class="card shadow mb-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Applications</h6>
                </div>
                <div class="card-body">
                    <?php 
                        $tblname = "view_user_access";
                        $condition = "username='" . $_SESSION['Microservices_UserLogin'] . "' AND user_level<>'Non Member'";
                        $order = "`order` ASC";
                        $app = $DB->get_data($tblname, $condition, $order);
                        $dapp = $app[0];
                        $qapp = $app[1];
                    ?>

                    <table class="" width="100%">
                    <thead>
                        <tr>
                            <th>Application</td>
                            <th>User level</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($_SESSION['Microservices_UserLevel'] != "Administrator") { ?>
                    <?php do { ?>
                        <tr>
                            <td><?php echo $dapp['title']; ?></td>
                            <td><?php echo $dapp['user_level']; ?></td>
                        </tr>
                    <?php } while($dapp=$qapp->fetch_assoc()); ?>
                    <?php 
                    } else {
                        echo '<tr><td colspan="2">You are is Administrator, you have full access rights.</td></tr>';
                    }
                    ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php } ?>