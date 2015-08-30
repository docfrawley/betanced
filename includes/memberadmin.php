<? include_once("initialize.php");

class memadmin {
	
	private $allmem;
	private $renewopen;
	private $renewyear;

	
	function __construct() {
		global $database;
		$sql="SELECT * FROM renewal ORDER BY ncednum";
		$result_set = $database->query($sql);
		$this->allmem = array();
		while ($value = $database->fetch_array($result_set)) {
			array_push($this->allmem, $value);
		}
		$sql="SELECT * FROM renewinfo";
		$result_set = $database->query($sql);
		$value= $database->fetch_array($result_set);
		$this->renewopen = $value['isopen'];
		$this->renewyear = $value['theyear'];
	}

	function get_highnum(){
		return count($this->allmem);
	}
	
	function get_numberOf($status){
		$numberOf = 0;
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if (($status == 'RENEWED') && $this->allmem[$counter]['renewyear'] > (date('Y') -1)) {$numberOf++;}
			elseif ($status == "NOT RENEWED" && 
				($this->allmem[$counter]['renewyear'] < date('Y')) && 
					($this->allmem[$counter]['status'] != 'REVOKED')) {$numberOf++;}
			else {
				if (($this->allmem[$counter]['status'] == 'REVOKED') && ($status == 'REVOKED')) {$numberOf++;}
			}
			
		}
		return $numberOf;
	}

	function search_member_form(){
		?> 
		 <form action="ncedamdin.php" method="POST">
		 	<fieldset>
		 			<legend>Search for a Member</legend>
		 			Enter NCED number <strong>OR</strong> Last Name<br/><br/>
		 	<div class="row">
		 		<div class="small-5 columns">
		 			<input type="text" name="ncednumber" placeholder="NCED Number"/>
		 		</div>
		 		<div class="small-7 columns">
		 			<input type="text" name="LastName" placeholder="Last Name"/>
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

	function set_renew(){
		?> 
		 <form action="ncedadmin.php" method="POST">
		 	<fieldset>
		 			<legend>Set Renew Year and Open/Close Renewal</legend>
		 	<div class="row">
		 		<div class="small-6 columns">
		 			<label>Renewal Year</label>
		 				<select name="ryear">
		 					<option selected="selected" value="<? echo $this->renewyear; ?>"/> <? echo $this->renewyear; ?> </option>
			        		<option value="<? echo $this->renewyear-1; ?>"/> <? echo $this->renewyear-1; ?> </option>
			        		<option value="<? echo $this->renewyear+1; ?>"/> <? echo $this->renewyear+1; ?> </option>

					 	</select>
		 		</div>
		 		<div class="small-6 columns">
		 			<label>Renewal Window</label>
		 			<? if ($this->renewopen) { ?>
		 				<input type="radio" name="renewal" value="open" checked> Open<br>
						<input type="radio" name="renewal" value="close"> Closed<br> <?
		 			} else { ?>
		 				<input type="radio" name="renewal" value="open"> Open<br>
						<input type="radio" name="renewal" value="close" checked> Closed<br> <?
		 			} ?>
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

	function update_renew($info) {
		global $database;
		$this->renewopen = ($info['renewal']=="open");
		$this->renewyear = $database->escape_value($info['ryear']);
		$sql = "UPDATE renewinfo SET ";
		$sql .= "isopen='". $this->renewopen ."', ";
		$sql .= "theyear='". $this->renewyear ."'";
	  	$database->query($sql);
	}

	function find_memberN($number){
		global $database;
	}

	function find_memberL($lname){
		global $database;
	}
	

}
?>