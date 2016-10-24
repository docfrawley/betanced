<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin'])) {
  ?><div ng-app="GetPagesApp"><?
  $all_emails = new email_object();
  $member_admin = new memadmin();
  $task=isset($_GET['task']) ? $_GET['task'] : "" ;
     if (!$task) $task=isset($_POST['task']) ? $_POST['task'] : "" ;
  $page=isset($_GET['page']) ? $_GET['page'] : "" ;
     if (!$page) $task=isset($_POST['page']) ? $_POST['page'] : null ;
  if ($page==null){$page=1;}
            ?>
        <div class = "row custom-row-class">
            <div class = "medium-3 columns">
              <form action="excel.php?task=emailist" method="post">
	        		     <input type="submit" name="export_excel" value="EMAIL LIST" class="button radius expand"/>
	        		</form>
            </div>
            <div class = "medium-3 columns">
              <form action="excel.php?task=activelist" method="post">
	        		     <input type="submit" name="export_excel" value="ACTIVE CERT HOLDERS" class="button radius expand"/>
	        		</form>
            </div>
            <div class = "medium-3 columns">
              <form action="excel.php?task=inactivelist" method="post">
	        		     <input type="submit" name="export_excel" value="INACTIVE/REVOKED" class="button radius expand"/>
	        		</form>
            </div>
            <div class = "medium-3 columns">
              <form action="excel.php?task=renewals" method="post">
	        		     <input type="submit" name="export_excel" value="RENEWALS" class="button radius expand"/>
	        		</form>
            </div>
        </div>
        <div class = "row custom-row-class">
            <div class = "medium-12 columns">
              <h3 class="text-center title-color">RENEWALS LIST</h3>
            </div>
        </div>
      <div ng-controller="SetPagesController as items">
        <div class = "row custom-row-class"  >

              <div class = "medium-9 columns" >
                <?
                $num_pages = $member_admin->get_num_pages(25);
                ?><select ng-model="items.page" ng-change="items.getNewPage(items.page);"> <?
                for ($x=1; $x<=$num_pages; $x++){ ?>
                  <option value="<? echo $x; ?>"><? echo $x; ?></option>
                <? } ?>
              </select>
              </div>
              <div class = "medium-3 columns">
                <form action="excel.php?task=renewals" method="post">
  	        		     <input type="submit" name="export_excel" value="DOWNLOAD RENEWALS" class="button radius expand"/>
  	        		</form>
              </div>
          </div>
        <div class = "row custom-row-class">
            <div class = "medium-12 columns">
              <table>
            <tr>
              <th>NCED#</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Active/Revoked</th>
              <th>Date Cert. Issued</th>
              <th> # CEUs </th>
              <th>Last Payment</th>
              <th>Method of Payment</th>
              <th>Amt of Last Payment</th>
              <th>Membership Year</th>
            </tr>
            <tr ng-repeat="item in items.list">

            <td>{{item.ncednum}}</td>
            <td>{{item.fname}}</td>
            <td>{{item.lname}}</td>
            <td>{{item.status}}</td>
            <td>{{item.memstart}}</td>
            <td>{{item.ceus}}</td>
            <td>{{item.paydate}}</td>
            <td>{{item.manner}}</td>
            <td>{{item.payment}}</td>
            <td>{{item.ryear}}</td>
              </tr></table>
            </div>
        </div>
      </div>
  </div>
        <?



}

include("../includes/layouts/footer.php"); ?>
