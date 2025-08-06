<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671529049";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "SURVEY";
        $mdl_permission = useraccess_v2($modulename);
        if(USERPERMISSION_V2=="810159712762c176ee4bbb27da703a78") {?>
            <script>
                $(document).ready(function() {
                    var table = $('#survey').DataTable( {
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
                                   window.location.href = "index.php?mod=survey&act=view&survey_id="+survey_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-trash'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var survey_id = table.cell( rownumber,0 ).data();
                                   cancelSurvey(survey_id);
                                }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=survey&act=add";
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
                  $primarykey = "survey_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  //view_table($DB, $tblname, $primarykey, $condition, $order);
                  $query = "SELECT survey_id, template_name, pic_name, survey_link, survey.created_by, survey.created_datetime from sa_survey survey join sa_question_template temp on survey.template_id = temp.template_id join sa_pic pic on pic.pic_id = survey.pic_id where survey.status = 'active' LIMIT 0,100";
                  $datatable = $DB1->get_sql($query);
                  $ddatatable = $datatable[0];
                  $qdatatable = $datatable[1];
                  $tdatatable = $datatable[2];
                  $modtitle = 'Catalog listing';
  
              $header = ['Survey ID', 'PIC Name', 'Template Name','Survey Link', 'Created By', 'Created Date'];
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
                                                        if ($ddatatable_header2['Field'] == 'pic_id') {
                                                            $ddatatable_header2['Field'] = 'pic_name';
                                                        }else if($ddatatable_header2['Field'] == 'template_id'){
                                                            $ddatatable_header2['Field'] = 'template_name';
                                                        }else if(in_array($ddatatable_header2['Field'],array('so_number', 'link_datetime', 'extra_information','status', 'type', 'main_template_id'))){
                                                            continue;
                                                        }
                                                        echo "<td>".$ddatatable[$ddatatable_header2['Field']]; ?></td>
                                                    <?php
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
                  include("components/modules/survey/form_survey.php"); 
              } 
    
              // End Function
    
              $database = 'sa_survey';
              include("components/modules/survey/connection.php");
              $DB1 = new Databases($hostname, $username, $userpassword, $database);
              $tblname = 'survey';
    
              include("components/modules/survey/func_survey.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">survey</h6>
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
      // } 
    }
    ?>

    <script>
        function cancelSurvey(survey_id){
            if(confirm('Are you sure to cancel the survey?') == false){
                return false;
            }else{
                $.ajax({
                    url: "components/modules/ajax/ajax.php",
                    type: "POST",
                    datatype:"json",
                    data: {
                        'act': 'cancelSurvey',
                        'survey_id': survey_id
                    },
                    cache: false,
                    success: function(result){
                        if($.trim(result) == 'Success'){
                            alert('Survey Cancelled, link cannot be accessed now');
                        }else{
                            alert("Update Fail");
                        }
                    }
                });
            }

        }
    </script>
    