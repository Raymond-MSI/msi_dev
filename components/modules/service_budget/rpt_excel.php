<?php
$tblname = "trx_project_list";
// $condition = "`project_id`='" . $_GET['project_id'] . "'";
$condition = "`project_code`='" . $_GET['project_code'] . "' AND so_number='" . $_GET['so_number'] . "'";
$order = "project_id DESC";
$project = $DTSB->get_data($tblname,$condition,$order);
$dproject = $project[0];
?>
<div class="container">
    <form name="form" method="post" action="index.php?mod=service_budget">
    <table id="tblexportData" class="" border="1">
        <!-------------------------->
        <!-- Document Information -->
        <!-------------------------->
        <tr>
            <td colspan="8"></td>
            <td class="text-right">Form-PS-04 Rev.06</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center"><h3><b>Form Service Budget Allocation</b></h3></td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9"><b>Document Information</b></td>
        </tr>
        <tr>
            <td colspan="3">FSB Number</td>
            <td colspan="6">
                <?php
                $tblname="trx_sb_number";
                $condition = "so_number='" . $_GET['so_number'] . "'";
                $sbnumbers = $DTSB->get_data($tblname, $condition);
                $dsbnumber = $sbnumbers[0];
                if($sbnumbers[2]>0) {
                    echo $dsbnumber['sb_number'];
                } else {
                    echo 'Draft';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Created by</td>
            <td colspan="6"><?php echo $dproject['create_by']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Approved by</td>
            <td colspan="6">
                <?php 
                echo $dproject['modified_by'];
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Approved Status</td>
            <td colspan="6">
            <?php 
                if($dproject['status']=='draft') {
                    echo "Draft";
                } elseif($dproject['status']=='submited') {
                    echo "Submit"; 
                } elseif($dproject['status']=='approved' && $dproject['po_number']!=NULL && $dproject['so_number']!=NULL) {
                    echo "Approved";
                // } elseif($dproject['approved']==2 && ($dproject['po_number']==NULL || $dproject['so_number']==NULL)) {
                } else {
                    echo "Temporary (PO atau SO belum ada)";
                } 
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Printed by</td>
            <td colspan="6"><?php echo $_SESSION['Microservices_UserEmail']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Printed Date</td>
            <td colspan="6"><?php echo date("d M Y H:i:s"); ?></td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>

        <!------------------------->
        <!-- Project Information -->
        <!------------------------->
        <tr>
            <td colspan="9"><b>Project Information</b></td>
        </tr>
        <tr>
            <td colspan="3">No.KP:</td>
            <td colspan="6"><?php echo $dproject['project_code']; ?></td>
        </tr>
        <tr>
            <td colspan="3">No.SO:</td>
            <td colspan="6"><?php echo $dproject['so_number']; ?></td>
        </tr>
        <tr>
            <td colspan="3">No PO/WO/SP/Kontrak (*):</td>
            <td colspan="6"><?php echo $dproject['po_number']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Nama Project:</td>
            <td colspan="6"><?php echo $dproject['project_name']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Nama Customer:</td>
            <td colspan="6"><?php echo $dproject['customer_name']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Status Project</td>
            <td colspan="6">
                <?php 
                echo ($dproject['newproject']==1? 'New':'Renewal'); 
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>

        <!------------------>
        <!-- Band Project -->
        <!------------------>
        <tr>
            <td colspan="9"><b>Agree Price</b></td>
        </tr>
        <tr>
            <td colspan="3">Band Project</td>
            <td><?php echo $dproject['band']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Implementation</td>
            <td>
                <?php
                $tblname = "trx_project_implementations";
                $condition = "`project_id`='" . $dproject['project_id'] . "' AND service_type=1";
                $implementation = $DTSB->get_data($tblname,$condition);
                $dimplementation = $implementation[0];
                $qimplementation = $implementation[1];
                $timplementation = $implementation[2];
                echo $timplementation>0 ? $dimplementation['agreed_price'] : "0";
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Maintenance</td>
            <td>
            <?php
                $tblname = "trx_project_implementations";
                $condition = "`project_id`='" . $dproject['project_id'] . "' AND service_type=2";
                $implementation = $DTSB->get_data($tblname,$condition);
                $dimplementation = $implementation[0];
                $qimplementation = $implementation[1];
                $timplementation = $implementation[2];
                echo $timplementation>0 ? $dimplementation['agreed_price'] : "0";
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>


        <!---------------------->
        <!-- Project Solution -->
        <!---------------------->
        <?php
        $tblname = "trx_project_solutions";
        // $condition = "`project_id`='" . $_GET['project_id'] . "'";
        $condition = "`project_id`='" . $dproject['project_id'] . "'";
        $solution = $DTSB->get_data($tblname, $condition);
        $dsolution = $solution[0];
        $qsolution = $solution[1];
        $tsolution = $solution[2];
        ?>
        <tr>
            <td colspan="9"><b>Project Solution</b></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-center">Product (%)</td>
            <td class="text-center">Service (%)</td>
            <td colspan="4" rowspan="8"></td>
        </tr>
        <?php
        if($tsolution>0) {
            $tsolutionproduct = 0;
            $tsolutionservice = 0;
            do {
                ?>
                <tr>
                    <td colspan="3">
                        <?php 
                        if($dsolution['solution_name']=='DCCI') {
                            echo 'Data Center & Cloud Infrastructure';
                        } elseif($dsolution['solution_name']=='EC') {
                            echo "Enterprise Collaboration";
                        } elseif($dsolution['solution_name']=='BDA') {
                            echo "Big Data & Analytics";
                        } elseif($dsolution['solution_name']=='DBM') {
                            echo "Digital Business Management";
                        } elseif($dsolution['solution_name']=='ASA') {
                            echo "Adaptive Security Architecture";
                        } elseif($dsolution['solution_name']=='SP') {
                            echo "Service Provider";
                        }
                        ?>
                    </td>
                    <td class="text-center"><?php echo $dsolution['product']; ?></td>
                    <td class="text-center"><?php echo $dsolution['services']; ?></td>
                </tr>
                <?php
                $dsp = $dsolution['product']; 
                if(empty($dsolution['product'])) {
                    $dsp = 0;
                }
                $dss = $dsolution['services'];
                if(empty($dsolution['services'])) {
                    $dss = 0;
                }
                $tsolutionproduct += $dsp;
                $tsolutionservice += $dss;
            } while($dsolution=$qsolution->fetch_assoc()); ?>
            <tr>
                <td colspan="3" class="text-right"><b>TOTAL</b></td>
                <td class="text-center"><b><?php echo $tsolutionproduct; ?></b></td>
                <td class="text-center"><b><?php echo $tsolutionservice; ?></b></td>
            </tr>
            <?php 
        } ?>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9"><b>Multi Years - Project Implementasi dan Managed Service (Durasi lebih dari 1 tahun)</b></td>
        </tr>
        <tr>
            <td colspan="3">Durasi:</td>
            <td colspan="6"><?php echo $dproject['duration']; ?> years</td>
        </tr>
        <tr>
            <td colspan="3">Jenis Kontrak</td>
            <td colspan="6"><?php echo $dproject['contract_type']; ?></td>
        </tr>
        <tr>
            <td colspan="3">Work Order</td>
            <td colspan="6">
                <?php
                $woName = ''; 
                $wotypes = explode(";",$dproject['wo_type']);
                for($i=0; $i<count($wotypes)-1;$i++) {
                    $tblname = "mst_type_of_service";
                    $condition = "tos_id=" . $wotypes[$i];
                    $wo = $DTSB->get_data($tblname,$condition);
                    $dwo = $wo[0];
                    $qwo = $wo[1];
                    $two = $wo[2];
                    $woName .= $dwo['tos_name'] . "; ";
                }
                echo $woName;
                ?>
            </td>
        </tr>
        <!-------------------->
        <!-- Implementation -->
        <!-------------------->
        <?php
        $tblname = "trx_project_implementations";
        // $condition = "`project_id`='" . $_GET['project_id'] . "' AND service_type=1";
        $condition = "`project_id`='" . $dproject['project_id'] . "' AND service_type=1";
        $implementation = $DTSB->get_data($tblname,$condition);
        $dimplementation = $implementation[0];
        $qimplementation = $implementation[1];
        $timplementation = $implementation[2];
        ?>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="9"><b>Implementation</b></td>
        </tr>
        <tr>
            <td colspan="3">Service Type</td>
            <td colspan="6">
                <?php
                if($timplementation>0) {
                    $tosName = "";
                    $tosid = explode(";",$dimplementation['tos_id']);
                    $tblname = "mst_type_of_service";
                    for($i=0;$i<count($tosid)-1;$i++) {
                        $condition = "tos_id=" . $tosid[$i];
                        $tos_id = $DTSB->get_data($tblname,$condition);
                        $dtos_id = $tos_id[0];
                        $tosName .= $dtos_id['tos_name'] . "; ";
                    }
                    echo $tosName; 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Project Category</td>
            <td colspan="6">
                <?php
                if($timplementation>0) {
                    if($dimplementation['tos_category_id']==1) {
                        echo "Hight";
                    } elseif($dimplementation['tos_category_id']==2) {
                        echo "Midle";
                    } else {
                        echo "Low";
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Estimation Duration Project</td>
            <td colspan="6">
                <?php 
                if($timplementation>0) {
                    echo $dimplementation['project_estimation']; 
                    if($dimplementation['project_estimation_id']==1) {
                        echo " Days";
                    } elseif($dimplementation['project_estimation_id']==2) {
                            echo " Months";
                    } else {
                        echo " Years";
                    }
                }
                ?>
            </td>
        </tr>


        <tr>
            <td colspan="3" rowspan="3"></td>
            <td colspan="5" class="text-center">Budget</td>
            <td rowspan="3" class="text-center">Total Budget</td>
        </tr>
        <tr>
            <td class="text-center">Brand 1</td>
            <td class="text-center">Brand 2</td>
            <td class="text-center">Brand 3</td>
            <td class="text-center">Brand 4</td>
            <td class="text-center">Brand 5</td>
        </tr>
        <?php 
            global $DTSB;

            $tblname = "trx_project_mandays";
            $array = array(); $i=1;
            for($i=1; $i<8; $i++) {
                // $condition = "project_id=" . $_GET['project_id'] . " AND service_type=1 AND (resource_level DIV 10)=" . $i;
                $condition = "project_id=" . $dproject['project_id'] . " AND service_type=1 AND (resource_level DIV 10)=" . $i;
                $order = "resource_level ASC";
                $data = $DTSB->get_data($tblname, $condition, $order);
                $ddata = $data[0];
                $qdata = $data[1];
                $tdata = $data[2];

                $arrayitems = array();
                $value = NULL;
                $j=0; 
                if($tdata>0) {
                do { 
                    if($ddata['resource_level'] != (($i)*10 +$j+1)) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                        $j++;
                    }
                    $arrayitem = array('brand'=>$ddata['brand'], 'mantotal'=>$ddata['mantotal'], 'mandays'=>$ddata['mandays'], 'value'=>$ddata['value']);
                    array_push($arrayitems, $arrayitem);
                    $j++;
                    $value = $ddata['value'];
                } while($ddata=$qdata->fetch_assoc());
                }
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                    }
                }
                array_push($array, $arrayitems);
            }

            $mysql = sprintf("SELECT `resource_level`,`project_id`,`brand`, COUNT(`brand`) AS `tbrand`, (`resource_level`-(`resource_level` DIV 10)*10) AS `res` FROM `sa_trx_project_mandays` WHERE `project_id`=%s AND service_type=1 GROUP BY `project_id`,`brand` ORDER BY `res` ASC",
                GetSQLValueString($dproject['project_id'], "int"));
                // GetSQLValueString($_GET['project_id'], "int"));

            $brandlist = $DTSB->get_sql($mysql);
            $dbrandlist = $brandlist[0];
            $qbrandlist = $brandlist[1];
            $tbrandlist = $brandlist[2]; 

            $j=0;
            $brands = array();
            if($tbrandlist>0) {
                do {
                    if(($dbrandlist['resource_level'] % 10) != ($j+1)) {
                        array_push($brands, NULL);
                        $j++;
                    }
                    array_push($brands, $dbrandlist['brand']);
                    $j++;
                } while($dbrandlist=$qbrandlist->fetch_assoc());
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        array_push($brands,NULL);
                    }
                }
            } else {
                for($k=0; $k<5; $k++) {
                    array_push($brands,NULL);
                }
            }

        $tblname = "mst_resource_catalogs";
        $resource = $DTSB->get_data($tblname);
        $dresource = $resource[0];
        $qresource = $resource[1]; 
        $resources = array($dresource['resource_qualification']=>$dresource['mandays']);
        while($dresource=$qresource->fetch_assoc()) {
            $res1 = array($dresource['resource_qualification']=>$dresource['mandays']);
            $resources = array_merge($resources, $res1);
        } 
        $reslevel = array("PD", "PM", "PC", "PA", "EE", "EP", "EA");
        ?>
        <tr>
        <?php
        for($i=0;$i<count($brands);$i++) {
            ?>
            <td class="text-center"><?php echo $brands[$i]; ?></td>
            <?php
        }
        ?>
        </tr>
        <tr>
            <td colspan="9">1. Mandays Calculation</td>
        </tr>
        <?php 
        $sb_usd[0] = 0;
        $sb_usd[1] = 0;
        $sb_usd[2] = 0;
        $sb_usd[3] = 0;
        $sb_usd[4] = 0;
        for($i=0; $i<7; $i++) { 
            switch ($i) {
                case 0:
                    $rlevel = "Project Director";
                    break;
                case 1:
                    $rlevel = "Project Manager";
                    break;
                case 2:
                    $rlevel = "Project Coordinator";
                    break;
                case 3:
                    $rlevel = "Project Admin";
                    break;
                case 4:
                    $rlevel = "Engineer Expert";
                    break;
                case 5:
                    $rlevel = "Engineer Professional";
                    break;
                case 6:
                    $rlevel = "Engineer Associate";

            } 
            ?>
            <tr>
                <td></td>
                <td colspan="8">
                    <?php 
                    echo $rlevel . ' (Rate USD.'; 
                    if($array[$i][4]['value']>0) { 
                        echo $array[$i][4]['value'] . ')'; 
                        $rate_usd = $array[$i][4]['value'];
                    } else { 
                        echo $resources[$rlevel] . ')';
                        $rate_usd = $resources[$rlevel];
                    } 
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Man Power</td>
                <td class="text-center"><?php echo $array[$i][0]['mantotal']; ?></td>
                <td class="text-center"><?php echo $array[$i][1]['mantotal']; ?></td>
                <td class="text-center"><?php echo $array[$i][2]['mantotal']; ?></td>
                <td class="text-center"><?php echo $array[$i][3]['mantotal']; ?></td>
                <td class="text-center"><?php echo $array[$i][4]['mantotal']; ?></td>
                <td class="text-center"><?php echo $array[$i][0]['mantotal']+$array[$i][1]['mantotal']+$array[$i][2]['mantotal']+$array[$i][3]['mantotal']+$array[$i][4]['mantotal']; ?></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>Mandays</td>
                <td class="text-center"><?php echo $array[$i][0]['mandays']; ?></td>
                <td class="text-center"><?php echo $array[$i][1]['mandays']; ?></td>
                <td class="text-center"><?php echo $array[$i][2]['mandays']; ?></td>
                <td class="text-center"><?php echo $array[$i][3]['mandays']; ?></td>
                <td class="text-center"><?php echo $array[$i][4]['mandays']; ?></td>
                <td class="text-center"><?php echo $array[$i][0]['mandays']+$array[$i][1]['mandays']+$array[$i][2]['mandays']+$array[$i][3]['mandays']+$array[$i][4]['mandays']; ?></td>
                <!-- <td class="text-center"><?php if($array[$i][4]['value']>0) { echo $array[$i][4]['value']; } else { echo $resources[$rlevel]; } ?></td> -->
            </tr>
            <?php
            $sb_usd[0] += $array[$i][0]['mantotal']*$array[$i][0]['mandays']*$rate_usd;
            $sb_usd[1] += $array[$i][1]['mantotal']*$array[$i][1]['mandays']*$rate_usd;
            $sb_usd[2] += $array[$i][2]['mantotal']*$array[$i][2]['mandays']*$rate_usd;
            $sb_usd[3] += $array[$i][3]['mantotal']*$array[$i][3]['mandays']*$rate_usd;
            $sb_usd[4] += $array[$i][4]['mantotal']*$array[$i][4]['mandays']*$rate_usd;
        } 
        
        global $username, $password, $hostname;
        $database = "sa_ps_service_budgets";
        $tblname = "trx_project_implementations";
        // $condition = "project_id=" . $_GET['project_id'] . " AND service_type=1";
        $condition = "project_id=" . $dproject['project_id'] . " AND service_type=1";
        $DIMP = new Databases($hostname, $username, $password, $database);
        $implement = $DIMP->get_data($tblname, $condition);
        $dimplement = $implement[0];
        $qimplement = $implement[1];
        $timplement = $implement[2];
        ?>
        <?php
        $tblname = "trx_addon";
        // $condition = "project_id=" . $_GET['project_id'] . ' AND `service_type`=1';
        $condition = "project_id=" . $dproject['project_id'] . ' AND `service_type`=1';
        $addon = $DTSB->get_data($tblname, $condition);
        $daddon = $addon[0];
        $qaddon = $addon[1];
        $taddon = $addon[2];
        $i=0;
        $totaladdon=0;
        if($taddon>0) {
            do {
                $totaladdon += $daddon['addon_price'];
            } while($daddon=$qaddon->fetch_assoc());
        }
        if($timplement>0) {
            $tmandays = $dimplement['implementation_price']-$dimplement['bpd_price']-$totaladdon;
        }
        $sb_usd_total = $sb_usd[0]+$sb_usd[1]+$sb_usd[2]+$sb_usd[3]+$sb_usd[4];
        if($sb_usd_total==0) {
            $sb_usd_total=1;
        }
        ?>
        <tr>
            <td></td>
            <td colspan="2" class="text-right"><b>Subtotal Mandays/Product (IDR)</b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($sb_usd[0]/$sb_usd_total*$tmandays,2,",","."); } ?></b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($sb_usd[1]/$sb_usd_total*$tmandays,2,",","."); } ?></b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($sb_usd[2]/$sb_usd_total*$tmandays,2,",","."); } ?></b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($sb_usd[3]/$sb_usd_total*$tmandays,2,",","."); } ?></b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($sb_usd[4]/$sb_usd_total*$tmandays,2,",","."); } ?></b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($tmandays,2,",","."); } ?></b></td>
        </tr>
        <tr>
            <td colspan="9">2. Business Trip</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Jumlah Lokasi</td>
            <td colspan="5"></td>
            <td class="text-center"><?php if($timplementation>0) { echo $dimplement['bpd_total_location']; } ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Keterangan Lokasi</td>
            <td colspan="5"><?php if($timplementation>0) { echo $dimplement['bpd_description']; } ?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>SubTotal Business Trip (IDR)</b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($dimplement['bpd_price'],2,",","."); } ?></b></td>
        </tr>

        <tr>
            <td colspan="9">3. Outsourcing Plan</td>
        </tr>
        <?php
        $tblname = "trx_addon";
        // $condition = "project_id=" . $_GET['project_id'] . ' AND `service_type`=1';
        $condition = "project_id=" . $dproject['project_id'] . ' AND `service_type`=1';
        $addon = $DTSB->get_data($tblname, $condition);
        $daddon = $addon[0];
        $qaddon = $addon[1];
        $taddon = $addon[2];
        $i=0;
        $totaladdon=0;
        if($taddon>0) {
            do {
                ?>
                <tr>
                    <td></td>
                    <td colspan="7"><?php if($taddon>0) { echo $i+1 . '.&nbsp;' . $daddon['addon_title']; } ?></td>
                    <td class="text-right"><?php if($taddon>0) { echo number_format($daddon['addon_price'],2,",","."); } ?></td>
                </tr>
                <?php
                $totaladdon += $daddon['addon_price'];
                $i++;
            } while($daddon=$qaddon->fetch_assoc());
        }
        ?>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Subtotal Outsourcing/Product (IDR)</b></td>
            <td class="text-right"><b><?php echo number_format($totaladdon,2,",","."); ?></b></td>
        </tr>
        <tr>
            <td colspan="8" class="text-right"><b>Harga Implementasi (sesuai PO/SPK)</b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($dimplement['implementation_price'],2,",","."); } ?></b></td>
        </tr>
        <tr>
            <td colspan="8" class="text-right"><b>Agreed Price</b></td>
            <td class="text-right"><b><?php if($timplementation>0) { echo number_format($dimplement['agreed_price'],2,",","."); } ?></b></td>
        </tr>

        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>


        <!------------------------->
        <!-- Project Maintenance -->
        <!------------------------->
        <tr>
            <td colspan="9"><b>Maintenance Local Support</b></td>
        </tr>
        <?php
        $database = "sa_ps_service_budgets";
        $tblname = "trx_project_implementations";
        // $condition = "`project_id`=" . $_GET['project_id'] . " AND service_type=2";
        $condition = "`project_id`=" . $dproject['project_id'] . " AND service_type=2";
        $DMNT = new Databases($hostname, $username, $password, $database);
        $maintenance = $DMNT->get_data($tblname, $condition);
        $dmaintenance = $maintenance[0];
        $qmaintenance = $maintenance[1];
        $tmaintenance = $maintenance[2];
        ?>
            <tr>
            <td colspan="3">Service Type</td>
            <td colspan="6">
                <?php
                if($tmaintenance>0) {
                    $dtosexp = explode(';',$dmaintenance['tos_id']);
                    for($i=0; $i<count($dtosexp)-1; $i++) {
                        $tblname = "mst_type_of_service";
                        $condition = "tos_id=" . $dtosexp[$i];
                        $tos = $DTSB->get_data($tblname, $condition);
                        $dtos = $tos[0];
                        $qtos = $tos[1];
                        $ttos = $tos[2];
                        echo $dtos['tos_name'] . ', ';
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">Estimation Duration Maintenance</td>
            <td colspan="6">
                <?php 
                if($tmaintenance>0) {
                    echo $dmaintenance['project_estimation'] . '&nbsp;'; 
                    if($dmaintenance['project_estimation_id']==1) {
                        echo 'days';
                    } elseif($dmaintenance['project_estimation_id']==2) {
                        echo 'months';
                    } elseif($dmaintenance['project_estimation_id']==3) {
                        echo 'years';
                    }
                }
                ?>
            </td>
        </tr>
        
        <tr>
            <td colspan="9">1. Business Trip</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Jumlah Lokasi</td>
            <td colspan="5"></td>
            <td class="text-center"><?php if($tmaintenance>0) { echo $dmaintenance['bpd_total_location']; } ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Keterangan Lokasi</td>
            <td colspan="5"><?php if($dmaintenance>0) { echo $dmaintenance['bpd_description']; } ?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>SubTotal Business Trip (IDR)</b></td>
            <td class="text-right"><b><?php if($dmaintenance>0) { echo number_format($dmaintenance['bpd_price'],2,",","."); } ?></b></td>
        </tr>

        <tr>
            <td colspan="9">2. Outsourcing Plan</td>
        </tr>
        <?php
        $tblname = "trx_addon";
        // $condition = "project_id=" . $_GET['project_id'] . ' AND `service_type`=2';
        $condition = "project_id=" . $dproject['project_id'] . ' AND `service_type`=2';
        $addon = $DTSB->get_data($tblname, $condition);
        $daddon = $addon[0];
        $qaddon = $addon[1];
        $taddon = $addon[2];
        $i=0;
        $totalout=0;
        do {
            ?>
            <tr>
                <td></td>
                <td colspan="7"><?php if($taddon>0) { echo $i+1 . '.&nbsp;' . $daddon['addon_title']; } ?></td>
                <td class="text-right"><?php if($taddon>0) { echo number_format($daddon['addon_price'],2,",","."); } ?></td>
            </tr>
            <?php
            if($taddon>0) { $totalout += $daddon['addon_price']; }
            $i++;
        } while($daddon=$qaddon->fetch_assoc()) ?>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Subtotal Outsourcing/Product (IDR)</b></td>
            <td class="text-right"><b><?php echo number_format($totalout,2,",","."); ?></b></td>
        </tr>

        <tr>
            <td colspan="9">3. Backup Unit</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="3"></td>
            <td colspan="5" class="text-center">Budget</td>
            <td rowspan="3" class="text-center">Total Budget</td>
        </tr>
        <tr>
            <td class="text-center">Brand 1</td>
            <td class="text-center">Brand 2</td>
            <td class="text-center">Brand 3</td>
            <td class="text-center">Brand 4</td>
            <td class="text-center">Brand 5</td>
        </tr>
        <?php 
            global $DTSB;

            $tblname = "trx_project_mandays";
            $array = array(); $i=1;
            for($i=1; $i<8; $i++) {
                // $condition = "project_id=" . $_GET['project_id'] . " AND service_type=2 AND (resource_level DIV 10)=" . $i;
                $condition = "project_id=" . $dproject['project_id'] . " AND service_type=2 AND (resource_level DIV 10)=" . $i;
                $order = "resource_level ASC";
                $data = $DTSB->get_data($tblname, $condition, $order);
                $ddata = $data[0];
                $qdata = $data[1];
                $tdata = $data[2];

                $arrayitems = array();
                $value = NULL;
                $j=0; 
                if($tdata>0) {
                do { 
                    if($ddata['resource_level'] != (($i)*10 +$j+1)) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                        $j++;
                    }
                    $arrayitem = array('brand'=>$ddata['brand'], 'mantotal'=>$ddata['mantotal'], 'mandays'=>$ddata['mandays'], 'value'=>$ddata['value']);
                    array_push($arrayitems, $arrayitem);
                    $j++;
                    $value = $ddata['value'];
                } while($ddata=$qdata->fetch_assoc());
                }
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                    }
                }
                array_push($array, $arrayitems);
            }

            $mysql = sprintf("SELECT `resource_level`,`project_id`,`brand`, COUNT(`brand`) AS `tbrand`, (`resource_level`-(`resource_level` DIV 10)*10) AS `res` FROM `sa_trx_project_mandays` WHERE `project_id`=%s AND service_type=2 GROUP BY `project_id`,`brand` ORDER BY `res` ASC",
                GetSQLValueString($dproject['project_id'], "int"));
                // GetSQLValueString($_GET['project_id'], "int"));

            $brandlist = $DTSB->get_sql($mysql);
            $dbrandlist = $brandlist[0];
            $qbrandlist = $brandlist[1];
            $tbrandlist = $brandlist[2]; 

            $j=0;
            $brands = array();
            if($tbrandlist>0) {
                do {
                    if(($dbrandlist['resource_level'] % 10) != ($j+1)) {
                        array_push($brands, NULL);
                        $j++;
                    }
                    array_push($brands, $dbrandlist['brand']);
                    $j++;
                } while($dbrandlist=$qbrandlist->fetch_assoc());
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        array_push($brands,NULL);
                    }
                }
            } else {
                for($k=0; $k<5; $k++) {
                    array_push($brands,NULL);
                }
            }

        $tblname = "mst_resource_catalogs";
        $resource = $DTSB->get_data($tblname);
        $dresource = $resource[0];
        $qresource = $resource[1]; 
        $resources = array($dresource['resource_qualification']=>$dresource['mandays']);
        while($dresource=$qresource->fetch_assoc()) {
            $res1 = array($dresource['resource_qualification']=>$dresource['mandays']);
            $resources = array_merge($resources, $res1);
        } 
        $reslevel = array("PD", "PM", "PC", "PA", "EE", "EP", "EA");
        ?>
        <tr>
        <?php
        for($i=0;$i<count($brands);$i++) {
            ?>
            <td class="text-center"><?php echo $brands[$i]; ?></td>
            <?php
        }
        ?>
        </tr>
        <?php for($i=0; $i<2; $i++) { 
            switch ($i) {
                case 0:
                    $rlevel = "1. Existing Backup Unit";
                    break;
                case 1:
                    $rlevel = "2. Investment Backup Unit";
            } 
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td colspan="2"><?php echo $rlevel; ?></td>
                <td class="text-right"><?php echo number_format($array[$i][0]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][1]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][2]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][3]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][4]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][0]['mandays']+$array[$i][1]['mandays']+$array[$i][2]['mandays']+$array[$i][3]['mandays']+$array[$i][4]['mandays'],2,",","."); ?></td>
            </tr>
            <?php
        } ?>
        <tr>
            <td></td>
            <td colspan="2" class="text-right"><b>Subtotal Mandays/Product (IDR)</b></td>
            <td class="text-right"><b><?php echo number_format($array[0][0]['mandays']+$array[1][0]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php echo number_format($array[0][1]['mandays']+$array[1][1]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php echo number_format($array[0][2]['mandays']+$array[1][2]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php echo number_format($array[0][3]['mandays']+$array[1][3]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php echo number_format($array[0][4]['mandays']+$array[1][4]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b>
                <?php 
                $totalbackup = $array[0][0]['mandays']+$array[1][0]['mandays']+$array[0][1]['mandays']+$array[1][1]['mandays']+$array[0][2]['mandays']+$array[1][2]['mandays']+$array[0][3]['mandays']+$array[1][3]['mandays']+$array[0][4]['mandays']+$array[1][4]['mandays']; 
                echo number_format($totalbackup,2,",",".");
                ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="9">4. Maintenance Package</td>
        </tr>
        <?php
        if($tmaintenance>0) {
            $tblname = "trx_addon";
            // $condition = "project_id=" . $_GET['project_id'] . ' AND `service_type`=3';
            $condition = "project_id=" . $dproject['project_id'] . ' AND `service_type`=3';
            $addon = $DTSB->get_data($tblname, $condition);
            $daddon = $addon[0];
            $qaddon = $addon[1];
            $taddon = $addon[2];
            $i=0;
            $totaladdon=0;
            if($taddon>0) {
                do {
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="7"><?php echo $i+1 . '.&nbsp;' . $daddon['addon_title']; ?></td>
                        <td class="text-right"><?php echo number_format($daddon['addon_price'],2,",","."); ?></td>
                    </tr>
                    <?php
                    $totaladdon +=  $daddon['addon_price'];
                    $i++;
                } while($daddon=$qaddon->fetch_assoc());
            }
        }
        ?>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Total Addon</b></td>
            <td class="text-right"><b><?php if($tmaintenance>0) { echo number_format($totaladdon,2,",","."); } ?></b></td>
        </tr>
    <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Other Non-Addon</b></td>
            <td class="text-right"><?php  if($tmaintenance>0) { echo number_format($dmaintenance['implementation_price']-$dmaintenance['bpd_price']-$totalout-$totalbackup-$totaladdon,2,",","."); } ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Total Pakage</b></td>
            <td class="text-right"><b><?php  if($tmaintenance>0) { echo number_format($dmaintenance['implementation_price']-$dmaintenance['bpd_price']-$totalout-$totalbackup,2,",","."); } ?></b></td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="8" class="text-right"><b>Harga Maintenance (sesuai PO/SPK)</b></td>
            <td class="text-right"><b><?php  if($tmaintenance>0) { echo number_format($dmaintenance['implementation_price'],2,",","."); } ?></b></td>
        </tr>
        <tr>
            <td colspan="8" class="text-right"><b>Agreed Price</b></td>
            <td class="text-right"><b><?php  if($tmaintenance>0) {echo number_format($dmaintenance['agreed_price'],2,",","."); } ?></b></td>
        </tr>

        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <!------------------------------->
        <!-- Project Extended Warranty -->
        <!------------------------------->
        <tr>
            <td colspan="9"><b>Extended Warranty </b></td>
        </tr>
        <?php
        $database = "sa_ps_service_budgets";
        $tblname = "trx_project_implementations";
        // $condition = "`project_id`=" . $_GET['project_id'] . " AND service_type=3";
        $condition = "`project_id`=" . $dproject['project_id'] . " AND service_type=3";
        $DWAR = new Databases($hostname, $username, $password, $database);
        $warranty = $DWAR->get_data($tblname, $condition);
        $dwarranty = $warranty[0];
        $qwarranty = $warranty[1];
        $twarranty = $warranty[2];
        ?>
        <tr>
            <td colspan="3">Service Catalog</td>
            <td colspan="6">
                <?php
                if($twarranty>0) {
                    $tblname = "mst_type_of_service";
                    $condition = "service_type=3 AND tos_id=" . $dwarranty['tos_id'];
                    $tos = $DTSB->get_data($tblname, $condition);
                    $dtos = $tos[0];
                    if($tos[2]>0) {
                        echo $dtos['tos_name'];
                    }
                }
                ?>                
            </td>
        </tr>
        <tr>
            <td colspan="3">Estimation Duration Warranty</td>
            <td colspan="6">
                <?php 
                if($twarranty>0) {
                    echo $dwarranty['project_estimation'];
                    if($dwarranty['project_estimation_id']==1) {
                        echo ' days';
                    } elseif($dwarranty['project_estimation_id']==2) {
                        echo ' months';
                    } elseif($dwarranty['project_estimation_id']==3) {
                        echo ' years';
                    } 
                }
                ?>
            </td>
        </tr>

        <?php 
            global $DTSB;

            $tblname = "trx_project_mandays";
            $array = array(); $i=1;
            for($i=1; $i<8; $i++) {
                // $condition = "project_id=" . $_GET['project_id'] . " AND service_type=3 AND (resource_level DIV 10)=" . $i;
                $condition = "project_id=" . $dproject['project_id'] . " AND service_type=3 AND (resource_level DIV 10)=" . $i;
                $order = "resource_level ASC";
                $data = $DTSB->get_data($tblname, $condition, $order);
                $ddata = $data[0];
                $qdata = $data[1];
                $tdata = $data[2];

                $arrayitems = array();
                $value = NULL;
                $j=0; 
                if($tdata>0) {
                do { 
                    if($ddata['resource_level'] != (($i)*10 +$j+1)) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                        $j++;
                    }
                    $arrayitem = array('brand'=>$ddata['brand'], 'mantotal'=>$ddata['mantotal'], 'mandays'=>$ddata['mandays'], 'value'=>$ddata['value']);
                    array_push($arrayitems, $arrayitem);
                    $j++;
                    $value = $ddata['value'];
                } while($ddata=$qdata->fetch_assoc());
                }
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                        array_push($arrayitems, $arrayitem);
                    }
                }
                array_push($array, $arrayitems);
            }

            $mysql = sprintf("SELECT `resource_level`,`project_id`,`brand`, COUNT(`brand`) AS `tbrand`, (`resource_level`-(`resource_level` DIV 10)*10) AS `res` FROM `sa_trx_project_mandays` WHERE `project_id`=%s AND service_type=3 GROUP BY `project_id`,`brand` ORDER BY `res` ASC",
                GetSQLValueString($dproject['project_id'], "int"));
                // GetSQLValueString($_GET['project_id'], "int"));

            $brandlist = $DTSB->get_sql($mysql);
            $dbrandlist = $brandlist[0];
            $qbrandlist = $brandlist[1];
            $tbrandlist = $brandlist[2]; 

            $j=0;
            $brands = array();
            if($tbrandlist>0) {
                do {
                    if(($dbrandlist['resource_level'] % 10) != ($j+1)) {
                        array_push($brands, NULL);
                        $j++;
                    }
                    array_push($brands, $dbrandlist['brand']);
                    $j++;
                } while($dbrandlist=$qbrandlist->fetch_assoc());
                if($j<5) {
                    for($k=$j; $k<5; $k++) {
                        array_push($brands,NULL);
                    }
                }
            } else {
                for($k=0; $k<5; $k++) {
                    array_push($brands,NULL);
                }
            }

        $tblname = "mst_resource_catalogs";
        $resource = $DTSB->get_data($tblname);
        $dresource = $resource[0];
        $qresource = $resource[1]; 
        $resources = array($dresource['resource_qualification']=>$dresource['mandays']);
        while($dresource=$qresource->fetch_assoc()) {
            $res1 = array($dresource['resource_qualification']=>$dresource['mandays']);
            $resources = array_merge($resources, $res1);
        } 
        $reslevel = array("PD", "PM", "PC", "PA", "EE", "EP", "EA");
        ?>
        <tr>
            <td colspan="3"></td>
            <?php
            for($i=0;$i<count($brands);$i++) {
                ?>
                <td class="text-center"><?php echo $brands[$i]; ?></td>
                <?php
            }
            ?>
            <!-- <td></td> -->
        </tr>
        <?php for($i=0; $i<2; $i++) { 
            switch ($i) {
                case 0:
                    $rlevel = "1. Price List Extended Warranty (Cisco)";
                    break;
                case 1:
                    $rlevel = "2. Discounted Extended Warranty (NON Cisco)";
            } 
            ?>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td colspan="2"><?php echo $rlevel; ?></td>
                <td class="text-right"><?php echo number_format($array[$i][0]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][1]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][2]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][3]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][4]['mandays'],2,",","."); ?></td>
                <td class="text-right"><?php echo number_format($array[$i][0]['mandays']+$array[$i][1]['mandays']+$array[$i][2]['mandays']+$array[$i][3]['mandays']+$array[$i][4]['mandays'],2,",","."); ?></td>
            </tr>
            <?php
        } ?>
        <tr>
            <td></td>
            <td colspan="7" class="text-right"><b>Total</b></td>
            <!-- <td class="text-right"><b><?php //echo number_format($array[0][0]['mandays']+$array[1][0]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php //echo number_format($array[0][1]['mandays']+$array[1][1]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php //echo number_format($array[0][2]['mandays']+$array[1][2]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php //echo number_format($array[0][3]['mandays']+$array[1][3]['mandays'],2,",","."); ?></b></td>
            <td class="text-right"><b><?php //echo number_format($array[0][4]['mandays']+$array[1][4]['mandays'],2,",","."); ?></b></td> -->
            <td class="text-right"><b><?php echo number_format($array[0][0]['mandays']+$array[1][0]['mandays']+$array[0][1]['mandays']+$array[1][1]['mandays']+$array[0][2]['mandays']+$array[1][2]['mandays']+$array[0][3]['mandays']+$array[1][3]['mandays']+$array[0][4]['mandays']+$array[1][4]['mandays'],2,",","."); ?></b></td>
        </tr>
        <tr>
            <td colspan="8" class="text-right"><b>PO Customer (IDR)</b></td>
            <td class="text-right"><b><?php echo number_format($array[2][1]['mandays'],2,",","."); ?></b></td>
        </tr>

    </table>

    <input type="submit" class="btn btn-secondary" name="btn-cancel" id="btn-cancel" value="Cancel">
    <button onclick="dataContentExportExl('tblexportData', 'SB-<?php echo $dproject['project_code'] . '-' . Date('YmdHis'); ?>')" class="btn btn-success">Export To Excel</button>
    </form>
</div>