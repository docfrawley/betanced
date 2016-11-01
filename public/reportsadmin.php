<?php require_once("../includes/initialize.php"); ?>
<? include("../includes/layouts/header.php");

if (isset($_SESSION['ncedadmin']) || isset($_SESSION['memberadmin'])) {
  ?><div ng-app="GetPagesApp"><?
  $all_emails = new email_object();
  $member_admin = new memadmin();
  $num_pages = $member_admin->get_num_pages(25);
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
      <div ng-controller="SetPagesController as items"
      ng-init="items.pageInit('<?php echo $num_pages; ?>')" >
        <div class = "row custom-row-class"  >

              <div class = "medium-9 columns" >
                <div class="row">
                  <div class = "small-4 columns text-right" >
                      <button class="button radius tiny">
                        <i class="fi-previous" ng-mousedown="items.firstPage();"
                          ng-mouseup="items.getNewPage(items.page);"></i>
                        </button>
                      <button class="button radius tiny"><i class="fi-arrow-left"
                          ng-mousedown="items.decreasePage();"
                          ng-mouseup="items.getNewPage(items.page);"></i>
                        </button>
                  </div>
                  <div class="small-4 columns">
                    <select ng-model="items.page"
                    ng-change="items.getNewPage(items.page);"><?
                    for ($x=1; $x<=$num_pages; $x++){ ?>
                      <option value="<? echo $x; ?>"><? echo $x; ?></option>
                    <? } ?>
                    </select>
                  </div>
                  <div class = "small-4 columns" >
                    <button class="button radius tiny">
                      <i class="fi-arrow-right" ng-mousedown="items.increasePage();"
                        ng-mouseup="items.getNewPage(items.page);"></i>
                      </button>
                    <button class="button radius tiny"><i class="fi-next"
                        ng-mousedown="items.lastPage();"
                        ng-mouseup="items.getNewPage(items.page);"></i>
                      </button>
                  </div>
              </div>
            </div>


              <div class = "medium-3 columns">
                <form action="excel.php?task=renewals" method="post">
  	        		     <input type="submit" name="export_excel"
                     value="DOWNLOAD RENEWALS" class="button radius expand"/>
  	        		</form>
              </div>
          </div>
        <div class = "row custom-row-class">
            <div class="small-12 text-center columns">
              <h4>Click on an NCED number to see individual profile</h4>
            </div>
            <div class = "medium-12 columns">
              <table>
            <thead>
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
            </thead>
            <tr ng-repeat="item in items.list">

            <td><a href="ncedadmin.php?ncednumberL={{item.ncednum}}">{{item.ncednum}}</a></td>
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

        <div class = "row custom-row-class"  >

              <div class = "medium-9 columns" >
                <div class="row">
                  <div class = "small-4 columns text-right" >
                      <button class="button radius tiny">
                        <i class="fi-previous" ng-mousedown="items.firstPage();"
                          ng-mouseup="items.getNewPage(items.page);"></i>
                        </button>
                      <button class="button radius tiny"><i class="fi-arrow-left"
                          ng-mousedown="items.decreasePage();"
                          ng-mouseup="items.getNewPage(items.page);"></i>
                        </button>
                  </div>
                  <div class="small-4 columns">
                    <select ng-model="items.page"
                    ng-change="items.getNewPage(items.page);"><?
                    for ($x=1; $x<=$num_pages; $x++){ ?>
                      <option value="<? echo $x; ?>"><? echo $x; ?></option>
                    <? } ?>
                    </select>
                  </div>
                  <div class = "small-4 columns" >
                    <button class="button radius tiny">
                      <i class="fi-arrow-right" ng-mousedown="items.increasePage();"
                        ng-mouseup="items.getNewPage(items.page);"></i>
                      </button>
                    <button class="button radius tiny"><i class="fi-next"
                        ng-mousedown="items.lastPage();"
                        ng-mouseup="items.getNewPage(items.page);"></i>
                      </button>
                  </div>
              </div>
            </div>


              <div class = "medium-3 columns">
                <form action="excel.php?task=renewals" method="post">
                     <input type="submit" name="export_excel"
                     value="DOWNLOAD RENEWALS" class="button radius expand"/>
                </form>
              </div>
          </div>
      </div>
  </div>
        <?



}

include("../includes/layouts/footer.php"); ?>
