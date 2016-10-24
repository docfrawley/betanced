<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

if (isset($_SESSION['ncednumber'])) {
    $updateceu=isset($_GET['updateceu']) ? $_GET['updateceu'] : "" ;
        if (!$updateceu) $updateceu=isset($_POST['updateceu']) ? $_POST['updateceu'] : "" ;
    if ($updateceu || isset($_GET['deleteceu'])){
        $numindex=isset($_GET['deleteceu']) ? $_GET['deleteceu'] : $updateceu;
        $upceu = new ceuobject($numindex);
        if ($updateceu){
            $upceu->update_ceu($_POST);
        } else {
            $upceu->delete_ceu();
        }
    }
    
    $archive = isset($_GET['archive']);
    $member = new memobject();
    $meminfo = new infobject();
    $ceuinfo = new ceuinfo($_SESSION['ncednumber'], $member->set_archivedate());
    if (isset($_POST['thearea'])){
        $ceuinfo->add_ceu($_POST);
    }
    ?> <div class = "row"> <?
        ?> <div class = "medium-10 columns"> 
                <? if ($archive) {
                    ?>
                    Listed Below are your archived CEUs. <br/><br/>
                    Please remember that your CEUs are on a five year cycle based on your initial membership date of <? echo $member->get_memstart(); ?>. 
                    If you would like to see your archived CEUs, please click the ARCHIVE button to the right.
                    <?
                } elseif (isset($_GET['whicharea'])) {
                    $ceuinfo->add_form($_GET['whicharea']);
                } elseif (isset($_GET['updateceu'])) {
                    $upceu->update_form();
                } elseif ($_SESSION['ceumessage']!="") {
                    echo $_SESSION['ceumessage'];
                    $_SESSION['ceumessage']="";
                } else {
                    ?>
                    Listed Below are your current CEUs. You may add entries by clickin on the ADD button in each area or
                    edit/delete an entry by clicking on the CEU date. <br/><br/>
                    Please remember that your CEUs are on a five year cycle based on your initial membership date of <? echo $member->get_memstart(); ?>. 
                    If you would like to see your archived CEUs, please click the ARCHIVE button to the right.
                    <?
                    }
                ?>
            </div> 
            <div class = "medium-2 columns"> 
            <? if ($archive) {
                echo '<a href="ceupage.php" class="button small ceu-button">CURRENT CEUs</a>'; 
            } elseif (isset($_GET['updateceu'])) {
                echo "<a href='ceupage.php' class='button small radius'>CEU MAIN PAGE</a><br/>"; 
                echo "<a href='ceupage.php?deleteceu={$upceu->get_numindex()}' class='button small radius alert'>DELETE CEU</a>"; 
                
            } else {
                echo '<a href="?archive=yes" class="button small radius">ARCHIVED CEUs</a>'; 
            } ?>
            </div> 
        </div> <? 
        for ($area = 1; $area <= 6; $area++) { ?>
            <div class="row">
                <div class="small-12 columns panel">
                    <? $ceuinfo->showarea($area, $archive); ?>
                </div>
            </div><? 
        }  
}

include("../includes/layouts/footer.php"); ?>