<? include_once("initialize.php");

class ind_reg_object {
	
	private $ncednum;
	private $inorout;
	private $street;
	private $city;
	private $state;
	private $zip;
	private $email;
	private $phone;
	private $speciality;
	private $speciality2;
	private $otherlanguage;
	private $in_database;
	
	function __construct($ncednum) {
		global $database;		
		$this->ncednum = $ncednum;
		$sql="SELECT * FROM natdirectory WHERE ncednum ='".$this->ncednum."'";
		$result_set = $database->query($sql);
		$this->in_database = ($database->num_rows($result_set)>0);
		$value = $database->fetch_array($result_set);
		$this->ncednum = $ncednum;
		$this->inorout = $value['inorout'];
		$this->street = $value['staddress'];
		$this->city = $value['city'];
		$this->state = $value['state'];
		$this->zip = $value['zip'];
		$this->email = $value['email'];
		$this->phone = $value['phone'];
		$this->speciality = $value['speciality'];
		$this->speciality2 = $value['speciality2'];
		$this->otherlanguage = $value['otherlanguage'];	
	}
	
	
	function get_inorout(){
		return $this->inorout;
	}

	function get_state(){
		return $this->state;
	}

	function get_speciality(){
		return $this->speciality;
	}

	function get_speciality2(){
		return $this->speciality2;
	}

	function display_info(){
		?>
			<tr><td><? echo $this->street; ?></td></tr>
			<tr><td><? echo $this->city.", ".$this->state."&nbsp&nbsp&nbsp".$this->zip; ?></td></tr>
			<tr><td><? echo $this->phone; ?></td></tr>
			<tr><td><? echo $this->email; ?></td></tr>
			
		<?
	}
	function display_specialties(){
		echo "<strong>SPECIALTIES</strong><br/>";
		echo $this->speciality; 
		if ($this->speciality=="Bi Lingual") {
			echo ": ".$this->otherlanguage;
		}
		echo "<br/>".$this->speciality2;
		if ($this->speciality2=="Bi Lingual") {
			echo ": ".$this->otherlanguage;
		}
	}

	function reg_update($info) {
		global $database;
		$this->inorout 			= $database->escape_value($info['inorout']);
		$this->street 			= $database->escape_value($info['staddress']);
		$this->city 			= $database->escape_value($info['city']);
		$this->state 			= $database->escape_value($info['state']);
		$this->zip 				= $database->escape_value($info['zip']);
		$this->email 			= $database->escape_value($info['email']);
		$this->phone 			= $database->escape_value($info['phone']);
		$this->speciality 		= $database->escape_value($info['speciality']);
		$this->speciality2 		= $database->escape_value($info['speciality2']);
		$this->otherlanguage 	= $database->escape_value($info['otherlanguage']);	
		if ($this->state==""){
			$member = new infobject($this->ncednum);
			$this->state = $member->get_state();
		}

		if ($this->speciality=="clear"){$this->speciality="";}
		if ($this->speciality2=="clear"){$this->speciality2="";}
		if (!$this->in_database) {
			$sql = "INSERT INTO natdirectory (";
			$sql .= "ncednum, inorout, staddress, city, state, zip, email, phone, speciality, speciality2, otherlanguage";
	 		$sql .= ") VALUES ('";
	 		$sql .= $this->ncednum ."', '";
			$sql .= $this->inorout ."', '";
			$sql .= $this->street ."', '";
			$sql .= $this->city ."', '";
			$sql .= $this->state ."', '";
			$sql .= $this->zip ."', '";
			$sql .= $this->email ."', '";
			$sql .= $this->phone."', '";
			$sql .= $this->speciality ."', '";
			$sql .= $this->speciality2 ."', '";
			$sql .= $this->otherlanguage ."')";
			$database->query($sql);
		} else {
			$sql = "UPDATE natdirectory SET ";
			$sql .= "inorout='". $this->inorout ."', ";
			$sql .= "staddress='". $this->street ."', ";
			$sql .= "city='". $this->city ."', ";
			$sql .= "state='". $this->state ."', ";
			$sql .= "email='". $this->email ."', ";
			$sql .= "zip='". $this->zip ."', ";
			$sql .= "phone='". $this->phone ."', ";
			$sql .= "speciality='". $this->speciality ."', ";
			$sql .= "speciality2='". $this->speciality2 ."', ";
			$sql .= "otherlanguage='". $this->otherlanguage ."' ";
			$sql .= "WHERE ncednum='". $this->ncednum  ."'";
			$database->query($sql);	
		}
		$_SESSION['tryagainc'] = "Your registry information has been updated.";
	}
	
	function reg_form() { ?> 
		
	    <form action="memberin.php" method="post">
	    <fieldset>
    		<legend>Registry Information</legend>
    		<a href="registry.php" class="button tiny radius ">GO TO REGISTRY</a>
    		<div class="row">
				<div class="small-12 columns">
					<p>Below is the registry information we have on file for you. You may remove or include yourself in the registry by clicking the yes or no button below. The information listed below is what would be displayed if you are listed in the registry.</p>
		        </div>
	    	</div>
		    <div class="row">

		    <div class="small-12 columns">
      			<label>Current Listing Status in Registry</label> <?
      			if ($this->inorout==true){ ?>
      				<input type="radio" name="inorout" value="1" id="inoroutYES" checked><label for="inoroutYES">Yes, include me in the Registry</label><br/>
      				<input type="radio" name="inorout" value="0" id="inoroutNO"><label for="inoroutNO">No, do not include me in the Registry</label> <?
      			} else { ?>
      					<input type="radio" name="inorout" value="1" id="inoroutYES"><label for="inoroutYES">Yes, include me in the Registry</label><br/>
      				<input type="radio" name="inorout" value="0" id="inoroutNO" checked><label for="inoroutNO">No, do not include me in the Registry</label> <?
      			} ?>
		    </div>
		    <div class="row">
		    	<div class="small-12 columns">
		        	<label>Street Address</label>
		        	<input type="text" name="staddress" value = "<? echo $this->street; ?>"/>
		        </div>
		        
		    </div>
		    <div class="row">
		    	<div class="small-5 columns">
		        	<label>City</label>
		        	<input type="text" name="city" value = "<? echo $this->city; ?>"/>
		        </div>
		    	<div class="small-5 columns">
		        	<label>State</label>
		        	<? statelist($this->state); ?>
		        </div>
		        <div class="small-62 columns">
		        	<label>Zip Code</label>
		        	<input type="text" name="zip" value = "<? echo $this->zip; ?>"/>
		        </div>
		    </div>
		    <div class="row">
		    	<div class="small-12 columns">
		        	<label>Email</label>
		        	<input type="email" name="email" value = "<? echo $this->email; ?>"/>
		        </div>
		    </div>
		    <div class="row">
		    	<div class="small-12 columns">
		        	<label>Phone</label>
		        	<input type="text" name="phone" value = "<? echo $this->phone; ?>"/>
		        </div>
		    </div>
		    <div class="row">
				<div class="small-12 columns">
		        	<label>Speciality</label>
						<select name="speciality">
			                <option selected="selected"  value="<? echo $this->speciality; ?>"> <? echo $this->speciality; ?> </option>\
			                <option value="clear">Clear this field</option>
			                <option value="Adults With Disabilties">Adults With Disabilties</option>
			                <option value="Autism">Autism</option>
			                <option value="Bi Lingual">Bi Lingual</option>
			                <option value="Cognitive Therapy">Cognitive Therapy</option>
			                <option value="Hearing Impairments">Hearing Impairments</option>
			                <option value="Math Disabilties">Math Disabilties</option>
			                <option value="Preschool Disabilities">Preschool Disabilities</option>
			                <option value="Reading Disorders">Reading Disorders</option>
			                <option value="Speech /Communication">Speech /Communication</option>
			                <option value="Traumatic Brain Injury">Traumatic Brain Injury</option>
			                <option value="Visual Impairments">Visual Impairments</option>
		                </select>
		        </div>
		    </div>
		    <div class="row">
				<div class="small-12 columns">
					<label>Second Speciality (if applicable)</label>
	                <select name="speciality2">
		                <option selected="selected"  value="<? echo $this->speciality2; ?>"> <? echo $this->speciality2; ?> </option>
		                <option value="clear">Clear this field</option>
		                <option value="Adults With Disabilties">Adults With Disabilties</option>
		                <option value="Autism">Autism</option>
		                <option value="Bi Lingual">Bi Lingual</option>
		                <option value="Hearing Impairments">Hearing Impairments</option>
		                <option value="Math Disabilties">Math Disabilties</option>
		                <option value="Preschool Disabilities">Preschool Disabilities</option>
		                <option value="Reading Disorders">Reading Disorders</option>
		                <option value="Speech /Communication">Speech /Communication</option>
		                <option value="Traumatic Brain Injury">Traumatic Brain Injury</option>
		                <option value="Visual Impairments">Visual Impairments</option>
	                </select>
	            </div>
		    </div>
		    <div class="row">
				<div class="small-12 columns">        
	               	<p>If you selected "Bi-Lingual," please enter language:</p>
	               	<input type="text" name="otherlanguage" value="<? echo $this->otherlanguage; ?>"/>
		        </div>
		    </div>
		    <input type="hidden" name="editreg" value="yes"/> 
		    <div class="row">
				<div class="small-12 columns">
					<input type="submit" value="submit" class="button small"/>
				</div>
			</div>
		</fieldset>
	    </form>
		<?
	}
}
?>