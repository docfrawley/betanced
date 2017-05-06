<?php require_once("../includes/initialize.php");

if ($_GET['task']=='markers'){
  $map_markers = new all_maps();
  $result = $map_markers->get_markers();
  echo $json_response = json_encode($result);
}

?>
