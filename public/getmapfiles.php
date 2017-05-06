<?php require_once("../includes/initialize.php");
      header('Access-Control-Allow-Origin: *'); 
if ($_GET['task']=='markers'){
  $map_markers = new all_maps();
  $result = $map_markers->get_markers();
  echo $json_response = json_encode($result);
}

?>
