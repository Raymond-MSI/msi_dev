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
            <h6 class="m-0 font-weight-bold text-primary">Timesheet</h6>
        </div>
        <div class="card-body">
        <h1>Coming Soon</h1>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">TAB1</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">TAB2</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">TAB3</div>
</div>


        </div>
    </div>
</div>

<?php } ?>