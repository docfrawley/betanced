<? include_once("initialize.php");

class all_maps {
	
	private $map_array;
	private $latitude;
	private $longitude;
	
	function __construct() {
		$this->map_array=array();
	}

	function set_maps(){
		global $database;
		$this->map_array=array();
		$sql="SELECT * FROM markers";
		$result_set = $database->query($sql);
		while($value = $database->fetch_array($result_set)){ //
			array_push($this->map_array,$value['numid']);
		} 
	}

	function get_latlong($address){
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $this->latitude = $output->results[0]->geometry->location->lat;
        $this->longitude = $output->results[0]->geometry->location->lng;

        echo $this->latitude. " ". $this->longitude;
	}

	function num_spots(){
		$this->set_maps();
		return count($this->an_array);
	}

	function map_form($editing=false, $id=0){
		$task = ($editing) ? "updateM" : "addM";
		if($id>0){
			$site = new map_object($id);
			$address = $site->get_address();
			$name = $site->get_name();
			$content = $site->get_content();
		} else {
			$address = "";
			$name = "";
			$content = "";
		}?>
		<div class="row">
			<div class="small-12 text-center columns">
				<?
					if ($editing){
						echo "<p>EDIT TEST SITE</p>";
					} else {
						echo "<p>ADD TEST SITE</p>";
					}
				?>
	        </div>
	        <form action="testadmin.php" method="post">
	        <div class="small-12 columns">
	        	<label>NAME</label>
	        	<input type="text" name="name" value = "<? echo $name; ?>"/>
			</div>
			<div class="small-12 columns">
	        	<label>ADDRESS</label>
				<input type="text" name="address" value = "<? echo $address; ?>"/>
	        </div>
			<div class="small-12 columns">
	        	<label>content</label>
	        	<textarea name="content"><? echo $content; ?></textarea>
	        </div>
	        	<input type="hidden" name="task" value="<? echo $task; ?>"/>
	        	<? 
	        	if ($editing){
	        		?> <input type="hidden" name="id" value="<? echo $id; ?>"/> <?
	        	}
	        	?>
	        	
			<div class="small-12 columns">
				<input type="submit" value="submit" class="button small"/>
			</div>
	        </form>
	    </div> <?
	}
	
	function delete_announce($numentry){
		global $database;
		$announce = new announceobject($numentry);
		$priority = $announce->get_priority();
		$this->set_priorities(0, $priority);
		$sql = "DELETE FROM announce ";
	  	$sql .= "WHERE id=". $database->escape_value($numentry);
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	  	if ($database->affected_rows() == 1) {echo "<br/>Announcement has been deleted.";}
	}
	
	function update_announce($info){
		global $database;
		$id = $database->escape_value($info['id']);
		$priority = $database->escape_value($info['priority']);
		$announce = new announceobject($id);
		$prev = $announce->get_priority();
		if ($announce->get_priority() !=  $priority){
			$this->set_priorities($priority, $prev); 
		}
		$sql = "UPDATE announce SET ";
		$sql .= "title='". $database->escape_value($info['title']) ."', ";
		$sql .= "announcement='". nl2br($database->escape_value($info['announcement'])) ."', ";
		$sql .= "priority='". $priority ."' ";
		$sql .= "WHERE id='". $id ."'";
	  	$database->query($sql);
	}

	function set_priorities($num, $prev=0){
		global $database;
		$this->set_announcements();
		if ($prev == 0){
			$prev = $this->num_announce();
		} elseif ($num == 0 ) {
			$num = $this->num_announce();
		}
		if ($prev < $num){
			$counter = $prev;
			for ($x = $prev; $x < $num; $x++){
				$info = new announceobject($this->an_array[$x]);
				$sql = "UPDATE announce SET ";
				$sql .= "priority='". $counter ."' ";
				$sql .= "WHERE id='". $info->get_id()."'";
		  		$database->query($sql);
		  		$counter++;
			}
		} else {
			$counter = $num;
			for ($x = $num - 1; $x < $prev; $x++){
				$counter++;
				$info = new announceobject($this->an_array[$x]);
				$sql = "UPDATE announce SET ";
				$sql .= "priority='". $counter ."' ";
				$sql .= "WHERE id='". $info->get_id()."'";
		  		$database->query($sql);
			}
		}
		
	}
	
	function add_announce($info){
		global $database;
		$this->set_priorities($database->escape_value($info['priority']));
		$sql = "INSERT INTO announce (";
	  	$sql .= "title, announcement, priority";
	  	$sql .= ") VALUES ('";
	  	$sql .= $database->escape_value($info['title']) ."', '";
	  	$sql .= nl2br($database->escape_value($info['announcement'])) ."', '";
		$sql .= $database->escape_value($info['priority']) ."')";
		$database->query($sql);
	}
	
	function print_maps(){
		$this->set_maps();
		foreach ($this->map_array as $ind_spot) {
			echo $ind_spot;
			$info = new map_object($ind_spot);
				?> <tr><td>
				<a href="?task=editM&id=<? echo $info->get_id(); ?>"><? echo $info->get_name().":  ".$info->get_address(); ?></a></br>
				<? echo nl2br($info->get_content()); ?>
				</td><td><a href="?task=deleteM&id=<? echo $info->get_id(); ?>" class="button tiny radius">DELETE</a>
				</td></tr><?
			
		}
	}
	
}
?>