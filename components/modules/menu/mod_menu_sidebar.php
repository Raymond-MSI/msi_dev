<!-- Sidebar -->
<ul class="navbar-nav bg-black bg-opacity-75 sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white" href="index.php">
        <!-- <div class="sidebar-brand-icon rotate-n-15"> -->
        <div class="sidebar-brand-icon">
            <?php
            $logo = $DB->getConfig("LOGO_OF_WEBSITE");
            ?>
            <img src='media/images/profiles/logo_icon.png' height="50px">
        </div>
        <div class="sidebar-brand-text mx-3 text-dark fst-italic h5 fw-bolder">MSIZone</div>
    </a>

    <!-- Divider -->
    <hr class="sidebat-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Applications
    </div>

    <?php
    $condition = 'parent=5 AND published=1';
    $order = 'ordering ASC, title ASC';
    $tblname = 'cfg_menus';
    $menusidebar = $DB->get_data($tblname, $condition, $order);
    $dmenusidebar = $menusidebar[0];
    $qmenusidebar = $menusidebar[1];

    do {
    ?>

        <!-- Nav Item - Main Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $dmenusidebar['id']; ?>"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas <?php echo $dmenusidebar['fontawesome']; ?>"></i>
                <span><?php echo $dmenusidebar['title']; ?></span>
            </a>
            <?php
            $condition = 'parent=' . $dmenusidebar['id'] . ' AND published=1';
            $tblname = 'cfg_menus';
            $menusubsidebar = $DB->get_data($tblname, $condition, $order);
            $dmenusubsidebar = $menusubsidebar[0];
            $qmenusubsidebar = $menusubsidebar[1];
            ?>
            <div id="collapse<?php echo $dmenusidebar['id']; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header"><?php echo $dmenusidebar['title']; ?>:</h6>
                    <?php do { ?>
                        <a class="collapse-item" href="<?php echo $dmenusubsidebar['link']; ?>" target="<?php echo $dmenusubsidebar['params']; ?>"><i class="fas <?php echo $dmenusubsidebar['fontawesome']; ?>"></i> <?php echo $dmenusubsidebar['title']; ?></a>
                    <?php } while ($dmenusubsidebar = $qmenusubsidebar->fetch_assoc()); ?>
                </div>
            </div>
        </li>
    <?php } while ($dmenusidebar = $qmenusidebar->fetch_assoc()); ?>

    <!-- Menu User Admin -->
    <?php
    if ($_SESSION['Microservices_UserLevel'] == 'Administrator' || $_SESSION['Microservices_UserLevel'] == 'Super Admin' || $_SESSION['Microservices_UserLevel'] == 'User Admin') {
    ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            User Admin
        </div>
        <?php
        $condition = 'parent=116 AND published=1';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        if ($menusidebar[2] > 0) {
            do {
        ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $menusidebar[0]['id']; ?>"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas <?php echo $menusidebar[0]['fontawesome']; ?>"></i>
                        <span><?php echo $menusidebar[0]['title']; ?></span>
                    </a>

                    <?php
                    $condition = 'parent=' . $menusidebar[0]['id'] . ' AND published=1';
                    $tblname = 'cfg_menus';
                    $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                    if ($menusubsidebar[2] > 0) {
                    ?>
                        <div id="collapse<?php echo $menusidebar[0]['id']; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header"><?php echo $menusidebar[0]['title']; ?>:</h6>
                                <?php do { ?>
                                    <a class="collapse-item" href="<?php echo $menusubsidebar[0]['link']; ?>" target="<?php echo $menusubsidebar[0]['params']; ?>"><i class="fas <?php echo $menusubsidebar[0]['fontawesome']; ?>"></i> <?php echo $menusubsidebar[0]['title']; ?></a>
                                <?php } while ($menusubsidebar[0] = $menusubsidebar[1]->fetch_assoc()); ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </li>
        <?php
            } while ($menusidebar[0] = $menusidebar[1]->fetch_assoc());
        }
        ?>


    <?php
    }
    ?>
    <!-- End User Admin -->

    <!-- Administrator -->
    <?php if ($_SESSION['Microservices_UserLevel'] == 'Administrator' || $_SESSION['Microservices_UserLevel'] == 'Super Admin') { ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Administrator
        </div>

        <?php
        $condition = 'parent=47 AND published=1';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        $dmenusidebar = $menusidebar[0];
        $qmenusidebar = $menusidebar[1];

        do {
        ?>

            <!-- Nav Item - Main Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $dmenusidebar['id']; ?>"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas <?php echo $dmenusidebar['fontawesome']; ?>"></i>
                    <span><?php echo $dmenusidebar['title']; ?></span>
                </a>
                <?php
                $condition = 'parent=' . $dmenusidebar['id'] . ' AND published=1';
                $tblname = 'cfg_menus';
                $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                $dmenusubsidebar = $menusubsidebar[0];
                $qmenusubsidebar = $menusubsidebar[1];
                $tmenusubsidebar = $menusubsidebar[2];

                if ($tmenusubsidebar > 0) {
                ?>
                    <div id="collapse<?php echo $dmenusidebar['id']; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header"><?php echo $dmenusidebar['title']; ?>:</h6>
                            <?php
                            do {
                            ?>
                                <a class="collapse-item" href="<?php echo $dmenusubsidebar['link']; ?>" target="<?php echo $dmenusubsidebar['params']; ?>"><i class="fas <?php echo $dmenusubsidebar['fontawesome']; ?>"></i> <?php echo $dmenusubsidebar['title']; ?></a>
                            <?php } while ($dmenusubsidebar = $qmenusubsidebar->fetch_assoc()); ?>
                        </div>
                    </div>
                <?php } ?>
            </li>
    <?php
        } while ($dmenusidebar = $qmenusidebar->fetch_assoc());
    }
    ?>
    <!-- End Administrator -->

    <!-- Developer -->
    <?php if ($_SESSION['Microservices_UserLevel'] == 'Administrator' || $_SESSION['Microservices_UserLevel'] == 'Super Admin') { ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Developer
        </div>

        <?php
        $condition = 'parent=64 AND published=1';
        $tblname = 'cfg_menus';
        $menusidebar = $DB->get_data($tblname, $condition, $order);
        $dmenusidebar = $menusidebar[0];
        $qmenusidebar = $menusidebar[1];
        if ($menusidebar[2] > 0) {
            do {
        ?>

                <!-- Nav Item - Main Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $dmenusidebar['id']; ?>"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas <?php echo $dmenusidebar['fontawesome']; ?>"></i>
                        <span><?php echo $dmenusidebar['title']; ?></span>
                    </a>
                    <?php
                    $condition = 'parent=' . $dmenusidebar['id'] . ' AND published=1';
                    $tblname = 'cfg_menus';
                    $menusubsidebar = $DB->get_data($tblname, $condition, $order);
                    $dmenusubsidebar = $menusubsidebar[0];
                    $qmenusubsidebar = $menusubsidebar[1];
                    $tmenusubsidebar = $menusubsidebar[2];

                    if ($tmenusubsidebar > 0) {
                    ?>
                        <div id="collapse<?php echo $dmenusidebar['id']; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header"><?php echo $dmenusidebar['title']; ?>:</h6>
                                <?php
                                do {
                                ?>
                                    <a class="collapse-item" href="<?php echo $dmenusubsidebar['link']; ?>" target="<?php echo $dmenusubsidebar['params']; ?>"><i class="fas <?php echo $dmenusubsidebar['fontawesome']; ?>"></i> <?php echo $dmenusubsidebar['title']; ?></a>
                                <?php } while ($dmenusubsidebar = $qmenusubsidebar->fetch_assoc()); ?>
                            </div>
                        </div>
                    <?php } ?>
                </li>
    <?php
            } while ($dmenusidebar = $qmenusidebar->fetch_assoc());
        }
    }
    ?>
    <!-- End Developer -->


    <!-- Divider -->
    <!-- <hr class="sidebar-divider d-none d-md-block"> -->

    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 d-md-none rounded-circle " id="sidebarToggle"></button>
    </div> -->

    <!-- Sidebar Message -->
    <!-- <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="applications/templates/sb_admin2/img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>You need help?</strong></p>
        <a class="btn btn-success btn-sm" href="msiguide" target="_new">User Guide</a>
    </div> -->

</ul>
<!-- End of Sidebar -->