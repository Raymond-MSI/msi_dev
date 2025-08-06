<?php
global $DTSB;
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1667208841";
        $author = 'Syamsul Arham';
    } else {
    //$modulename = "trx_edo_request";
        $mdlname = "RESOURCE_ASSIGNMENT";
        $mdl_permission = useraccess_v2($mdlname);
        if(USERPERMISSION_V2=="bf37f41d848ff7e0d874d3d93e4c561c") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#view_resource_assignment').DataTable( {
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
                                   var id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=resource_assignment&act=view&id="+id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var id = table.cell( rownumber,0 ).data();
                                   var project_code = table.cell( rownumber,1 ).data();
                                   var no_so = table.cell( rownumber,2).data();
                                   window.location.href = "index.php?mod=resource_assignment&act=edit&project_code="+project_code+"&no_so="+no_so+"&id="+id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=resource_assignment&act=add";
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [0],
                               "visible": false,
                           }
                        ],
                     } );
                  } );
               </script>
           <?php 
    
          // Function
          //if($_SESSION['Microservices_UserLevel'] == "Super Admin"  || $_SESSION['Microservices_UserLevel'] == "Super Admin") {
              function view_data($tblname) {
                  // Definisikan tabel yang akan ditampilkan dalam DataTable
                  global $DTSB;
                  $primarykey = "id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  view_table($DTSB, $tblname, $primarykey, $condition, $order);
              }
              function form_data($tblname) {
                global $DTSB;
                  include("components/modules/resource_assignment/form_resource_assignment.php"); 
              } 
    
              // End Function
    
            //   $database = 'sa_wrike_integrate';
            //   include("components/modules/resource_assignment/connection.php");
            //   $DB = new Databases($hostname, $username, $userpassword, $database);
            //   $tblname = 'resource_assignment';
              
            $tblname = 'cfg_web';
            $condition = 'config_key="MODULE_RESOURCE_ASSIGNMENT"';
            $setupDB = $DB->get_data($tblname, $condition);
            $dsetupDB = $setupDB[0];
            if($setupDB[2]>0) {
                $params = get_params($dsetupDB['params']);
                $hostname = $params['database']['hostname'];
                $username = $params['database']['username'];
                $userpassword = $params['database']['userpassword'];
                $database = $params['database']['database_name'];

                $DTSB = new Databases($hostname, $username, $userpassword, $database);
                $tblname = 'view_resource_assignment';

                include("components/modules/resource_assignment/func_resource_assignment.php");
            
    
    
              // Bodyyy
         ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">resource_assignment</h6>
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
       //} 
       
    }
    ?>
    