<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1671415099";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "SURVEY";
        $mdl_permission = useraccess_v2($modulename);
        if(USERPERMISSION_V2=="810159712762c176ee4bbb27da703a78") {?>
            <script>
                $(document).ready(function() {
                    var table = $('#pic').DataTable( {
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                               text: "<i class='fa fa-pencil'></i>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var pic_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=survey&sub=mod_pic&act=edit&pic_id="+pic_id+"&submit=Submit";
                               }
                            },
                            {
                               text: "<i class='fa fa-plus'></i>",
                               action: function () {
                                   window.location.href = "index.php?mod=survey&sub=mod_pic&act=add";
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [ 0, 8, 9],
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
                  $query = "SELECT pic_id, pic_name, pic_phone, pic_email, pic_address, customer_company_name, title, pic_city, pic.created_by, pic.created_datetime FROM sa_pic pic JOIN sa_customer cust ON pic.customer_code = cust.customer_code";
                $datatable = $DB1->get_sql($query);
                $ddatatable = $datatable[0];
                $qdatatable = $datatable[1];
                $tdatatable = $datatable[2];
                $modtitle = 'Catalog listing';

            $header = ['ID', 'Name', 'Email', 'Phone', 'Address', 'City', 'Title','Company Name', 'Created By', 'Created Datetime'];
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
                                                <?php
                                                    if ($ddatatable_header2['Field'] == 'customer_code') {
                                                        $ddatatable_header2['Field'] = 'customer_company_name';
                                                    }
                                                    if($ddatatable_header2['Field'] != 'survey_count'){
                                                        echo '<td>'.$ddatatable[$ddatatable_header2['Field']]; ?></td>
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
                  include("components/modules/pic/form_pic.php"); 
              } 
    
              // End Function
    
            //   $database = 'sa_survey';
            //   include("components/modules/pic/connection.php");
            //   $DB1 = new Databases($hostname, $username, $userpassword, $database);
            $mdlname = "SURVEY";
            $DB1 = get_conn($mdlname);
            $tblname = 'pic';
    
              include("components/modules/pic/func_pic.php");
    
              // Body
                  ?>
              <div class="col-lg-12">
                  <div class="card shadow mb-4">
                       <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">PIC Customer</h6>
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
               <?php if(isset($_GET['act']) && $_GET['act']=='edit') { 
                    $querylog = 'select * from sa_logs where pic_id = '.$_GET['pic_id'];
                    $logpic = $DB1->get_sql($querylog);
                    $log = $logpic[0];
                    $nextlog = $logpic[1];
                    ?>
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h5>History Logs</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead class="bg-light">
                                        <th class="col-lg-2">Date</th><th class="col-lg-2">Time</th><th class="col-lg-8">Description</th></thead>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if($logpic[2] > 0){
                                        $tgl = ""; 
                                        ?>
                                        <?php do { ?>
                                            <tr>
                                                <td style="font-size: 12px">
                                                    <?php if($tgl != date("Y-m-d", strtotime($log['entry_date']))) { ?>
                                                        <table class="table table-sm table-light table-striped">
                                                            <tr>
                                                                <td class="text-center fw-bold" colspan="2">
                                                                    <?php echo date("Y", strtotime($log['entry_date'])); ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center"><?php echo date("M", strtotime($log['entry_date'])); ?></td>
                                                                <td class="text-center"><?php echo date("d", strtotime($log['entry_date'])); ?></td>
                                                            </tr>
                                                        </table>
                                                        
                                                    <?php } ?>
                                                </td>
                                                <td style="font-size: 12px"><?php echo date("H:i:s", strtotime($log['entry_date'])); ?></td>
                                                <?php 
                                                if($log['entry_by']!="system") {
                                                    if(strpos($log['entry_by'], "<")==0)
                                                    {
                                                        $name = $DBHCM->get_profile($log['entry_by'], "employee_name"); 
                                                    } else
                                                    {
                                                        $name = $log['entry_by'];
                                                    }
                                                } else {
                                                    $name = "system";
                                                }
                                                ?>
                                                <td style="font-size: 12px">
                                                    <?php
                                                    $desc = str_replace('>', '&gt;', str_replace('<', '&lt;',$log['description']));
                                                    echo $desc . "Performed by " . $name; 
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php $tgl = date("Y-m-d", strtotime($log['entry_date'])); ?>
                                        <?php } while($log=$nextlog->fetch_assoc()); }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
           <?php
    
          } else { 
              $ALERT->notpermission();
          } 
          // End Body
       //} 
    }
    ?>
    