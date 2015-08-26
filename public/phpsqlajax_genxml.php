<?require_once("../includes/initialize.php"); 
header("Content-type: text/xml");
// Start XML file, create parent node

$sql="SELECT * FROM markers WHERE 1";
    $result_set = $database->query($sql);
echo '<markers>';
// Iterate through the rows, printing XML nodes for each
while ($row = $database->fetch_array($result_set)){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['name']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';
?>