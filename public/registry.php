<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncednumber'])) {
	$member = new memobject(_SESSION['ncednumber']);
  if ($member->get_memstatus =='RENEWED'){
	?>
	<div class="row">
		<div class="small-12 columns">
			<p>Welcome to the NCED National Registry. You may search for a qualified educational diagnostician below.
				You may fill in as many fields as you would like. So you can search by the first letter of last names, OR by state, OR by speciality.
				You can also run a more specific search by entering two or all three fields. The results of your search will be listed below.
				Please note that only the states listed are those with registered NCED members participating in the national registry.</p>
		</div>
		<div class="small-12 columns">
			<form action="registry.php" method="POST">
	 			<div class="row">
	 				<div class="medium-3 columns">
					<select name="lname">
					<option value="">First Letter of Last Name</option>
					<?
					foreach (range('A', 'Z') as $h) {
						?><option value="<? echo $h; ?>"><? echo $h; ?></option><?
					} //foreach (range('A', 'Z') as $h)
					?>
					</select>
				</div>
				<div class="medium-3 columns">
					<? statelist()?>
			 	</div>
			 	<div class="medium-3 columns">
					<select name="speciality">
						<option value="">Speciality</option>
	                	<option value="Adults With Disabilties">Adults With Disabilties</option>
	                	<option value="Autism">Autism</option>
	                	<option value="Bi Lingual">Bi Lingual</option>
	                	<option value="Cognitive Therapy">Cognitive Therapy</option>
	                	<option value="Hearing Impairments">Hearing Impairments</option>
	                	<option value="Math Disabilties">Math Disabilties</option>
	                	<option value="Preschool Disabilities">Preschool Disabilities</option>
	                	<option value="Reading Disorders">Reading Disorders</option>
	                	<option value="Speech /Communication">Speech /Communication</option>
	                	<option value="Traumatic Brain Injury">Traumatic Brain Injury</option>
	                	<option value="Visual Impairments">Visual Impairments</option>
	                </select>
	            </div>
	            <div class="medium-3 columns">
	            	<input type="submit" value="Submit" class="button small"/>
	        	</div>
	        </div>
			</form>
		</div>
		<div>
			<?
			if (isset($_POST['lname'])){
				$rObject = new registryObject($_POST);
				if ($rObject->check_submission()){
					echo "Please check your submssion and try again";
				} else {
					?>
						<div class="small-12 columns">
					<?
					$rObject->create_accordian();
					?>
			</div>
					<?
				}
			}
			?>
		</div>
	</div>

	<!-- modal windows -->
	 <? } else {
		 ?><h1>Your renewal status does not allow you access to this page.</h1><?
	 }
	}
include("../includes/layouts/footer.php"); ?>
