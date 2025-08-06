<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1626883354";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "mst_type_of_service";
        $userpermission = useraccess($modulename);
        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#mst_type_of_service').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               extend: 'colvis',
                               text: "<i class='fa fa-columns'></i>",
                               collectionLayout: 'fixed four-column',
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-eye'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var tos_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=type_of_service&act=view&tos_id="+tos_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var tos_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=type_of_service&act=edit&tos_id="+tos_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=type_of_service&act=add";
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [ 0],
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
                  global $DBSB;
                  $primarykey = "tos_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  view_table($DBSB, $tblname, $primarykey, $condition, $order);
              }
              function form_data($tblname) {
                  include("components/modules/type_of_service/form_type_of_service.php"); 
              } 
    
              // End Function
    
            //   $hostname = 'localhost';
            //   $database = 'sa_ps_service_budgets';
            //   $username = 'root';
            //   $password = '';
            //   include_once( "components/classes/func_databases_v3.php" );
            $tblname = 'cfg_web';
            $condition = 'config_key="MODULE_SERVICE_BUDGET"';
            $setupDB = $DB->get_data($tblname, $condition);
            $dsetupDB = $setupDB[0];
            if($setupDB[2]>0) {
                $params = get_params($dsetupDB['params']);
                $hostname = $params['database']['hostname'];
                $username = $params['database']['username'];
                if(isset($params['database']['userpassword'])) {
                    $userpassword = $params['database']['userpassword'];
                }
                $database = $params['database']['database_name'];

                $DBSB = new Databases($hostname, $username, $password, $database);
              $tblname = 'mst_type_of_service';
    
              include("components/modules/type_of_service/func_type_of_service.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">mst_type_of_service</h6>
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
                           }
                           ?>
                       </div>
                   </div>
               </div>
           <?php
            } else {
                echo "Aplikasi belum disetup";            
            }
    
          } else { 
              $ALERT->notpermission();
          } 
          // End Body
       } 
    }
    ?>
    