<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-4">
            <?php
            $tblname = "mst_employees";
            $username = $_SESSION['Microservices_UserEmail'];
            $condition = "email='" . $username . "'";
            $status = $DBHCM->get_data($tblname, $condition);
            if($status[2]>0 && $status[0]['resign_date']!="")
            {
                // Status user resign (URD)
                ?>
                <div class="alert alert-danger" role="alert">
                Harap menghubungi admin. (URD)
                </div>
                <?php 
            } 
            ?>
            <?php
            $birth = $DBHCM->get_profile($_SESSION["Microservices_UserEmail"], "date_of_birth");
            if(date("d-m", strtotime($birth))==date("d-m")) {
            ?>
            <div class="card shadow mb-4">
                <div class="card-body" style="background: url('media/images/happy-birthday/1.jpg'); background-size:cover; background-repeat:no-repeat; height:350px;   background-position: center; ">
                    <div class="row mt-5">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-7">
                            <?php
                            $mdlname = "POEMS";
                            $DBPOEMS = get_conn($mdlname);
                            $tblname = "poems";
                            $condition = "category_id=1";
                            $total = $DBPOEMS->get_data($tblname);
                            $poems = $DBPOEMS->get_data($tblname,$condition,"", rand(0,$total[2]-1),1);
                            ?>
                            <p><b>Happy Birthday!</b></p>
                            <p><?php echo $poems[0]['poetry_quote']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">News</h6>
                </div>
                <?php 
                $tblname = 'cfg_web';
                $condition = 'config_key="MODULE_DOCUMENTATION"';
                $setupDB = $DB->get_data($tblname, $condition);
                $dsetupDB = $setupDB[0];
                if($setupDB[2]>0) {
                    $params = get_params($dsetupDB['params']);
                    $hostname = $params['database']['hostname'];
                    $username = $params['database']['username'];
                    $userpassword = $params['database']['userpassword'];
                    $database = $params['database']['database_name'];
        

                    $DBDOC = new Databases($hostname, $username, $password, $database);

                    $mysql = 'SELECT `post_title`, `post_content`, `post_name`, `post_modified_gmt` FROM `wp_posts` WHERE `post_type`="post" AND `post_status`="publish" ORDER BY `post_modified_gmt` DESC LIMIT 0,5';
                    $sb = $DBDOC->get_sql($mysql);
                    $dsb = $sb[0];
                    $qsb = $sb[1];
                    $contents1 = explode("/p>", $dsb['post_content']); 
                    $contents2 = strip_tags_content($contents1[0]); 
                    $contents = explode("<", $contents2);

                    $title0 = strtolower($dsb['post_title']);
                    $permalink0 = str_replace("– ","",$title0);
                    $permalink0 = str_replace(".","-", $permalink0);
                    $permalink = str_replace(" ","-",$permalink0);

                    $diff = (strtotime(date("y-m-d"))-strtotime($dsb['post_modified_gmt']))/(60*60*24); 
                    $text='';
                    $bgcolor = "";
                    if($diff<7) {
                        $text = '<span class="badge rounded-pill bg-danger">NEW</span>';
                        $bgcolor = "bg-info";
                    }
                    ?>
                    <div class="card-body <?php echo $bgcolor; ?>">
                        <div style="font-size:12px">
                            <p><b><?php echo $dsb['post_title'] . '</b> ' . $text; ?></p><p><?php echo $contents[0]; ?></p>
                            <p><a href="msiguide/<?php echo $dsb['post_name']; ?>" target="_new">Open MSIGuide</a></p>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "Aplikasi belum disetup";            
                }            
                ?>
            </div>
        </div>

        <div class="col-lg-8">
            <?php
            $mdlname = "MICROSERVICES";
            $DBMS = get_conn($mdlname); 
            if(MD5($_SESSION['Microservices_UserLevel'])!='7b7bc2512ee1fedcd76bdc68926d4f7b') {
                $tblname = "view_user_access";
                $condition = "user_level <> 'Non Member' AND tblname LIKE '%dashboard: visibility=1%' AND username = '" . $_SESSION['Microservices_UserLogin'] . "'"; 
                $mdl = $DBMS->get_data($tblname, $condition);
            } else {
                $mysql = "SELECT config_value AS module FROM sa_cfg_web WHERE config_key LIKE 'MODULE%' AND params LIKE '%dashboard: visibility=1%' AND config_value <> 'microservices'";
                $mdl = $DBMS->get_sql($mysql);
            }
            $dmdl = $mdl[0];
            $qmdl = $mdl[1];
            $tmdl = $mdl[2];
            if($tmdl>0) {
                do {
                    include($dmdl['module'] . "/mod_dashboard.php");
                } while($dmdl=$qmdl->fetch_assoc());
            }
            ?>
        </div>
    </div>
</div>
 
<?php } ?>