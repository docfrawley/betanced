<? include_once("initialize.php");

class announceobject {
	
	private $announcement;
	private $title;
	private $idnum;
	private $priority;
	
	function __construct($id) {
		global $database;
		$sql="SELECT * FROM announce WHERE id='".$id."'";
		$result_set = $database->query($sql);
		$value = $database->fetch_array($result_set);
		$this->title = $value['title'];
		$this->announcement = $value['announcement'];
		$this->idnum = $value['id'];
		$this->priority = $value['priority'];
	}

	function get_announce() {
		return $this->announcement;
	}

	function get_title() {
		return $this->title;
	}

	function get_id() {
		return $this->idnum;
	}

	function get_priority() {
		return $this->priority;
	}
}
?>