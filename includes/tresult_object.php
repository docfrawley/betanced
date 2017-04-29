<? include_once("initialize.php");

class tresult_object {

	private $result;
  private $hresult;
  private $ncednum;

	function __construct() {
    $this->result = "";
    $this->ncednum = "";
    $this->hresult = false;
	}

	function not_repeat($number) {
		global $database;
    $sql="SELECT * FROM testresults WHERE tnumber ='".$number."'";
		$result_set = $database->query($sql);
    $this->hresult = ($database->num_rows($result_set) >0);
    if ($this->hresult){
      $value = $database->fetch_array($result_set);
      $this->result = $value['result'];
      $this->ncednum = $value['ncednum'];
    }
	}

  function have_result(){
    return $this->hresult;
  }

	function show_form(){
    ?>
		 <form action="<? tresults.php ?>" method="POST">
		 	<strong>PLEASE ENTER YOUR EXAM NUMBER</strong><br/><br/>
		 	<div class="row">
		 		<div class="small-5 columns">
		 			<input type="text" name="enumber" placeholder="Exam Number"/>
		 		</div>
		 	</div>
			<div class="row">
		 		<div class="small-12 columns">
        	<input type="submit" value="Submit" class="button small radius"/>
        </div>
      </div>
        </form><?
  }

  function process_request($info){
    global $database;
    $number = $database->escape_value($info['enumber']);
    $this->not_repeat($number);
    if (!$this->hresult){
      ?>
      <div class="row">
        <div class = "small-6 columns panel center">
          <h4>That is not a valid number. please try again.</h4>
        </div>
      </div>
      <?
    }
  }

  function show_result(){
    if ($this->result === "PASSED"){
      ?><h3>Congratulations! You passed the exam.</h3><br/>
  		<br/>Here is your certificate, which includes your NCED 4 digit number:&nbsp
			<a href="certificatepdf.php?ncednum=<? echo $this->ncednum; ?>"
				 target="_blank"
				 class="button tiny radius">Your Certificate</a>
  		<br/><br/>Please make sure login into your member page to ensure your contact
      information is current and to see the NCED registry.
      <br/><a href="login.php" class="button tiny radius">MEMBER LOGIN</a>
      <?
    } else {
      ?>
        You did not pass the exam.  You may request a re-examination within two (2) years of the first examination.
        In order to be approved for your re-take, you must verify that you still meet all eligibility requirements
        per your initial application. The re-take will be an alternate form of the examination at the next scheduled
        examination. For any questions regarding your exam or the retake, please email
        <strong>exam@ncedonline.org</strong>. You can do so directly via our
        <a href="contact.php" class="button tiny radius">CONTACT PAGE</a>
      <?
    }
  }


}
?>
