<? include_once("initialize.php");

class map_object {
	
	private $lat;
	private $lng;
	private $address;
	private $content;
	private $name;
	private $numid;
	
	function __construct($id = 0) {
		global $database;
		if ($id==0){
			$this->lat = 0;
			$this->lng = 0;
			$this->address = "";
			$this->name = "";
			$this->content = "";
			$this->numid = 0;
		} else {
			$sql="SELECT * FROM markers WHERE numid='".$id."'";
			$result_set = $database->query($sql);
			$value = $database->fetch_array($result_set);
			$this->lat = $value['lat'];
			$this->lng = $value['lng'];
			$this->address = $value['address'];
			$this->name = $value['name'];
			$this->content = $value['content'];
			$this->numid = $id;
		}
	}

	function get_latlong($address){
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $this->lat = $output->results[0]->geometry->location->lat;
        $this->lng = $output->results[0]->geometry->location->lng;
	}

	function get_lat() {
		return $this->lat;
	}

	function get_lng() {
		return $this->lng;
	}

	function get_address() {
		return $this->address;
	}

	function get_name() {
		return $this->name;
	}

	function get_content() {
		return $this->content;
	}

	function get_id() {
		return $this->numid;
	}
}
?>