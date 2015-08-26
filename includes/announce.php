<? include_once("initialize.php");

class all_announcements {
	
	private $an_array = array();
	
	function __construct() {
		global $database;
		$sql="SELECT * FROM announce";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			array_push($this->an_array,$value['id']);
		} 
	}
	
	function num_announce(){
		$value = count($this->an_array);
		return $value;
	}
	
	function delete_announce($numentry){
		global $database;
		$sql = "DELETE FROM announce ";
	  	$sql .= "WHERE numentry=". $database->escape_value($numentry);
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	  	if ($database->affected_rows() == 1) {echo "<br/>Announcement has been deleted.";}
		else {echo "<br/> Announcement was not deleted.";}
		array_splice($this->an_array, $this->get_key($numentry), 1);
	}
	
	function edit_announce($info){
		global $database;
		$sql = "UPDATE rcaannounce SET ";
		$sql .= "thetitle='". $database->escape_value($info['thetitle']) ."', ";
		$sql .= "tbody='". $database->escape_value($info['tbody']) ."', ";
		$sql .= "college='". $database->escape_value($info['thecollege']) ."', ";
		$time_mk = $this->convert_time($info);
		$sql .= "whenend='". $time_mk ."', ";
		$sql .= "posted='". $database->escape_value($info['posted']) ."' ";
		$sql .= "WHERE numentry='". $database->escape_value($info['numentry'])."'";
	  	$database->query($sql);
	}
	
	function add_announce($info){
		global $database;
		$sql = "INSERT INTO announce (";
	  	$sql .= "announcement";
	  	$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['announcement']) ."')";
		if($database->query($sql)) {echo "<br/>Announcement has been posted.";} 
		else {echo "<br/>Announcement did not post.";}
	}
	
	function get_content($numentry) {
		global $database;
		$sql = "SELECT * FROM rcaannounce WHERE numentry='".$numentry."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		return $value;
	}
	
	function print_announcements() {
		$count = 1;
		foreach ($this->an_array as $ind_announce) {
			$info = new announceobject($ind_announce);
			?> 	<li data-orbit-slide="headline-<? echo $count; ?> ">
	              <div>
	                <h3><? echo $info->get_title(); ?></h3>
	                <h5><? echo $info->get_announce(); ?></h5>
	              </div>
            	</li>
            <?
            $count++;
		}
	}
	
	
	function announcment_form(){
		global $database;
				
					?><br/><form action = "<? echo $self; ?>" method="post">
                    <table><tr><td>Title:</td><td><input type="text" name="thetitle" value="<? echo $athetitle; ?>"/></td></tr></table>
					<table><tr><td>Announcement:</td><tr>
                    <tr><td> <textarea name="tbody" rows="7" cols="40"><? echo $atbody; ?>
							</textarea></td></tr></table>
                            <table><tr><td>Date When Take Down Post</td></tr></table>
                            <table><tr><td>Month</td><td>Day</td><td>Year</td></tr>
                            <tr><td>
						<select name="month">
							<option selected="selected" value="<? echo $anummonth; ?>"><? echo $amonth; ?></option>
							<? GetMonths(); ?>
							</select></td><td>
						<select name="day">
							<option selected="selected" "<? echo $aday; ?>"><? echo $aday; ?></option>
							<? GetDays(); ?>
							</select>  </td><td>
							<select name="year">
								<option selected="selected" value="<? echo $ayear; ?>"><? echo $ayear; ?></option>
								<? GetYears(); ?>  
							</select></td></tr></table>
							<table><tr><td> This Announcement is for:</td><td>
                            <? if ($athecollege =="All") { 
									?> <input type="radio" name="thecollege" value="All" checked/> All RCAs <br/><?
								} else { // ($athecollege =="All")
									?> <input type="radio" name="thecollege" value="All"/> All RCAs <br/> <?
								} // ($athecollege =="All")
								
								if ($athecollege ==$college) { 
									?> <input type="radio" name="thecollege" value="<? echo $college; ?>" checked/> <? echo $college; ?> RCAs<?
								}else { //if ($athecollege ==$college)
									?> <input type="radio" name="thecollege" value="<? echo $college; ?>"/> <? echo $college; ?> RCAs <?
								} // if ($athecollege ==$college)
							?></td></tr>
							<input type="hidden" name="numentry" value="<? echo $numentry; ?>"/>
							<input type="hidden" name="posted" value="<? echo date('U'); ?>"/>
							<input type="hidden" name="taskannounce" value="addedit"/>
							<input type="hidden" name="whichform" value="announce"/>
							<tr><td><input type="submit" value="submit" /></td></tr></table>
							</form><?
							if (($numentry) AND ($whichform=='announce')) {
								echo ("<a href='../public/rcahome.php?numentry=".$numentry."&taskannounce=deleteannounce&whichform=announce'>".'DELETE THIS EVENT'."</a>"); 
							} // if (($numentry) AND ($whichform=='announce'))

	}

	
	
	

}
?>