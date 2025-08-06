<?php
    if((isset($property)) && ($property == 1)) {
        $version = '1.0';
        $released = "1631589701";
        $author = 'Syamsul Arham';
    } else {
    
        $modulename = "Legal Documents";
        $userpermission = useraccess($modulename);
        if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533" || USERPERMISSION=="5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION=="335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0" || USERPERMISSION=="858ba4765e53c712ef672a9570474b1d") {
        ?>
            <script>
                $(document).ready(function() {
                    var table = $('#view_documents').DataTable( {
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
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View Document'><i class='fa fa-eye'></i></span>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var doc_id = table.cell( rownumber,0 ).data();
                                   window.location.href = "index.php?mod=legal_documents&act=view&doc_id="+doc_id+"&submit=Submit";
                               },
                               enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit Document'><i class='fa fa-pen'></i></span>",
                               action: function () {
                                   var rownumber = table.rows({selected: true}).indexes();
                                   var doc_id = table.cell( rownumber,0 ).data();
                                   if(doc_id==null) {
                                       alert ("Please select the data.");
                                   } else {
                                        window.location.href = "index.php?mod=legal_documents&act=edit&doc_id="+doc_id+"&submit=Submit";
                                   }
                                }
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add Document'><i class='fa fa-plus'></i></span>",
                               action: function () {
                                   window.location.href = "index.php?mod=legal_documents&act=add";
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add Provider'><i class='fa fa-object-ungroup'></i></span>",
                               action: function () {
                                   window.location.href = "index.php?mod=legal_documents&act=prov";
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add Category'><i class='fa fa-layer-group'></i></span>",
                               action: function () {
                                   window.location.href = "index.php?mod=legal_documents&act=cat";
                               },
                               // enabled: false
                            },
                            {
                               text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add Category'><i class='fa fa-tools'></i></span>",
                               action: function () {
                                   window.location.href = "index.php?mod=legal_documents&act=setup";
                               },
                               // enabled: false
                            }
                        ],
                        "columnDefs": [
                           {
                              "targets": [0 ],
                               "visible": false,
                           },
                           {
                                "targets": [5,6],
                                "className": 'dt-body-right',
                                "render": DataTable.render.datetime('DD MMM YYYY'),
                           },
                        ],
                        "order": [
                            [ 6, "asc"]
                        ]
                     } );
                  } );
               </script>
           <?php 
    
          // Function
        //   if($_SESSION['Microservices_UserLevel'] == "Administrator") {
            function view_data($tblname) {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DBLD;
                $primarykey = "doc_id";
                $condition = "";
                $order = "date_expired";
                if(isset($_GET['err']) && $_GET['err']=="datanotselected") { 
                    global $ALERT;
                    $ALERT->datanotselected();
                } 
                view_table($DBLD, $tblname, $primarykey, $condition, $order);
            }
            function form_data($tblname) {
                include("components/modules/legal_documents/form_legal_documents.php"); 
            } 
            function form_add_prov($tblname) {
                include("components/modules/legal_documents/form_legal_provider.php"); 
            }
            function form_add_cat($tblname) {
                include("components/modules/legal_documents/form_legal_category.php"); 
            }
            function setup($tblname) {
                include("components/modules/legal_documents/setup.php"); 
            }
        
              // End Function
    
            //   $database = 'sa_legal_documents';
            //   include("components/modules/legal_documents/connection.php");
            $tblname = 'cfg_web';
            $condition = 'config_key="MODULE_LEGAL_DOCUMENTS"';
            $setupDB = $DB->get_data($tblname, $condition);
            $dsetupDB = $setupDB[0];
            if($setupDB[2]>0) {
                $params = get_params($dsetupDB['params']);
                $hostname = $params['database']['hostname'];
                $username = $params['database']['username'];
                $userpassword = $params['database']['userpassword'];
                $database = $params['database']['database_name'];
                $DBLD = new Databases($hostname, $username, $userpassword, $database);
        
                include("components/modules/legal_documents/func_legal_documents.php");
                // include("components/modules/legal_documents/func_legal_provider.php");
                // include("components/modules/legal_documents/func_legal_category.php");
        
                $tblname = 'view_documents';
                // Body
                    ?>
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <?php
                                if(!isset($_GET['act'])) {
                                    echo "List Documents";
                                } elseif($_GET['act'] == 'add') {
                                    echo "Add Document";
                                } elseif($_GET['act'] == 'new') {
                                    echo "New Dcument";
                                } elseif($_GET['act'] == 'edit') {
                                    echo "Edit Document";
                                } elseif($_GET['act'] == 'del') {
                                    echo "Delete Document";
                                } elseif($_GET['act'] == 'save') {
                                    echo "Save Document";
                                } elseif($_GET['act'] == "prov") {
                                    echo "Add Provider";
                                } elseif($_GET['act'] == "cat") {
                                    echo "Add Category";
                                } elseif($_GET['act'] == "setup") {
                                    echo "Setup";
                                }
                                ?>
                            </h6>
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
                            } elseif($_GET['act'] == "prov") {
                                form_add_prov($tblname);
                            } elseif($_GET['act'] == "cat") {
                                form_add_CAT($tblname);
                            } elseif($_GET['act'] == "setup") {
                                setup($tblname);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo "Aplikasi belum disetup";            
            }
    
        //   } else { 
        //       $ALERT->notpermission();
        //   } 
          // End Body
       } else {
           $ALERT->notpermission();
       }
    }
    ?>
    