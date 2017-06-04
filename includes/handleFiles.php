<? include_once("initialize.php");


class files_object {

  function add_file($info, $filestuff) {
    global $database;
    $purpose = $database->escape_value($info['purpose']);
    $tmp_file = $filestuff['file_upload']['tmp_name'];
    $target_file = basename($filestuff['file_upload']['name']);
    $upload_dir = ($purpose =='admin')? "documents" : "pdfs";
    $the_place = $upload_dir."/".$target_file;
    if(move_uploaded_file($tmp_file, $upload_dir."/".$target_file)) {
      $message = "File uploaded successfully.";
    } else {
      $error = $_FILES['file_upload']['error'];
      $message = $upload_errors[$error];
    }
    $fname = $database->escape_value($info['titleFile']);
    if ($purpose !='general'){
      $sql = "INSERT INTO uploadfiles (";
      $sql .= "fname, fpath, fdate, fpurpose";
      $sql .= ") VALUES ('";
      $sql .= $fname ."', '";
      $sql .= $the_place ."', '";
      $sql .= date('U') ."', '";
      $sql .= $purpose."')";
      $database->query($sql);
    } else {
      $sql = "UPDATE uploadfiles SET ";
			$sql .= "fpath='". $the_place ."' ";
			$sql .= "WHERE fname='". $fname."'";
			$database->query($sql);
    }


  }

  function get_path($fname){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fname = '".$fname."'";
		$result_set = $database->query($sql);
    $value = $database->fetch_array($result_set);
    return $value['fpath'];
  }

  function get_path_admin($id){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE filed = '".$id."'";
		$result_set = $database->query($sql);
    $value = $database->fetch_array($result_set);
    return $value['fpath'];
  }

  function show_newsletters(){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fpurpose = 'newsletter'";
		$result_set = $database->query($sql);
    while ($info = $database->fetch_array($result_set)) {
      $title = $info['fname'];
      $path = $info['fpath'];
      ?>
      <div class = "row custom-row-class">
          <div class = "medium-3 columns left">
            <a class="button radius expand"
            href="<? echo $path ?>"
              target="_blank"><? echo $title; ?></a>
          </div>
      </div>
      <?
    }
  }

  function show_newsletters_files(){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fpurpose = 'newsletter' ORDER BY fileid";
		$result_set = $database->query($sql);
    ?>
        <table>
          <thead>
            <tr>
              <th width="200">Document Title</th>
              <th>Date Updated</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
        <?
  		while ($value = $database->fetch_array($result_set)) { ?>

          <tr>
            <td>
            <a class="button radius small"
            href="<? echo $value['fpath'] ?>"
              target="_blank"><? echo $value['fname']; ?></a>
            </td>
            <td><? echo date('n/j/y', $value['fdate']); ?></td>
            <td><a href="?task=delete&fid=<? echo $value['fileid']; ?>"
              class="button radius tiny alert"/>
              DELETE
            </a></td>
          </tr><?
      }?>
      </tbody>
    </table><?
  }

  function show_general_files(){
    global $database;
    $sql="SELECT * FROM uploadfiles WHERE fpurpose = 'general' ORDER BY fname";
		$result_set = $database->query($sql);
    ?>
        <table>
          <thead>
            <tr>
              <th width="200">Document Title</th>
              <th>Date Updated</th>
              <th width="150"></th>
              <th width="150"></th>
              <th width="150"></th>
            </tr>
          </thead>
          <tbody>
        <?
  		while ($value = $database->fetch_array($result_set)) { ?>
          <form action="ncedpdfs.php" enctype="multipart/form-data" method="post">
          <tr>
            <td><? echo $value['fname']; ?></td>
            <td><? echo date('n/j/y', $value['fdate']); ?></td>
            <td>
            <button class="file-upload button radius tiny">Upload File
              <input type="file" name="file_upload" />
              <input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
            </button

            <form action="upload.php"  method="POST"></td>
              <td>
                <input type="hidden" name="titleFile" value="<? echo $value['fname']; ?>"/>
                <input type="hidden" name="purpose" value="general"/>
                <input type="submit" name="submit" value="SUBMIT" class="button radius tiny"/>
              </td>
          </tr>
        </form><?
      }?>
      </tbody>
    </table><?
  }

  function show_admin_files(){
    global $database;

    $sql="SELECT * FROM uploadfiles WHERE fpurpose = 'admin' ORDER BY fname";
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
            <td><a href="<? echo $value['fpath']; ?>" class="button radius small info"
                  target="_blank"/>
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

  function newsletter_file_form(){
    ?>
    <form action="ncedpdfs.php" enctype="multipart/form-data" method="post">
      <fieldset>
        <legend>UPLOAD NEWSLETTER</legend>
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
    <input type="hidden" name="purpose" value="newsletter"/>
    <div class="row">
      <div class="large-6 medium-12 columns">
            <input type="submit" name="submit" value="SUBMIT" class="button radius small"/>
      </div>
    </div>
  </fieldset>
    </form>
    <?
  }

function admin_file_form(){
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
