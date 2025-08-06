<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671431734";
        $author = 'Syamsul Arham';
    } else {
        $modulename = "SURVEY";
        $mdl_permission = useraccess_v2($modulename);
        if(USERPERMISSION_V2=="810159712762c176ee4bbb27da703a78") {?>
            <script>
                $(document).ready(function() {
                    var table = $('#question_template').DataTable( {
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
                                   var template_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=question_template&act=view&template_id="+template_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-pen'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var template_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=question_template&act=edit&template_id="+template_id+"&submit=Submit";
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=question_template&act=add";
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
                  $primarykey = "template_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  //view_table($DB, $tblname, $primarykey, $condition, $order);
                  $query = "SELECT template_id, template_name, valid_year, created_by, template_type, created_datetime from sa_question_template LIMIT 0,100";
                  $datatable = $DB1->get_sql($query);
                  $ddatatable = $datatable[0];
                  $qdatatable = $datatable[1];
                  $tdatatable = $datatable[2];
                  $modtitle = 'Catalog listing';
  
              $header = ['Template ID', 'Template Name', 'Valid Year', 'Template Type', 'Created By', 'Created Date'];
              $header1 = '';
              foreach($header as $head){
                  $header1 .="<th class='text-center align-middle'>" . $head . "</th>";
              }
                  ?>
  
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname; ?>" width="100%" cellspacing="0">
                              <thead>
                                  <tr>
                                      <?php
                                          echo $header1;
                                      ?>
                              </tr>
                              </thead>
                              <tfoot>
                                  <tr>
                                      <?php echo $header1; ?>
                                  </tr>
                              </tfoot>
                              <tbody>
                                  <?php
                                      if($tdatatable > 0) {
                                          do { ?>
                                              <tr>
                                                  <?php 
                                                      $datatable_header2 = $DB1->get_columns($tblname);
                                                      $ddatatable_header2 = $datatable_header2[0];
                                                      $qdatatable_header2 = $datatable_header2[1];
                                                  ?>
                                                  <?php do { ?><?php
                                                    if(!in_array($ddatatable_header2['Field'],array('modified_by', 'modified_datetime', 'questions'))){
                                                        echo "<td>".$ddatatable[$ddatatable_header2['Field']]; ?></td>
                                                    <?php }else{
                                                        continue;
                                                    }
                                                  } while ($ddatatable_header2 = $qdatatable_header2->fetch_assoc()); ?>
                                              </tr>
                                          <?php 
                                          } while ($ddatatable = $qdatatable->fetch_assoc()); 
                                      }
                                  ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <?php
              }
              function form_data($tblname) {
                  include("components/modules/question_template/form_question_template.php"); 
              } 
    
              // End Function
    
            //   $database = 'sa_survey';
            //   include("components/modules/question_template/connection.php");
            //   $DB1 = new Databases($hostname, $username, $userpassword, $database);
            $mdlname = "SURVEY";
            $DB1 = get_conn($mdlname);
              $tblname = 'question_template';
    
              include("components/modules/question_template/func_question_template.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">question_template</h6>
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
    