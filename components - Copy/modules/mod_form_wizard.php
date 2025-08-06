<?php
if((isset($property)) && ($property == 1)) {
  $version = '2.0';
  $author = 'Syamsul Arham';
} else {
?>
<?php
 $editFormAction = $_SERVER[ 'PHP_SELF' ];
 if ( isset( $_SERVER[ 'QUERY_STRING' ] ) ) {
   $editFormAction .= "?mod=" . htmlentities( $_GET[ "mod" ] );
 }
 ?>

<?php
function typeexp($i) {
  $typeexp = explode("(", $_POST['type'][$i]);
  switch ($typeexp[0]) {
    case "varchar":
      $type = "text";
      break;
    case "timestamp":
      $type = "date";
      break;
    case "datetime":
      $type = "date";
      break;
    case "tinyint":
      $type = "int";
      break;
    default:
      $type = $typeexp[0];
  }
  return $type;
}
?>

<?php function listDatabases() { ?>
  <?php 
  global $DB;
  $gDatabases = $DB->get_databases();
  $dDatabases = $gDatabases[0];
  $qDatabases = $gDatabases[1];
  ?>
  <select class="form-control" name="table_name">
  <?php do { ?>
    <option value="<?php echo $dDatabases["Database"]; ?>"><?php echo $dDatabases["Database"]; ?></option>
  <?php } while($dDatabases=$qDatabases->fetch_assoc()); ?>
  </select>
<?php } ?>

<?php function listTables() { ?>
  <?php global $DB; ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Table Name</th>
            <th>Select</th>
          </tr>
        </thead>
        <tbody>
          <?php
//          $database = $_POST["table_name" . $_POST["database"]];
          $database = $_POST["table_name"];
          $gTable = $DB->get_tables($database);
          if ( $gTable[ 2 ] > 0 ) {
            $dTable = $gTable[ 0 ];
            $qTable = $gTable[ 1 ];
            $i = 0;
            do {
              ?>
              <tr>
                <td>
                  <input type="hidden" name="database" value="<?php echo $database; ?>">
                  <input type="hidden" name="table_name<?php echo $i; ?>" value="<?php echo $dTable["Tables_in_" . $_POST["table_name"]]; ?>">
                  <?php /*?><?php echo $dTable["Tables_in_" . $_POST["table_name" . $_POST["database"]]]; ?><?php */?>
                  <?php echo $dTable["Tables_in_" . $_POST["table_name"]]; ?>
                </td>
                <td>
                  <input type="radio" name="selected_table" value="<?php echo $dTable['Tables_in_' . $_POST['table_name']]; ?>">
                  <?php /*?><input type="checkbox" name="checkBox[]" value="<?php echo $dTable['Tables_in_' . $_POST['table_name' . $_POST['database']]]; ?>"><?php */?>
                </td>
              </tr>
              <input type="hidden" name="totalTables" value="<?php echo $i; ?>">
              <?php 
              $i++;
            } while($dTable=$qTable->fetch_assoc()); 
          } 
          ?>
        </tbody>
      </table>
<?php } ?>

<?php function tableProperties() { ?>
  <?php
  $selected = $_POST['selected_table']; 
  if(empty($selected)) {
    echo "No selected";
  } else { 
      $DB = new Databases("localhost", "root", "", $_POST['database']);
      $table_name = $_POST['selected_table'];
      $tableName = substr($table_name, 3);
      $gColumns = $DB->get_columns($tableName);
      $dColumns = $gColumns[0];
      $qColumns = $gColumns[1];
      ?>
      <input type="hidden" name="database" value="<?php echo $_POST['database']; ?>">
      <div class="form-group row">
        <label for="table_name" class="col-sm-2 col-form-label">Table Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="table_name" placeholder="Table Name" value="<?php echo $table_name; ?>" name="table_name" readonly>
        </div>
      </div>
      <div class="form-group row">
        <label for="form_name" class="col-sm-2 col-form-label">Form Name</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="form_name" placeholder="Form Name" value="<?php echo substr($table_name, 3, strlen($table_name)); ?>" name="form_name">
        </div>
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Null</th>
            <th>Key</th>
            <th>Default</th>
            <th>Extra</th>
            <th>Select</th>
            <th class="text-center" style="width:200px">Add-On</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=0; ?>
      <?php do { ?>
        <tr>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Field"]; ?>" name="field[]" readonly></td>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Type"]; ?>" name="type[]" readonly></td>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Null"]; ?>" name="Null[]" readonly></td>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Key"]; ?>" name="key[]" readonly></td>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Default"]; ?>" name="default[]" readonly></td>
          <td><input class="border-0 bg-transparent" type="text" value="<?php echo $dColumns["Extra"]; ?>" name="extra[]" readonly></td>
          <td class="text-center">
            <input type="checkbox" name="selected[<?php echo $i; ?>]" value="<?php echo $dColumns['Field']; ?>">
          </td>
          <td class="text-center">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop<?php echo $i; ?>">
              Data Addon
            </button>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop<?php echo $i; ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Data Addon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <textarea class="form-control" name="toolbox[]" rows="4"></textarea>
                    <div class="text-left">
                    Format:<br/>
                    type: [select/checkbox/radio/textarea];<br/>
                    src: [tblname];<br/>
                    key: [primary key];<br/>
                    title: [title];
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
          <?php $i++; ?>
      <?php } while($dColumns=$qColumns->fetch_assoc()); ?>
          </tbody>
      </table>
  <?php } ?>
<?php } ?>

<?php function resultForm() { ?>
    <?php global $DB; ?>
    <?php
    $selected = $_POST['selected']; 
    if(empty($selected)) {
        echo "No selected";
    } else { 
        $table_name = $_POST["table_name"];  
        $CR = '
    ';
        $tblname = substr($_POST["table_name"], 3); 

        // Create module
        $content = '<?php' . $CR;
        $content .= 'if((isset($property)) && ($property == 1)) {' . $CR;
        $content .= '    $version = \'1.0\';' . $CR;
        $content .= '    $released = "' . strtotime(date("d F Y H:i:s")) . '";' . $CR;
        $content .= '    $author = \'Syamsul Arham\';' . $CR;
        $content .= '} else {' . $CR;
        $content .= $CR;
        $content .= '    $modulename = "' . substr($table_name, 3, strlen($table_name)) . '";' . $CR;
        $content .= '    $userpermission = useraccess($modulename);' . $CR;
        $content .= '    if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {' . $CR;
        $content .= '    ?>' . $CR;
        $content .= '        <script>' . $CR;
        $content .= '            $(document).ready(function() {' . $CR;
        $content .= '                var table = $(\'#' . substr($table_name, 3, strlen($table_name)) . '\').DataTable( {' . $CR;
        $content .= '                    dom: \'Blfrtip\',' . $CR;
        $content .= '                    select: {' . $CR;
        $content .= '                        style: \'single\'' . $CR;
        $content .= '                    },' . $CR;
        $content .= '                    buttons: [' . $CR;
        $content .= '                        {' . $CR;
        $content .= '                           extend: \'colvis\',' . $CR;
        $content .= '                           text: "<i class=\'fa fa-columns\'></i>",' . $CR;
        $content .= '                           collectionLayout: \'fixed four-column\'' . $CR;
        $content .= '                        },' . $CR;
        $content .= '                        {' . $CR;
        $content .= '                           text: "<i class=\'fa fa-eye\'></i>",' . $CR;
        $content .= '                           action: function () {' . $CR;
        $content .= '                               var rownumber = table.rows({selected: true}).indexes();' . $CR;
        $content .= '                               var ' . $_POST['field'][0] . ' = table.cell( rownumber,0 ).data();' . $CR;
        $content .= '                               window.location.href = "index.php?mod=' . $_POST['form_name'] . '&act=view&' . $_POST['field'][0] . '="+' . $_POST['field'][0] . '+"&submit=Submit";' . $CR;
        $content .= '                           },' . $CR;
        $content .= '                           enabled: false' . $CR;
        $content .= '                        },' . $CR;
        $content .= '                        {' . $CR;
        $content .= '                           text: "<i class=\'fa fa-pen\'></i>",' . $CR;
        $content .= '                           action: function () {' . $CR;
        $content .= '                               var rownumber = table.rows({selected: true}).indexes();' . $CR;
        $content .= '                               var ' . $_POST['field'][0] . ' = table.cell( rownumber,0 ).data();' . $CR;
        $content .= '                               window.location.href = "index.php?mod=' . $_POST['form_name'] . '&act=edit&' . $_POST['field'][0] . '="+' . $_POST['field'][0] . '+"&submit=Submit";' . $CR;
        $content .= '                            }' . $CR;
        $content .= '                        },' . $CR;
        $content .= '                        {' . $CR;
        $content .= '                           text: "<i class=\'fa fa-plus\'></i>",' . $CR;
        $content .= '                           action: function () {' . $CR;
        $content .= '                               window.location.href = "index.php?mod=' . $_POST['form_name'] . '&act=add";' . $CR;
        $content .= '                           },' . $CR;
        $content .= '                           // enabled: false' . $CR;
        $content .= '                        }' . $CR;
        $content .= '                    ],' . $CR;
        $content .= '                    "columnDefs": [' . $CR;
        $content .= '                       {' . $CR;
        $content .= '                          "targets": [ ],' . $CR;
        $content .= '                           "visible": false,' . $CR;
        $content .= '                       }' . $CR;
        $content .= '                    ],' . $CR;
        $content .= '                 } );' . $CR;
        $content .= '              } );' . $CR;
        $content .= '           </script>' . $CR;
        $content .= '       <?php ' . $CR;
        $content .= $CR;
        $content .= '      // Function' . $CR;
        $content .= '      if($_SESSION[\'Microservices_UserLevel\'] == "Administrator") {' . $CR;
        $content .= '          function view_data($tblname) {' . $CR;
        $content .= '              // Definisikan tabel yang akan ditampilkan dalam DataTable' . $CR;
        $content .= '              global $DB;' . $CR;
        $content .= '              $primarykey = "' . $_POST['field'][0] . '";' . $CR;
        $content .= '              $condition = "";' . $CR;
        $content .= '              $order = "";' . $CR;
        $content .= '              if(isset($_GET[\'err\']) && $_GET[\'err\']=="datanotfound") { ' . $CR;
        $content .= '                  global $ALERT;' . $CR;
        $content .= '                  $ALERT->datanotfound();' . $CR; 
        $content .= '              } ' . $CR;
        $content .= '              view_table($DB, $tblname, $primarykey, $condition, $order);' . $CR;
        $content .= '          }' . $CR;
        $content .= '          function form_data($tblname) {' . $CR;
        $content .= '              include("components/modules/' . $_POST['form_name'] . '/form_' . $_POST['form_name'] . '.php"); ' . $CR;
        $content .= '          } ' . $CR;
        $content .= $CR;
        $content .= '          // End Function' . $CR;
        $content .= $CR;
        $content .= '          $database = \'' . $_POST['database'] . '\';' . $CR;
        $content .= '          include("components/modules/' . $_POST['form_name'] . '/connection.php");' . $CR;
        $content .= '          $DB = new Databases($hostname, $username, $userpassword, $database);' . $CR;
        $content .= '          $tblname = \'' . substr($table_name, 3, strlen($table_name)) . '\';' . $CR;
        $content .= $CR;
        $content .= '          include("components/modules/' . $_POST['form_name'] . '/func_' . $_POST['form_name'] . '.php");' . $CR;
        $content .= $CR;
        $content .= '          // Body' . $CR;
        $content .= '              ?>' . $CR;
        $content .= '          <div class="col-lg-12">' . $CR;
        $content .= '              <div class="card shadow mb-4">' . $CR;
        $content .= '                   <div class="card-header py-3">' . $CR;
        $content .= '                      <h6 class="m-0 font-weight-bold text-primary">' . substr($table_name, 3, strlen($table_name)) . '</h6>' . $CR;
        $content .= '                   </div>' . $CR;
        $content .= '                   <div class="card-body">' . $CR;
        $content .= '                       <?php' . $CR;
        $content .= '                       if(!isset($_GET[\'act\'])) {' . $CR;
        $content .= '                          view_data($tblname);' . $CR;
        $content .= '                       } elseif($_GET[\'act\'] == \'add\') {' . $CR;
        $content .= '                          form_data($tblname);' . $CR;
        $content .= '                       } elseif($_GET[\'act\'] == \'new\') {' . $CR;
        $content .= '                          new_projects($tblname);' . $CR;
        $content .= '                       } elseif($_GET[\'act\'] == \'edit\') {' . $CR;
        $content .= '                          form_data($tblname);' . $CR;
        $content .= '                       } elseif($_GET[\'act\'] == \'del\') {' . $CR;
        $content .= '                          echo \'Delete Data\';' . $CR;
        $content .= '                       } elseif($_GET[\'act\'] == \'save\') {' . $CR;
        $content .= '                          form_data($tblname);' . $CR;
        $content .= '                       }' . $CR;
        $content .= '                       ?>' . $CR;
        $content .= '                   </div>' . $CR;
        $content .= '               </div>' . $CR;
        $content .= '           </div>' . $CR;
        $content .= '       <?php' . $CR;
        $content .= $CR;
        $content .= '      } else { ' . $CR;
        $content .= '          $ALERT->notpermission();' . $CR;
        $content .= '      } ' . $CR;
        $content .= '      // End Body' . $CR;
        $content .= '   } ' . $CR;
        $content .= '}' . $CR;
        $content .= '?>' . $CR;
        
        $directory = './components/modules';
        $fp = fopen($directory . '/mod_' . $_POST['form_name'] . '.php', 'w');
        fwrite($fp, $content);
        fclose($fp);

        // Create FORM
        $content = '<?php' . $CR;
        $content .= 'if($_GET[\'act\']==\'edit\') {' . $CR;
        $content .= '    global $DB;' . $CR;
        $content .= '    $condition = "' . $_POST['field'][0] . '=" . $_GET[\'' . $_POST['field'][0] . '\'];' . $CR;
        $content .= '    $data = $DB->get_data($tblname, $condition);' . $CR;
        $content .= '    $ddata = $data[0];' . $CR;
        $content .= '    $qdata = $data[1];' . $CR;
        $content .= '    $tdata = $data[2];' . $CR;
        $content .= '}' . $CR;
        $content .= '?>' . $CR;
        $content .= '<form method="post" action="index.php?mod=<?php echo $_GET[\'mod\']; ?>">' . $CR;
        $content .= '    <div class="row">' . $CR;
        $content .= '        <div class="col-lg-6">' . $CR;
        // for($i=0; $i<count($_POST['field']); $i++) {
        for($i=0; $i<count($_POST['selected']); $i++) {
          $content .= '            <div class="row mb-3">' . $CR;
            $content .= '                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">';
            $titleexp = explode("_", $_POST['field'][$i]);
            $title = ucfirst($titleexp[0]);
            for($x=1;$x<count($titleexp); $x++) {
              $title .= " " . ucfirst($titleexp[$x]);
            }
        $content .= $title . '</label>' . $CR;
            $content .= '                <div class="col-sm-9">' . $CR;
            if($_POST['toolbox'][$i]!=""){ 
              $exp = explode(";", $_POST['toolbox'][$i]);
              $type1 = explode(":", $exp[0]);
              $type = trim($type1[1]);
              $scr1 = explode(":", $exp[1]);
              $scr = trim($scr1[1]);
              $key1 = explode(":", $exp[2]);
              $key = trim($key1[1]);
              $title1 = explode(":", $exp[3]);
              $title = trim($title1[1]); print_r($type);
              if($type=="select") {
                $content .= '                <select class="form-control" id="' . $_POST['field'][$i] . '" name="' . $_POST['field'][$i] . '">' . $CR;
                $content .= '                    <?php' . $CR;
                $content .= '                    $tblname="' . $scr . $CR . '";';
                $content .= '                    $condition="";' . $CR;
                $content .= '                    $order="";' . $CR;
                $content .= '                    $select = $DB->get_data($tblname, $condition, $order);' . $CR;
                $content .= '                    $dselect = $select[0];' . $CR;
                $content .= '                    $qselect = $select[1];' . $CR;
                $content .= '                    $tselect = $select[2];' . $CR;
                $content .= '                    do {' . $CR;
                $content .= '                    ?>' . $CR;
                $content .= '                        <option value="<?php echo $dselect[\'' . $key . '\']; ?>">' . $CR;
                $content .= '                            <?php echo $dselect["' . $title . '"]; ?>' . $CR;
                $content .= '                        </option>' . $CR;
                $content .= '                    <?php } while($dselect=$qselect->fetch_assoc()); ?>' . $CR;
                $content .= '                </select>' . $CR;
              } elseif($type=="checkbox") {
                $content .= '                <div class="col-sm-9">' . $CR;
                $content .= '                    <?php' . $CR;
                $content .= '                    $tblname="' . $scr . $CR . '";';
                $content .= '                    $condition="";' . $CR;
                $content .= '                    $order="";' . $CR;
                $content .= '                    $select = $DB->get_data($tblname, $condition, $order);' . $CR;
                $content .= '                    $dselect = $select[0];' . $CR;
                $content .= '                    $qselect = $select[1];' . $CR;
                $content .= '                    $tselect = $select[2];' . $CR;
                $content .= '                    do {' . $CR;
                $content .= '                    ?>' . $CR;
                $content .= '                    <div class="form-check">' . $CR;
                $content .= '                        <input class="form-check-input" type="checkbox" name="' . $_POST['field'][$i] . '" id="' . $_POST['field'][$i] . '" value="<?php echo $dselect[\'' . $key . '\']; ?>">' . $CR;
                $content .= '                        <label class="form-check-label"><?php echo $dselect["' . $title . '"]; ?></label>' . $CR;
                $content .= '                    </div>' . $CR;
                $content .= '                    <?php } while($dselect=$qselect->fetch_assoc()); ?>' . $CR;
                $content .= '                </div>' . $CR;
              } elseif($type=="radio") {
                $content .= '                <div class="col-sm-9">' . $CR;
                $content .= '                    <?php' . $CR;
                $content .= '                    $tblname="' . $scr . '";'.  $CR;
                $content .= '                    $condition="";' . $CR;
                $content .= '                    $order="";' . $CR;
                $content .= '                    $select = $DB->get_data($tblname, $condition, $order);' . $CR;
                $content .= '                    $dselect = $select[0];' . $CR;
                $content .= '                    $qselect = $select[1];' . $CR;
                $content .= '                    $tselect = $select[2];' . $CR;
                $content .= '                    do {' . $CR;
                $content .= '                    ?>' . $CR;
                $content .= '                    <div class="form-check">' . $CR;
                $content .= '                        <input class="form-check-input" type="radio" name="' . $_POST['field'][$i] . '" id="' . $_POST['field'][$i] . '" value="<?php echo $dselect[\'' . $key . '\']; ?>">' . $CR;
                $content .= '                        <label class="form-check-label"><?php echo $dselect["' . $title . '"]; ?></label>' . $CR;
                $content .= '                    </div>' . $CR;
                $content .= '                    <?php } while($dselect=$qselect->fetch_assoc()); ?>' . $CR;
                $content .= '                </div>' . $CR;
              } elseif($type=="textarea") {
                $content .= '                <textarea class="form-control" id="' . $_POST['field'][$i] . '" name="' . $_POST['field'][$i] . '" rows="3"><?php if($_GET[\'act\']==\'edit\') { echo $ddata[\'' . $_POST['field'][$i] . '\']; } ?></textarea>' . $CR;
              }
            } else {
              $content .= '                <input type="text" class="form-control form-control-sm" id="' . $_POST['field'][$i] . '" name="' . $_POST['field'][$i] . '" value="<?php if($_GET[\'act\']==\'edit\') { echo $ddata[\'' . $_POST['field'][$i] . '\']; } ?>">' . $CR;
            }
            $content .= '                </div>' . $CR;
            $content .= '            </div>' . $CR;
            if($i==(int)(count($_POST['field'])/2)) {
                $content .= '        </div>' . $CR;
                $content .= '        <div class="col-lg-6">' . $CR;
            }
        }
        $content .= '        </div>' . $CR;
        $content .= '    </div>' . $CR;
        $content .= '    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">' . $CR;
        $content .= '    <?php if(isset($_GET[\'act\']) && $_GET[\'act\']==\'edit\') { ?>' . $CR;
        $content .= '        <input type="submit" class="btn btn-primary" name="save" value="Save">' . $CR;
        $content .= '    <?php } elseif(isset($_GET[\'act\']) && $_GET[\'act\']==\'add\') { ?>' . $CR;
        $content .= '        <input type="submit" class="btn btn-primary" name="add" value="Save">' . $CR;
        $content .= '    <?php } ?>' . $CR;
        $content .= '</form>' . $CR;

        $directory = './components/modules/' . $_POST['form_name'];
        if (!mkdir($directory)) {
          die('Failed to create directories...');
        }
        $fp = fopen($directory . '/form_' . $_POST['form_name'] . '.php', 'w');
        fwrite($fp, $content);
        fclose($fp);

        // Create func form (Update/Insert data)
        // Insert Data
        $content = '<?php' . $CR;
        $content .= 'if(isset($_POST[\'add\'])) {' . $CR;
          $type = typeexp(0);

            $insert1 = '`' . $_POST['field'][0] . '`';
            $insert2 = "%s";
            $insert3 = "        GetSQLValueString(\$_POST['" . $_POST['field'][0] . "'], \"" . $type . "\")";
            // for($i=1; $i<count($_POST['field']); $i++) {
            for($i=1; $i<count($_POST['selected']); $i++) {
              $type = typeexp($i);
              $insert1 .= ",`" . $_POST['field'][$i] . '`';
              $insert2 .= ",%s";
              $insert3 .= "," . $CR . "        GetSQLValueString(\$_POST['" . $_POST['field'][$i] . "'], \"" . $type . "\")"; 
            }
        $content .= '    $insert = sprintf("(' . $insert1 . ') VALUES (' . $insert2 . ')",' . $CR . $insert3 . $CR;
        $content .= '    );' . $CR;
        $content .= '    $res = $DB->insert_data($tblname, $insert);' . $CR;
        $content .= '    $ALERT->savedata();' . $CR;
        $content .= '} elseif(isset($_POST[\'save\'])) {' . $CR;
        $content .= '    $condition = "' . $_POST['field'][0] . '=" . $_POST[\'' . $_POST['field'][0] . '\'];' . $CR;
            $type = typeexp(0);
            $update1 = '`' . $_POST['field'][0] . '`=%s';
            $update2 = '        GetSQLValueString($_POST[\'' . $_POST['field'][0] . '\'], "' . $type . '")';
            // for($i=1; $i<count($_POST['field']); $i++) {
            for($i=1; $i<count($_POST['selected']); $i++) {
                $type = typeexp($i);
                $update1 .= ',`' . $_POST['field'][$i] . '`=%s';
                $update2 .= ',' . $CR . '        GetSQLValueString($_POST[\'' . $_POST['field'][$i] . '\'], "' . $type . '")';
            }
        $content .= '    $update = sprintf("' . $update1 . '",' . $CR . $update2 . $CR . ');' . $CR;
        $content .= '    $res = $DB->update_data($tblname, $update, $condition);' . $CR;
        $content .= '    $ALERT->savedata();' . $CR;
        $content .= '}' . $CR;
        $content .= '?>';

        $directory = './components/modules/' . $_POST['form_name'];
        $fp = fopen($directory . '/func_' . $_POST['form_name'] . '.php', 'w');
        fwrite($fp, $content);
        fclose($fp);

        // connection
        $directory = './components/modules/' . $_POST['form_name'];
        $fp = fopen($directory . '/connection.php', 'w');
        $content = "<?php" . $CR;
        $content .= "\$hostname = \"localhost\";" . $CR;
        $content .= "\$username = \"root\";" . $CR;
        $content .= "\$userpassword = \"\";";
        $content .= "?>";
        fwrite($fp, $content);
        fclose($fp);
?>
        <h1>Selamat!!!</h1>
        <p>Module yang Anda configure sudah jadi. Anda bisa mengaksesnya <a href="index.php?mod=<?php echo $_POST['form_name']; ?>">disini.</a></p>
        <p>Untuk membuat menu, silahkan Next</p>
    <?php 
    } 
} 
?>

<?php function menu() { ?>
  <script>
  window.location.href = "index.php?mod=menu&act=add";
  </script>
<?php } ?>


<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>
          <?php
          if(!isset($_POST["step"]) || $_POST["step"]==1) {
            echo "Select Database";
            $step = 2;
          } elseif($_POST["step"]==2) {
            echo "Select Table";
            $step = 3;
          } elseif($_POST["step"]==3) {
            echo "Table Properties";
            $step = 4;
          } elseif($_POST["step"]==4) {
            echo "Create application";
            $step = 5;
          } elseif($_POST["step"]==5) {
            echo "Create Menu";
            $step = 5;
          }
          ?>
        </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form method="post" action="<?php echo $editFormAction; ?>">
          <?php
          if(!isset($_POST["step"]) || $_POST["step"]==1) {
            $step = 2;
            listDatabases();
          } elseif($_POST["step"]==2) {
            $step = 3;
            listTables();
          } elseif($_POST["step"]==3) {
            $step = 4;
            tableProperties();
          } elseif($_POST["step"]==4) {
            $step = 5;
            resultForm();
          } elseif($_POST["step"]==5) {
            $step = 6;
            menu();
          }
          ?>
          <hr />
          <div class="text-right">
            <input type="hidden" value="<?php echo $step; ?>" name="step" />
            <input class="btn btn-info" type="submit" value="Next" name="btnNext" <?php if($step==6) { echo "disabled"; } ?> />
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php } ?>