<?php require_once("../includes/initialize.php");
require("../includes/vendor/fpdf/fpdf.php");

if (isset($_GET['ncednum'])){
  $ncednum = $_GET['ncednum'];
  $wpayment = $_GET['wreceipt'];
  $member = new memobject($ncednum);
  $name = $member->get_name();
  $payment_info = $member->get_payment_info($wpayment);
  $amount = "$". number_format($payment_info['amount'], 2, '.', '');
  $received = $payment_info['rdate'];

  $pdf = new FPDF('L');
  $pdf->AddPage();
  $pdf->Image('img/NCED Shield.png',10,10,60,80);
  $pdf->Image('img/titlepdf.jpg',80,10,195,40);
  $pdf->Image('img/signatures.jpg',10,120,50,50);
  $pdf->SetFont('times','',28);
  // Centered text in a framed 20*10 mm cell and line break
  $pdf->SetLeftMargin(60);
  $pdf->Cell(0,40,' ',0,1,'C');
  $pdf->Cell(0,18,'This is an official receipt that',0,1,'C');
  $pdf->SetFont('times','B',28);
  $pdf->Cell(0,18,$name,0,1,'C');
  $pdf->SetFont('times','',28);
  $pdf->Cell(0,18,'paid the amount of:',0,1,'C');
  $pdf->SetFont('times','B',28);
  $pdf->Cell(0,18,$amount,0,1,'C');

  $pdf->SetFont('times','',28);


  $pdf->Cell(0,18,'On this date:',0,1,'C');

  $pdf->SetFont('times','B',28);
  $pdf->Cell(0,18,$received,0,1,'C');

  $pdf->SetFont('times','',28);


  $pdf->Cell(0,18,'for NCED membership renewal',0,1,'C');

  $pdf->Output();
}
?>
