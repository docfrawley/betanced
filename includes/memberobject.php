<? include_once("initialize.php");

class memobject {
	
	private $username;
	private $password;
	private $lname;
	private $fname;
	private $memstart;
	private $ryear;
	private $memstatus;
	
	function __construct($ncednum) {
		global $database;
		$sql="SELECT * FROM renewal WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->username = $value['username'];
		$this->password = $value['password'];
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		$this->ryear = $value['renewyear'];
		$this->memstatus = $value['status'];
		$sql="SELECT * FROM memstart WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->memstart = $value['whenst'];
	}
	
	
	function get_status() {
		if ($this->memstatus == "RENEWED" && ($this->ryear == date('Y') || $this->ryear > date('Y'))) {
			echo "Renewed through {$this->ryear}";
		} else {
			echo "Your membership is NOT renewed";
		}
	}

	function get_ryear() {
		return $this->ryear;
	}

	function get_memstart() {
		return $this->memstart;
	}

	function get_displayname() {
		return $this->fname.' '.$this->lname;
	}

	function display_member() {
		?>
		<table>
			<tr>
				<td>
					Name:
				</td>
				<td>
					<? echo $this->get_displayname(); ?>
				</td>
			</tr>
			<tr>
				<td>
					Membership Began:
				</td>
				<td>
					<? echo $this->memstart ; ?>
				</td>
			</tr>
			<tr>
				<td>
					Membership Status:
				</td>
				<td>
					<? echo $this->get_status(); ?>
				</td>
			</tr>
		</table><?
	}

	function profile_update($info) {
		global $database;
		if (isset($info['firstp'])) {
			if ($info['secondp']==""){
				$_SESSION['tryagainc'] = "You need to reconfirm your password.";
			} elseif ($info['firstp'] != $info['secondp']) {
				$_SESSION['tryagainc'] = "Your passwords did not match.";
			} else {
				$_SESSION['tryagainc'] = "";
			}

		}
		if ($_SESSION['tryagainc'] == "") {
			$sql = "UPDATE renewal SET ";
			$sql .= "username='". $database->escape_value($info['uname']) ."', ";
			$sql .= "password='". $database->escape_value($info['firstp']) ."' ";
			$sql .= "WHERE ncednum='". $_SESSION['ncednumber'] ."'";
			$database->query($sql);
			$_SESSION['tryagainc'] = "username and/or password has been updated.<br/>";
			$this->username = $database->escape_value($info['uname']);
			$this->password = $database->escape_value($info['firstp']);
		}
	}
	
	function login_form() {?> 
		<div class="row">
			<div class="small-12 columns">
				<p>You can change your username and/or password below</p>
	        </div>
	        <form action="memberin.php" method="post">
	        <div class="small-12 columns">
				<label>USERNAME</label>
				<input type="text" name="uname" value = "<? echo $this->username; ?>"/>
			</div>
			<div class="small-12 columns">
	        	<label>NEW PASSWORD:</label>
	        	<input type="password" name="firstp" value = "<? echo $this->password; ?>"/>
	        </div>
			<div class="small-12 columns">
	        	<label>CONFIRM PASSWORD</label>
	        	<input type="password" name="secondp"/>
	        </div>
			<div class="small-12 columns">
				<input type="submit" value="submit" class="button small"/>
			</div>
	        </form>
	    </div>
		<?
		$_SESSION['tryagainc'] = " ";
	}

	function get_multiple(){
		$the_year = date('Y', strtotime($this->memstart));
		$diffyear = date('Y') - $the_year;
		return (int)($diffyear/5);
	}

	function set_archivedate(){
		$thedate = date('m/d/Y', strtotime($this->memstart));
		$exdate = explode("/", $thedate);
		$the_year = (int)$exdate[2] +  (5*$this->get_multiple());
		$dateneeded = mktime(0, 0, 0, (int)$exdate[0], (int)$exdate[1], $the_year);
		return $dateneeded;
	}

	function next_archive(){
		return strtotime('+5 years', $this->set_archivedate());
	}

	function admin_renew(){
		?> 
		 <form action="ncedadmin.php" method="POST">
		 	<fieldset>
		 			<legend>Change Membership Status</legend>
		 		<div class="row">
			 		<div class="small-12 columns">
			 			<label>Renewal Status</label>
			 				<select name="rstatus">
			 					<option selected="selected" value="<? echo $this->memstatus; ?>"/> <? echo $this->memstatus; ?> </option>
				        		<option value="RENEWED"/> RENEWED </option>
				        		<option value="REVOKED"/> REVOKED</option>
						 	</select>
			 		</div>
			 	</div>
			 	<div class="row">	
			 		<div class="small-12 columns">
			 			<label>Renewal Year</label>
			 				<select name="rstatus">
			 					<option selected="selected" value="<? echo $this->ryear; ?>"/> <? echo $this->ryear; ?> </option>
						 		<? 
			 					for ($x=0; $x<6; $x++){
			 						?><option value="<? echo date('Y')+$x; ?>"/> <? echo date('Y')+$x; ?> </option><?
			 					}
			 					?>
			 			 	</select>
			 		</div>
		 		</div>
			<div class="row">
		 		<div class="small-12 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        	</div>
        </fieldset>
        </form><?
	}
}
?>