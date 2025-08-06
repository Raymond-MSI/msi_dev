<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671078547";
        $author = 'Syamsul Arham';
    } else {
    
        $mdlname = "SURVEY";
        $mdl_permission = useraccess_v2($mdlname);
        
        if(USERPERMISSION_V2=="810159712762c176ee4bbb27da703a78") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#trx_survey').DataTable( {
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
                                   var survey_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=trx_survey&act=view&survey_id="+survey_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var survey_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=trx_survey&act=edit&survey_id="+survey_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=trx_survey&act=add";
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
          //if($_SESSION['Microservices_UserLevel'] == "Administrator") {
              function view_data($tblname) {
                  // Definisikan tabel yang akan ditampilkan dalam DataTable
                  global $DB;
                  $primarykey = "survey_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  view_table($DB, $tblname, $primarykey, $condition, $order);
              }
              function form_data($tblname) {
                  include("components/modules/trx_survey/form_trx_survey.php"); 
              } 
    
              // End Function
    
              $database = 'sa_survey';
              include("components/modules/trx_survey/connection.php");
              $DB = new Databases($hostname, $username, $userpassword, $database);
              $tblname = 'trx_survey';
    
              include("components/modules/trx_survey/func_trx_survey.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">trx_survey</h6>
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
       //} 
    }
    ?>
    