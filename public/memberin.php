<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncednumber'])) {

$member = new memobject($_SESSION['ncednumber']);
$meminfo = new infobject($_SESSION['ncednumber']);
$ceuinfo = new ceuinfo($_SESSION['ncednumber'], $member->set_archivedate());
?> <div class = "row"> <?
    ?> <div class = "small-12 columns"> 
            <div class="row">
                <div class="small-6 columns">
                    <? $member->display_member(); ?>
                </div>
                <div class="small-6 columns">
                    <? $ceuinfo->snapshot(); ?>
                </div>
            </div>
        </div> <?
    ?> <div class = "medium-6 columns"> <?
        if (isset($_POST['uname'])) {
            $member->profile_update($_POST); 
        echo $_SESSION['tryagainc'].'<br/>';
    }
    ?> </div> <?
?> </div> 
    <div class = "row"> <?
    ?> <div class = "medium-6 columns"> <?
        if (isset($_POST['editinfo'])) {
            $meminfo->info_update($_POST); 
            echo $_SESSION['tryagainc'].'<br/>';
        }
        echo $meminfo->info_form();
    ?> </div> <?
?> </div> <?
}

include("../includes/layouts/footer.php"); ?>