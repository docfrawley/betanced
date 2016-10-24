<? include_once("initialize.php"); 

class registryObject {
	
	private $lastLetter;
	private $state;
	private $speciality;
	private $reg_members;
	
	function __construct($info) { 
		global $database;
		$this->lastLetter = $database->escape_value($info['lname']);
		$this->state = $database->escape_value($info['state']);
		$this->speciality = $database->escape_value($info['speciality']);
		$this->reg_members = array();
		$tempArray = array();
		$sql="SELECT * FROM natdirectory WHERE inorout =true ORDER BY ncednum";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){
			array_push($tempArray, $value['ncednum']);
		}
		$sql="SELECT * FROM renewal WHERE status != 'REVOKE' ORDER BY lname";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){
			if (in_array($value['ncednum'], $tempArray)) {
				array_push($this->reg_members, $value['ncednum']);
			}
		}
		
	}

	function check_submission() {
		return ($this->lastLetter=="" && $this->state=="State" && $this->speciality=="");
	}

	function create_list(){ 
		global $database;

		if ($this->lastLetter !=""){
			$tempArray =array();
			foreach ($this->reg_members as $value) {
				$member = new infobject($value);
				$tempname = ucfirst($member->get_lname());
				if ($this->lastLetter == $tempname[0]){
					array_push($tempArray, $value);
				}
			}
			$this->reg_members = $tempArray;
			
		} 

		if ($this->state !="State"){ 
			$tempArray =array(); 
			foreach ($this->reg_members as $value) {
				$rmember = new ind_reg_object($value); 
				if ($this->state == $rmember->get_state()){
					array_push($tempArray, $value);
				}
			}
			$this->reg_members = $tempArray;
		}

		if ($this->speciality !=""){
			$tempArray =array();
			foreach ($this->reg_members as $value) {
				$rmember = new ind_reg_object($value);
				if (($this->speciality == $rmember->get_speciality()) || ($this->speciality == $rmember->get_speciality2())){
					array_push($tempArray, $value);
				}
			} 
			$this->reg_members = $tempArray;
		} 
	}

	function create_accordian(){
		$this->create_list();
		if (count($this->reg_members)>0){
			$counter = 1;
			$first = true; ?>
			<ul class="accordion" data-accordion> <?  
				foreach ($this->reg_members as $value) {
					$member = new memobject($value);
					$registry_info = new ind_reg_object($value); ?>
			  		<li class="accordion-navigation">
				  		<? $panelhash = "#panel".$counter."a"; ?>
				  		<? $panel = "panel".$counter."a"; ?>
				    	<a href="<? echo $panelhash; ?>"><? echo $member->get_displayname(); ?></a>
					    <? if ($first){
					    	?> <div id="<? echo $panel; ?>" class="content active"> <?
					    } else {
					    	?> <div id="<? echo $panel; ?>" class="content"> <?
					    } ?>
					    	<div class="row">
					    	<div class="small-4 columns">
						    <table><tr><td><?
							    echo $member->get_displayname(); ?>
							    </td></tr> <?
							    $registry_info->display_info(); ?>
							</table>
							</div>
							<div class="small-8 columns">
							<? $registry_info->display_specialties(); ?>
							</div>
					    </div>
			    	</li> <? 
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