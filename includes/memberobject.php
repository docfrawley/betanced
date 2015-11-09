<? include_once("initialize.php");

class memobject {
	
	private $lname;
	private $fname;
	private $memstart;
	private $ryear;
	private $memstatus;
	private $ncednum;
	
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
		$sql="SELECT * FROM memstart WHERE ncednum ='".$this->ncednum."'";
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

	function payment_history(){
		global $database;
		$sql="SELECT * FROM rmoney WHERE ncednum ='".$this->ncednum."' ORDER BY rdate";
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
		$this->ryear = $database->escape_value($value['ryear']);
		$this->memstatus = $database->escape_value($value['rstatus']);
		$sql = "UPDATE renewal SET ";
		$sql .= "status='". $this->memstatus ."', ";
		$sql .= "renewyear='". $this->ryear ."'";
		$sql .= " WHERE ncednum='". $this->ncednum ."'";
	  	$database->query($sql);

	  	$today = new DateTime;
	  	$amount = $database->escape_value($value['amount']);
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

	  	if ($value['email']=='yes'){
	  		$sql="SELECT * FROM nceddata WHERE ncednum ='".$this->ncednum."'";
			$result_set = $database->query($sql);
			$info = $database->fetch_array($result_set);
			$to = $info['email'];
			$subject = "NCED Renewal Confirmation";
			$message = 'Dear '.$this->get_displayname().','."\r\n"."\r\n";
			$message .= "Congratulations. You are renewed through {$this->ryear}."."\r\n"."\r\n";
			$message .= 'Thank you for maintaining your NCED membership.'."\r\n"."\r\n";
			$message .= 'If you have any questions about your membership, please feel free to reply to this email.'."\r\n"."\r\n";
			$message .= 'Sincerely,'."\r\n"."\r\n";
			$message .= 'The NCED Board';
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
			 		<div class="small-12 columns">
			 			<label>Renewal Status</label>
			 				<select name="rstatus">
			 					<option selected="selected" value="<? echo $this->memstatus; ?>"/> <? echo $this->memstatus; ?> </option>
				        		<option value="RENEWED"/> RENEWED </option>
				        		<option value="REVOKED"/> REVOKED</option>
						 	</select>
			 		</div>	
			 		<div class="small-12 columns">
			 			<label>Renewal Year</label>
			 				<select name="ryear">
			 					<option selected="selected" value="<? echo $this->ryear; ?>"/> <? echo $this->ryear; ?> </option>
						 		<? 
			 					for ($x=0; $x<6; $x++){
			 						?><option value="<? echo date('Y')+$x; ?>"/> <? echo date('Y')+$x; ?> </option><?
			 					}
			 					?>
			 			 	</select>
			 		</div>
			 		<div class="row">
				 		<div class="small-6 columns">
				 			<label>Payment Amount</label>
				 			<input type="text" name="amount" placeholder="Eg. 35.00"/>	
				 		</div>	
				 		<div class="small-6 columns">
				 			<label>Payment Method</label>
				 				<select name="manner">
					        		<option value="paypal"/> PAYPAL </option>
					        		<option value="check"/> CHECK</option>
							 	</select>
				 		</div>	
				 	</div>
			 		<input type="hidden" name="ncednumber" value="<? echo $this->ncednum; ?>"/>
				 	<div class="small-12 columns">
	        			<input type="checkbox" name="email" value="yes"> <label>Send email about renewal status change?</label>
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