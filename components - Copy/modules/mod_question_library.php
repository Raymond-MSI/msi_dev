<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671156906";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "QUESTION_LIBRARY";
        $mdl_permission = useraccess_v2($modulename);
        if(USERPERMISSION_V2=="e3abf64d4a6b8bdb54bc8866ee2ed067") {?>
            <script>
                $(document).ready(function() {
                    var table = $('#question_library').DataTable( {
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
                                   var question_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=question_library&act=view&question_id="+question_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var question_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=question_library&act=edit&question_id="+question_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=question_library&act=add";
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
                  global $DB1;
                  $primarykey = "question_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  view_table($DB1, $tblname, $primarykey, $condition, $order);
                  
              }
              function form_data($tblname) {
                  include("components/modules/question_library/form_question_library.php"); 
              } 
    
              // End Function
    
              $database = 'sa_survey';
              include("components/modules/question_library/connection.php");
              $DB1 = new Databases($hostname, $username, $userpassword, $database);
              $tblname = 'question_library';
    
              include("components/modules/question_library/func_question_library.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">question_library</h6>
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
    