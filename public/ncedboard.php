<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $member_admin = new memadmin();

$the_board = new boardadmin();
$the_board->print_board();
} ?>

<!-- modal windows -->
 <?  $the_board->generate_boxes(); 
include("../includes/layouts/footer.php"); ?>