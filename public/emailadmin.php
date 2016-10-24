<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncedadmin'])) {
    $all_emails = new email_object();
    $member_admin = new memadmin();
    $task=isset($_GET['task']) ? $_GET['task'] : "" ;
        if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
    $ncednumber=isset($_GET['ncednumber']) ? $_GET['ncednumber'] : "" ;
        if (!$ncednumber) $ncednumber=isset($_POST['ncednumber']) ? $_POST['ncednumber'] : "" ;
     if (isset($_POST['find_member'])){
        $ncednumber = $member_admin->get_memberN($_POST, 'emailadmin');
        $task = "email_ind";
     }
     if (isset($_GET['ncednumberL'])){
        $ncednumber = $_GET['ncednumberL'];
        $task = "email_ind";
     }
        

    $meminfo = new infobject($ncednumber);

    if (isset($_POST['editinfo'])){
           $meminfo->info_update($_POST); 
    }

    if ($task == 'send_email'){
        $all_emails->send_email($_POST, $_FILES);
    }
    
    if ($task == "addEmail"){
        $meminfo->info_form(true, 'emailadmin.php'); 
    } elseif ($task=="searchmember") {
        ?><div class = "row"><div class = "medium-7 columns"> <?
        $member_admin->search_member_form("emailadmin"); 
        ?> </div> </div>  <?
    } elseif ($task === "email_ind" && $ncednumber !="" || $task === "email_all"){
        if ($task === "email_ind") {
            $all_emails->mail_form($ncednumber);
        } else {
            $all_emails->mail_form();
        }
    } else {

            ?>
        <div class = "row">
            <div class = "medium-7 columns panel"> 
                <h3 class = "text-center">EMAIL DATA:</h3> <br/>
                <? $all_emails->get_nums(); ?>
            </div> 
            <div class = "medium-5 columns"> 
                <a href="?task=searchmember" class = "button small radius right">EMAIL INDIVIDUAL HOLDER</a><br/>
                <a href="?task=email_all"class = "button small radius right">EMAIL ALL HOLDERS</a>
            </div> 
        </div> 
        <div class = "row">
             <div class = "medium-12 columns"> 
            <h4>No email listed: </h4><br/>
            <? $all_emails->list_noemail() ?>
            </div>
        </div> 
        <?
    }

        
}

include("../includes/layouts/footer.php"); ?>