<? include_once("initialize.php");

class memadmin {
	
	private $allmem;
	private $renewopen;
	private $renewyear;
	private $oneYear;
	private $threeYear;

	
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
		$this->oneYear = $value['oneyr'];
		$this->threeYear = $value['threeyr'];
	}

	function get_year(){
		return $this->renewyear;
	}

	function get_highnum(){
		return count($this->allmem);
	}

	function printMemberCounts(){
		$prevYr = 0;
		$currYr = 0;
		$currYrOnly =0;
		$currYrThree = 0;
		$nextYr = 0;
		$twoYr = 0;
		$revoked = 0;
		$threeAgo = 0;
		$twoAgo = 0;
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if ($this->allmem[$counter]['status'] == 'REVOKED') {
				$revoked++;
			} elseif ($this->allmem[$counter]['renewyear'] < $this->renewyear) {
				if ($this->allmem[$counter]['renewyear'] == ($this->renewyear -1)) {
					$prevYr++;
				} elseif ($this->allmem[$counter]['renewyear'] == ($this->renewyear -2)) {
					$twoAgo++;
				} else {
					$threeAgo++;
				}
			} elseif (($this->allmem[$counter]['renewyear'] == $this->renewyear) || ($this->allmem[$counter]['renewyear'] > $this->renewyear)) {
				$currYr++;
				$member = new memobject($this->allmem[$counter]['ncednum']);
				if ($member->get_ryear() == $this->renewyear){
					if (($member->get_payment() == 35) || ($member->get_payment() == '')) {
						$currYrOnly++;
					} else {
						$currYrThree++;
					}
				} elseif ($member->get_ryear() == ($this->renewyear + 1)) {
					$currYrThree++;
					$nextYr++;
				} else {
					$currYrThree++;
					$nextYr++;
					$twoYr++;
				}
			}
			
		}

		?>
			<div class="row">
	            <div class = "medium-4 columns"> <h5><?
	            	$previous = $this->renewyear-1;
	                echo "# Renewed ".$previous.": <strong>{$prevYr}</strong>"; ?></h5>
	            </div>
	            <div class = "medium-4 columns"> <h5><?
	                echo "# Renewed ".$this->renewyear.": <strong>{$currYr}</strong>"; ?></h5>
	            </div>
	            <div class = "medium-4 columns"> <h5><?
		            echo "# Revoked: <strong>{$revoked}</strong>"; ?></h5>
	            </div> 
        	</div>
        	<div class="row">
	            <div class = "medium-3 columns"> <h6><?
	                echo "# ".$this->renewyear." (1yr renewal): <strong>{$currYrOnly}</strong>"; ?></h6>
	            </div>
	            <div class = "medium-3 columns"> <h6><?
	                echo "# ".$this->renewyear." (3yr renewal): <strong>{$currYrThree}</strong>"; ?></h6>
	            </div>
	            <div class = "medium-3 columns"> <h6><?
	            	$nextYear = $this->renewyear + 1;
		            echo "# ".$nextYear.": <strong>{$nextYr}</strong>"; ?></h6>
	            </div> 
	            <div class = "medium-3 columns"> <h6><?
	            	$nextYear++;
		            echo "# ".$nextYear.": <strong>{$twoYr}</strong>"; ?></h6>
	            </div> 
        	</div>
        	<div class="row">
	            <div class = "medium-6 columns"> <h6><?
	            	$previous--;
	                echo "# Last renewed in ".$previous.": <strong>{$twoAgo}</strong>"; ?></h6>
	            </div>
	            <div class = "medium-6 columns"> <h6><?
	            	$previous--;
	                echo "# Last renewed in ".$previous." or longer: <strong>{$threeAgo}</strong>"; ?></h6>
	            </div>
        	</div>
            <?
	}



	function search_member_form($whichPage){
		?> 
		 <form action="<? echo $whichPage; ?>.php" method="POST">
		 	<fieldset>
		 		<?
		 		if ($whichPage == "ncedboard"){
		 			echo "<legend>Search for a Member to List as Board Member</legend>";
		 		} else {
		 			echo "<legend>Search for a Member </legend>";
		 		}
		 		?>
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
		 		<div class="small-6 columns">
		 			<label>Amount for 1yr Renewal</label>
		 			<input type="text" name="oneYear" value="<? echo $this->oneYear; ?>"/>
		 		</div>
		 		<div class="small-6 columns">
		 			<label>Amount for 3yr Renewal</label>
		 			<input type="text" name="threeYear" value="<? echo $this->threeYear; ?>"/>
		 		</div>
		 	</div>
			<div class="row">
		 		<div class="small-6 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        		<div class="small-6 columns right">
        			<? if ($this->renewopen) { ?>
        				<a href="?task=pending" class="button tiny radius">LIST OF PENDING RENEWALS</a>
        			<? } ?>
        		</div>
        	</div>
        </fieldset>
        </form><?
	}

	function pending_list(){
		?> <h4>Click on NCED Number to see member information and update his/her membership status</h4>
		<table>
		<tr><td>NCED #</td><td>NAME</td><td>RENEWAL STATUS</td><td>LAST RENEWED YEAR</td></tr><?
		foreach ($this->allmem as $value) {
			if ($value['pending']=='yes'){
				$ncednumber=$value['ncednum'];
				$person = new memobject($ncednumber);
				?> <tr><td> 
					<a href="?ncednumberL=<? echo $ncednumber;  ?>"> <? echo $ncednumber; ?> </a>
				</td><td> <?
					echo $person->get_displayname();
				?> </td><td> <?
					echo $value['status'];
				?> </td><td> <?
					echo $value['renewyear'];
				?> </td></tr> <?			
			}
		}
		?> </table> <?
	}

	function update_renew($info) {
		global $database;
		$this->renewopen = ($info['renewal']=="open");
		$this->renewyear = $database->escape_value($info['ryear']);
		$this->oneYear = $database->escape_value($info['oneYear']);
		$this->threeYear = $database->escape_value($info['threeYear']);
		$sql = "UPDATE renewinfo SET ";
		$sql .= "isopen='". $this->renewopen ."', ";
		$sql .= "theyear='". $this->renewyear ."', ";
		$sql .= "oneyr='". $this->oneYear ."', ";
		$sql .= "threeyr='". $this->threeYear ."'";
	  	$database->query($sql);
	}

	function find_memberN($number){
		global $database;
		$sql="SELECT * FROM renewal WHERE ncednum='".$number."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		if ($value['ncednum']==$number) {return $number;}
		else {return 0;}
	}

	function find_memberL($lname, $where){
		global $database;
		$sql="SELECT * FROM renewal WHERE lname='".$lname."'";
		$result_set = $database->query($sql);
		if ($database->num_rows($result_set) >1) {
			echo "There are more than one member with that last name.<br/>Please chose from the members listed below.<br/>"
			?><ul><?
			while ($value = $database->fetch_array($result_set)) {
				echo "<li><a href='{$where}.php?ncednumberL={$value["ncednum"]}'>{$value['fname']} {$value['lname']}</a></li>";
			}
			?></ul><?
		} elseif ($database->num_rows($result_set)==1) {
			$value = $database->fetch_array($result_set);
			return $value['ncednum'];
		} else {
			return 0;
		}
	}

	function get_memberN($info, $where){
		if ($info['ncednumber']) {$ncednumber = $this->find_memberN($info['ncednumber']);}
		else {$ncednumber = $this->find_memberL($info['LastName'], $where);}
		if ($ncednumber<1) { $_SESSION['findmember']="What you entered does not correspond to any member info on file.";}
		return $ncednumber;
	}


	function add_member($info){
		global $database;
		$renewyear = date('Y')+1;
		$status = "RENEWED";
		$ncednum = $database->escape_value($info['ncednum']);
		$sql = "INSERT INTO renewal (";
		$sql .= "ncednum, fname, lname, status, renewyear";
 		$sql .= ") VALUES ('";
 		$sql .= $ncednum ."', '";
		$sql .= $database->escape_value($info['fname']) ."', '";
		$sql .= $database->escape_value($info['lname']) ."', '";
		$sql .= $status ."', '";
		$sql .= $renewyear ."')";
		$database->query($sql);

		$sql = "INSERT INTO nceddata (";
		$sql .= "ncednum, fname, lname, email, staddress, city, state, zip, wphone, hphone, cphone";
 		$sql .= ") VALUES ('";
 		$sql .= $ncednum ."', '";
		$sql .= $database->escape_value($info['fname']) ."', '";
		$sql .= $database->escape_value($info['lname']) ."', '";
		$sql .= $database->escape_value($info['email']) ."', '";
		$sql .= $database->escape_value($info['staddress']) ."', '";
		$sql .= $database->escape_value($info['city']) ."', '";
		$sql .= $database->escape_value($info['state']) ."', '";
		$sql .= $database->escape_value($info['zip']) ."', '";
		$sql .= $database->escape_value($info['wphone']) ."', '";
		$sql .= $database->escape_value($info['hphone']) ."', '";
		$sql .= $database->escape_value($info['cphone']) ."')";
		$database->query($sql);

		$today = date("F j, Y");
		$sql = "INSERT INTO memstart (";
		$sql .= "ncednum, whenst";
 		$sql .= ") VALUES ('";
 		$sql .= $ncednum ."', '";
		$sql .= $today ."')";
		$database->query($sql);

		array_push($this->allmem, $info);

		$_SESSION['memmessage']="New Member has been added.";
	}
	
	function new_member_form(){
		?> 
		 <form action="ncedadmin.php" method="POST">
		 	<fieldset>
		 			<legend>Add New Member</legend>
		 	<div class="row">
		 		<div class="medium-3 columns">
		 			<input type="text" name="ncednum" placeholder="NCED Number (# <? echo $this->get_highnum(); ?>)"/>
		 		</div>
		 		<div class="medium-5 columns">
		 			<input type="text" name="email" placeholder="Email Address"/>
		 		</div>
		 		<div class="medium-4 columns">
		 		</div>
		 	</div>		
		 	<div class="row">
		 		<div class="medium-5 columns">
		 			<input type="text" name="fname" placeholder="First Name"/>
		 		</div>
		 		<div class="medium-7 columns">
		 			<input type="text" name="lname" placeholder="Last Name"/>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="medium-4 columns">
		 			<input type="text" name="staddress" placeholder="Street Address"/>
		 		</div>
		 		<div class="medium-4 columns">
		 			<input type="text" name="city" placeholder="City"/>
		 		</div>
		 		<div class="medium-2 columns">
		 				<? statelist("State"); ?>
		 		</div>
		 		<div class="medium-2 columns">
		 			<input type="text" name="zip" placeholder="Zip"/>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="medium-4 columns">
		 			<input type="text" name="wphone" placeholder="Work Phone"/>
		 		</div>
		 		<div class="medium-4 columns">
		 			<input type="text" name="hphone" placeholder="Home Phone"/>
		 		</div>
		 		<div class="medium-4 columns">
		 			<input type="text" name="cphone" placeholder="Cell Phone"/>
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