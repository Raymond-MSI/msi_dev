<?php
if(isset($_GET['sub']) && $_GET['sub']=="msanalytic")
{
?>
    <div class="card">
        <div class="card-header">
            MSAnalytic
        </div>
        <div class="card-body">
            <iframe src="https://elk.mastersystem.co.id/goto/0f22c2a0-4b95-11ed-bb36-8da6e8a15c02" height="800" width="100%"></iframe>
        </div>
    </div>
<?php
} elseif(isset($_GET['sub']) && $_GET['sub']=="cidb")
{
?>
    <div class="card">
        <div class="card-header">
            CIDB
        </div>
        <div class="card-body">
            <iframe src="https://cidb.mastersystem.co.id/" height="800" width="100%"></iframe>
        </div>
    </div>
<?php
}
?>