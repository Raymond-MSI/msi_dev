<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1645673665";
        $author = 'Syamsul Arham';
    } else {
 
include("components/modules/google_drive/func_extension_google_drive.php");

$database = 'sa_google_drive';
            //   include("components/modules/google_drive/connection.php");
            //   $DBGD = new Databases($hostname, $username, $userpassword, $database);
$tblname = "cfg_web";
$mdlname = 'GOOGLE_DRIVE';
$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
$cons = $DB->get_data($tblname,$condition);
$dcon = $cons[0];
if($cons[2]>0) {
    $params = get_params($dcon['params']);
    $hostname = $params['database']['hostname'];
    $username = $params['database']['username'];
    $userpassword = $params['database']['userpassword'];
    $database = $params['database']['database_name'];
    //echo $hostname;
    $DBGD = new Drive($hostname,$username,$userpassword,$database);
}

$database_wr = 'sa_wrike_integrate';
$tblname = "cfg_web";
$mdlname = 'WRIKE_INTEGRATE';
$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
$cons = $DB->get_data($tblname,$condition);
$dcon = $cons[0];
if($cons[2]>0) {
    $params = get_params($dcon['params']);
    $hostname = $params['database']['hostname'];
    $username = $params['database']['username'];
    $userpassword = $params['database']['userpassword'];
    $database = $params['database']['database_name'];
    //echo $hostname;
    $DBWR = new Drive($hostname,$username,$userpassword,$database);
}

$tblname = "cfg_web";
$mdlname = 'SERVICE_BUDGET';
		$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
		$cons = $DB->get_data($tblname,$condition);
		$dcon = $cons[0];
		if($cons[2]>0) {
			$params = get_params($dcon['params']);
			$hostname = $params['database']['hostname'];
			$username = $params['database']['username'];
			$userpassword = $params['database']['userpassword'];
			$database = $params['database']['database_name'];
			
			$DBGD_SB = new SBDrive($hostname,$username,$userpassword,$database);
		}

   
        $modulename = "folder_project";
        $userpermission = useraccess($modulename);
        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#folder_project').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               extend: 'colvis',
                               text: "<i class='fa fa-columns'></i>",
                               collectionLayout: 'fixed four-column'
                            },
                            {
                               text: "<i class='fa fa-eye'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var folder_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=google_drive&act=view&folder_id="+folder_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var folder_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=google_drive&act=edit&folder_id="+folder_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=add";
                               },
                               // enabled: false
                            },
                            {
                               text: "<i class='fa fa-file-code'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=add_wrike";
                                  
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Module SB -> WRIKE 1'><i class='fa fa-check-square'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=wrike_budget";
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Module SB -> WRIKE 2'><i class='fa fa-check-square'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=wrike_budget2";
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Module SB -> WRIKE 3'><i class='fa fa-check-square'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=wrike_budget3";
                               },
                               // enabled: false
                            },
                            {
                               text: "<i class='fa fa-paper-plane'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=wrike_cr";
                               },

                               // enabled: false
                            },
                            {
                               text: "<i class='fa fa-id-badge' aria-hidden='true'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=google_drive&act=wrike_evans";
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [ ],
                               "visible": false,
                           }
                        ],
                     } );
                  } );
               </script>
           <?php 

    
          // Function
          if($_SESSION['Microservices_UserLevel'] == "Administrator") {
              function view_data($tblname) {
                  // Definisikan tabel yang akan ditampilkan dalam DataTable
                  global $DBGD;
                  $primarykey = "project_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  }
                  view_table($DBGD, $tblname, $primarykey, $condition, $order);
              }

              function form_data($tblname) {
                  include("components/modules/google_drive/form_google_drive.php"); 
              } 

              function form_wrike_data($tblname) {
                include("components/modules/google_drive/form_wrike_project.php"); 
              } 

              function form_wrike_budget($tblname) {
                include("components/modules/google_drive/form_wrike_budget.php"); 
              } 
              
              function form_wrike_budget2($tblname) {
                include("components/modules/google_drive/form_wrike_budget2.php"); 
              } 

              function form_wrike_budget3($tblname) {
                include("components/modules/google_drive/form_wrike_budget3.php"); 
              } 
              
              function form_wrike_cr($tblname){
                include("components/modules/google_drive/form_wrike_cr.php");
              }
    		
	      function form_wrike_evans($tblname){
                include("components/modules/google_drive/form_wrike_evans.php");
              }

              // End Function
    
              $database = 'sa_google_drive';
              //include("components/modules/google_drive/connection.php");
            //   $DBGD = new Databases($hostname, $username, $userpassword, $database);
              $DBGD = new Drive($hostname, $username, $userpassword, $database);
            //   $modulename = 'WRIKE_INTEGRATE';
            //   $DBWR = get_conn($modulename);
              $tblname = 'folder_project';
    
              include("components/modules/google_drive/func_google_drive.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">folder_project</h6>
                       </div>
                       <div class="card-body">
                           <?php
                           if(!isset($_GET['act'])) {
                              view_data($tblname);
                           } elseif($_GET['act'] == 'add') {
                              form_data($tblname);
                           } elseif($_GET['act'] == 'new') {
                              new_projects($tblname);
                           } elseif($_GET['act'] == 'edit') {
                              form_data($tblname);
                           } elseif($_GET['act'] == 'del') {
                              echo 'Delete Data';
                           } elseif($_GET['act'] == 'save') {
                              form_data($tblname);
                           } elseif($_GET['act'] == 'add_wrike') {
                              form_wrike_data($tblname);
                           } elseif($_GET['act'] == 'wrike_budget') {
                            form_wrike_budget($tblname);
                           } elseif($_GET['act'] == 'wrike_budget2') {
                            form_wrike_budget2($tblname);
                           }elseif($_GET['act'] == 'wrike_budget3') {
                            form_wrike_budget3($tblname);
                           }elseif($_GET['act'] == 'wrike_cr'){
                            form_wrike_cr($tblname);
                           }elseif($_GET['act'] == 'wrike_evans'){
                            form_wrike_evans($tblname);
                           }
                           ?>
                       </div>
                   </div>
               </div>
           <?php
    
          } else { 
              $ALERT->notpermission();
          } 
          // End Body
       } 
    }
    ?>
    