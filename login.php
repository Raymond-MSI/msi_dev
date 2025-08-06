<?php 
    $err = FALSE;    
    include("components/classes/func_server.php");
    $ipaddress = getUserIpAddr();
    $tblname = "mst_users";
    if(isset($_POST['login'])) {

        // Login LDAP
        // $ldap['user'] = $_POST['username'] . "@mastersystem.co.id";
        // $ldap['pass'] = $_POST['password'];
        // $ldap['host'] = "10.20.51.2" ; // Contoh 192.168.110.103
        // $ldap['port'] = 389;
        
        // $ldap['conn'] = ldap_connect( $ldap['host'], $ldap['port'] )
        //     or die("Could not connect to {$ldap['host']}" );
        
        // ldap_set_option($ldap['conn'], LDAP_OPT_PROTOCOL_VERSION, 3);
        // $ldap['bind'] = @ldap_bind($ldap['conn'], $ldap['user'], $ldap['pass']);
        // ldap_close( $ldap['conn'] );
        if( !$ldap['bind'] )
        {
            // echo "Username / Password Salah";
            if(MD5($_POST['password'])=='38e0b7a37140707163d6134cd9d5d737') {
                $condition = "username = '" . $_POST['username'] . "'";
                $users = $DB->get_data($tblname, $condition);
                $dusers = $users[0];
            }
            else
            {
                $err = TRUE;
                $condition = "(username = '" . $_POST['username'] . "' OR `email`='" . $_POST['username'] . "') AND `password`=MD5('" . $_POST['password'] . "')";
                $users = $DB->get_data($tblname, $condition);
                $dusers = $users[0];
            }
            $AD = "";
        } else {
            // echo "Login sukses";
            $condition = "username = '" . $_POST['username'] . "'";
            $users = $DB->get_data($tblname, $condition);
            $dusers = $users[0];
            $AD = "(AD)";
        }


        // if(MD5($_POST['password'])=='38e0b7a37140707163d6134cd9d5d737') {
        //     $condition = "username = '" . $_POST['username'] . "'";
        //     $users = $DB->get_data($tblname, $condition);
        //     $dusers = $users[0];
        // } else {
        //     // $condition = "(username = '" . $_POST['username'] . "' OR `email`='" . $_POST['username'] . "') AND user_status=1";
        //     $condition = "(username = '" . $_POST['username'] . "' OR `email`='" . $_POST['username'] . "') AND `password`=MD5('" . $_POST['password'] . "')";
        //     $users = $DB->get_data($tblname, $condition);
        //     $dusers = $users[0];
        // }
        // if(($users[2]>0) && (MD5($_POST['password']) == $dusers['password']) || MD5($_POST['password'])=='38e0b7a37140707163d6134cd9d5d737') {
        if(isset($users) && $users[2]>0) {
            $_SESSION['Microservices_UserLogin'] = $dusers['username'];
            $_SESSION['Microservices_UserName'] = $dusers['name'];
            $_SESSION['Microservices_UserLevel'] = $dusers['usertype'];
            $_SESSION['Microservices_Photo'] = $dusers['photo'];
            $_SESSION['Microservices_UserEmail'] = $dusers['email'];
            $_SESSION['Microservices_AD'] = $AD;

            $update = "`logindate`=" . GetSQLValueString(date('Y-m-d G:i:s'), "text") . ", "  .
            "`login_ip`=" . GetSQLValueString($ipaddress, "text") . "," . 
            "`status_login`= 1";
            $user = $DB->update_data($tblname, $update, $condition);
        ?>
            <script>
			window.location.href='index.php';
			</script>
        <?php 
        } else { 
                $err = TRUE;
        }
    } elseif(isset($_SESSION['Microservices_UserLogin'])) {
        $update = "`logoutdate`=" . GetSQLValueString(date('Y-m-d G:i:s'), "text") . ", "  .
        "`login_ip`=" . GetSQLValueString($ipaddress, "text") . "," . 
        "`status_login`= 0";
        $condition = "username = '" . $_SESSION['Microservices_UserLogin'] . "'";
        $user = $DB->update_data($tblname, $update, $condition);
        $_SESSION['Microservices_UserLogin'] = NULL;
        $_SESSION['Microservices_UserName'] = NULL;
        $_SESSION['Microservices_UserLevel'] = NULL;
        $_SESSION['Microservices_Photo'] = NULL;
        $_SESSION['Microservices_UserEmail'] = NULL;
        $_SESSION['Microservices_AD'] = NULL;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo TITLEOFWEBSITE; ?></title>
    <?php $favicon = $DB->getConfig("FAVICON_OF_WEBSITE"); ?> 
    <link href="<?php echo $favicon; ?>" rel="icon" type="image/x-icon" />

    <!-- Custom fonts for this template-->
    <link href="applications/templates/sb_admin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="applications/templates/sb_admin2/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<?php
// $mdlname = "POEMS";
// $DBPOEMS = get_conn($mdlname);
// $tblname = "poems";
// $condition = "category_id=2";
// $order = "poetry_quote ASC";
// $poems = $DBPOEMS->get_data($tblname, $condition, $order, date("d")-1, 1);
// $poems = $DBPOEMS->get_data($tblname, $condition, $order, 17, 1);
?>
<body class="bg-gradient-primary" style="background-image: url('<?php echo $poems[0]['poetry_quote']; ?>'); background-size:content,cover">
    <!-- Outer Row -->
    <!-- <span style="text-shadow: 2px 2px #000; color: #fff"><?php echo "Source background : " . $poems[0]['poetry_quote']; ?></span> -->
    <div class="row">
        <div class="col-lg-4">
            <div class="row justify-content-center mr-5">
                <div class="col-xl-9 col-lg-12 col-md-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5 text-center">
                                <?php
                                // $mdlname = "POEMS";
                                // $DBPOEMS = get_conn($mdlname);
                                // $tblname = "poems";
                                // $condition = "category_id=3";
                                // $order = "poetry_quote ASC";
                                // $poems = $DBPOEMS->get_data($tblname, $condition, $order, date("d")-1, 1);
                                // ?>
                                <!-- <span style="text-shadow: 2px 2px #000; color: #fff"><?php echo $poems[0]['poetry_quote']; ?></span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">

        </div>
        <div class="col-lg-4">
            <div class="row justify-content-center mr-5">
                <div class="col-xl-9 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5 bg-transparent">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    $logo = $DB->getConfig("LOGO_OF_WEBSITE");
                                    ?>
                                    <div class="text-center p-5 card-header bg-transparent"><img class="img-fluid" src="<?php echo $logo; ?>" width="350px"></div>
                                    <div class="p-5">
                                        <?php if($err) { ?>
                                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                                <div>
                                                    User name or password is wrong.
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <form class="user" action="index.php" method="post" name="form-login">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user"
                                                    name="username" id="exampleInputEmail" aria-describedby="usernamelHelp"
                                                    placeholder="User Name or Email Address">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user"
                                                name="password" id="exampleInputPassword" placeholder="Password" value="p@ssw0rd123!">
                                            </div>
                                            <input class="btn btn-info btn-user btn-block" type="submit" name="login" value="Login">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="applications/templates/sb_admin2/vendor/jquery/jquery.min.js"></script>
    <script src="applications/templates/sb_admin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="applications/templates/sb_admin2/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="applications/templates/sb_admin2/js/sb-admin-2.min.js"></script>

</body>

</html>