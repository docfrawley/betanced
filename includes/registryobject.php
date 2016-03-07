<? include_once("initialize.php");

class registryObject {
	
	private $lastLetter;
	private $state;
	private $speciality;
	
	function __construct($info) {
		global $database;
		$this->lastLetter = $database->escape_value($info['lname']);
		$this->state = $database->escape_value($info['state']);
		$this->speciality = $database->escape_value($info['speciality']);
	}

	function check_submission() {
		return ($this->lastLetter=="" && $this->state=="State" && $this->speciality=="");
	}

	function create_list(){
		global $database;
		$tempArray =array();
		$arrayCreated = false;  
		if ($this->lastLetter !=""){
			$arrayCreated=true;
			$sql="SELECT * FROM natdirectory ORDER BY lname";
			$result_set = $database->query($sql);
			while($value = $database->fetch_array($result_set)){
				$tempname = ucfirst($value['lname']);
				if ($this->lastLetter == $tempname[0]){
					array_push($tempArray, $value);
				}
			}
		} 
		if ($this->state !="State"){
			if (!$arrayCreated){
				$arrayCreated=true;
				$sql="SELECT * FROM natdirectory ORDER BY lname";
				$result_set = $database->query($sql);
				while($value = $database->fetch_array($result_set)){
					if ($this->state == ucfirst($value['state'])){
						array_push($tempArray, $value);
					}
				}
			} else {
				$stempArray = array();
				foreach ($tempArray as $value) {
					if ($this->state == $value['state']){
						array_push($stempArray, $value);
					}
				}
				$tempArray = $stempArray;
			}
		}

		if ($this->speciality !=""){
			if (!$arrayCreated){
				$arrayCreated=true;
				$sql="SELECT * FROM natdirectory ORDER BY lname";
				$result_set = $database->query($sql);
				while($value = $database->fetch_array($result_set)){
					if (($this->speciality == $value['speciality']) || ($this->speciality == $value['speciality2'])){
						array_push($tempArray, $value);
					}
				}
			} else {
				$sptempArray = array();
				foreach ($tempArray as $value) {
					if (($this->speciality == $value['speciality']) || ($this->speciality == $value['speciality2'])){
						array_push($sptempArray, $value);
					}
				}
				$tempArray = $stempArray;
			}
		}
		return $tempArray;
	}

	function create_accordian(){
		$theArray = $this->create_list();
		if (count($theArray)>0){
			$counter = 1;
			$first = true;
			?>
			<ul class="accordion" data-accordion>
				<?  foreach ($theArray as $value) {
					$ncednum = $value['ncednum'];
					$member = new infobject($ncednum);
				 ?>
			  <li class="accordion-navigation">
			  	<? $panelhash = "#panel".$counter."a"; ?>
			  	<? $panel = "panel".$counter."a"; ?>
			    <a href="<? echo $panelhash; ?>"><? echo $member->full_name(); ?></a>
			    <? if ($first){
			    	?> <div id="<? echo $panel; ?>" class="content active"> <?
			    } else {
			    	?> <div id="<? echo $panel; ?>" class="content"> <?
			    } 
			      echo $value['staddress']."<br/>";
			      echo $value['city']."<br/>";
			      echo $value['state']."<br/>";
			      echo $value['zip']."<br/>";
			      echo $value['email']."<br/>";
			      echo $value['phone']."<br/>";
			      echo $value['speciality']."<br/>";
			      echo $value['speciality2']."<br/>";
			      echo $value['otherlanguage']."<br/>";
			      ?>
			    </div>
			  </li>
			  <? 
			  $first = false;
			  	$counter++;
				} ?>
			</ul><?
		} else {
			echo "There are no matches for that search. Please try again.";
		}
		
	}
}
?>