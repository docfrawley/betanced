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

	function announce_form($editing=false, $id=0){
		$task = ($editing) ? "updateA" : "addA";
		if($id>0){
			$announce = new announceobject($id);
			$title = $announce->get_title();
			$announcement = $announce->get_announce();
			$priority = $announce->get_priority();
		} else {
			$title = "";
			$announcement = "";
			$priority = "";
		}?>
		<div class="row">
			<div class="small-12 text-center columns">
				<?
					if ($editing){
						echo "<p>EDIT ANNOUNCEMENT</p>";
					} else {
						echo "<p>ADD ANNOUNCEMENT</p>";
					}
				?>
	        </div>
	        <form action="announceadmin.php" method="post">
	        <div class="small-12 columns">
				<label>TITLE</label>
				<input type="text" name="title" value = "<? echo $title; ?>"/>
			</div>
			<div class="small-12 columns">
	        	<label>ANNOUNCEMENT</label>
	        	<textarea name="announcement"><? echo $announcement; ?></textarea>
	        </div>
			<div class="small-12 columns">
	        	<label>PRIORITY</label>
	        	<input type="text" name="priority" value = "<? echo $priority; ?>"/>
	        </div>
	        	<input type="hidden" name="task" value="<? echo $task; ?>"/>
	        	<? 
	        	if ($editing){
	        		?> <input type="hidden" name="id" value="<? echo $announce->get_id(); ?>"/> <?
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
	
	function print_announcements($editing = false){
		$this->set_announcements();
		$count = 1;
		foreach ($this->an_array as $ind_announce) {
			$info = new announceobject($ind_announce);
			if ($editing){
				?> <tr><td>
				<a href="?task=editA&id=<? echo $info->get_id(); ?>"><? echo $info->get_priority().":  ".$info->get_title(); ?></a></br>
				<? echo nl2br($info->get_announce()); ?>
				</td><td><a href="?task=deleteA&id=<? echo $info->get_id(); ?>" class="button tiny radius">DELETE</a>
				</td></tr><?
			} else {
				?> 	<li data-orbit-slide="headline-<? echo $count; ?> ">
	              <div>
	                <strong><? echo $info->get_title(); ?></strong>
	                <p><? echo nl2br($info->get_announce()); ?></p>
	              </div>
            	</li>
            	<?
			}
			
            $count++;
		}
	}
	
}
?>