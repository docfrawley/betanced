<?php require_once("../includes/initialize.php"); ?>
<?


$task=isset($_GET['task']) ? $_GET['task'] : "" ;
    if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;

switch ($task) {
  case 'emailist':
    $all_emails = new email_object();
    $output = $all_emails->excel_list();
    break;
  case 'activelist':
    $activemems = new memadmin();
    $output = $activemems->get_excel_list("active");
    break;
  case 'inactivelist':
    $inactivemems = new memadmin();
    $output = $inactivemems->get_excel_list("revoked");
    break;
  case 'renewals':
    $renewals= new memadmin();
    $output = $renewals->get_renewal_list();
    break;
  default:
    echo "break";
    break;
}

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=download.xls");
echo $output;

?>
