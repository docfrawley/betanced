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
		?><form action="ncedboard.php" method="post"><?
		foreach ($this->the_board as $key => $value) {
			if ($key != 'boardindex'){
				?>
				<div class="row">
		 			<div class="small-12 columns">
						 <?
						$title = convert_key($key);
						$boardMember = new boardMember($value);
						?><label><? echo $title.": "; ?>
					 		<select name="<? echo $key; ?>">
					 			<option selected="selected" value="<? echo $value; ?>"><? echo $boardMember->get_name(); ?></option>
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
        </form><?
			}
		}

	}

}
?>