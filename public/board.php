<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 



$the_board = new boardadmin();
$the_board->print_board();
?>

<!-- modal windows -->
 <?  $the_board->generate_boxes(); 
include("../includes/layouts/footer.php"); ?>