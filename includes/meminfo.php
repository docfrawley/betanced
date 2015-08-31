<? include_once("initialize.php");

class infobject {
	
	private $lname;
	private $fname;
	private $email;
	private $preferred;
	private $street;
	private $city;
	private $state;
	private $zip;
	private $wphone;
	private $hphone;
	private $cphone;
	private $prefphone;
	
	function __construct($ncednum) {
		global $database;
		$sql="SELECT * FROM nceddata WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		$this->preferred = $value['preferred'];
		$this->street = $value['street'];
		$this->city = $value['city'];
		$this->state = $value['state'];
		$this->zip = $value['zip'];
		$this->wphone = $value['wphone'];
		$this->hphone = $value['hphone'];
		$this->cphone = $value['cphone'];
		$this->prefphone = $value['prefphone'];		
	}
	
	
	function get_lname(){
		return $this->lname;
	}

	function full_name(){
		return $this->fname.' '.$this->lname;
	}

	function get_email() {
		return $this->infoarray['email'];
	}

	function has_displayname() {
		return ($this->preferred !='');
	}

	function info_update($info) {
		global $database;
		$sql = "UPDATE nceddata SET ";
		$sql .= "lname='". $database->escape_value($info['lname']) ."', ";
		$sql .= "fname='". $database->escape_value($info['fname']) ."', ";
		$sql .= "preferred='". $database->escape_value($info['preferred']) ."', ";
		$sql .= "street='". $database->escape_value($info['street']) ."', ";
		$sql .= "city='". $database->escape_value($info['city']) ."', ";
		$sql .= "state='". $database->escape_value($info['state']) ."', ";
		$sql .= "zip='". $database->escape_value($info['zip']) ."', ";
		$sql .= "wphone='". $database->escape_value($info['wphone']) ."', ";
		$sql .= "hphone='". $database->escape_value($info['hphone']) ."', ";
		$sql .= "cphone='". $database->escape_value($info['cphone']) ."', ";
		$sql .= "prefphone='". $database->escape_value($info['prefphone']) ."' ";
		$sql .= "WHERE ncednum='". $_SESSION['ncednumber'] ."'";
		$database->query($sql);		
		$_SESSION['tryagainc'] = "Your contact information has been updated.";
		$sql="SELECT * FROM nceddata WHERE ncednum ='".$_SESSION['ncednumber']."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		$this->preferred = $value['preferred'];
		$this->street = $value['street'];
		$this->city = $value['city'];
		$this->state = $value['state'];
		$this->zip = $value['zip'];
		$this->wphone = $value['wphone'];
		$this->hphone = $value['hphone'];
		$this->cphone = $value['cphone'];
		$this->prefphone = $value['prefphone'];	
	}
	
	function info_form() {?> 
		<div class="row">
			<div class="small-12 columns">
				<p>Below is the contact information we have on file for you. We will use this information to contact you so please keep this information updated. Thank you.</p>
	        </div>
	    </div>
	    <form action="memberin.php" method="post">
	    <div class="row">
	        <div class="small-6 columns">
				<label>First Name</label>
				<input type="text" name="fname" value = "<? echo $this->fname; ?>"/>
			</div>
			<div class="small-6 columns">
	        	<label>Last Name</label>
	        	<input type="text" name="lname" value = "<? echo $this->lname; ?>"/>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="small-12 columns">
	    		<label>Preferred Display Name</label>
	    		<? 
	    		$dname = ($this->has_displayname()) ? $this->preferred : $this->full_name() ;
	    		?>
	    		<input type="text" name="preferred" value = "<? echo $dname; ?>"/>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="small-6 columns">
	        	<label>Street Address</label>
	        	<input type="text" name="street" value = "<? echo $this->street; ?>"/>
	        </div>
	        <div class="small-6 columns">
	        	<label>City</label>
	        	<input type="text" name="city" value = "<? echo $this->city; ?>"/>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="small-6 columns">
	        	<label>State</label>
	        	<? statelist($this->state); ?>
	        </div>
	        <div class="small-6 columns">
	        	<label>Zip Code</label>
	        	<input type="text" name="zip" value = "<? echo $this->zip; ?>"/>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="small-4 columns">
	        	<label>Work Phone</label>
	        	<input type="text" name="wphone" value = "<? echo $this->wphone; ?>"/>
	        </div>
	        <div class="small-4 columns">
	        	<label>Home Phone</label>
	        	<input type="text" name="hphone" value = "<? echo $this->hphone; ?>"/>
	        </div>
	        <div class="small-4 columns">
	        	<label>Cell Phone</label>
	        	<input type="text" name="cphone" value = "<? echo $this->cphone; ?>"/>
	        </div>
	    </div>
	    <div class="row">
			<div class="small-12 columns">
	        	<label>Preferred Phone to Contact</label>
	        	<select name="prefphone"/>
        			<option selected="selected" value="<? echo $this->prefphone; ?>"/> <? echo preferred_phone($this->prefphone); ?> </option>
					<option value="home">Home Phone</option> 
					<option value="work">Work Phone</option> 
					<option value="cell">Cell Phone</option> 
    			</select>
	        </div>
	    </div>
	    <input type="hidden" name="editinfo" value="editinfo"/> 
	    <div class="row">
			<div class="small-12 columns">
				<input type="submit" value="submit" class="button small"/>
			</div>
		</div>
	        </form>
		<?
		$_SESSION['tryagainc'] = " ";
	}
}
?>