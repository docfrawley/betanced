<? include_once("initialize.php");

class memobject {
	
	private $lname;
	private $fname;
	private $memstart;
	private $ryear;
	private $memstatus;
	private $ncednum;
	private $lastPayment;
	private $paymentDate;
	private $pending;
	
	function __construct($ncednum) {
		global $database;
		$this->ncednum = $database->escape_value($ncednum);
		$sql="SELECT * FROM renewal WHERE ncednum ='".$this->ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		$this->ryear = $value['renewyear'];
		$this->memstatus = $value['status'];
		$this->pending = $value['pending'];
		$sql="SELECT * FROM memstart WHERE ncednum ='".$this->ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->memstart = $value['whenst'];
		$sql="SELECT * FROM rmoney WHERE ncednum ='".$this->ncednum."' ORDER BY numid";
		$result_set = $database->query($sql);
		$temp = array();
		while ($info = $database->fetch_array($result_set)) {
			$this->lastPayment = $info['amount'];
			$this->paymentDate = $info['rdate'];
		}
	}
	
	
	function get_status() {
		if ($this->memstatus == "RENEWED") {
			echo "Renewed through {$this->ryear}";
			if ($this->get_pending()){
				echo ".<br/>Member has initiated renewal process and is PENDING renewal.";
			}
		} elseif ($this->memstatus == "REVOKED") {
			echo "Membership has been revoked";
		} else {
			echo "Membership is NOT renewed. Last year of renewal: {$this->ryear}" ;
		}
	}

	function get_ryear() {
		return $this->ryear;
	}

	function get_pending() {
		return ($this->pending == 'yes');
	}

	function get_memstart() {
		return $this->memstart;
	}

	function get_displayname() {
		global $database;
		$sql="SELECT * FROM renewal WHERE ncednum ='".$this->ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->lname = $value['lname'];
		$this->fname = $value['fname'];
		return $this->fname.' '.$this->lname;
	}

	function get_payment(){
		return $this->lastPayment;
	}

	function get_lastPayDate(){
		return $this->paymentDate;
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

	function payment_history(){
		global $database;
		$sql="SELECT * FROM rmoney WHERE ncednum ='".$this->ncednum."' ORDER BY numid";
		$result_set = $database->query($sql);
		?> 
		<h4>Payment History</h4>
		<table> 
			<tr><td>Amount</td><td>Method</td><td>Date Entered</td></tr><?
		while ($info = $database->fetch_array($result_set)) {
			?> <tr><td><?
			echo "$". number_format($info['amount'], 2, '.', '');
			?></td><td><?
			echo $info['manner'];
			?></td><td><?
			echo $info['rdate'];
			?></td></tr><?
		}
		?> </table> <?
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

	function update_renew($value){
		global $database;
		$howchange = $database->escape_value($value['howchange']);
		switch ($howchange) {
			case 'oneyear':
				$this->ryear++;
				$this->memstatus = 'RENEWED';
				$this->pending = "";
				break;
			case 'threeyear':
				$this->ryear +=3;
				$this->memstatus = 'RENEWED';
				$this->pending = "";
				break;
			case 'nonpend':
				$this->pending = "";
				break;
			case 'revoked':
				$this->memstatus = 'REVOKED';
			break;
			default:
				break;
		}
		$sql = "UPDATE renewal SET ";
		$sql .= "status='". $this->memstatus ."', ";
		$sql .= "pending='". $this->pending ."', ";
		$sql .= "renewyear='". $this->ryear ."'";
		$sql .= " WHERE ncednum='". $this->ncednum ."'";
	  	$database->query($sql);
	  	if ($howchange == 'oneyear' || $howchange== 'threeyear'){
		  	$today = new DateTime;
		  	$amount = $database->escape_value($value['amount']);
		  	$manner = $database->escape_value($value['amount']);
		  	if ($manner == "SELECT") {
		  		$manner = "";
		  	}
		  	if ($amount[0] == "$"){
		  		$amount = substr($amount, 1);
		  	}
		  	$sql = "INSERT INTO rmoney (";
			$sql .= "ncednum, amount, rdate, manner";
	 		$sql .= ") VALUES ('";
	 		$sql .= $this->ncednum ."', '";
	 		$sql .= $amount ."', '";
			$sql .= $today->format('F j, Y') ."', '";
			$sql .= $database->escape_value($value['manner']) ."')";
			$database->query($sql);
		}
	  	if ($value['email']=='yes'){
	  		$sql="SELECT * FROM nceddata WHERE ncednum ='".$this->ncednum."'";
			$result_set = $database->query($sql);
			$info = $database->fetch_array($result_set);
			$to = $info['email'];
			if ($howchange == 'oneyear' || $howchange== 'threeyear'){
				$subject = "NCED Renewal Confirmation";
				$message = 'Dear '.$this->get_displayname().','."\r\n"."\r\n";
				$message .= "Congratulations. You are renewed through {$this->ryear}."."\r\n"."\r\n";
				$message .= 'Thank you for maintaining your NCED membership.'."\r\n"."\r\n";
				$message .= 'If you have any questions about your membership, please feel free to reply to this email.'."\r\n"."\r\n";
				$message .= 'Sincerely,'."\r\n"."\r\n";
				$message .= 'The NCED Board';
			} elseif ($howchange == 'nonpend'){
				$subject = "NCED Renewal Form";
				$message = 'Dear '.$this->get_displayname().','."\r\n"."\r\n";
				$message .= "We have reset your online renewal form so that you can now begin again the renewal online process."."\r\n"."\r\n";
				$message .= 'Thank you for maintaining your NCED membership.'."\r\n"."\r\n";
				$message .= 'If you have any questions about your membership, please feel free to reply to this email.'."\r\n"."\r\n";
				$message .= 'Sincerely,'."\r\n"."\r\n";
				$message .= 'The NCED Board';
			} 
			$headers = "From: membership@ncedonline.org"."\r\n"."Reply-To: membership@ncedonline.org"."\r\n"."X-mailer:PHP/".phpversion();
			mail ($to, $subject, $message, $headers);
	  	}
	}

	function admin_renew(){
		?> 
		 <form action="ncedadmin.php" method="POST">
		 	<fieldset>
		 		<legend>Change Membership Status</legend>
		 		<div class="row">
		 			<? $meminfo = new infobject($this->ncednum); 
		 			if ($meminfo->get_email()=="") { ?>
			 			<div class="small-12 columns">
			 				<p>We do not have an email for this member. You will have to enter an email address in the contact information form to the left and submit before completing this form if you want to send an automatic email when updating this member's membership status.</p>
			 			</div> <?
				 	} ?>
				 	<div class="small-12 columns">
				 			<p>
				 				<?
				 				$oneYearR = $this->ryear +1;
				 				$threeYearR = $this->ryear + 3;
				 				echo $this->get_displayname()." is renewed through <strong>".$this->ryear.".</strong> ";
				 				echo "A one year renewal payment will renew this member through <strong>".$oneYearR."</strong>. ";
				 				echo "A three year renewal payment will renew this member through <strong>".$threeYearR."</strong>.<br/>";
				 				if ($this->get_pending()){
				 					echo "This member has initiated the renewal process. To reset their renewal form so they can start the renewal process again, click on NON-PENDING.";

				 				}

				 				?>
				 			</p>
				 	</div>
			 		<div class="small-12 columns">
			 			<label>Renewal Status</label>

			 			<input type="radio" name="howchange" value="oneyear"> ONE YEAR RENEWAL<br/>
						<input type="radio" name="howchange" value="threeyear"> THREE YEAR RENEWAL<br/>
						<? if ($this->get_pending()) { ?>
							<input type="radio" name="howchange" value="nonpend"> NON-PENDING<br/> 
						<? } ?>
						<input type="radio" name="howchange" value="revoked"> REVOKED<br/>
			 		</div>	
			 		
			 		<div class="row">
				 		<div class="small-6 columns">
				 			<label>Payment Amt For Renewals</label>
				 			<input type="text" name="amount" placeholder="Eg. 35.00"/>	
				 		</div>	
				 		<div class="small-6 columns">
				 			<label>Payment Method</label>
				 				<select name="manner">
				 					<option value="select"/> SELECT </option>
					        		<option value="paypal"/> PAYPAL </option>
					        		<option value="check"/> CHECK</option>
					        		<option value="money order"/> MONEY ORDER</option>
					        		<option value="cash"/> CASH</option>
							 	</select>
				 		</div>	
				 	</div>
			 		<input type="hidden" name="ncednumber" value="<? echo $this->ncednum; ?>"/>
				 	<div class="small-12 columns">
				 		<? 	if ($meminfo->get_email() != "") { ?>
	        					<input type="checkbox" name="email" value="yes"> <label>Send email about renewal status change?<br/>Does not work for REVOKE</label> <?
				 			} ?>
				 		
	        		</div>	
			 		<div class="small-12 columns">
	        			<input type="submit" value="Submit" class="button tiny radius"/>
	        		</div>
        		</div>
        	</fieldset>
        </form><?
	}
}
?>