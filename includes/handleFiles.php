<? include_once("initialize.php");


class files_object {

  function add_file_general($info, $filestuff) {
    global $database;
    $tmp_file = $filestuff['file_upload']['tmp_name'];
    $target_file = basename($filestuff['file_upload']['name']);
    $upload_dir = ($info['purpose']=='admin')? "documents" : "pdfs";
    $the_place = $upload_dir."/".$target_file;
    if(move_uploaded_file($tmp_file, $upload_dir."/".$target_file)) {
      $message = "File uploaded successfully.";
    } else {
      $error = $_FILES['file_upload']['error'];
      $message = $upload_errors[$error];
    }

    $sql = "INSERT INTO uploadfiles (";
    $sql .= "fname, fpath, fdate, fpurpose";
    $sql .= ") VALUES ('";
    $sql .= $database->escape_value($info['titleFile']) ."', '";
    $sql .= $the_place ."', '";
    $sql .= date('U') ."', '";
    $sql .= $database->escape_value($info['purpose']) ."')";
    $database->query($sql);

  }

  function get_path($id){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fileid = '".$id."'";
		$result_set = $database->query($sql);
    $value = $database->fetch_array($result_set);
    return $value['fpath'];
  }

  function show_general_files(){
    global $database;

    $sql="SELECT * FROM uploadfiles ORDER BY fname";
		$result_set = $database->query($sql);
    ?><div class="row custom-row-class">
      <div class="medium-12 columns">
        <table>
          <thead>
            <tr>
              <th width="200">Document Title</th>
              <th>Date Added</th>
              <th width="150"></th>

            </tr>
          </thead>
          <tbody>
        <?
  		while ($value = $database->fetch_array($result_set)) {

        if ($value['fpurpose']=='admin') { ?>
          <tr>
            <td><a href="<? echo $value['fpath']; ?>" class="button radius small info"/>
              <? echo $value['fname']; ?>
            </a></td>
            <td><? echo date('F j, Y', $value['fdate']); ?></td>
            <td><a href="?task=delete&fid=<? echo $value['fileid']; ?>" class="button radius tiny alert"/>
              DELETE
            </a></td>
          </tr>
      <?}
      }?>
      </tbody>
    </table>
  </div>
  </div><?

  }

  function delete_file($fileid, $purpose='admin'){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fileid = '".$fileid."' AND fpurpose='".$purpose."'";
		$result_set = $database->query($sql);
    $value = $database->fetch_array($result_set);
    $the_path =  SITE_ROOT.DS.'public'.DS.$value['fpath'];
    if (file_exists($the_path)){
      unlink($the_path);
    }
    $sql = "DELETE FROM uploadfiles ";
	  	$sql .= "WHERE fileid=". $fileid;
	  	$sql .= " LIMIT 1";
	 	$database->query($sql);
  }

function general_file_form(){
  ?>
  <form action="filesadmin.php" enctype="multipart/form-data" method="post">
    <fieldset>
      <legend>UPLOAD NEW DOCUMENT</legend>
    <div class="row">
      <div class="medium-6 columns">
        <label>Title: </label>
        <input type="text" name="titleFile"/>
      </div>
    <div class="medium-6 columns">
        <label>Upload File:</label>
        <button class="file-upload button radius tiny">Upload File
          <input type="file" name="file_upload" />
          <input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
        </button

        <form action="upload.php"  method="POST">
    </div>
  </div>
  <input type="hidden" name="purpose" value="admin"/>
  <div class="row">
    <div class="large-6 medium-12 columns">
          <input type="submit" name="submit" value="SUBMIT" class="button radius small"/>
    </div>
  </div>
</fieldset>
  </form>
  <?
}

}

?>
