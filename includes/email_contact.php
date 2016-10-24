<? include_once("initialize.php");
require_once 'vendor/autoload.php';

class contactObject {

	function mail_form(){ ?>
			<div class="custom-row-class">
		        <div class="small-6 small-centered columns">
		        <h3>Please complete the form below</h3>
		        </div>
		    </div>
		  <form action="contact.php" method="POST">
      <div class="row">
    				<div class="small-6 small-centered columns">
    		        	<label>Name: </label>
    		        	<input type="text" name="name"/>
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
			    	<textarea name="message" rows="10" cols="40"></textarea>
			    </div>
			</div>

			<div class="row">
			    <div class="small-6 small-centered columns">
			    	<label>Your email address:</label>
			    	<input type="text" name="sender"/>
			    </div>
			</div>
		    <input type="hidden" name="task" value="send_email"/>

		  <div class="row">
				<div class="small-6 small-centered columns">
					<input type="submit" value="Send Email" class="button small"/>
				</div>
			</div>
		        </form>
			<?
	}

	function send_email($info){
		global $database;
    $name = $database->escape_value($info['name']);
		$subject = $database->escape_value($info['subject_line']);
		$body = $database->escape_value($info['message']);
		$thesender = $database->escape_value($info['sender']);
		$m = new PHPMailer;
		$m->From = $thesender;
		$m->FromName = "";
		$m->addReplyTo($thesender, "Reply Address");
		$m->Subject = $subject;
		$m->Body = $body;
		$m->addAddress("chair@ncedonline.org");
		$m->send();
	}

}
?>
