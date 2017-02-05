<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncednumber'])) {

$member = new memobject($_SESSION['ncednumber']);
$paymentHistory = $member->get_payhistory();
?>
    <div class = "row custom-row-class">
        <div class = "medium-12 columns">

          <h3 class="text-center title-color">Payment History</h4>
            <h5 class="text-center title-color">Click on Date to get receipt</h3>
          <table>
            <tr><td><strong>Date Entered</strong></td><td><strong>Amount</strong>
            </td><td><strong>Method</strong></td></tr><?
          foreach ($paymentHistory as $info) {
            ?> <tr><td>
              <a href="getreceiptpdf.php?ncednum=<? echo $_SESSION['ncednumber']; ?>
                &wreceipt=<? echo $info['numid']; ?>"
								 target="_blank" class="button tiny radius"> <? echo $info['rdate']; ?></a>
            </td><td><?
              echo "$". number_format($info['amount'], 2, '.', '');
            ?></td><td><?
              if ($info['manner']=="select"){
                echo "";
              } else {
                echo $info['manner'];
              }
            ?></td></tr><?
          }
          ?> </table>


        </div>
    </div> <?
}

include("../includes/layouts/footer.php"); ?>
