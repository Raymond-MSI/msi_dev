<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
<div class="col-lg-12">

    <div class="row">
        
        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Setting</h6>
                </div>
                <div class="card-body">
                    <?php if($_SESSION['Microservices_UserLevel']=='Administrator') { ?>
                        <table class="table table-sm">
                        <?php
                        $tblname = "mst_users_level";
                        $userlevel = $DB->get_data($tblname);
                        do {
                            echo "<tr><td>" . $userlevel[0]['user_level'] . "</td><td>" . MD5($userlevel[0]['user_level']) . "</td></tr>";
                        } while($userlevel[0]=$userlevel[1]->fetch_assoc());
                        ?>
                        </table>
                    <?PHP } ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php } ?>