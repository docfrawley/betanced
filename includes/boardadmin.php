<? include_once("initialize.php");

class boardadmin {
	
	private $the_board;
	private $possible_board;
	
	function __construct() {
		global $database;
		$the_board = array();
		$sql="SELECT * FROM ncedboard";
		$result_set = $database->query($sql);
		$this->the_board = $database->fetch_array($result_set);
		$this->possible_board = array();
	}

	function print_board($admin=false){
		foreach ($this->the_board as $key => $value) {
			if ($key != 'boardindex'){
				$title = convert_key($key);
				$boardMember = new boardMember($value);
				echo $title.": ";
				$boardMember->print_member($key);
				echo "<br/>";
			}
			
		}
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

	function add_bmember_form($value = 0){
		$amEditing = ($value>0);
		if ($amEditing){
			$boardMember = new boardMember($value);
			$title = "<legend>Edit or Delete Board Member</legend>
		 			Click on DELETE button to delete member from board or edit content hit Submit.<br/><br/>";
		} else {
			$title = "<legend>Add Possible Board Member</legend>
		 			Enter NCED number <strong>OR</strong> Last Name<br/><br/>";
		}
		?> 
		 <form action="ncedboard.php" method="POST">
		 	<fieldset><? 
		 		echo $title;
		 	?>
		 	<div class="row">
		 		<?
		 			if ($amEditing){ 
		 				echo "<div class='small-8 columns'>";
		 				echo "<h3>".$boardMember->get_name()."</h3>";
		 				echo "</div>";
		 			} else {?>
		 				<div class="small-3 columns">
		 					<input type="text" name="ncednum" placeholder="NCED #"/>
		 				</div>
				 		<div class="small-5 columns">
				 			<input type="text" name="LastName" placeholder="Last Name"/>
				 		</div>
		 		 <? } ?>
		 		<div class="small-4 columns">
		 			<?  
		 			if ($amEditing){
		 				echo '<input type="text" name="bmtitle" value="'.$boardMember->get_title().'"/>';
		 			} else {
		 				echo '<input type="text" name="bmtitle" placeholder="Title(e.g. M.Ed.)"/>';
		 			} ?>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<label>bio</label>
        			<textarea cols="40" rows="5" name="bio">
        				<?
        				if ($amEditing){
        					echo $boardMember->get_bio();
        				}
        				?>
        			</textarea>
        		</div>
        	</div>
        	<? $task=($amEditing) ? "mupdate" : "madd" ;?>
        	<input type="hidden" name="task" value="<? echo $task; ?>"/>
			<div class="row">
				<?
					if ($amEditing){
						?>
						<div class="small-6 columns">
        					<input type="submit" value="Submit" class="button tiny radius"/>
        				</div>
        				<div class="small-6 columns text-right">
        					<? echo '<a href="?member='. $value.'&task=mdelete" class="button tiny radius">DELETE</a>'; ?>
        				</div>
						<?

					} else {
						?>  
						<div class="small-12 columns">
        					<input type="submit" value="Submit" class="button tiny radius"/>
        				</div>
						<?
					}
				?>
        	</div>
        </fieldset>
        </form><?
	}

	function edit_position_form($position, $person){
		$this->set_poss_board();
		$title = convert_key($position);
		?><form action="ncedboard.php" method="post">
		<fieldset>
		 	<legend>Change Board Member for <? echo $title; ?> </legend>
			<div class="row">
		 		<div class="small-12 columns"><?
					$boardMember = new boardMember($person);
						?><label><? echo $title.": "; ?>
					 		<select name="<? echo $position; ?>">
					 			<option selected="selected" value="<? echo $person; ?>"><? echo $boardMember->get_name(); ?></option>
		        				<? foreach ($this->possible_board as $nvalue) {
		        					$bMember = new boardMember($nvalue);
		        					echo '<option value="'. $nvalue. '">'.$bMember->get_name().'</option>';
		        				} ?>
				 			</select>
				 		</label>
		 		</div>
		 	</div>
			<div class="row">
		 		<div class="small-12 columns">
        			<input type="submit" value="Submit" class="button tiny radius"/>
        		</div>
        	</div>
        	</fieldset>
        </form><?
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
		$this->set_poss_board();
		?><form action="ncedboard.php" method="post">
		<fieldset>
		 	<legend>Click on Board Position to Change Member</legend>
		 	<div class="row">
		 		<div class="small-12 columns">
				<?
				foreach ($this->the_board as $key => $value) {
					if ($key != 'boardindex'){
						$title = convert_key($key);
						$boardMember = new boardMember($value);
						echo '<a href="?member='. $value.'&position='.$key.'">'.$title.'</a>: ';
						echo $boardMember->get_name();
						echo "<br/>";
					}
				}?>	
				<br/>
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
		$sql .= $database->escape_value($info['ncednum']) ."', '";
		$sql .= $database->escape_value($info['bmtitle']) ."', '";
		$sql .= $database->escape_value($info['bio']) ."')";
		$database->query($sql);
		$_SESSION['boardMessage']="You have successfully added a new possible board member.";
	}

}
?>