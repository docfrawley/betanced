<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php"); 

session_start();

if (!isset($_SESSION["tryagain"])) {$_SESSION['tryagain'] = ""; }
if (!isset($_SESSION["tryagainc"])) {$_SESSION['tryagainc'] = ""; }

if ($_SESSION['tryagain'] == "create user") {
	$luser = new loginuser($_SESSION['lname'], $_SESSION['email']);
	?><div id="loginholder3"><? echo $_SESSION['tryagainc']; ?></div><?
	$luser->create_form();	
} else {
	?>
    <div class="row">
        <div class="small-12 columns center"><br/>
            <? echo $_SESSION['tryagain']; ?><br/><br/>
        </div>
    </div>

    <div class="row">
        <div class="medium-6 columns">
            <div class="row">
                <div class="small-12 columns">
                    <p>Sign in here if you haven't created a user account yet.</p>
                </div>
                <form  action="logincheck.php" method="POST">
                <div class="small-12 columns">
                    <label>LAST NAME</label>
        	        <input type="text" name="lname" placeholder="Last Name"/>
                </div>
                <div class="small-12 columns">
                    <label>NCED NUMBER</label>
                    <input type="text" name="ncednumber" placeholder="NCED Number"/>
                </div>
                <div class="small-12 columns">
                    <input type="submit" value="Submit" class="button small"/>  
                </div>  
        	   </form> 
            </div>
        </div>

        <div class="medium-6 columns">
            <div class="row">
                <div class="small-12 columns">
                    <p>Sign in here if you have already created an user account.</p>
                </div>
                <form  action="logincheck.php" method="POST">
                <div class="small-12 columns">
                    <label>USERNAME</label>
                    <input type="text" name="username" placeholder="Username"/>
                </div>
                <div class="small-12 columns">
                    <label>PASSWORD</label>
                    <input type="password" name="password" placeholder="Password"/>
                </div>
                <div class="small-12 columns">
                    <input type="submit" value="Submit" class="button small"/>  
                </div>  
               </form> 
            </div>
        </div>
    </div>
    <? 
}
include("../includes/layouts/footer.php"); ?>