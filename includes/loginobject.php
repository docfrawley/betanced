<?php include_once("initialize.php");
session_start();

class loginuser {
	
	private $first;
	private $second;
	
  function __construct($user, $pass) {
  	global $database;
	$this->first = $database->escape_value($user);
	$this->second = $database->escape_value($pass);
  }

	function first_check(){
		global $database;
		if ((strtolower($this->first) == "ncedadmin") && (strtolower($this->second) == "ncedin1!")) { 
			$_SESSION['ncedadmin']="yes";
			redirect_to('ncedadmin.php'); 
		} else {
			$sql = "SELECT * FROM renewal WHERE lname='".$this->first."' AND ncednum='".$this->second."'";
			$result_set = $database->query($sql);
			$user = $database->fetch_array($result_set);
			if ($user['lname'] =="" || $user['ncednum']=="") {
				$_SESSION['tryagain'] = "<br/>That last name and/or NCED number does not correspond to any in our database. Please try again. If you continue to have problems, please contact the head of membership at membership@ncedonline.org.<br/><br/>";
			} else {
				$_SESSION['tryagain'] = "create user";
				$_SESSION['lname'] = $this->first;
				$_SESSION['ncednumber'] = $this->second;
				redirect_to('memberin.php');
			}
			redirect_to('login.php');
		}
	}
	
	function check_form($info){
		global $database;
		$_SESSION['user'] = $database->escape_value($info['username']);
		$fpass = $database->escape_value($info['firstpass']);
		$spass = $database->escape_value($info['secondpass']);
		$sql = "SELECT * FROM renewal WHERE password='".$fpass."'";
		$result_set = $database->query($sql);
		if ($database->num_rows($result_set)>0){
			$_SESSION['tryagainc'] = "That password has already been taken, please create another password.";
			if (isset($_SESSION['tryagainc'])) {redirect_to('login.php');}
		}
		elseif ($fpass != $spass) {
			$_SESSION['tryagainc'] = "The two passwords you entered did not match. Please try again.";
			if (isset($_SESSION['tryagainc'])) {redirect_to('login.php');}
		} else { $this->create_user($fpass);}
	}
}


?>