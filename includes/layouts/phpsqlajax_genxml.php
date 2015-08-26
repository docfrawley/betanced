hello
<?php include_once("initialize.php");
error_reporting(-1);
echo "hello";
// Start XML file, create parent node
$doc = domxml_new_doc("1.0");
$node = $doc->create_element("markers");
$parnode = $doc->append_child($node);
$sql="SELECT * FROM markers";
    $result_set = $database->query($sql);
header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = $database->fetch_array($result_set)){
  // ADD TO XML DOCUMENT NODE
  $node = $doc->create_element("marker");
  $newnode = $parnode->append_child($node);

  $newnode->set_attribute("name", $row['name']);
  $newnode->set_attribute("address", $row['address']);
  $newnode->set_attribute("lat", $row['lat']);
  $newnode->set_attribute("lng", $row['lng']);
  $newnode->set_attribute("type", $row['type']);
}

$xmlfile = $doc->dump_mem();
echo $xmlfile;
?>