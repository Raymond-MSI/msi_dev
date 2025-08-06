<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1749001173";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "hcm_requirement_interview";
        $userpermission = useraccess($modulename);
        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#hcm_requirement_interview').DataTable( {
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
                                   var email_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm_requirement_interview&act=view&email_id="+email_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var email_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=hcm_requirement_interview&act=edit&email_id="+email_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=hcm_requirement_interview&act=add";
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
                  global $DB;
                  $primarykey = "email_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  view_table($DB, $tblname, $primarykey, $condition, $order);
              }
              function form_data($tblname) {
                  include("components/modules/hcm_requirement_interview/form_hcm_requirement_interview.php"); 
              } 
    
              // End Function
    
              $database = 'sa_md_hcm';
              include("components/modules/hcm_requirement_interview/connection.php");
              $DB = new Databases($hostname, $username, $userpassword, $database);
              $tblname = 'hcm_requirement_interview';
    
              include("components/modules/hcm_requirement_interview/func_hcm_requirement_interview.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">hcm_requirement_interview</h6>
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
              $ALERT->notpermission();
          } 
          // End Body
       } 
    }
    ?>
    