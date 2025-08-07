<?php
class Property
{
	public $NewProperty;
	public $OldProperty;
	public $file_name;
	public $components;
	public $major = 1;
	public $minor = 0;
	public $control = 0;
	public $author;
	public $connection;
	public $update;
	public $hostname;
	public $username;
	public $password;
	public $database;
	public $class;
	public $msg;
	public $dashboardEnable;
	public $mod;
	public $sub;
	public $modName;
	public $launched;
	public $title;
	public $type;
	public $folder;
	public $revisionType;
	public $moduleDesc;
	public $configID;
	public $showRepeat;
	public $OldProperty4;
	public $OldProperty3;

	function __construct()
	{
		$this->showRepeat = false;
		if (isset($_GET['sub'])) {
			$this->sub = $_GET['sub'];
		} else
		if (isset($_GET['mod'])) {
			$this->sub = "";
		} else {
			$this->sub = '';
		}
	}

	function get_new_property()
	{
		$fstat = stat($this->components . $this->file_name);
		$this->NewProperty = array(
			"file_name" => $this->file_name,
			"released" => strtotime(date("d F Y H:i:s", $fstat['mtime'])),
			"created" => date("Y-m-d H:i:s", $fstat['ctime']),
			"modified" => date("Y-m-d H:i:s", $fstat['mtime']),
			"atime" => date("Y-m-d H:i:s", $fstat['atime']),
			"mtime" => date("Y-m-d H:i:s", $fstat['mtime']),
			"ctime" => date("Y-m-d H:i:s", $fstat['ctime']),
			"dev" => $fstat['dev'],
			"ino" => $fstat['ino'],
			"mode" => $fstat['mode'],
			"nlink" => $fstat['nlink'],
			"uid" => $fstat['uid'],
			"gid" => $fstat['gid'],
			"rdev" => $fstat['rdev'],
			"size" => $fstat['size'],
			"blksize" => $fstat['blksize'],
			"blocks" => $fstat['blocks']
		);
	}

	function get_old_property()
	{
		global $DB;
		$mysql = "SELECT `id`, `config_value`, 'params' FROM `sa_cfg_web` WHERE `config_key` = 'MODULE_" . strtoupper($this->mod) . "'";
		$rsversions = $DB->get_sql($mysql);
		$this->configID = $rsversions[0]['id'];

		// OldProperty
		if ($rsversions[0]['config_value'] == NULL || $rsversions[0]['config_value'] == "{}") {
			$this->OldProperty = json_decode("{}", true);
		} else {
			$this->OldProperty = json_decode($rsversions[0]['config_value'], true);
			if (isset($this->OldProperty['module'][$this->modName]['update'])) {
				$this->update = $this->OldProperty['module'][$this->modName]['update'];
			} else {
				$this->update = date("Y-m-d G:i:s");
			}
		}

		// OldProperty4
		$this->OldProperty4 = json_decode($rsversions[0]['params'], true);
		$this->OldProperty3 = $rsversions[0]['params'];
	}

	function save_property()
	{
		global $DB;
		$TempProperty = $this->OldProperty;

		if (isset($this->OldProperty['version']) && $this->OldProperty['version'] == "4.2") {
			// VERSION 4.2
			// Old
			$NewUpdate = $this->NewProperty['modified'];
			$TempProperty['module'][$this->modName]['title'] = $this->title;
			if (isset($this->OldProperty['module'][$this->modName])) {
				// Update
				$hostname = $this->OldProperty['connection']['hostname'];
				$username = $this->OldProperty['connection']['username'];
				$password = $this->OldProperty['connection']['password'];
				$database = $this->OldProperty['connection']['database'];
				$class = $this->OldProperty['connection']['class'];
				$author = $this->OldProperty['module'][$this->modName]['author'];
				$major = $this->OldProperty['module'][$this->modName]['major'];
				$minor = $this->OldProperty['module'][$this->modName]['minor'];
				$control = $this->OldProperty['module'][$this->modName]['control'];
				$launched = $this->OldProperty['module'][$this->modName]['launched'];
				$update = $this->NewProperty['modified'];
				if ($this->revisionType == "major") {
					$major++;
					$minor = 0;
					$control = 0;
				} else
				if ($this->revisionType == "minor") {
					$minor++;
					$control = 0;
				} else
				if ($this->revisionType == "control") {
					$control++;
				}
			} else {
				$major = 1;
				$minor = 0;
				$control = 0;
				if ($_SERVER['SERVER_NAME'] == "localhost") {
					$hostname = "localhost";
					$username = "root";
					$password = "";
					$database = "";
					$class = "";
				} else {
					$hostname = "mariadb.mastersystem.co.id:4006";
					$username = "ITAdmin";
					$password = "P@ssw0rd.1";
					$database = "";
					$class = "";
				}
				$author = "Unknown";
				$launched = $this->NewProperty['modified'];
				$update = $this->NewProperty['modified'];
			}
		} else
		if (isset($this->OldProperty['version']) && $this->OldProperty['connection'] == "4.1") {
			// VERSION 4.1
			$major = $this->OldProperty['version']['module']['major'];
			$minor = $this->OldProperty['version']['module']['minor'];
			$control = $this->OldProperty['version']['module']['control'];
			$author = $this->OldProperty['version']['author'];
			$hostname = $this->OldProperty['connection']['hostname'];
			$username = $this->OldProperty['connection']['username'];
			$password = $this->OldProperty['connection']['password'];
			$database = $this->OldProperty['connection']['database'];
			$class = $this->OldProperty['connection']['class'];
		} else
		if (isset($this->OldProperty4['version']) && $this->OldProperty4['version']['connection'] == "4") {
			// VERSION 4
			$major = $this->OldProperty4['version']['module']['major'];
			$minor = $this->OldProperty4['version']['module']['minor'];
			$control = $this->OldProperty4['version']['module']['control'];
			$author = $this->OldProperty4['version']['author'];
			$hostname = $this->OldProperty4['connection']['hostname'];
			$username = $this->OldProperty4['connection']['username'];
			$password = $this->OldProperty4['connection']['password'];
			$database = $this->OldProperty4['connection']['database'];
			$class = $this->OldProperty['connection']['class'];
		} else
		if (isset($this->OldProperty3) && strpos($this->OldProperty3, "version=3") != false) {
			// VERSION 3
			$temp1 = explode($this->OldProperty3, ":");
			$temp2 = explode($temp1[1], ";");
			foreach ($temp2 as $temp3) {
				$temp4 = explode($temp3, "=");
				if ($temp4[0] == "hostname") {
					$hostname = $temp4[1];
				} else
				if ($temp4[0] == "username") {
					$username = $temp4[1];
				} else
				if ($temp4[0] == "password") {
					$password = $temp4[1];
				} else
				if ($temp4[0] == "database") {
					$database = $temp4[1];
				} else
				if ($temp4[0] == "class") {
					$database = $temp4[1];
				}
			}
			$major = 1;
			$minor = 0;
			$control = 0;
			$author = "Unknown";
		} else {
			// VERSION NEW
			$major = 1;
			$minor = 0;
			$control = 0;
			$author = "Unknown";
			if ($_SERVER['SERVER_NAME'] == "localhost") {
				$hostname = "localhost";
				$username = "root";
				$password = "";
				$database = "";
				$class = "";
			} else {
				$hostname = "mariadb.mastersystem.co.id:4006";
				$username = "ITAdmin";
				$password = "P@ssw0rd.1";
				$database = "";
				$class = "";
			}
			$launched = $this->NewProperty['modified'];
			$update = $this->NewProperty['modified'];
			// New Config Value
			// $launched = $this->update;

			// $saveProperty1 = json_encode($TempProperty, JSON_PRETTY_PRINT);
			// $mysql = sprintf("UPDATE `sa_cfg_web` SET `config_value` = %s WHERE `config_key` = 'MODULE_" . strtoupper($this->mod) . "'",
			// GetSQLValueString($saveProperty1, "text"));
			// $res = $DB->get_sql($mysql, false);
		}

		$TempProperty['version'] = "4.2";
		if ($_SERVER['SERVER_NAME'] == "localhost") {
			$TempProperty['connection']['hostname'] = "localhost";
			$TempProperty['connection']['username'] = "root";
			$TempProperty['connection']['password'] = "";
			$TempProperty['connection']['database'] = $database;
			$TempProperty['connection']['class'] = $class;
		} else {
			$TempProperty['connection']['hostname'] = "mariadb.mastersystem.co.id:4006";
			$TempProperty['connection']['username'] = "ITAdmin";
			$TempProperty['connection']['password'] = "P@ssw0rd.1";
			$TempProperty['connection']['database'] = $database;
			$TempProperty['connection']['class'] = $class;
		}
		$TempProperty['module'][$this->modName]['title'] = $this->title;
		$TempProperty['module'][$this->modName]['type'] = $this->type;
		$TempProperty['module'][$this->modName]['author'] = $author;
		$TempProperty['module'][$this->modName]['update'] = $update;
		$TempProperty['module'][$this->modName]['launched'] = $launched;
		$TempProperty['module'][$this->modName]['file_name'] = $this->file_name;
		$TempProperty['module'][$this->modName]['folder'] = $this->components;
		$TempProperty['module'][$this->modName]['dashboardEnable'] = $this->dashboardEnable;
		$TempProperty['module'][$this->modName]['description'] = $this->moduleDesc;
		$TempProperty['module'][$this->modName]['update_comment'] = $this->msg;
		$TempProperty['miscellaneous'] = "{}";

		if ($TempProperty != $this->OldProperty) {
			$TempProperty['module'][$this->modName]['major'] = $major;
			$TempProperty['module'][$this->modName]['minor'] = $minor;
			$TempProperty['module'][$this->modName]['control'] = $control;
			$saveProperty = json_encode($TempProperty, JSON_PRETTY_PRINT);
			$mysql = sprintf(
				"UPDATE `sa_cfg_web` SET `config_value` = %s WHERE `config_key` = 'MODULE_" . strtoupper($this->mod) . "'",
				GetSQLValueString($saveProperty, "text")
			);
			$res = $DB->get_sql($mysql, false);
		}

		$this->major = $major;
		$this->minor = $minor;
		$this->control = $control;
		$this->launched = $launched;
	}

	function get_version()
	{
		$control = $this->control;
		$control--;
		$version = $this->major . "." . $this->minor . "." . $control;
		return $version;
	}

	function show_version()
	{
		$version = $this->get_version();
		echo strtoupper($this->title) . " Version " . $version . " &copy; MSIZone " . date("M-Y", strtotime($this->launched));
	}

	function show_footer($path, $title, $moduleDesc = "", $type = "main-module", $revisionType = "control", $dashboardEnable = false, $author = "", $msg = "", $show = false)
	{
		$file_name = basename($path);
		$this->file_name = $file_name;
		$this->title = $title;
		$this->moduleDesc = $moduleDesc;
		$this->type = $type;
		$this->revisionType = $revisionType;
		$this->author = $author;
		$this->msg = $msg;
		$this->dashboardEnable = $dashboardEnable;
		if (isset($_GET['mod'])) {
			$this->mod = $_GET['mod'];
		} else {
			$this->mod = "dashboard";
		}
		if ($type == "main-module") {
			$this->components = "components/modules/";
		} else {
			$this->components = "components/modules/" . $this->mod . "/";
		}
		$this->modName = pathinfo($this->file_name, PATHINFO_FILENAME);
		$this->get_old_property();
		$this->get_new_property();
		$this->save_property();

		if ($show == true && $this->showRepeat == false) {
?>
			<div class="card-footer text-right" style='font-size:10px'>
				<div class="row">
					<div class="col-lg-6">
					</div>
					<div class="col-lg-6 text-right">
						<?php
						$this->show_version();
						?>
					</div>
				</div>
			</div>
<?php
			$this->showRepeat = true;
		}
	}
}
?>