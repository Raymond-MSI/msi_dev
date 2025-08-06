<?php
function xmenu($title, $link, $logo="") {
    echo '<a class="dropdown-item d-flex align-items-center" href="' . $link . '" target="_new">
    <div class="mr-3">';

            if($logo!="") {
                echo '<div class="icon-circle text-light"><image src="' . $logo . '" height="40px"></div>';
            } else {
                $exp = explode(" ", $title);
                $subtitle = substr($exp[0],0,1);
                $subtitle .= substr($exp[1],0,1);
                echo '<div class="icon-circle bg-danger text-light">' . $subtitle .'</div>';
            }

    echo '
    </div>
    <div>
        <div class="small text-gray-500">' . $title . '</div>
    </div>
</a>';
}
?>
<nav class="navbar navbar-expand navbar-light bg-danger topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>

<h1 class="h3 mb-0 text-white">
    <?php
        if(isset($_GET['mod'])) {
            $exp = explode('_', $_GET['mod']);
            for($i=0; $i<count($exp); $i++) {
            echo ucfirst($exp[$i]) . " ";
            }
        } else {
            echo 'Dashboard';
        }
    ?>
</h1>



<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                        placeholder="Search for..." aria-label="Search"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <!-- Nav Item - Alerts -->
    <?php
    // $mdlname = "SERVICE_BUDGET";
    // $DBSB = get_conn($mdlname);
    // $tblname = "trx_project_list";
    // $condition = "status='submited'";
    // $order = "modified_date DESC";
    // $notif = $DBSB->get_data($tblname, $condition, $order);
    // $dnotif = $notif[0];
    // $qnotif = $notif[1];
    // $tnotif = $notif[2]; 


    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblname = "trx_notification";
    $condition = "notif_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND notif_status=1";
    $order = "notif_date DESC";
    $notif = $DBNOTIF->get_data($tblname, $condition, $order, 0, 25);
    $dnotif = $notif[0];
    $qnotif = $notif[1];
    $tnotif = $notif[2];

    ?>

    <!-- Notification on top -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-dark badge-counter"><?php echo $tnotif; ?></span>
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header bg-danger border-light">
                Alerts Center
            </h6>
            <?php 
            if($tnotif>0) { 
                do { ?>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo $dnotif['notif_link']; ?>">
                        <div class="mr-3">
                            <div class="">
                                <!-- <i class="fas fa-file-alt text-white"></i> -->
                                <?php
                                $xemail = explode("<", $notif[0]['notif_from']);
                                if(count($xemail)==0) {
                                    $notiffrom = $notif[0]['notof_from'];
                                } else {
                                    $yemail = explode(">", $xemail[1]);
                                    $notiffrom = $yemail[0];
                                }
                                ?>
                                <img class='img-profile rounded-circle' src='data:image/jpeg;base64, <?php echo base64_encode($DBHCM->get_profile($notiffrom, "unitdrawing")); ?>' height="40px" />
                            </div>
                        </div>
                        <div>
                            <?php
                            $notif_status = "";
                            if($dnotif['notif_status']==1) {
                                $notif_status = "fw-bold";
                            }
                            ?>
                            <div class="small text-gray-500"><?php echo date("d-M-Y G:i:s", strtotime($dnotif['notif_date'])); ?></div>
                            <div class="small <?php echo $notif_status; ?>"><?php echo $dnotif['notif_subject']; ?></div>
                        </div>
                    </a>
                    <?php 
                } while($dnotif=$qnotif->fetch_assoc()); 
                ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                <?php 
            } else { ?>
                <span>Nothing Alerts</span>
                <?php 
            } 
            ?>
        </div>
    </li>


    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-circle-info fa-fw"></i>
            <!-- Counter - Alerts -->
            <!-- <span class="badge badge-dark badge-counter"><?php //echo $tnotif; ?></span> -->
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header bg-danger border-light">
                User Guide
            </h6>
            <?php xmenu("MSIZone", "https://msizone.mastersystem.co.id/msiguide/category/msizone/", "media/images/profiles/logo_icon.png"); ?>
            <?php xmenu("Service Budget", "https://msizone.mastersystem.co.id/msiguide/category/service-budget/"); ?>
            <?php xmenu("Legal Document", "https://msizone.mastersystem.co.id/msiguide/category/legal-document/"); ?>
            <?php xmenu("CIDB", "https://msizone.mastersystem.co.id/msiguide/category/cidb/", "media/images/profiles/logo_cidb.png"); ?>
            <?php xmenu("EVANS", "https://msizone.mastersystem.co.id/msiguide/category/evans/", "media/images/profiles/logo_evans.png"); ?>
            <?php xmenu("Porta MSI", "https://msizone.mastersystem.co.id/msiguide/category/portal-msi/"); ?>
            <?php xmenu("Porta Maintenance", "https://msizone.mastersystem.co.id/msiguide/category/portal-maintenance/"); ?>
            <?php xmenu("Cisco CCWR", "https://msizone.mastersystem.co.id/msiguide/category/ccwr/"); ?>
        </div>
    </li>


    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-white small"><?php echo $_SESSION['Microservices_UserName'] . " " . $_SESSION['Microservices_AD']; ?></span>
            <!-- <img class="img-profile rounded-circle"
                src="applications/templates/sb_admin2/img/undraw_profile.svg"> -->
            <?php
            $row = get_leader($_SESSION['Microservices_UserEmail'], 1);
            echo "<img class='img-profile rounded-circle' src='data:image/jpeg;base64, " . base64_encode($DBHCM->get_profile($row[0]['employee_email'], "unitdrawing")) . "' />";
            ?>
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="index.php?mod=profile">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <a class="dropdown-item" href="index.php?mod=setting">
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                Settings
            </a>
            <a class="dropdown-item" href="index.php?mod=change_password&act=edit">
                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                Change Password
            </a>
            <a class="dropdown-item" href="index.php?mod=activity_log">
                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                Activity Log
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>

</ul>

</nav>
