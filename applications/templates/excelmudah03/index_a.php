<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo TITLEOFWEBSITE; ?> | Administrator</title>
        <link href="<?php echo DOMAIN . $webParams['MEDIA_BANNER_FOLDER']; ?>favicon.ico" rel="icon" type="image/x-icon" />
        <link href="applications/templates/<?php echo TEMPLATE; ?>/css/styles.css" rel="stylesheet" />
        
      <!-- DataTables -->
      <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
      <!-- Font-Awesome -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

  </head>
    <body class="sb-nav-fixed">
       <?php if(!isset($_SESSION[$_SERVER['SERVER_NAME'] . "UserLogin"])) {  ?>
        <div class="d-flex justify-content-center loginx">
          <div class="col-md-2">
            <?php include("components/modules/mod_admin_login.php"); ?>
          </div>
        </div>
      <?PHP } else { ?>
       <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="<?php echo DOMAIN; ?>index.php?template=<?php echo TEMPLATE; ?>&amod=dashboard"><?php echo TITLEOFWEBSITE; ?></a>
             <?php /*?><a href="<?php echo DOMAIN; ?>/home" class="site_title"><i class="fa fa-paw"></i> <span><?php echo TITLEOFWEBSITE; ?></span></a><?php */?>
           <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?template=<?php echo TEMPLATE; ?>&amod=login&status=logout">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">General</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArticle" aria-expanded="false" aria-controls="collapseArticle">
                                <div class="sb-nav-link-icon"><i class="far fa-edit"></i></div>
                                Articles
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseArticle" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=article">Article</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=article_category">Category</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBanners" aria-expanded="false" aria-controls="collapseBanners">
                                <div class="sb-nav-link-icon"><i class="far fa-image"></i></div>
                                Banners
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseBanners" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=banner">Banner</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=banner_group">Banner Group</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=banner_type">Banner Type</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=comment">
                                <div class="sb-nav-link-icon"><i class="far fa-comment-dots"></i></div>
                                Comments
                            </a>
                            <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=celetuk">
                                <div class="sb-nav-link-icon"><i class="far fa-lightbulb"></i></div>
                                Insight
                            </a>
                            <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=subscriber">
                                <div class="sb-nav-link-icon"><i class="far fa-bell"></i></div>
                                Subscriber
                            </a>
                            <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=Youtube">
                                <div class="sb-nav-link-icon"><i class="fab fa-youtube"></i></div>
                                Youtube
                            </a>

                            <div class="sb-sidenav-menu-heading">Administrator</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Users
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseAdmin" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=#">User List</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Reset Password</a>
                                </nav>
                            </div>
                          
                            <div class="sb-sidenav-menu-heading">Desain</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseConfig" aria-expanded="false" aria-controls="collapseConfig">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Configurations
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseConfig" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=#">Web Configuration</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Content Configuration</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Media Configuration</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Meta Configuration</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Module Configuration</a>
                                </nav>
                            </div>
                          
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDesain" aria-expanded="false" aria-controls="collapseDesain">
                                <div class="sb-nav-link-icon"><i class="far fa-eye"></i></div>
                                Design
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDesain" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=#">Module Design</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Position List</a>
                                    <a class="nav-link" href="index.php?template=<?php echo TEMPLATE; ?>&amod=$">Menu</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $_SESSION[ "pmtools_UserName" ]; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                      <?php include("components/modules/mod_admin_" . $_GET["amod"] . ".php"); ?>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
      <?php } ?>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="applications/templates/<?php echo TEMPLATE; ?>/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="applications/templates/<?php echo TEMPLATE; ?>/assets/demo/chart-area-demo.js"></script>
        <script src="applications/templates/<?php echo TEMPLATE; ?>/assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="applications/templates/<?php echo TEMPLATE; ?>/assets/demo/datatables-demo.js"></script>
    </body>
</html>
