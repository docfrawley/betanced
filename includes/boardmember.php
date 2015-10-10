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

	function print_member(){
		?><table><tr> <?
		?><td><? echo $this->name; ?></td><?
		?><td><? echo $this->title; ?></td><?
		?><td><? echo $this->bio; ?></td><?
		?></tr></table> <?
	}

}
?>