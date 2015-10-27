<? include_once("initialize.php");

class all_announcements {
	
	private $an_array;
	
	function __construct() {
		$this->an_array=array();
	}

	function set_announcements(){
		global $database;
		$this->an_array=array();
		$sql="SELECT * FROM announce ORDER BY priority";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			array_push($this->an_array,$value['id']);
		} 
	}

	function num_announce(){
		$this->set_announcements();
		return count($this->an_array);
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
		$sql = "UPDATE announce SET ";
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
	
	function print_announcements($editing = false){
		$this->set_announcements();
		$count = 1;
		foreach ($this->an_array as $ind_announce) {
			$info = new announceobject($ind_announce);
			if ($editing){
				?> <tr><td>
				<a href="?task=editA&idnum=<? echo $info->get_id(); ?>"><? echo $info->get_title(); ?></a></br>
				<? echo $info->get_announce(); ?>
				</td><td><a href="?task=deleteA&idnum=<? echo $info->get_id(); ?>" class="button tiny radius">DELETE</a>
				</td></tr><?
			} else {
				?> 	<li data-orbit-slide="headline-<? echo $count; ?> ">
	              <div>
	                <h4><? echo $info->get_title(); ?></h4>
	                <h5><? echo $info->get_announce(); ?></h5>
	              </div>
            	</li>
            	<?
			}
			
            $count++;
		}
	}
	
}
?>