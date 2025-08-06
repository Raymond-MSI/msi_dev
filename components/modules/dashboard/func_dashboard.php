<?php
function show_report($items)
{
    foreach ($items as $item) {
        if ($item['type'] == "item3") {
            echo $item['data']['data'];
        }
    }
}

function get_coding($reportName)
{
    global $DB, $periode;
    $tblname = "cfg_web";
    $condition = "config_key='" . $reportName . "'";
    $data = $DB->get_data($tblname, $condition);
    return $data;
}

function option($mdlname, $title, $value)
{
    // $userpermission = useraccess_v2($mdlname, false);
    // if($userpermission['mdllevel']!='') 
    // {
    // $link = "mod=" . $_GET['mod'] . "&sub=" . $_GET['sub'];
    $xxx = explode("_", $_GET['sub']);
    $link = "mod=" . $_GET['mod'] . "&sub=" . $xxx[0] . "_" . $xxx[1];
    echo '<option value="' . $value . '" ' . ($link == $value ? "selected" : "") . '>' . $title . '</option>';
    // }
}

function menu_dashboard()
{
    global $DB;
?>
    <div class="row">
        <div class="col-lg-12">
            <select class="form-select" id="onload" name="onload" onchange="loadpage()">
                <option value="dashboard" <?php echo (isset($_GET['sub']) && $_GET['sub'] == "dashboard") ? "selected" : ""; ?>>Dashboard</option>
                <?php
                // option("SERVICE_BUDGET", "Service Budget", "dashboard_sbf");
                // option("KPI_PROJECT", "KPI Project Implementation", "dashboard_kpi");
                // option("EDO", "Extra Day Off", "dashboard_edo");
                // option("ASSET", "Asset", "dashboard_asset");
                // option("CHANGE_REQUEST", "Change Request", "dashboard_cr");
                // option("SURVEY", "Survey", "dashboard_survey");
                // $tblname = "cfg_web";
                // $condition = "parent=152";
                // $order = "`order` ASC";
                // $dash = $DB->get_data($tblname, $condition, $order);
                // if($dash[2]>0)
                // {
                //     do {
                //         $xxx = $dash[0]['params'];
                //         $values = json_decode($xxx, true);
                //         if($values['status']=="enabled") {
                //             option($values['module'], $values['title'], $values['value']);
                //         }
                //     } while($dash[0]=$dash[1]->fetch_assoc());
                // }
                $tblname = "cfg_web";
                $condition = "parent=10";
                $order = "`order` ASC";
                $dash = $DB->get_data($tblname, $condition, $order);
                if ($dash[2] > 0) {
                    do {
                        $xxx = $dash[0]['config_value'];
                        $values = json_decode($xxx, true);
                        if (isset($values['module']['dashboard']['status']) && $values['module']['dashboard']['status'] == "enabled") {
                            option($values['module']['dashboard']['module'], $values['module']['dashboard']['title'], $values['module']['dashboard']['link']);
                        }
                    } while ($dash[0] = $dash[1]->fetch_assoc());
                }
                ?>
            </select>
        </div>
    </div>
<?php
}

function content($title, $value, $color = "danger", $width = "12", $tiptool = "")
{
?>
    <div class="col-lg-<?php echo $width; ?> mb-3">
        <div class="card border-left-<?php echo $color; ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-xs font-weight-bold text-<?php echo $color; ?> text-uppercase mb-1" title="<?php echo $tiptool; ?>"><?php echo $title; ?></div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800y"><?php echo $value; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>