<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {

$member_admin = new memadmin();

?> <div class = "row"> <?
    ?> <div class = "medium-6 columns"> 
            <div class="row">
                <div class="small-12 columns">
                    <? echo "Current Renewed Members: ";
                    echo $member_admin->get_numberOf('RENEWED').'<br/>';
                    echo $member_admin->get_numberOf('NOT RENEWED').'<br/>';
                    echo $member_admin->get_numberOf('REVOKED');
                    ?>
                </div>
                <div class="small-12 columns">
                    <? $member_admin->search_member_form(); ?>
                </div>
            </div>
        </div> <?
    ?> <div class = "medium-6 columns"> <?
        if (isset($_POST['renewal'])) { $member_admin->update_renew($_POST); }
            $member_admin->set_renew();
    ?> </div> <?
?> </div> 
    <div class = "row"> <?
    ?> <div class = "medium-6 columns"> <?
        
    ?> </div> <?
    ?> <div class = "medium-6 columns"> <?
      
    ?> </div> <?
?> </div> <?
}

include("../includes/layouts/footer.php"); ?>