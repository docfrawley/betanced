<? include_once("initialize.php");
require_once 'vendor/autoload.php';

class email_object {

	private $allmem;
	private $those_with;
	private $those_without;



	function __construct() {
		global $database;
		$sql="SELECT * FROM renewal WHERE status !='REVOKED' AND status !='ncedadmin' ORDER BY lname";
		$result_set = $database->query($sql);
		$this->allmem = array();
		$this->those_with = array();
		$this->those_without = array();
		while ($value = $database->fetch_array($result_set)) {
			array_push($this->allmem, $value['ncednum']);
		}

		for ($counter=0; $counter< count($this->allmem); $counter++) {
			$sql="SELECT * FROM nceddata WHERE ncednum = '".$this->allmem[$counter]."'";
			$result_set = $database->query($sql);
			if ($database->num_rows($result_set)<1) {
				$member = new memobject($this->allmem[$counter]);
				$sql = "INSERT INTO nceddata (";
				$sql .= "ncednum, fname, lname";
 				$sql .= ") VALUES ('";
 				$sql .= $this->allmem[$counter] ."', '";
				$sql .= $database->escape_value($member->get_fname()) ."', '";
				$sql .= $database->escape_value($member->get_lname()) ."')";
				$database->query($sql);
			}
		}

	}


	function get_groupings(){
		$this->those_with = array();
		$this->those_without = array();
		for ($counter=0; $counter< count($this->allmem); $counter++) {
			$member = new infobject($this->allmem[$counter]);
			if ($member->get_email()==""){
				array_push($this->those_without, $this->allmem[$counter]);
			} else {
				array_push($this->those_with, $this->allmem[$counter]);
			}
		}
	}

	function excel_list(){
		$this->get_groupings();
		$output = "";
         $output .= '
            <table class="table" bordered="1">
						<tr><th>Email List</th></tr>
            	<tr>
								<th>NCED#</th>
            		<th>Name</th>
                <th>Primary Email</th>
								<th>Secondary Email</th>
              </tr>
           ';
           for ($counter=0; $counter< count($this->those_with); $counter++) {
           		$member = new infobject($this->those_with[$counter]);
                $output .= '
                     <tr>
										 			<td>'.$member->get_ncednum().'</td>
                          <td>'.$member->full_name().'</td>
                          <td>'.$member->get_email().'</td>
													<td>'.$member->sec_email().'</td>
                     </tr>
                ';
           }
           $output .= '</table>';
           return $output;
	}

	function list_noemail(){
		$this->get_groupings();
		for ($counter=0; $counter< count($this->those_without); $counter++) {
			$member = new infobject($this->those_without[$counter]);
			echo "<a href='?task=addEmail&ncednumber={$this->those_without[$counter]}'>".$member->full_name().", NCED#: ".$this->those_without[$counter]."</a><br/>";
		}
	}

	function get_nums(){
		$this->get_groupings();
		echo "<h4># of holders with emails listed: <strong>".count($this->those_with)."</strong></h4>";
		echo "<br/><h4># of holders without emails listed: <strong>".count($this->those_without)."</strong></h4>";
		return count($this->those_with);
	}

	function mail_form($toWhom='all'){
		if ($toWhom == 'all'){
			?>
			<div class="row">
	        	<div class="small-6 small-centered columns">
	        		For bulk emails to all members, first download<br/>
	        		the current list of members and emails to <br/>
	        		upload to mailchimp<br/><br/>
	        		<form action="excel.php" method="post">
	        		<input type="submit" name="export_excel" value="Export to Excel" class="button"/>
	        		</form>
	        	</div>
	    	</div>
	    	<?
		} else {
			?>
			<div class="row">
		        <div class="small-6 small-centered columns">
		        This email will be sent to
		        <?
		        	$member = new infobject($toWhom);
		        	$toWhom = $member->get_email();
		        	echo $member->full_name()." at: ".$toWhom."<br/><br/>";
		        ?>
		        </div>
		    </div>
		    <form action="emailadmin.php" enctype="multipart/form-data"  method="post">
		    <div class="row">
		        <div class="small-6 small-centered columns">
					<label>From Which Admin Email Address:</label>
					<select name="ncedemail">
			            <option value="chair@ncedonline.org">chair@ncedonline.org</option>
			            <option value="vicechair@ncedonline.org">vicechair@ncedonline.org</option>
			            <option value="treasurer@ncedonline.org">treasurer@ncedonline.org</option>
			            <option value="secretary@ncedonline.org">secretary@ncedonline.org</option>
			            <option value="membership@ncedonline.org">membership@ncedonline.org</option>
			            <option value="exam@ncedonline.org">exam@ncedonline.org</option>
			            <option value="profgrowth@ncedonline.org">profgrowth@ncedonline.org</option>
			            <option value="publicity@ncedonline.org">publicity@ncedonline.org</option>
			            <option value="website@ncedonline.org">website@ncedonline.org</option>
		            </select>
				</div>
			</div>
			<div class="row">
				<div class="small-6 small-centered columns">
		        	<label>Subject Line: </label>
		        	<input type="text" name="subject_line"/>
		        </div>
		    </div>
			<div class="row">
			    <div class="small-6 small-centered columns">
			    	<label>Body of Email:</label>
			    	<textarea name="message" rows="7" cols="40"></textarea>
			    </div>
			</div>
			<div class="row">
			    <div class="small-6 small-centered columns">
			    	<label>Upload PDF:</label>
			    	<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
			    	<input type="file" name="fileToUpload" id="fileToUpload">
			    </div>
			</div>
		    <input type="hidden" name="toWhom" value="<? echo $toWhom; ?>"/>
		    <input type="hidden" name="task" value="send_email"/>
		    <div class="row">
				<div class="small-6 small-centered columns">
					<input type="submit" value="Send Email" class="button small"/>
				</div>
			</div>
		        </form>
			<?
		}
	}

	function send_email($info, $filestuff){
		global $database;

		$tmp_file = $filestuff['fileToUpload']['tmp_name'];
		$target_file = basename($filestuff['fileToUpload']['name']);
		$upload_dir = "pdfs";
		$the_place = $upload_dir."/".$target_file;
		if(move_uploaded_file($tmp_file, $upload_dir."/".$target_file)) {
			$message = "File uploaded successfully.";
		} else {
			$error = $_FILES['fileToUpload']['error'];
			$message = $upload_errors[$error];
		}

		$this->get_groupings();
		$subject = $database->escape_value($info['subject_line']);
		$body = $info['message'];
		$thesender = $database->escape_value($info['ncedemail']);
		$Whom = $database->escape_value($info['toWhom']);
		if ($Whom=='all'){
			$the_count = 0;
			for ($counter=0; $counter< count($this->those_with); $counter++) {
				$member = new infobject($this->those_with[$counter]);
				$m = new PHPMailer;
				$m->From = $thesender;
				$m->FromName = "";
				$m->addReplyTo($thesender, "Reply Address");
				$m->Subject = $subject;
				$m->Body = $body;
				$m->AltBody = "";
				$m->addAddress($member->get_email());
				if ($target_file !=""){
					$m->addAttachment($the_place);
				}
				if ($m->send()){
					$the_count++;
				}
			}
			if ($the_count>0){
				?>
				<div class="row">
					<div class = "small-5 columns panel center">
						<h2>Email has been sent <?echo $counter; ?></h2>
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
		} else {

			$m = new PHPMailer;
			$m->From = $thesender;
			$m->FromName = "";
			$m->addReplyTo($thesender. "Reply Address");
			$m->Subject = $subject;
			$m->Body = $body;
			$m->AltBody = "";
	        $m->addAddress($Whom);
	        if ($target_file !=""){
				$m->addAttachment($the_place);
			}
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

}
?>
