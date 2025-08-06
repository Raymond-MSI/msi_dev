<?php

use Elementor\Core\Base\Document;

function xmenu($title, $link, $logo = "")
{
    echo '<a class="dropdown-item d-flex align-items-center" href="' . $link . '" target="_new">
        <div class="mr-3">';
    if ($logo == "None") {
        $exps = explode(" ", $title);
        // $subtitle = substr($exp[0],0,1);
        // if(count($exp)>1) {
        //     $subtitle .= substr($exp[1],0,1);
        // }
        $subtitle = "";
        foreach ($exps as $exp) {
            $subtitle .= substr($exp, 0, 1);
        }
        echo '<div class="icon-circle bg-danger text-light">' . $subtitle . '</div>';
    } elseif ($logo != "") {
        echo '<div class="icon-circle text-light"><image src="' . $logo . '" width="40px"></div>';
    }
    echo '
        </div>
        <div>
            <div class="small text-gray">' . $title . '</div>
        </div>
    </a>';
}
?>
<nav class="navbar navbar-expand navbar-light bg-danger topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link ml-3 mr-3 bg-light text-danger">
        <i class="fa fa-bars"></i>
    </button>
    <?php spinner(); ?>
    <!-- <div class="col-lg-6">
        <h1 class="h3 mb-0 text-white">
            <?php
            // if(isset($_GET['mod'])) {
            //     $exp = explode('_', $_GET['mod']);
            //     for($i=0; $i<count($exp); $i++) {
            //     echo ucfirst($exp[$i]) . " ";
            //     }
            // } else {
            //     echo 'Dashboard';
            // }
            ?>
        </h1>
    </div> -->


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
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
        // $mdlname = "MSIGUIDE";
        // $DBDOC = get_conn($mdlname);
        // $mysql = 'SELECT `post_title`, `post_content`, `post_name`, `post_modified_gmt` FROM `wp_posts` WHERE `post_type`="post" AND `post_status`="publish" ORDER BY `post_modified_gmt` DESC';
        // $news = $DBDOC->get_sql($mysql);
        ?>

        <!-- Notification News -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="News">
                <i class="fa-solid fa-newspaper fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-dark badge-counter" id="news"></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-danger border-light">
                    Alert News
                </h6>
                <!-- <div class="overflow-auto" style="max-height:750px">
                    <?php
                    // $i = 0;
                    // if($news[2]>0)
                    // {
                    //     do {
                    //         $contents1 = explode("/p>", $news[0]['post_content']); 
                    //         $contents2 = strip_tags_content($contents1[0]); 
                    //         $contents = explode("<", $contents2);

                    //         $title0 = strtolower($news[0]['post_title']);
                    //         $permalink0 = str_replace("– ","",$title0);
                    //         $permalink0 = str_replace(".","-", $permalink0);
                    //         $permalink = str_replace(" ","-",$permalink0);

                    //         $diff = (strtotime(date("y-m-d"))-strtotime($news[0]['post_modified_gmt']))/(60*60*24); 
                    //         $text='';
                    //         if($diff<7) {
                    //             $text = '<span class="badge rounded-pill bg-danger">NEW</span>';
                    //             $bgcolor = "bg-info";
                    //             $i++;

                    //             $title = $news[0]['post_title'] . "&nbsp;&nbsp;" . $text;
                    //             // $link = "msiguide/" . $news[0]['post_name'];
                    //             xmenu($title, $link);
                    //         }
                    //     } while($news[0]=$news[1]->fetch_assoc());
                    // }
                    // if($i==0)
                    // {
                    //     xmenu("No data to display.", "#");
                    // }
                    ?>
                    <script> document.getElementById("news").innerHTML = <?php echo $i != 0 ? $i : ""; ?>;</script>
                </div> -->
            </div>
        </li>

        <!-- Notification on top -->
        <?php
        $mdlname = "NOTIFICATION";
        $DBNOTIF = get_conn($mdlname);
        $tblname = "trx_notification";
        $condition = "notif_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND notif_status=1";
        $order = "notif_date DESC";
        $notif = $DBNOTIF->get_data($tblname, $condition, $order, 0, 25);
        ?>
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifications">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-dark badge-counter"><?php echo $notif[2] != 0 ? $notif[2] : ""; ?></span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-danger border-light">
                    Waiting for Action from You
                </h6>
                <div class="overflow-auto" style="max-height:750px">
                    <?php
                    if ($notif[2] > 0) {
                        $i = 0;
                        do {
                            if ($i < 10)
                                // {
                    ?>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo $notif[0]['notif_link']; ?>">
                                <div class="mr-3">
                                    <div class="">
                                        <!-- <i class="fas fa-file-alt text-white"></i> -->
                                        <?php
                                        $xemail = explode("<", $notif[0]['notif_from']);
                                        if (count($xemail) == 0) {
                                            $notiffrom = $notif[0]['notof_from'];
                                        } else {
                                            $yemail = explode(">", $xemail[1]);
                                            $notiffrom = $yemail[0];
                                        }
                                        ?>
                                        <img class='img-profile rounded-circle' src='data:image/jpeg;base64, <?php echo base64_encode($DBHCM->get_photo($notiffrom)); ?>' height="40px" />
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $notif_status = "";
                                    if ($notif[0]['notif_status'] == 1) {
                                        $notif_status = "fw-bold";
                                    }
                                    ?>
                                    <div class="small text-gray-500"><?php echo date("d-M-Y G:i:s", strtotime($notif[0]['notif_date'])); ?></div>
                                    <div class="small <?php echo $notif_status; ?>"><?php echo $notif[0]['notif_subject']; ?></div>
                                </div>
                            </a>
                        <?php
                            // }
                            $i++;
                        } while ($notif[0] = $notif[1]->fetch_assoc());
                        ?>
                        <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> -->
                    <?php
                    } else {
                        xmenu("No data to display.", "#");
                    }
                    ?>
                </div>
            </div>
        </li>


        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="User Guide">
                <i class="fas fa-circle-info fa-fw"></i>
                <!-- Counter - Alerts -->
                <!-- <span class="badge badge-dark badge-counter"><?php //echo $notif[2]; 
                                                                    ?></span> -->
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header bg-danger border-light">
                    User Guide
                </h6>
                <div class="overflow-auto" style="max-height:750px">
                    <?php
                    $tblname = "cfg_menus";
                    $condition = "published=1 AND parent=125";
                    // $condition = "published=1 AND parent=80";
                    $order = "ordering ASC, title ASC";
                    $menus = $DB->get_data($tblname, $condition, $order);
                    if ($menus[2] > 0) {
                        do {
                            xmenu($menus[0]['title'], $menus[0]['link'], ($menus[0]['fontawesome'] <> '' ? $menus[0]['fontawesome'] : "None"));
                        } while ($menus[0] = $menus[1]->fetch_assoc());
                    }
                    ?>
                </div>
            </div>
        </li>


        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white small"><?php echo $_SESSION['Microservices_UserName'] . " " . $_SESSION['Microservices_AD']; ?></span>
                <!-- <img class="img-profile rounded-circle"
                    src="applications/templates/sb_admin2/img/undraw_profile.svg"> -->
                <?php
                // $row = get_leader($_SESSION['Microservices_UserEmail'], 1);
                // echo "<img class='img-profile rounded-circle' src='data:image/jpeg;base64, " . base64_encode($DBHCM->get_photo($row[0]['employee_email'])) . "' />";
                // if($_SESSION['Microservices_AD']=="")
                // {
                //     echo "<img class='img-profile rounded-circle' src='media/images/profiles/blank-profile.png'>";
                // }
                // else
                // {
                //     echo "<img class='img-profile rounded-circle' src='data:image/jpeg;base64, " . base64_encode($DBHCM->get_photo($row[0]['employee_email'])) . "' />";
                // }
                ?>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="index.php?mod=profile">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="index.php?mod=setting">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item disabled" href="index.php?mod=change_password&act=edit">
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