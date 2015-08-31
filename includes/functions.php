<?
function getnewarray($thearray, $where) {
		if ($where==0) {
			$newarray = array_shift($thearray);
		} else {
			$array1 = array_slice($thearray, $where);
			$array2 = array_splice($thearray, $where);
			$array3 = array_shift($array1);
			$newarray = array_merge ($array2, $array3);	
		}
		return $newarray;
	} //function getnewarray($thearray, $where) 
	
	
function setlocation($theplace) {
		$string_place = "<div id='locatorhome'>";
		$string_place .= "<div id='whereat'>";
        $string_place .= $theplace;
        $string_place .= "</div>";
        $string_place .= "<div id='gohome'>";
        $string_place .= "<a href='index.php'>GO HOME</a>";
        $string_place .= "</div></div>";
		echo $string_place;
}

function redirect_to($new_location) {
	$host = $_SERVER['HTTP_HOST'];
	  header("Location: http://$host/betanced/public/$new_location");
	  exit;
	}	
	
function setyear() {
	//if (date('n') > 11) {
	//	return date('Y') +1;
	///} else {
		return date('Y');
	//}
}

function revokedmessage() {
	echo "Your membership is listed as revoked such that you cannot renew your membership</br>";
	echo "To become a member of NCED again, you must pass the NCED exam.<br/><br/>If you believe";
	echo "that your listed membership status is a mistake, please contact the NCED board<br/>";
	echo "at membership@ncedonline.org";
}

function getrenewtime($renew_year, $currentyear) {
	//return (mktime(0, 0, 0, date('n'), date('j'), date('Y')) > mktime(0, 0, 0, 11, 23, $currentyear) || 
	//	mktime(0, 0, 0, date('n'), date('j'), date('Y')) <= mktime(23, 59, 59, 3, 31, $renew_year));
	return true;
}

function get_state($abbrvst) {
	global $database;
	if ($abbrvst=="State") { return "State"; }
	$sql = "SELECT * FROM states WHERE abbrv = '".$abbrvst."'";
	$result_set = $database->query($sql);
	$value = $database->fetch_array($result_set);
	return $value['fullname'];
}

function statelist($thestate="State") {
	global $database;
	$sql = "SELECT * FROM states";
	$result_set = $database->query($sql);?>
	<select name="state"/>
        <option selected="selected" value="<? echo $thestate; ?>"/> <? echo get_state($thestate); ?> </option><? 
        while ($value = $database->fetch_array($result_set)) { ?>
			<option value="<? echo $value['abbrv'];  ?>"><? echo $value['fullname'];  ?></option> <?
		}?>
    </select><?
}

function preferred_phone($preferred){
	if ($preferred=='home') {return "Home Phone";}
	elseif ($preferred=='work') {return "Work Phone";}
	elseif ($preferred=='cell') {return "Cell Phone";}
	else {return $preferred;}
}

function get_area($whicharea){
	echo "AREA ";
			switch ($whicharea) {
				case "1":
				echo "1: Professional Meetings";
				break;
				case "2":
				echo "2: Collaborative Study";
				break;
				case "3":
				echo "3: Independent Study";
				break;
				case "4":
				echo "4: Teaching, Research, Development";
				break;
				case "5":
				echo "5: Graduate Coursework";
				break;
				case "6":
				echo "6: Professional Consults";
				break;
				default:
				echo "yike";
			}
}

function get_type($whicharea){
	switch ($whicharea) {
		case "1": ?>
    		<option value="Conferences">Conferences</option>
   			<option value="Workshops and seminars">Workshops and seminars</option>
    		<option value="Online seminars">Online seminars</option>
    		<option value="Professional development sessions">Professional development sessions</option>
            <option value="Other">Other</option> <?
		break;
		case "2": ?>
    		<option value="Study groups">Study groups</option>
   			<option value="Case discussion groups">Case discussion groups</option>
    		<option value="Journal clubs">Journal clubs</option>
    		<option value="Book discussion groups">Book discussion groups</option>
            <option value="Other">Other</option> <?
		break;
		case "3": ?>
    		<option value="Videos, DVDs">Videos, DVDs</option>
   			<option value="Audiotapes, CDs">Audiotapes, CDs</option>
    		<option value="Books, monographs, journals">Books, monographs, journals</option>
            <option value="Other">Other</option> <?
		break;
		case "4": ?>
    		<option value="Presentations at professional meetings">Presentations at professional meetings</option>
   			<option value="Academic courses">Academic courses</option>
    		<option value="Web sites">Web sites</option>
    		<option value="Books, grants">Books, grants</option>
            <option value="Assessment/intervention products">Assessment/intervention products</option>
            <option value="Other">Other</option> <?
		break;
		case "5": ?>
    		<option value="Graduate-level courses">Graduate-level courses</option>
            <option value="Other">Other</option> <?
		break;
		case "6": ?>
    		<option value="Mentoring">Mentoring</option>
            <option value="Program evaluations/reviews">Program evaluations/reviews</option>
            <option value="Program development">Program development</option>
            <option value="Other">Other</option> <?
		break;
		default:
			echo "yike";
		}
}

function GetMonths() {
	$i = 1;
$month = strtotime('2013-01-01');
	while($i <= 12)
	{
		$month_name = date('F', $month);
		echo '<option value="'. $i. '">'.$month_name.'</option>';
		$month = strtotime('+1 month', $month);
		$i++;
	}
}

function GetDays() {
	for($i=1; $i<=31;$i++){
		?><option value="<? echo $i; ?>"><? echo $i; ?></option><?
	} 
}

function GetYears() {
	for($i=date("Y"); $i>=date("Y")-5;$i--){
		?><option value="<? echo $i; ?>"><? echo $i; ?></option><?
	} 
}

function parseToXML($htmlStr)
{
	$xmlStr=str_replace('<','&lt;',$htmlStr);
	$xmlStr=str_replace('>','&gt;',$xmlStr);
	$xmlStr=str_replace('"','&quot;',$xmlStr);
	$xmlStr=str_replace("'",'&#39;',$xmlStr);
	$xmlStr=str_replace("&",'&amp;',$xmlStr);
	return $xmlStr;
}
?>