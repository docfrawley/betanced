<? include_once("initialize.php");

class announceobject {
	
	private $announcement;
	private $title;
	
	function __construct($id) {
		global $database;
		$sql="SELECT * FROM announce WHERE id='".$id."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->title = $value['title'];
		$this->announcement = $value['announcement'];
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

	function get_announce() {
		echo $this->announcement;
	}

	function get_title() {
		echo $this->title;
	}
}
?>