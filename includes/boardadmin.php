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

	function add_bmember_form(){
		?> 
		 <form action="ncedboard.php" method="POST">
		 	<fieldset>
		 			<legend>Add Possible Board Member</legend>
		 			Enter NCED number <strong>OR</strong> Last Name<br/><br/>
		 	<div class="row">
		 		<div class="small-3 columns">
		 			<input type="text" name="ncednumber" placeholder="NCED Number"/>
		 		</div>
		 		<div class="small-6 columns">
		 			<input type="text" name="LastName" placeholder="Last Name"/>
		 		</div>
		 		<div class="small-3 columns">
		 			<input type="text" name="title" placeholder="Title(e.g. M.Ed.)"/>
		 		</div>
		 	</div>
		 	<div class="row">
		 		<div class="small-12 columns">
		 			<label>bio</label>
        			<textarea cols="40" rows="5" name="bio">
        			</textarea>
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
					    echo '<a href="?member='. $nvalue.'&task=edit">'.$bMember->get_name().'</a><br/>';
					} ?>
				</fieldset>
			</div>
		</div><?
	}

}
?>