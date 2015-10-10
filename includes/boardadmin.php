<? include_once("initialize.php");

class boardadmin {
	
	private $the_board;
	
	function __construct() {
		global $database;
		$the_board = array();
		$sql="SELECT * FROM ncedboard";
		$result_set = $database->query($sql);
		$this->the_board = $database->fetch_array($result_set);
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
	

}
?>