<? include_once("initialize.php");

class ceuobject {
	
	private $numindex;
	private $entrydate;
	private $ceudate;
	private $areaceu;
	private $typeceu;
	private $notes;
	private $numhrs;
	
	function __construct($numindex) {
		global $database;
		$sql="SELECT * FROM ceurenewal WHERE numindex ='".$numindex."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->numindex = $numindex;
		$this->entrydate = $value['entrydate'];
		$this->ceudate = $value['ceudate'];
		$this->areaceu = $value['areaceu'];
		$this->typeceu = $value['typeceu'];
		$this->notes = $value['notes'];
		$this->numhrs = $value['numhrs'];
		if ($this->ceudate==0) { 
			$sql = "UPDATE ceurenewal SET ";
			$sql .= "ceudate='". $this->entrydate ."' ";
			$sql .= "WHERE numindex='". $numindex."'";
			$database->query($sql);
			$this->ceudate = $this->entrydate; 
		}
	}

	function get_numindex(){
		return $this->numindex;
	}

	function get_hrs(){
		return $this->numhrs;
	}

	function get_area(){
		return $this->areaceu;
	}

	function get_ceudate(){
		return $this->ceudate;
	}
	
	function show_entry($archive=false){
		?> <tr> <td><?
		if ($archive){
			echo date('n/j/y', $this->ceudate);
		} else {
			echo "<a href='?updateceu={$this->numindex}'>".date('n/j/y', $this->ceudate)."</a>";
		}
		?> </td><td> <?
			echo date('n/j/y', $this->entrydate);
		?> </td><td> <?
			echo $this->typeceu;
		?> </td><td> <?
			echo $this->numhrs;
		?> </td><td> <?
			echo $this->notes;
		?> </td></tr><?
	}

	function update_ceu($info){
		global $database;
		$datereceipt= mktime(10,0,0, $database->escape_value($info['month']) , 
			$database->escape_value($info['day']), 
			$database->escape_value($info['year']));
		$sql = "UPDATE ceurenewal SET ";
		$sql .= "ceudate='". $database->escape_value($datereceipt) ."', ";
		$sql .= "typeceu='". $database->escape_value($info['typeceu']) ."', ";
		$sql .= "numhrs='". $database->escape_value($info['numhrs']) ."', ";
		$sql .= "notes='". $database->escape_value($info['notes'])."'";
		$sql .= " WHERE numindex='". $this->numindex ."'";
	  	$database->query($sql);
	  	$_SESSION['ceumessage']="You have successfully edited that CEU entry. Changes are reflected below.";
	}

	function update_form(){
		?> <strong> <? get_area($this->areaceu); ?> </strong><br/>
		 <form action="ceupage.php" method="POST">
		 	<div class="row">
		 		<fieldset>
		 			<legend>Date of CEU</legend>
		 				<div class="small-4 columns">
		 					<label>Month
			 					<select name="month">
			 					<option selected="selected" value="<? echo date('n', $this->ceudate); ?>"><? echo date('F', $this->ceudate);; ?></option>
        						<? getMonths(); ?>
		 						</select>
		 					</label>
		 				</div>
						<div class="small-4 columns">
							<label>Day
								<select name="day">
								<option selected="selected" value="<? echo date('d', $this->ceudate); ?>"><? echo date('d', $this->ceudate); ?></option>
			        			<? getDays(); ?>
					 			</select>
					 		</label>
						</div>
						<div class="small-4 columns">
							<label>Year
					 			<select name="year">
					 				<option selected="selected" value="<? echo date('Y', $this->ceudate); ?>"><? echo date('Y', $this->ceudate); ?></option>
			        				<? getYears(); ?>
					 			</select>
				 			</label>
						</div>
				</fieldset>
			</div>
		 	<div class="row">
		 		<div class="small-4 columns">
		 			<label>Enter CEU hours:</label>
		 			<input type="text" name="numhrs" value="<? echo $this->numhrs;  ?>"/>
		 		</div>
		 		<div class="small-8 columns">
		 			<label>Enter type of CEU:</label>
		 			<select name="typeceu">
		 				<option selected="selected" value="<? echo $this->typeceu; ?>"><? echo $this->typeceu; ?></option>
        				<? get_type($this->areaceu); ?>
		 			</select>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<label>If "Other", please describe activity: </label>
        			<input type="text" name="othertype" name="othertype" value="<? ?>"/>
        		</div>
        	</div>
        	<div class="row">
        		<div class="small-12 columns">
        			<label>Please enter any notes to describe CEU activity: </label>
        			<textarea name="notes"><? echo $this->notes; ?></textarea>
        		</div>
        	</div>
        	<input type="hidden" name="updateceu" value="<? echo $this->numindex; ?>"/>
			<div class="row">
		 		<div class="small-12 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        	</div>
        </form><?
	}

	function delete_ceu(){
		global $database;
		$sql = "DELETE FROM ceurenewal ";
	  	$sql .= "WHERE numindex='".$this->numindex."' ";
	  	$sql .= "LIMIT 1";
		$database->query($sql);
		if ($database->affected_rows() == 1) {
			$_SESSION['ceumessage']="You have successfully deleted that CEU, which is reflected in your CEU listings below.";
		}
	}
}
?>