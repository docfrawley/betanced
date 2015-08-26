<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncednumber'])) {

$member = new memobject();
$meminfo = new infobject();
$ceuinfo = new ceuinfo($member->set_archivedate());
?> <div class = "row"> <?
    ?> <div class = "medium-6 columns"> 
            <div class="row">
                <div class="small-12 columns">
                    <? $member->display_member(); ?>
                </div>
                <div class="small-12 columns">
                    <? $ceuinfo->snapshot(); ?>
                </div>
            </div>
        </div> <?
    ?> <div class = "medium-6 columns"> <?
        if (isset($_POST['uname'])) {
            $member->profile_update($_POST); 
        echo $_SESSION['tryagainc'].'<br/>';
    }
        $member->login_form();
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
    ?> <div class = "medium-6 columns"> <?
        if (isset($_POST['uname'])) {
            $member->profile_update($_POST); 
        echo $_SESSION['tryagainc'].'<br/>';
    }
        $member->login_form();
    ?> </div> <?
?> </div> <?
}

include("../includes/layouts/footer.php"); ?>