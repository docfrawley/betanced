<? include_once("initialize.php");

class boardadmin {
	
	private $the_board;
	private $possible_board;
	
	function __construct() {
		global $database;
		$the_board = array();
		$this->the_board = array();
	}

	function print_board($admin=false){
		$this->set_board();

		?><table><?
				foreach ($this->the_board as $key => $value) {
					if ($key != 'boardindex'){
						$title = convert_key($key);
						if ($title !=""){
							$boardMember = new boardMember($value);
							echo '<tr><td>'.$title.'</td><td>';
							echo $boardMember->print_member($key)."</td></tr>";
						}
					}
				}
		?></table><?
	}

	function set_board(){
		global $database;
		$this->the_board = array();
		$sql="SELECT * FROM ncedboard";
		$result_set = $database->query($sql);
		$this->the_board = $database->fetch_array($result_set);
	}

	function set_poss_board(){
		global $database;
		$this->possible_board = array();
		$sql="SELECT * FROM binfo";
		$result_set = $database->query($sql);
		$counter = 0;
		while ($value = $database->fetch_array($result_set)){
			array_push($this->possible_board, $value['ncednum']);
		}
	}

	function add_bmember_form($value){
		$boardMember = new boardMember($value);
		if ($boardMember->is_current()){
			$title = "<legend>Edit or Delete Board Member</legend>
		 			Click on DELETE button to delete member from board or edit content hit Submit.<br/><br/>";
		} else {
			$title = "<legend>Add Possible Board Member</legend>";
		}
		?> 
		 <form action="ncedboard.php" method="POST">
		 	<fieldset><? 
		 		echo $title;
		 	?>
		 	<div class="row">
		 		<?
		 		echo "<div class='small-8 columns'>";
		 		echo "<h3>".$boardMember->get_name()."</h3>";
		 		echo "</div>";
		 		?>
		 		<div class="small-4 columns">
		 			<input type="text" name="bmtitle" value="<? echo $boardMember->get_title(); ?>" placeholder="Title(e.g. M.Ed.)" />
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<label>bio</label>
        			<textarea cols="40" rows="5" name="bio">
        				<?echo $boardMember->get_bio();?>
        			</textarea>
        		</div>
        	</div>
        	<? $task=($boardMember->is_current()) ? "mupdate" : "madd" ;?>
        	<input type="hidden" name="task" value="<? echo $task; ?>"/>
        	<input type="hidden" name="whatnumber" value="<? echo $value; ?>"/>
			<div class="row">
				<div class="small-6 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        		<div class="small-6 columns text-right"><? 
        			if ($boardMember->is_current()){
        				echo '<a href="?member='. $value.'&task=mdelete" class="button tiny radius">DELETE</a>';
        			}?>
        		</div>
        	</div>
        </fieldset>
        </form><?
	}

	function bmember_update($info){
		global $database;
		$sql = "UPDATE binfo SET ";
		$sql .= "bmtitle='". $database->escape_value($info['bmtitle'])."', ";
		$sql .= "bio='". $database->escape_value($info['bio']) ."'";
		$sql .= " WHERE ncednum='". $database->escape_value($info['whatnumber']) ."'";
	  	$database->query($sql);		
	}

	function edit_position_form($person, $position){
		$this->set_poss_board();
		$title = convert_key($position);
		?><form action="ncedboard.php" method="post">
		<fieldset>
		 	<legend>Change Board Member for <? echo $title; ?> </legend>
			<div class="row">
		 		<div class="small-12 columns"><?
					$boardMember = new boardMember($person);?>
					 <select name="person">
					 	<option selected="selected" value="<? echo $person; ?>"><? echo $boardMember->get_name(); ?></option>
		        		<? foreach ($this->possible_board as $nvalue) {
		        			$bMember = new boardMember($nvalue);
		        			echo '<option value="'. $nvalue. '">'.$bMember->get_name().'</option>';
		        		   } ?>
				 	 </select>
		 		</div>
		 	</div>
		 	<input type="hidden" name="position" value="<? echo $position; ?>"/>
		 	<input type="hidden" name="task" value="bchangeU"/>
			<div class="row">
		 		<div class="small-12 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        	</div>
        	</fieldset>
        </form><?
	}

	function update_position($info){
		global $database;
		$sql = "UPDATE ncedboard SET ";
		$sql .= $database->escape_value($info['position'])."='". $database->escape_value($info['person']) ."'";
		$database->query($sql);
	}


	function generate_boxes(){
	    foreach ($this->the_board as $key => $value) {
	    	if ($key != 'boardindex'){
	    		?>
		        <div id="<? echo $key; ?>" class="reveal-modal" data-reveal>
		          <?
		          	$boardMember = new boardMember($value);
		          	echo "<strong>".$boardMember->get_name()."</strong>, ".$boardMember->get_title().", ".$boardMember->get_bio();
		          ?>
		          <a class="close-reveal-modal">&#215;</a>
		        </div>
	      		<?
	    	}
	    }
  	}
	
	function change_form(){
		$this->set_board();
		?><form action="ncedboard.php" method="post">
		<fieldset>
		 	<legend>Click on Board Position to Change Member</legend>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<table>
				<?
				foreach ($this->the_board as $key => $value) {
					if ($key != 'boardindex'){
						$title = convert_key($key);
						if ($title !=""){
							$boardMember = new boardMember($value);
							echo '<tr><td><a href="?task=bchange&member='. $value.'&position='.$key.'">'.$title.'</a>: </td><td>';
							echo $boardMember->get_name()."</td></tr>";
						}
					}
				}?>	
				</table>
				</div>
			</div>
        </fieldset>
        </form><?
	}

	function members_form(){
		$this->set_poss_board(); ?>
		<div class="row">
			<div class="small-12 columns"> 
				<fieldset>
		 			<legend>Edit/Delete Board Members</legend>
					<?
					foreach ($this->possible_board as $nvalue) {
					    $bMember = new boardMember($nvalue);
					    echo '<a href="?member='. $nvalue.'&task=medit_form">'.$bMember->get_name().'</a><br/>';
					} ?>
				</fieldset>
			</div>
		</div><?
	}

	function bmember_add($info){
		global $database;
		$sql = "INSERT INTO binfo (";
		$sql .= "ncednum, bmtitle, bio";
 		$sql .= ") VALUES ('";
		$sql .= $database->escape_value($info['whatnumber']) ."', '";
		$sql .= $database->escape_value($info['bmtitle']) ."', '";
		$sql .= $database->escape_value($info['bio']) ."')";
		$database->query($sql);
		$_SESSION['boardMessage']="You have successfully added a new possible board member.";
	}

	function bmember_delete($value){
		global $database;
		echo "I'm here".$value;
		$sql = "DELETE FROM binfo ";
		$sql .= "WHERE ncednum='".$value."' ";
		$sql .= "LIMIT 1";
		$database->query($sql);
 	}

}
?>