<?php
if((isset($property)) && ($property == 1)) {
	$version = '1.0';
	$author = 'Syamsul Arham';
} else {

	class Alert {
		// public $hostname;
		// public $username;
		// public $password;
		// public $database;
		
		// function __construct($hostname, $username, $password, $database) {
		//   $this->conn = new mysqli($hostname, $username, $password, $database);
		//   if ($this->conn->connect_errno) {
		//       echo "Failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
		//   }
		// }
		function __construct() {

		}

		function msgcustom($color, $msg) {
			echo "<div class='alert alert-" . $color . "' role='alert'>" . $msg . "</div>";
		}

		function notpermission() {
			echo "<div class='alert alert-danger' role='alert'>
			You don't have permission to open this page. Please contact the application admin.
			</div>";
		}

		function datanotfound() {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
			Data not found.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function datanotselected() {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
			Please select the data.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function savedata() {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Horeee!</strong> Data has been successfully added.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function password() {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<strong>Owow!</strong> Password is not the same.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function email_not_send() {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<strong>Owow!</strong> The notification email was not sent to the recipient\'s address. Please contact the recipient about this request. 
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function email_send() {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Owow!</strong> A notification email has been sent to the recipient\'s address. 
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function change_password_success() {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Owow!</strong> Password has been changed successfully.  
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

		function datatable_error($msg) {
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $msg . 
			'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}

	}
}
?>