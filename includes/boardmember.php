<? include_once("initialize.php");

class boardMember {
	
	private $ncednum;
	private $name;
	private $bio;
	private $state;
	private $title;
	
	function __construct($ncednum) {
		global $database;
		$this->ncednum = $ncednum;
		$bMember = new memobject($ncednum);
		$this->name = $bMember->get_displayname();
		$sql="SELECT * FROM binfo WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->title = $value['bmtitle'];
		$this->bio = $value['bio'];
		$sql="SELECT * FROM nceddata WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->state = $value['state'];
	}

	function print_member($key){
		?><table><tr> <?
		?><td><? echo "<a data-reveal data-reveal-id='".$key."''>".$this->name."</a>"; ?></td><?
		?><td><? echo $this->title; ?></td><?
		?><td><? echo get_state($this->state); ?></td><?
		?></tr></table> <?
	}

	function get_name(){
		return $this->name;
	}

	function get_title(){
		return $this->title;
	}

	function get_bio(){
		return $this->bio;
	}

}
?>