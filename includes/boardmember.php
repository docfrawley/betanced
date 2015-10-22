<? include_once("initialize.php");

class boardMember {
	
	private $ncednum;
	private $name;
	private $bio;
	private $state;
	private $title;
	private $currentB;
	
	function __construct($ncednum) {
		global $database;
		$this->ncednum = $ncednum;
		$bMember = new memobject($ncednum);
		$this->name = $bMember->get_displayname();
		$sql="SELECT * FROM binfo WHERE ncednum ='".$ncednum."'";
		$result_set = $database->query($sql);
		$this->currentB = ($database->num_rows($result_set) >0 );
		if ($this->currentB) {
			$value = $database->fetch_array($result_set);
			$this->title = $value['bmtitle'];
			$this->bio = $value['bio'];
			$sql="SELECT * FROM nceddata WHERE ncednum ='".$ncednum."'";
			$result_set = $database->query($sql);
			$value = $database->fetch_array($result_set);
			$this->state = $value['state'];
		} else {
			$this->title = "";
			$this->bio = "";
			$this->state = "";
		}
	}

	function is_current(){
		return $this->currentB;
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