<? include_once("initialize.php");
require_once 'vendor/autoload.php';

class memobject {

	private $lname;
	private $fname;
	private $memstart;
	private $ryear;
	private $memstatus;
	private $ncednum;
	private $lastPayment;
	private $paymentDate;
	private $paymentHistory;
	private $pending;
	private $date_revoked;
	private $manner;

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
		$this->date_revoked = $value['date_revoked'];
		$sql="SELECT * FROM memstart WHERE ncednum ='".$this->ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->memstart = $value['whenst'];
		$this->paymentHistory = array();

		$date1 = new DateTime("now");
		$date = "2/28/".date('Y');
		$date2 = new DateTime($date);
		$year = date('Y');
		if ($this->ryear< $year &&
			$date1 > $date2 && $this->memstatus != 'REVOKED'){
			$this->memstatus = 'NON-RENEWED';
			$sql = "UPDATE renewal SET ";
			$sql .= "status='NON-RENEWED'";
			$sql .= " WHERE ncednum='". $this->ncednum ."'";
			$database->query($sql);
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
		} elseif ($this->memstatus=="NON-RENEWED"){
			echo "Renewed through {$this->ryear}, but your current status is 'NON-RENEWED'
			because you have missed the deadline to renew. As such you do not have access to
			the registry or online newsletters. Please update your renewal status as soon as possible.";
		} else {
			echo "Membership is NOT renewed. Last year of renewal: {$this->ryear}" ;
		}
	}

	function set_phistory(){
		global $database;
		$this->paymentHistory = array();
		$sql="SELECT * FROM rmoney WHERE ncednum ='".$this->ncednum."' ORDER BY numid";
		$result_set = $database->query($sql);
		while ($info = $database->fetch_array($result_set)) {
			// list($tmonth, $year) = explode(',', $info['rdate']);
			// list($month, $day) = explode(' ', $tmont);
			// $themonth = intval($month);
			// $theday = intval($day);
			// $theyear = intval($year);
			// $info['rdate']= mktime(1,1,1,$themonth,$theday,$theyear);
			array_push($this->paymentHistory, $info);
		}
	}

	function get_num(){
		return $this->ncednum;
	}

	function get_name() {
		return $this->fname." ".$this->lname;
	}

	function get_fname(){
		return $this->fname;
	}

	function get_lname(){
		return $this->lname;
	}

	function get_ryear() {
		return $this->ryear;
	}

	function get_payhistory(){
		$this->set_phistory();
		return $this->paymentHistory;
	}

	function get_pending() {
		return ($this->pending == 'yes' || $this->pending== 'reset');
	}

	function get_memstart() {
		return $this->memstart;
	}

	function get_memstatus() {
		return $this->memstatus;
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

	function get_payment_info($numid){
		global $database;
		$sql="SELECT * FROM rmoney WHERE numid ='".$numid."'";
		$result_set = $database->query($sql);
		$info= $database->fetch_array($result_set);
		return $info;
	}

	function get_payment(){
		return $this->lastPayment;
	}

	function get_manner(){
		return $this->manner;
	}

	function get_lastPayDate(){
		return $this->paymentDate;
	}

	function get_date_revoked(){
		return $this->date_revoked;
	}

	function display_member() {
		global $database;
		?>
			<legend>Membership Info</legend>
			<div class="row">
				<? if (isset($_SESSION['ncedadmin'])) { ?>
					<div class="small-6 columns">
				<? } else { ?>
					<div class="small-9 columns">
				<? } ?>
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
								NCED#:
							</td>
							<td>
								<? echo $this->ncednum ; ?>
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
					</table>
					</div>
				<? if (isset($_SESSION['ncedadmin'])) { ?>
					<div class="small-6 columns">
						<? $this->change_renew_year(); ?>
					</div>
				<? } else {?>
					<div class="small-3 columns">
						<? if ($this->memstatus != "REVOKED"){ ?>
							<a href="certificatepdf.php?ncednum=<? echo $_SESSION['ncednumber']; ?>"
								 target="_blank"
								 class="button tiny radius">Your Certificate</a>
							</div>
					<?	} ?>

			<?	}?>
			</div>
			<?
	}

	function task_renew($info){
		global $database;
		$task = $database->escape_value($info['task']);
		$this->pending = $task;
		$sql = "UPDATE renewal SET ";
		$sql .= "pending='". $this->pending ."'";
		$sql .= " WHERE ncednum='". $this->ncednum ."'";
		$database->query($sql);

		$member = new infobject($this->ncednum);
		$m = new PHPMailer;
		$m->From = $member->get_email();
		$m->FromName = $this->get_displayname();
		$m->addReplyTo($member->get_email(), "Reply Address");
		if ($task=="yes" || $task=="reset"){
			if ($task=="yes"){
				$m->Subject = "Initiated Renewal";
				$message = 'Dear Membership Chair'.','."\r\n"."\r\n";
				$message .= "I have initiated the renewal process and am using Paypal."."\r\n"."\r\n";
			} else {
				$m->Subject = "Please reset my renewal process";
				$message = 'Dear Membership Chair'.','."\r\n"."\r\n";
				$message .= "I have initiated the renewal process but wish to start again."."\r\n"."\r\n";
			}
			$message .= 'My NCED membership # is:'.$this->ncednum."\r\n"."\r\n";
			$m->Body = $message;
			$m->AltBody = "";
			$m->addAddress("admin@ncedonline.org");
			$m->send();
		}
	}

	function renew_process(){
		global $database;
		$fadmin = new files_object();
		switch ($this->pending) {
		case 'yes': ?>
			<div class="row">
				<div class="small-1 columns">
					<i class="fi-alert"></i>
				</div>
				<div class="small-11 columns">
				You have initiated the renewal process such that your status is now
				listed as pending. The membership chair will update your renewal shortly.<br/><br/>
				If you have made a mistake and wish to start the renewal process over again,
				please click on the link below which will alert the membership chair who
				will email you to let you know when your renewal process has been reset.
				</div>
			</div>
			<div class="row">
				<div class="small-6 small-centered columns">
					<a href="memberin.php?task=reset" class="button extend radius ceu-button">RESET RENEW</a>
				</div>
			</div>
			<?
		break;
		case 'reset': ?>
		<div class="row">
			<div class="small-1 columns">
				<i class="fi-alert"></i>
			</div>
			<div class="small-11 columns">
			The membership chair has been alerted that you wish to reset your
			renewal process. You will be emailed when it has been reset. Thank
			you for your patience.
			</div>
		</div>
		<?
		break;
		case 'renew': ?>
			<div class="row">
				<div class="small-1 columns">
					<i class="fi-paypal"></i>
				</div>
				<div class="small-11 columns">
					You have elected to pay via paypal. Please click on the Paypal button
					below to pay your $35.00 for one year renewal or $100.00 for 3 year
					renewal registration fee. Please verify that you are in compliance
					with your CEU requirements.
				</div >
			</div>
			<div class="row">
				<div class="small-6 columns">
					<h3>$35.00 button</h3>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="BP4E3HHUQQHYC">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
				<div class="small-6 columns">
					<h3>$100.00 button</h3>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="RFKH2SAB7VWRG">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div><?

			$this->pending = "yes";
			$sql = "UPDATE renewal SET ";
			$sql .= "pending='". $this->pending ."'";
			$sql .= " WHERE ncednum='". $this->ncednum ."'";
			$database->query($sql);
		break;
		default: ?>
			<div class="row">
				<div class="small-12 columns">
					You may renew your membership for one year for $35.00 or for three years for $100.00.
					You have two options for how you renew your membership. You must renew by January 31st.<br/>
				</div>
			</div>
	<div class="row">
		<div class="small-1 columns">
			<i class="fi-mail"></i>
		</div>
		<div class="small-11 columns">
			Download and complete this <strong>
				<a href="<? echo $fadmin->get_path('FORM'); ?>"
					target="_blank">FORM</a></strong>.
			Make sure to mail the form and payment to the address listed on the form. You will
			notified when your payment and form have been processed.
		</div>
	</div>
	<div class="row">
		<div class="small-1 columns">
			<i class="fi-paypal"></i>
		</div>
		<div class="small-11 columns">
			Renew online using PayPal. First click the renew button below and the two paypal options for renewal
			will appear. You will be notified when your payment has been processed.
		</div>
	</div>
	<div class="row">
		<div class="small-6 small-centered columns">
			<a href="memberin.php?task=renew" class="button extend radius ceu-button">RENEW MEMBERSHIP</a>
		</div>
	</div>
		<?
		break;
		}
	}

	// function compare_dates($a, $b){
	// 	return strnatcmp($a['rdate'], $b['rdate']);
	// }

	function payment_history(){
		$this->set_phistory();
  	// usort($this->paymentHistory, $this->compare_dates());
		?>
		<h4>Payment History</h4>
		<table>
			<tr><td>Amount</td><td>Method</td><td>Date Entered</td><td>Check #</td></tr><?
		// while ($info = $database->fetch_array($result_set)) {
		foreach ($this->paymentHistory as $info) {
			?> <tr><td><?
			echo "$". number_format($info['amount'], 2, '.', '');
			?></td><td><?
			if ($info['manner']=="select"){
				echo "";
			} else {
				echo $info['manner'];
			}
			?></td><td><?

			echo $info['rdate'];
			?></td><td><?
			echo $info['checkNum'];
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
		if ($howchange=='revoked'){
			$today = new DateTime;
			$sql .= "date_revoked='". $today->format('F j, Y') ."', ";
		}
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
			$sql .= "ncednum, amount, rdate, manner, checkNum";
	 		$sql .= ") VALUES ('";
	 		$sql .= $this->ncednum ."', '";
	 		$sql .= $amount ."', '";
			$sql .= $today->format('F j, Y') ."', '";
			$sql .= $database->escape_value($value['manner']) ."', '";
			$sql .= $database->escape_value($value['checkNum']) ."')";
			$database->query($sql);
		}
	  	if ($value['email']=='yes'){
			$member = new infobject($this->ncednum);
			$m = new PHPMailer;
			$m->From = "membership@ncedonline.org";
			$m->FromName = "";
			$m->addReplyTo("membership@ncedonline.org", "Reply Address");
			if ($howchange == 'nonpend'){
				$m->Subject = "NCED Renewal Form";
				$message = 'Dear '.$this->get_displayname().','."\r\n"."\r\n";
				$message .= "We have reset your online renewal form so that you can now begin again the renewal online process."."\r\n"."\r\n";
				$message .= 'Thank you for maintaining your NCED membership.'."\r\n"."\r\n";
				$message .= 'If you have any questions about your membership, please feel free to reply to this email.'."\r\n"."\r\n";
				$message .= 'Sincerely,'."\r\n"."\r\n";
				$message .= 'The NCED Board';
			} else {
				$m->Subject = "NCED Renewal Form";
				$message = 'Dear '.$this->get_displayname().','."\r\n"."\r\n";
				$message .= "Congratulations. You are renewed through {$this->ryear}."."\r\n"."\r\n";
				$message .= 'Thank you for submitting your dues.  Please update your contact information and verify that you are in compliance with your CEU requirements by visiting our website.'."\r\n"."\r\n";
				$message .= 'Sincerely,'."\r\n"."\r\n";
				$message .= 'The NCED Board';
			}
			$m->Body = $message;
			$m->AltBody = "";
	        $m->addAddress($member->get_email());
	        if ($m->send()){
				?>
				<div class="row">
					<div class = "small-5 columns panel center">
						<h2>Email has been sent</h2>
					</div>
				</div>
				<?
			} else {
				?>
				<div class="row">
					<div class = "small-5 columns panel center">
						<h2>Email has been NOT sent</h2>
					</div>
				</div>
				<?
			}
	  	}
	}

	function update_renewed_year($year){
		global $database;
		$changeyear = $database->escape_value($year);
		$this->ryear = $changeyear;
		$sql = "UPDATE renewal SET ";
		$sql .= "renewyear='". $this->ryear ."'";
		$sql .= " WHERE ncednum='". $this->ncednum ."'";
	  	$database->query($sql);
	}

	function change_renew_year(){
		?>
		<form action="ncedadmin.php" method="POST">
		<div class="row">
			<div class="small-12 columns">
				<label>Change Year Renewed for Member</label>
					<input type="text" name="changeyear" value="<? echo $this->ryear; ?>"/>
			</div>
			<input type="hidden" name="ncednumber" value="<? echo $this->ncednum; ?>"/>
			<div class="small-12 columns">
	        	<input type="submit" value="Submit" class="button tiny radius"/>
	        </div>
		</div>
		</form> <?
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
				 		<div class="small-3 columns">
				 			<label>Payment Amt</label>
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
						<div class="small-3 columns">
				 			<label>Check #</label>
				 			<input type="text" name="checkNum" placeholder="Check #"/>
				 		</div>
				 	</div>
			 		<input type="hidden" name="ncednumber" value="<? echo $this->ncednum; ?>"/>
				 	<div class="small-12 columns">
				 		<? 	if ($meminfo->get_email() != "") { ?>
	        					<input type="checkbox" name="email" value="yes"> <label>Send email about renewal status change?<br/>Does not work for REVOKE</label> <?
				 			} else {
				 				echo "We do not have an email address listed for this member. Please consider entering an email address in the contact info form to the left before completing this form so that an automatic email can be sent.";
				 			}?>

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
