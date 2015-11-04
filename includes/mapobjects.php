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
			$tdate = $site->get_tdate();
		} else {
			$address = "";
			$name = "";
			$content = "";
			$tdate = "";
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
	        	<label>CONTENT</label>
	        	<textarea name="content"><? echo $content; ?></textarea>
	        </div>
	        <div class="small-12 columns">
	        	<label>DATE</label>
	        	<input type="text" name="tdate" class="span2"  id="dp1" value="<? echo $tdate; ?>">
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
	
	function delete_map($numentry){
		global $database;
		$sql = "DELETE FROM markers ";
	  	$sql .= "WHERE numid=". $database->escape_value($numentry);
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
	  	if ($database->affected_rows() == 1) {echo "<br/>Test has been deleted.";}
	}
	
	function update_map($info){
		global $database;
		$id = $database->escape_value($info['id']);
		$site = new map_object($id);
		$address = $database->escape_value($info['address']);
		$site->get_latlong($address);
		$lat = $site->get_lat();
		$lng = $site->get_lng();
		$sql = "UPDATE markers SET ";
		$sql .= "name='". $database->escape_value($info['name']) ."', ";
		$sql .= "address='". $address ."', ";
		$sql .= "lat='". $lat ."', ";
		$sql .= "lng='". $lng ."', ";
		$sql .= "tdate='". $database->escape_value($info['tdate']) ."', ";
		$sql .= "content='". nl2br($database->escape_value($info['content'])) ."' ";
		$sql .= "WHERE numid='". $id ."'";
	  	$database->query($sql);
	}
	
	function add_map($info){
		global $database;
		$address = $database->escape_value($info['address']);
		$site = new map_object();
		$site->get_latlong($address);
		$sql = "INSERT INTO markers (";
	  	$sql .= "name, address, lat, lng, tdate, content";
	  	$sql .= ") VALUES ('";
	  	$sql .= $database->escape_value($info['name']) ."', '";
	  	$sql .= $database->escape_value($info['address']) ."', '";
	  	$sql .= $site->get_lat() ."', '";
	  	$sql .= $site->get_lng()  ."', '";
	  	$sql .= $database->escape_value($info['tdate'])  ."', '";
	  	$sql .= nl2br($database->escape_value($info['content'])) ."')";
		$database->query($sql);
	}
	
	function print_maps(){
		$this->set_maps();
		foreach ($this->map_array as $ind_spot) {
			$info = new map_object($ind_spot);
				?> <tr><td>
				<a href="?task=editM&id=<? echo $info->get_id(); ?>"><? echo $info->get_name().":  ".$info->get_address(); ?></a></br>
				<? echo nl2br($info->get_content()); 
					echo "<br/>Date: ".$info->get_tdate();
				?>
				</td><td><a href="?task=deleteM&id=<? echo $info->get_id(); ?>" class="button tiny radius">DELETE</a>
				</td></tr><?
			
		}
	}
	
}
?>
