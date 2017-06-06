<? include_once("initialize.php");

class memadmin {

	private $allmem;
	private $activeCerts;
	private $inactiveCerts;
	private $revoked;
	private $renewopen;
	private $renewyear;
	private $oneYear;
	private $threeYear;
	private $nonrenewed;


	function __construct() {
		global $database;
		$sql="SELECT * FROM renewinfo";
		$result_set = $database->query($sql);
		$value= $database->fetch_array($result_set);
		$this->renewopen = $value['isopen'];
		$this->renewyear = $value['theyear'];
		$this->oneYear = $value['oneyr'];
		$this->threeYear = $value['threeyr'];

		$sql="SELECT * FROM renewal ORDER BY ncednum";
		$result_set = $database->query($sql);
		$this->allmem = array();
		$this->activeCerts = array();
		$this->inactiveCerts = array();
		$this->revoked = array();
		$this->nonrenewed = array();
		$temp_array = array();
		while ($value = $database->fetch_array($result_set)) {
			array_push($temp_array, $value);
		}

		$date1 = new DateTime("now");
		$date = "2/28/".date('Y');
		$date2 = new DateTime($date);
		foreach ($temp_array as $value) {
			if ($value['renewyear']<$this->renewyear &&
				$date1 > $date2 && $value['status']!= 'REVOKED'){
					$value['status'] = 'NON-RENEWED';
					$sql = "UPDATE renewal SET ";
					$sql .= "status='NON-RENEWED'";
					$sql .= " WHERE ncednum='". $value['ncednum'] ."'";
					$database->query($sql);
				}
				array_push($this->allmem, $value);
		}
	}

	function get_year(){
		return $this->renewyear;
	}

	function get_highnum(){
		return count($this->allmem);
	}

	function get_revoked(){
		$this->revoked = array();
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if ($this->allmem[$counter]['status'] == 'REVOKED') {
				array_push($this->revoked, $this->allmem[$counter]['ncednum']);
			}
		}
	}

	function get_num_pages($limit=20){
		return ceil(count($this->allmem)/$limit);
	}

	function get_activeCerts(){
		$this->activeCerts = array();
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if (($this->allmem[$counter]['status'] == 'RENEWED' ||
					$this->allmem[$counter]['status'] == 'NON-RENEWED') &&
					$this->allmem[$counter]['renewyear'] > ($this->renewyear-4)){
				array_push($this->activeCerts, $this->allmem[$counter]['ncednum']);
			}
		}
	}

	function get_nonrenewed(){
		$this->activeCerts = array();
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if ($this->allmem[$counter]['status'] == 'NON-RENEWED'){
				array_push($this->nonrenewed, $this->allmem[$counter]['ncednum']);
			}
		}
	}

	function get_inactiveCerts(){
		$this->inactiveCerts = array();
		for ($counter=1; $counter<= count($this->allmem); $counter++) {
			if ($this->allmem[$counter]['status'] == 'RENEWED' &&
					$this->allmem[$counter]['renewyear'] < $this->renewyear){
				array_push($this->inactiveCerts, $this->allmem[$counter]['ncednum']);
			}
		}
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
		$this->get_nonrenewed();
		$num_nonren = count($this->nonrenewed);
		?>
			<div class="row">
	            <div class = "medium-3 columns"> <h5><?
	            	$previous = $this->renewyear-1;
	                echo "# Renewed ".$previous.": <strong>{$prevYr}</strong>"; ?></h5>
	            </div>
	            <div class = "medium-3 columns"> <h5><?
	                echo "# Renewed ".$this->renewyear.": <strong>{$currYr}</strong>"; ?></h5>
	            </div>
	            <div class = "medium-3 columns"> <h5><?
		            echo "# Revoked: <strong>{$revoked}</strong>"; ?></h5>
	            </div>
							<div class = "medium-3 columns"> <h5><?
		            echo "# Non-Renewed: <strong>{$num_nonren}</strong>"; ?></h5>
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

	function get_renewal_list(){
		$output = "";
				 $output .= '
						<table class="table" bordered="1">
						<tr>';
				$output .='<th>Complete Renewal List</th></tr>';
				$output .='
							<tr>
								<th>NCED#</th>
								<th>Active/Revoked</th>
								<th>Date Certificate Issued</th>
								<th> # CEUs </th>
								<th>Date of Last Payment</th>
								<th>Method of Payment</th>
								<th>Amt of Last Payment</th>
								<th>Paid upto Membership Year</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Address</th>
								<th>City</th>
								<th>State</th>
								<th>Zip</th>
								<th>Work Phone</th>
								<th>Home Phone</th>
								<th>Cell Phone</th>
								<th>Primary Email</th>
								<th>Secondary Email</th>
							</tr>
					 ';
					 for ($counter=0; $counter< count($this->allmem); $counter++) {
							$member = new infobject($this->allmem[$counter]['ncednum']);
							$member_info = new memobject($this->allmem[$counter]['ncednum']);
							$ceuinfo = new ceuinfo($this->allmem[$counter]['ncednum'], $member_info->set_archivedate());
							$output .= '
									 <tr>
												<td>'.$member->get_ncednum().'</td>
												<td>'.$member_info->get_memstatus().'</td>
												<td>'.$member_info->get_memstart().'</td>
												<td>'.$ceuinfo->num_ceus().'</td>
												<td>'.$member_info->get_lastPayDate().'</td>
												<td>'.$member_info->get_manner().'</td>
												<td>'.$member_info->get_payment().'</td>
												<td>'.$member_info->get_ryear().'</td>
												<td>'.$member->get_fname().'</td>
												<td>'.$member->get_lname().'</td>
												<td>'.$member->get_address().'</td>
												<td>'.$member->get_city().'</td>
												<td>'.$member->get_state().'</td>
												<td>'.$member->get_zip().'</td>
												<td>'.$member->get_wphone().'</td>
												<td>'.$member->get_hphone().'</td>
												<td>'.$member->get_cphone().'</td>
												<td>'.$member->get_email().'</td>
												<td>'.$member->sec_email().'</td>
									 </tr>';
					 }
					 $output .= '</table>';
					 return $output;
	}


	function get_excel_list($wlist){
		if ($wlist=="active") {
			$this->get_activeCerts();
			$temp_array = $this->activeCerts;
		} else {
			$this->get_revoked();
			$temp_array = $this->revoked;
		}
		$output = "";
				 $output .= '
						<table class="table" bordered="1">
						<tr>';
				if ($wlist=='active'){
					$output .='<th>Active Certificate Holders</th></tr>';
				} else {
					$output .='<th>Revoked List</th></tr>';
				}
				$output .='
							<tr>
								<th>NCED#</th>
								<th>Last Name</th>
								<th>First Name</th>
								<th>Date Certificate Issued</th>';
								if ($wlist=='revoked'){
									$output .='<th>Date Revoked</th>';
								} else {
									$output .='<th>Last Paid</th>';
								}
				$output .='
								<th>Address</th>
								<th>City</th>
								<th>State</th>
								<th>Zip</th>
								<th>Work Phone</th>
								<th>Home Phone</th>
								<th>Cell Phone</th>
								<th>Primary Email</th>
								<th>Secondary Email</th>
							</tr>
					 ';
					 for ($counter=0; $counter< count($temp_array); $counter++) {
							$member = new infobject($temp_array[$counter]);
							$member_info = new memobject($temp_array[$counter]);
								$output .= '
										 <tr>
													<td>'.$member->get_ncednum().'</td>
													<td>'.$member->get_lname().'</td>
													<td>'.$member->get_fname().'</td>
													<td>'.$member_info->get_memstart().'</td>';
													if ($wlist=='revoked'){
														$output .='<td>'.$member_info->get_date_revoked().'</td>';
													} else {
														$output .='<td>'.$member_info->get_lastPayDate().'</td>';
													}
									$output .='
													<td>'.$member->get_address().'</td>
													<td>'.$member->get_city().'</td>
													<td>'.$member->get_state().'</td>
													<td>'.$member->get_zip().'</td>
													<td>'.$member->get_wphone().'</td>
													<td>'.$member->get_hphone().'</td>
													<td>'.$member->get_cphone().'</td>
													<td>'.$member->get_email().'</td>
													<td>'.$member->sec_email().'</td>
										 </tr>
								';
					 }
					 $output .= '</table>';
					 return $output;
	}

	function show_renewals($page=1, $limit){
		$start = $page*$limit-$limit;
		for ($counter=$start; $counter< $page*$limit; $counter++) {
			$member = new infobject($this->allmem[$counter]['ncednum']);
			$member_info = new memobject($this->allmem[$counter]['ncednum']);
			$ceuinfo = new ceuinfo($this->allmem[$counter]['ncednum'], $member_info->set_archivedate());?>
					<tr><td><? echo $member->get_ncednum(); ?></td>
					<td><? echo $member->get_fname(); ?></td>
					<td><? echo $member->get_lname(); ?></td>
					<td><? echo $member_info->get_memstatus(); ?></td>
					<td><? echo $member_info->get_memstart(); ?></td>
					<td><? echo $ceuinfo->num_ceus(); ?></td>
					<td><? echo $member_info->get_lastPayDate(); ?></td>
					<td><? echo $member_info->get_manner(); ?></td>
					<td><? echo $member_info->get_payment(); ?></td>
					<td><? echo $member_info->get_ryear(); ?></td></tr><?
		}
	}

	function ajax_renewals($page=1, $limit){
		$start = $page*$limit-$limit;
		$temp_array = [];
		for ($counter=$start; $counter< $page*$limit; $counter++) {
			$next_array = [];
			$member = new infobject($this->allmem[$counter]['ncednum']);
			$member_info = new memobject($this->allmem[$counter]['ncednum']);
			$ceuinfo = new ceuinfo($this->allmem[$counter]['ncednum'], $member_info->set_archivedate());
					$next_array['ncednum']= $member->get_ncednum();
					$next_array['fname']= $member->get_fname();
					$next_array['lname']= $member->get_lname();
					$next_array['status']= $member_info->get_memstatus();
					$next_array['memstart']= $member_info->get_memstart();
					$next_array['ceus']= $ceuinfo->num_ceus();
					$next_array['paydate']= $member_info->get_lastPayDate();
					$next_array['manner']= $member_info->get_manner();
					$next_array['payment']= $member_info->get_payment();
					$next_array['ryear']= $member_info->get_ryear();
					array_push($temp_array, $next_array);
		}
		return $temp_array;
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
		 		<? if ($whichPage == 'emailadmin') { ?>
		 			<input type="hidden" name="find_member" value="yes"/>
		 		<? } ?>
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
			?><div class="row"><div class="small-7 columns panel"><?
			echo "There are more than one member with that last name.<br/>Please chose from the members listed below.<br/>"
			?><ul><?
			while ($value = $database->fetch_array($result_set)) {
				echo "<li><a href='{$where}.php?ncednumberL={$value["ncednum"]}'>{$value['fname']} {$value['lname']}</a></li>";
			}
			?></ul></div></div><?
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

	function TestResultsToday(){
		global $database;
		$today = date("F j, Y");
		$temp_array=array();
		$sql="SELECT * FROM testresults WHERE tdate='".$today."' ORDER BY tnumber";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			array_push($temp_array, $value);
		}
		return $temp_array;
	}

	function addTestResult($info){
		global $database;
		$today = date("F j, Y");
		$sql = "INSERT INTO testresults (";
		$sql .= "tnumber, result, ncednum, tdate";
 		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['testnum']) ."', '";
		$sql .= $database->escape_value($info['testR']) ."', '";
		$sql .= $database->escape_value($info['ncednum']) ."', '";
		$sql .= $today ."')";
		$database->query($sql);
		if (isset($info['ncednum'])) {
			$this->add_member($info);
		}
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
		$sql .= "ncednum, fname, lname, email, secemail, staddress, city, state, zip, wphone, hphone, cphone";
 		$sql .= ") VALUES ('";
 		$sql .= $ncednum ."', '";
		$sql .= $database->escape_value($info['fname']) ."', '";
		$sql .= $database->escape_value($info['lname']) ."', '";
		$sql .= $database->escape_value($info['email']) ."', '";
		$sql .= $database->escape_value($info['secemail']) ."', '";
		$sql .= $database->escape_value($info['staddress']) ."', '";
		$sql .= $database->escape_value($info['city']) ."', '";
		$sql .= $database->escape_value($info['state']) ."', '";
		$sql .= $database->escape_value($info['zip']) ."', '";
		$sql .= $database->escape_value($info['wphone']) ."', '";
		$sql .= $database->escape_value($info['hphone']) ."', '";
		$sql .= $database->escape_value($info['cphone']) ."')";
		$database->query($sql);

		$sql = "INSERT INTO natdirectory (";
		$sql .= "ncednum, inorout, state";
 		$sql .= ") VALUES ('";
 		$sql .= $ncednum ."', '";
		$sql .= 0 ."', '";
		$sql .= $database->escape_value($info['state']) ."')";
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
		 		<div class="medium-4 columns">
		 			<input type="text" name="email" placeholder="Email Address"/>
		 		</div>
		 		<div class="medium-4 columns left">
		 			<input type="text" name="secemail" placeholder="Secondary Email"/>
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

	function new_member_test(){
		?>
		 <form action="tresultsadmin.php" method="POST">
			<fieldset>
					<legend>Enter Test Results</legend>

			<div class="row">
				<div class="medium-3 columns">
					<input type="text" name="testnum" placeholder="Test Number"/>
				</div>
			</div>
			<div class="row">
				<div class="medium-4 columns">
					      <label>Pass or Fail</label>
					      <input type="radio" name="testR" value="PASSED" ><label for="TestRPASSED">PASS</label>
					      <input type="radio" name="testR" value="NOT PASSED"><label for="TestNOT PASSED">NOT PASSED</label>
				</div>
			</div>

			<div class="row">
				<div class="medium-3 columns">
					<input type="text" name="ncednum" placeholder="NCED Number (# <? echo $this->get_highnum(); ?>)"/>
				</div>
				<div class="medium-4 columns">
					<input type="text" name="email" placeholder="Email Address"/>
				</div>
				<div class="medium-4 columns left">
					<input type="text" name="secemail" placeholder="Secondary Email"/>
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
