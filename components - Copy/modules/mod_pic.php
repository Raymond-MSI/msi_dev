<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671415099";
        $author = 'Syamsul Arham';
    } else {
    
        $mdlname = "PIC";
        $mdl_permission = useraccess_v2($mdlname);
        
        if(USERPERMISSION_V2=="74c1e2647898e2c52ba6322a020e712a") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#pic').DataTable( {
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
                                   var pic_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=pic&act=view&pic_id="+pic_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=pic&act=add";
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
                  $primarykey = "pic_id";
                  $condition = "";
                  $order = "";
                  if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                      global $ALERT;
                      $ALERT->datanotfound();
                  } 
                  //SELECT * FROM sa_pic LIMIT 0, 100
                  //view_table($DB, $tblname, $primarykey, $condition, $order);
                  $query = "SELECT pic_id, pic_name, pic_phone, pic_email, pic_address, customer_company_name, pic_city, pic.created_by, pic.created_datetime FROM sa_pic pic JOIN sa_customer cust ON pic.customer_id = cust.customer_id LIMIT 0,100";
                $datatable = $DB1->get_sql($query);
                $ddatatable = $datatable[0];
                $qdatatable = $datatable[1];
                $tdatatable = $datatable[2];
                $modtitle = 'Catalog listing';

            $header = ['PIC ID', 'PIC Name', 'PIC Email', 'PIC Phone', 'PIC Address', 'PIC City', 'Company Name', 'Created By', 'Created Datetime'];
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
                                                <?php do { ?>
                                                <td><?php
                                                    if ($ddatatable_header2['Field'] == 'customer_id') {
                                                        $ddatatable_header2['Field'] = 'customer_company_name';
                                                    }
                                                    echo $ddatatable[$ddatatable_header2['Field']]; ?></td>
                                                <?php } while ($ddatatable_header2 = $qdatatable_header2->fetch_assoc()); ?>
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
                  include("components/modules/pic/form_pic.php"); 
              } 
    
              // End Function
    
              $database = 'sa_survey';
              include("components/modules/pic/connection.php");
              $DB1 = new Databases($hostname, $username, $userpassword, $database);
              $tblname = 'pic';
    
              include("components/modules/pic/func_pic.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">pic</h6>
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
    