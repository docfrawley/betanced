<? include_once("initialize.php");

class infobject {

	private $lname;
	private $fname;
	private $email;
	private $sec_email;
	private $preferred;
	private $street;
	private $city;
	private $state;
	private $zip;
	private $wphone;
	private $hphone;
	private $cphone;
	private $prefphone;
	private $ncednum;

	function __construct($ncednum) {
		global $database;
		$sql="SELECT * FROM nceddata WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		$this->preferred = $value['preferred'];
		$this->email = $value['email'];
		$this->sec_email = $value['secemail'];
		$this->street = $value['staddress'];
		$this->city = $value['city'];
		$this->state = $value['state'];
		$this->zip = $value['zip'];
		$this->wphone = $value['wphone'];
		$this->hphone = $value['hphone'];
		$this->cphone = $value['cphone'];
		$this->prefphone = $value['prefphone'];
		$this->ncednum = $ncednum;
	}


	function get_lname(){
		return $this->lname;
	}

	function get_fname(){
		return $this->fname;
	}

	function full_name(){
		return $this->fname.' '.$this->lname;
	}

	function get_email() {
		return $this->email;
	}

	function sec_email() {
		return $this->sec_email;
	}

	function get_ncednum() {
		return $this->ncednum;
	}

	function has_displayname() {
		return ($this->preferred !='');
	}

	function get_state(){
		return $this->state;
	}

	function get_address(){
		return $this->street;
	}

	function get_city(){
		return $this->city;
	}

	function get_zip(){
		return $this->zip;
	}

	function get_hphone(){
		return $this->hphone;
	}

	function get_wphone(){
		return $this->wphone;
	}

	function get_cphone(){
		return $this->cphone;
	}

	function info_update($info) {
		global $database;
		$this->lname = $database->escape_value($info['lname']);
		$this->fname = $database->escape_value($info['fname']);
		$this->preferred = $database->escape_value($info['preferred']);
		$this->email = $database->escape_value($info['email']);
		$this->sec_email = $database->escape_value($info['secemail']);
		$this->street = $database->escape_value($info['street']);
		$this->city = $database->escape_value($info['city']);
		$this->state = $database->escape_value($info['state']);
		$this->zip = $database->escape_value($info['zip']);
		$this->wphone = $database->escape_value($info['wphone']);
		$this->hphone = $database->escape_value($info['hphone']);
		$this->cphone = $database->escape_value($info['cphone']);
		$this->prefphone = $database->escape_value($info['prefphone']);

		$sql = "UPDATE nceddata SET ";
		$sql .= "lname='". $this->lname ."', ";
		$sql .= "fname='". $this->fname ."', ";
		$sql .= "preferred='". $this->preferred ."', ";
		$sql .= "email='". $this->email ."', ";
		$sql .= "secemail='". $this->sec_email ."', ";
		$sql .= "staddress='". $this->street ."', ";
		$sql .= "city='". $this->city ."', ";
		$sql .= "state='". $this->state ."', ";
		$sql .= "zip='". $this->zip ."', ";
		$sql .= "wphone='". $this->wphone ."', ";
		$sql .= "hphone='". $this->hphone ."', ";
		$sql .= "cphone='". $this->cphone ."', ";
		$sql .= "preferredph='". $this->prefphone ."' ";
		$sql .= "WHERE ncednum='". $this->ncednum  ."'";
		$database->query($sql);

		$_SESSION['tryagainc'] = "Your contact information has been updated.";
		$sql = "UPDATE renewal SET ";
		$sql .= "lname='". $this->lname ."', ";
		$sql .= "fname='". $this->fname ."' ";
		$sql .= "WHERE ncednum='". $this->ncednum  ."'";
		$database->query($sql);
	}

	function info_form($admin=false, $where = 'memberin.php') { ?>

	    <form action="<? echo $where; ?>" method="post">
	    <fieldset>
    		<legend>Contact Information</legend>
    		<div class="row">
				<div class="small-12 columns">
					<? if ($admin){
						?><h5> Below is the contact information for <?echo $this->full_name(); ?></h5><?
					} else { ?>
					<p>Below is the contact information we have on file for you. We will use this information to contact you so please keep this information updated. Thank you.</p>
					<? } ?>
		        </div>
	    	</div>
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
		    	<div class="small-12 columns">
		        	<label>Primary Email</label>
		        	<input type="text" name="email" value = "<? echo $this->email; ?>"/>
		        </div>
		    </div>
		    <div class="row">
		    	<div class="small-12 columns">
		        	<label>Secondary Email</label>
		        	<input type="text" name="secemail" value = "<? echo $this->sec_email; ?>"/>
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
		    <input type="hidden" name="editinfo" value="yes"/>
		    <input type="hidden" name="ncednumber" value="<? echo $this->ncednum; ?>"/>
		    <div class="row">
				<div class="small-12 columns">
					<input type="submit" value="submit" class="button small"/>
				</div>
			</div>
		</fieldset>
	    </form>
		<?
		$_SESSION['tryagainc'] = " ";
	}
}
?>
