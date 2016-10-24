<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncednumber'])) {

$member = new memobject($_SESSION['ncednumber']);
$meminfo = new infobject($_SESSION['ncednumber']);
$ceuinfo = new ceuinfo($_SESSION['ncednumber'], $member->set_archivedate());
$reg_info = new ind_reg_object($_SESSION['ncednumber']);
?> <div class = "row"> <?
    ?> <div class = "small-12 columns">
            <div class="row">
                <div class="small-12 medium-6 columns">
                  <fieldset>
                    <? $member->display_member();
                       if (isset($_GET['task'])){
                         $member->task_renew($_GET);
                       }
                       $member->renew_process();
                    ?>
                  </fieldset>
                </div>
                <div class="small-12 medium-6 columns">
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
    <div class = "row">
        <div class = "medium-6 columns"> <?
            if (isset($_POST['editinfo'])) {
                $meminfo->info_update($_POST);
                echo $_SESSION['tryagainc'].'<br/>';
            }
            echo $meminfo->info_form(); ?>
        </div>
        <div class = "medium-6 columns"> <?
            if (isset($_POST['editreg'])) {
                $reg_info->reg_update($_POST);
                echo $_SESSION['tryagainc'].'<br/>';
            }
            echo $reg_info->reg_form(); ?>
        </div>
    </div> <?
}

include("../includes/layouts/footer.php"); ?>
