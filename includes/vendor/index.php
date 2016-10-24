<?
require_once 'vendor/autoload.php';

$m = new PHPMailer;

$m->isSMTP();
$m->SMTPAuth = true;
//$m->STMPDebug = 2;

$m->Host = 'smtp.gmail.com';
$m->Username = "";
$m->Password = "";
$m->SMTPSecure = "ssl";
$m->Port = 465;

$m->From = 'chair@ncedonline.org';
$m->FromName = "";
$m->addReplyTo("email address". "Reply Address");
$m->addAddress("email address");
$m->addAddress("email address");
$m->addCC();
$m->addBCC();
$m->Subject = "";
$m->Body = "";
$m->AltBody = "";
$m->addAttachment();

if ($m->send()){

} else {

}

?>