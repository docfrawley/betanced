<? include_once("initialize.php");

class ceuinfo {

	private $ceuarray;
	private $archivearray;
	private $cutoff;
	private $ncednumber;

	function __construct($ncednumber, $dateneeded) {
		global $database;
		$this->cutoff = $dateneeded;
		$this->ncednumber = $ncednumber;
		$this->ceuarray = array();
		$this->archivearray = array();
		$sql="SELECT * FROM ceurenewal WHERE ncedid ='".$ncednumber."' AND ceudate > '".$dateneeded."' ORDER BY areaceu";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$newceu = new ceuobject($value['numindex']);
			array_push($this->ceuarray, $newceu);
		}
		$sql="SELECT * FROM ceurenewal WHERE ncedid ='".$ncednumber."' AND ceudate < '".$dateneeded."' ORDER BY areaceu";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$newceu = new ceuobject($value['numindex']);
			array_push($this->archivearray, $newceu);
		}
	}

	function set_ceuarray(){
		global $database;
		$this->ceuarray = array();
		$sql="SELECT * FROM ceurenewal WHERE ncedid ='".$this->ncednumber."' AND ceudate > '".$this->cutoff."' ORDER BY ceudate";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$newceu = new ceuobject($value['numindex']);
			array_push($this->ceuarray, $newceu);
		}
	}

	function set_archivearray(){
		$this->archivearray = array();
		global $database;
		$sql="SELECT * FROM ceurenewal WHERE ncedid ='".$this->ncednumber."' AND ceudate < '".$this->cutoff."' ORDER BY ceudate";
		$result_set = $database->query($sql);
		while ($value = $database->fetch_array($result_set)) {
			$newceu = new ceuobject($value['numindex']);
			array_push($this->archivearray, $newceu);
		}
	}

	function num_ceus(){
		$this->set_ceuarray();
		return count($this->ceuarray);
	}

	function total_current_ceus(){
		$this->set_ceuarray();
		$total_hrs = 0;
		for ($x = 1; $x <= 6; $x++) {
    	$total_hrs += $this->ceus_area($x);
		}
		return $total_hrs;
	}

	function num_archive(){
		$this->set_archivearray();
		return count($this->archivearray);
	}

	function ceus_area($whicharea){
		$this->set_ceuarray();
		$totalceus = 0;
		foreach ($this->ceuarray as $info) {
			if ($info->get_area()==$whicharea){$totalceus += $info->get_hrs();}
		}
		return $totalceus;
	}

	function snapshot($whatTOdo = true) {
		?>
		<fieldset>
    		<legend>CEU SNAPSHOT</legend>
    		<? if ($whatTOdo) {echo "<a href='ceupage.php' class='button tiny radius'>GO TO CEU PAGE</a>";} ?>
			<table>
				<tr>
					<td>
						<strong>Current Number of CEU Entries: </strong>
					</td>
					<td><strong>
						<? echo $this->num_ceus(); ?></strong>
					</td>
				</tr>
				<tr>
					<td><strong>
						Total of Current CEU Hours:</strong>
					</td>
					<td><strong>
						<? echo $this->total_current_ceus(); ?></strong>
					</td>
				</tr>
				<tr>
					<td>
						Number of Archived CEUs:
					</td>
					<td>
						<? echo $this->num_archive(); ?>
					</td>
				</tr>
				<tr>
					<td>
						Archival Date:<br/>CEUs prior to this date have been archived
					</td>
					<td>
						<? echo date('F d, Y', $this->cutoff); ?>
					</td>
				</tr>
				<tr>
					<td>
						Next Archival Date:
					</td>
					<td>
						<? echo date('F d, Y', strtotime('+5 years', $this->cutoff)); ?>
					</td>
				</tr>
				<? for ($i = 1; $i <= 6; $i++) { ?>
				<tr>
					<td>
						<?
						echo "CEUs for Area#{$i}";
						?>
					</td>
					<td>
						<? echo $this->ceus_area($i); ?>
					</td>
				</tr>
				<? } ?>
			</table>
		</fieldset>
		<?

	}

	function showarea($area, $archive=false){
		if ($archive) {$this->set_archivearray();}
		else {$this->set_ceuarray();}
		?> <strong> <? get_area($area); ?> </strong><br/><?
		$whicharray=($archive) ? $this->archivearray: $this->ceuarray ;
		if (!$archive){
			echo "<a href='?addceu=yes&whicharea={$area}' class='button tiny radius'>ADD CEUs</a><br/>";
		}
		?>
		<table class="custom-table">
			<thead>
			    <tr>
			      <th width="100">CEU Date</th>
			      <th width="100">Entry Date</th>
			      <th width="150">CEU Type</th>
			      <th width="100">CEU units</th>
			      <th>Notes</th>
			    </tr>
  			</thead>
  			<tbody>
		<?
		foreach ($whicharray as $info) {
			if ($info->get_area()==$area){$info->show_entry($archive);}
		}
		?>
			</tbody>
		</table> <?
		if (!$archive) { echo "Total Hours: ".$this->ceus_area($area); }
	}

	function add_form($whicharea){
		 ?> <strong> <? get_area($whicharea); ?> </strong><br/>
		 <form action="ceupage.php" method="POST">
		 	<div class="row">
		 		<fieldset>
		 			<legend>Date of CEU</legend>
		 				<div class="small-4 columns">
		 					<label>Month
			 					<select name="month">
        						<? getMonths(); ?>
		 						</select>
		 					</label>
		 				</div>
						<div class="small-4 columns">
							<label>Day
					 			<select name="day">
			        				<? getDays(); ?>
					 			</select>
					 		</label>
						</div>
						<div class="small-4 columns">
							<label>Year
					 			<select name="year">
			        				<? getYears(); ?>
					 			</select>
				 			</label>
						</div>
				</fieldset>
			</div>
		 	<div class="row">
		 		<div class="small-4 columns">
		 			<label>Enter CEU hours:</label>
		 			<input type="text" name="numhrs" placeholder="CEU hrs"/>
		 		</div>
		 		<div class="small-8 columns">
		 			<label>Enter type of CEU:</label>
		 			<select name="areatype" id="areatype" placeholder="CEU Type">
        				<? get_type($whicharea); ?>
		 			</select>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<label>If "Other", please describe activity: </label>
        			<input type="text" name="othertype" name="othertype" placeholder="Other Description"/>
        		</div>
        	</div>
        	<div class="row">
        		<div class="small-12 columns">
        			<label>Please enter any notes to describe CEU activity: </label>
        			<textarea name="notes"></textarea>
        		</div>
        	</div>
			<input type="hidden" name="thearea" value="<? echo $whicharea; ?>"/>
			<div class="row">
		 		<div class="small-12 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        	</div>
        </form><?
	}

	function add_ceu($info){
		global $database;
		$ceudate = mktime(0, 0, 0, $database->escape_value($info['month']),
			$database->escape_value($info['day']),
			$database->escape_value($info['year']));
		$sql = "INSERT INTO ceurenewal (";
		$sql .= "ncedid, entrydate, ceudate, areaceu, typeceu, numhrs, notes";
 		$sql .= ") VALUES ('";
		$sql .= $_SESSION['ncednumber'] ."', '";
		$sql .= date('U') ."', '";
		$sql .= $ceudate ."', '";
		$sql .= $database->escape_value($info['thearea']) ."', '";
		$sql .= $database->escape_value($info['areatype']) ."', '";
		$sql .= $database->escape_value($info['numhrs']) ."', '";
		$sql .= $database->escape_value($info['notes']) ."')";
		$database->query($sql);
		$_SESSION['ceumessage']="You have successfully added a new CEU entry which is listed below.";
	}

}
?>
