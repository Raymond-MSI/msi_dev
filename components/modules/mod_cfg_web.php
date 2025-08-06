<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = '1626808295';
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "cfg_web";
        $userpermission = useraccess($modulename);
        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#cfg_web').DataTable( {
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
                                   window.location.href = "index.php?mod=cfg_web&act=view&id="+id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var id = table.cell( rownumber,0 ).data();
                                   var mod_id = <?php echo $_GET['mod_id']; ?>;
                                   window.location.href = "index.php?mod=cfg_web&act=edit&mod_id="+mod_id+"&id="+id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   var mod_id = <?php echo $_GET['mod_id']; ?>;
                                   window.location.href = "index.php?mod=cfg_web&act=add&mod_id="+mod_id;
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [ 0,5,6],
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
                  global $DB;
                  $primarykey = "mod_id";
                  $condition = "parent=" . $_GET['mod_id'];
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                              view_table($DB, $tblname, $primarykey, $condition, $order);
              }
              function form_data($tblname) {
                  include("components/modules/cfg_web/form_cfg_web.php"); 
              } 
    
              // End Function
    
            //   $hostname = 'localhost';
              $database = 'sa_microservices';
            //   $username = 'root';
            //   $password = '';
              include_once( "components/classes/func_databases_v3.php" );
              $DB = new Databases($hostname, $username, $password, $database);
              $tblname = 'cfg_web';
    
              include("components/modules/cfg_web/func_cfg_web.php");
    
              ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">cfg_web</h6>
                      </div>
                      <div class="card-body">
                          <?php
         // Body
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
              $ALERT->notpermission();
          } 
          // End Body
       } 
    }
    ?>
    