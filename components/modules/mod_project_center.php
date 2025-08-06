<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
<?php 
    // $hostname = 'localhost';
    $database = 'sa_service_budget';
    // $username = 'root';
    // $password = '';
    include_once( "components/classes/func_databases_v3.php" );
    $DBSB = new Databases($hostname, $username, $password, $database);
?>
<div class="col-lg-12">

        <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Project Center</h6>
        </div>
        <div class="card-body">
            <h1>Coming Soon</h1>
            <?php
                // $mysql = "SELECT `menu1`.`id`, `menu1`.`title`, `menu1`.`link`, `menu1`.`published`, `menu2`.`title` AS `parent2`, `menu1`.`ordering`, `menu1`.`params` FROM `sa_cfg_menus` AS `menu1` LEFT JOIN `sa_cfg_menus` AS `menu2` ON `menu2`.`id` = `menu1`.`parent` ORDER BY `menu1`.`parent` ASC, `menu1`.`ordering`";
                // $menu = $DBSB->get_sql($mysql);
                // $dmenu = $menu[0];
                // $qmenu = $menu[1];
                // $modtitle = 'Menu listing';

                // include('components/classes/func_datatable.php');
            ?>

        </div>
    </div>
</div>
 
<?php } ?>