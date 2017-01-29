<?php require_once("../includes/initialize.php");
require("../includes/vendor/fpdf/fpdf.php");

if (isset($_GET['ncednum'])){
  $ncednum = $_GET['ncednum'];
  
  $member = new memobject($ncednum);
  $name = $member->get_name();
  $start_date = $member->get_memstart();
  $nced_num = $member->get_num();

  $word = "Hello world!";
  $pdf = new FPDF('L');
  $pdf->AddPage();
  $pdf->Image('img/NCED Shield.png',10,10,60,80);
  $pdf->Image('img/titlepdf.jpg',80,10,195,40);
  $pdf->Image('img/signatures.jpg',10,120,50,50);
  $pdf->SetFont('times','',16);
  // Centered text in a framed 20*10 mm cell and line break
  $pdf->SetLeftMargin(60);
  $pdf->Cell(0,40,' ',0,1,'C');
  $pdf->Cell(0,14,'Be it Known That',0,1,'C');
  $pdf->SetFont('times','B',36);
  $pdf->Cell(0,20,$name,0,1,'C');
  $pdf->SetFont('times','',16);
  $pdf->Cell(0,8,'Having Fulfilled the Requirements of the',0,1,'C');
  $pdf->Cell(0,8,'National Certification of Educational Diagnosticians Board',0,1,'C');
  $pdf->Cell(0,8,'Is Hereby Approved by Action of the Board as a',0,1,'C');
  $pdf->Cell(0,10,'Having Fulfilled the Requirements of the',0,1,'C');

  $pdf->SetFont('times','B',22);
  $pdf->Cell(0,12,'Nationally Certified Educational Diagnostician',0,1,'C');

  $pdf->SetFont('times','',16);
  $pdf->Cell(0,8,'In Witness Whereof,',0,1,'C');
  $pdf->Cell(0,8,'We have Hereunto set our Hands and',0,1,'C');
  $pdf->Cell(0,10,'Affixed the Seal of this Board',0,1,'C');

  $pdf->SetFont('times','',22);
  $pdf->Cell(0,10,$start_date,0,1,'C');

  $pdf->SetFont('times','',12);
  $pdf->Cell(0,14,'Certificate Number',0,1,'C');
  $pdf->SetFont('times','B',32);
  $pdf->Cell(0,8,$nced_num,0,1,'C');

  $pdf->Output();
}
?>
