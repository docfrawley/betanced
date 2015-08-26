<?php include_once("initialize.php");
session_start();

class loginuser {
	
	private $first;
	private $second;
	
  function __construct($user, $pass) {
	$this->first = $user;
	$this->second = $pass;
  }

	function first_check(){
		global $database;
		$sql = "SELECT * FROM renewal WHERE lname='".$this->first."' AND ncednum='".$this->second."'";
		$result_set = $database->query($sql);
		$user = $database->fetch_array($result_set);
		if ($user['lname'] =="" || $user['ncednum']=="") {
			$_SESSION['tryagain'] = "<br/>That last name and/or NCED number does not correspond to any in our database. Please try again. If you continue to have problems, please contact the head of membership for njalc at membership@ncedonline.org.<br/><br/>";
		} else {
			if ($user['username'] !='') {$_SESSION['tryagain'] = "Our records indicate that you have already created a user account so please login with your username and password in the box below to the right. Thank you.";}
			else {
				$_SESSION['tryagain'] = "create user";
				$_SESSION['lname'] = $this->first;
				$_SESSION['ncednumber'] = $this->second;
			}
		}
		redirect_to('login.php');
	}
	
	function create_form(){
		$_SESSION["tryagainc"]="check";
		if (!isset($_SESSION['user'])) { $_SESSION['user'] ="";}
		?>
		<div class="row">
			<div class="small-6 columns center">
				<div class="row">
					<div class="small-12 columns left">
		        		<p>Please enter a username and password for your user account</p>
		        	</div>
		        	<form  action="logincheck.php" method="POST">
		        	<div class="small-12 columns left">
		        		<label>ENTER A USERNAME</label>
		        		<input type="text" name="username" value "<? echo $_SESSION['user']; ?>"/>
		        	</div>
		        	<div class="small-12 columns left">
		        		<label>ENTER A PASSWORD</label>
		        		<input type="password" name="firstpass"/>
		        	</div>
		        	<div class="small-12 column center">
		        		<label>REENTER PASSWORD</label>
		        		<input type="password" name="secondpass"/>
		        	</div>
		        	<div class="small-12 columns left">
		        		<input type="submit" value="Submit" class="button small" />
		        	</div>
		        </div>
		    </div>
    	</div> <?
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
	
	function create_user($password){
		global $database;
		$_SESSION["tryagainc"]="";
		$_SESSION["tryagain"]="";
		$sql = "UPDATE renewal SET ";
		$sql .= "username='". $database->escape_value($_SESSION['user']) ."', ";
		$sql .= "password='". $database->escape_value($password) ."' ";
		$sql .= "WHERE lname='". $this->first."' AND ";
		$sql .= "ncednum='". $this->second."'";
	  	$database->query($sql);
		$_SESSION['ncednumber'] = $this->second;
		redirect_to('memberin.php');
	}
	
	function do_check() {
		global $database;
		$sql = "SELECT * FROM renewal WHERE username='".$this->first."' AND password='".$this->second."'";
		$result_set = $database->query($sql);
		$user = $database->fetch_array($result_set);
		
		if (($user['username'] != "") AND  ($user['password'] != "")) {
			$_SESSION['tryagain'] = "";
			
			if ($user['status'] == "ncedadmin") { 
				$_SESSION['ncedadmin']=$user['numindex'];
				redirect_to('ncedadmin.php'); }
			else { 
				$_SESSION['ncednumber'] = $user['ncednum'];
				redirect_to('memberin.php');}
		} else { 
				$_SESSION['tryagain'] = "I am sorry. Either that username or password does not correspond to any in our database. Please try again.";
				redirect_to('login.php');
				
		}
	}
}


?>