<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<?php
//$servername = "10.120.50.92";
//$servername = "10.20.50.161";
$servername = "mariadb.mastersystem.co.id:4006";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_wrike_integrate";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!-- Button trigger modal -->
        <button type='button' class='btn btn-primary mt-2' data-toggle='modal' data-target='#exampleModalLong'>
            Check Person
        </button>";

$sql = "SELECT resource_email, project_code, no_so, project_name, roles, status, created_in_msizone FROM sa_resource_assignment WHERE approval_status = 'approved' AND resource_email LIKE '%".$_GET['resource']."%' AND status IN ('Penuh','Mutasi')";
$dataAjax = mysqli_query($conn, $sql);

$sqlCount = "SELECT count(resource_email) as jumlah FROM sa_resource_assignment WHERE approval_status = 'approved' AND resource_email LIKE '%".$_GET['resource']."%' AND status IN ('Penuh','Mutasi')";
$dataAjaxCount = mysqli_query($conn, $sqlCount);
$jumlahProject = mysqli_fetch_row($dataAjaxCount);

echo "
        <!-- Modal -->
        <div class='modal fade' id='exampleModalLong' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden='true' >
        <div class='modal-dialog modal-lg' role='document' style='max-width: 80% !important;'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='exampleModalLongTitle'>On going project</h5>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body' >
      <div class='col-12'>
      
      <table class='table'>
      <tr>
        <th>Resource Name</th>
        <th>Project Code</th>
        <th>No SO</th>
        <th>Project Name</th>
        <th>Roles</th>
        <th>Status</th>
        <th>Created</th>
      </tr>
      ";
      while($data = mysqli_fetch_array($dataAjax)){
        $resourceEmail = $data['resource_email'];
        $projectCode = $data['project_code'];
        $projectName = $data['project_name'];
        $roles = $data['roles'];
        $rolesFinal = str_replace(" - "," ", $roles);
        $noSO = $data['no_so'];
        $status = $data['status'];
        $createdIn = $data['created_in_msizone'];

        echo "<tr>
        <td>$resourceEmail</td>
        <td>$projectCode</td>
        <td>$noSO</td>
        <td>$projectName</td>
        <td>$rolesFinal</td>
        <td>$status</td>
        <td>$createdIn</td>
      </tr>";
    }
      echo"
    </table>
    <p style='margin-left: auto;'>Jumlah project yang sedang dikerjakan : ".$jumlahProject[0]."</p>
      </div>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
      </div>
    </div>
  </div>
    </div>";



?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>