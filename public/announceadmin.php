<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $announcements = new all_announcements();

        ?><div class = "row">
            <div class = "medium-12 columns"> 
              <table>
                <? $announcements->print_announcements(true); ?>
              </table>
            </div> 
        </div> <?
}

include("../includes/layouts/footer.php"); ?>