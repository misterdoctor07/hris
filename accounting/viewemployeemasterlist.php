<?php
include('../config.php');
$company=$_GET['company'];
$sqlName=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$company'");
$companyname=mysqli_fetch_array($sqlName);
?>
<style>
.table-scroll {
	position:relative;
	max-width:100%;
	margin:auto;
	overflow:hidden;
	border:1px solid #000;
}
.table-wrap {
	width:100%;
	overflow:auto;
}
.table-scroll table {
	width:100%;
	margin:auto;
	border-collapse:separate;
	border-spacing:0;
}
.table-scroll th, .table-scroll td {
	padding:5px 10px;
	border:1px solid #000;
	background:#fff;
	white-space:nowrap;
	vertical-align:top;
}
.table-scroll thead, .table-scroll tfoot {
	background:#f9f9f9;
}
.clone {
	position:absolute;
	top:0;
	left:0;
	pointer-events:none;
}
.clone th, .clone td {
	visibility:hidden
}
.clone td, .clone th {
	border-color:transparent
}
.clone tbody th {
	visibility:visible;
	color:red;
}
.clone .fixed-side {
	border:1px solid #000;
	background:#eee;
	visibility:visible;
}
.clone thead, .clone tfoot{background:transparent;}
</style>
<script src="lib/jquery/jquery.min.js"></script>
<script>
jQuery(document).ready(function() {
   jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
 });
</script>
<div class="col-lg-12">
            <!-- <div class="content-panel">
              <div class="panel-heading"> -->
							<table border="0">
								<tr>
									<td><h4>EMPLOYEE LIST (<?=$companyname['companyname'];?>)</h4></td>
									<td>
										<form name="f1" action="viewemployeemasterlist.php" method="get">
											<input type="hidden" name="company" value="<?=$company;?>">
											<input type="text" name="searchme"> <input type="submit" name="submit" value="Search"> <a href="viewemployeemasterlist.php?company=<?=$company;?>"><button>Refresh</button></a>
										</form>

									</td>
								</tr>
							</table>
            <!-- </div> -->
              <!-- <div class="panel-body"> -->
              <div id="table-scroll" class="table-scroll">
  <div class="table-wrap">
                    <table class="main-table">
                        <thead>
                          <tr style="font-weight:bold;font-size:12px;">
                            <th colspan="11" align="center" style='background-color:#fef2cb;' class="fixed-side">
                              Employee Information
                            </th>
                            <th colspan="5" align="center" style='background-color:#ffe598;'>
                              Employment Information
                            </th>
                            <th colspan="6" align="center" style='background-color:#ffd965;'>
                              Employee Benefits Information
                            </th>
                            <th colspan="9" align="center" style='background-color:#ffc000;'>
                              Probationary Employee/s Initial Checklist
                            </th>
                            <th colspan="4" align="center" style='background-color:#ffc000;'>
                              Contract Status
                            </th>
                            <th colspan="2" align="center" style='background-color:yellow;'>
                              Referral Information
                            </th>
                          </tr>
                          <tr style="font-weight:bold; font-size:12px;">
                            <th width="1%" align="center" style='background-color:#fef2cb;' class="fixed-side">No.</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Emp No.</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Department</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Last Name</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">First Name</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Middle Name</th>
                            <td align="center" style='background-color:#fef2cb;' class="fixed-side">Nickname</td>
                            <td align="center" style='background-color:#fef2cb;' class="fixed-side">Date of Birth</td>
                            <td align="center" style='background-color:#fef2cb;' class="fixed-side">Civil Status</td>
                            <td align="center" style='background-color:#fef2cb;' class="fixed-side">Sex</td>
                            <td align="center" style='background-color:#fef2cb;' class="fixed-side">Eligibility</td>
                            <td align="center" style='background-color:#ffe598;'>Employment Status</td>
                            <td align="center" style='background-color:#ffe598;'>Date of Hired</td>
                            <td align="center" style='background-color:#ffe598;'>Date of Reg</td>
                            <td align="center" style='background-color:#ffe598;'>Job Title/s</td>
                            <td align="center" style='background-color:#ffe598;'>Work Shift</td>
                            <td align="center" style='background-color:#ffd965;'>Life Insurance Effectivity</td>
                            <td align="center" style='background-color:#ffd965;'>HMO Effectivity</td>
                            <td align="center" style='background-color:#ffd965;'>SSS No.</td>
                            <td align="center" style='background-color:#ffd965;'>TIN No.</td>
                            <td align="center" style='background-color:#ffd965;'>Philhealtd No.</td>
                            <td align="center" style='background-color:#ffd965;'>Pag-ibig No.</td>
                            <td align="center" style='background-color:#ffc000;'>Date Oriented</td>
                            <td align="center" style='background-color:#ffc000;'>Released Temp ID</td>
                            <td align="center" style='background-color:#ffc000;'>Released Permanent ID</td>
                            <td align="center" style='background-color:#ffc000;'>NBI/Police Clearance</td>
                            <td align="center" style='background-color:#ffc000;'>City Healtd Card <?=date('Y');?></td>
                            <td align="center" style='background-color:#ffc000;'>Birtd Certificate</td>
                            <td align="center" style='background-color:#ffc000;'>1x1 Picture</td>
                            <td align="center" style='background-color:#ffc000;'>2x2 Picture</td>
                            <td align="center" style='background-color:#ffc000;'>Status</td>
                            <td align="center" style='background-color:#ffc000;'>Probationary</td>
                            <td align="center" style='background-color:#ffc000;'>Date</td>
                            <td align="center" style='background-color:#ffc000;'>Regular</td>
                            <td align="center" style='background-color:#ffc000;'>Date</td>
                            <td align="center" style='background-color:yellow;'>Referred By</td>
                            <td align="center" style='background-color:yellow;'>Referral Fee Effectivity</td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $x=1;
                          mysqli_query($con,"SET NAMES 'utf8'");
													if(!isset($_GET['submit'])){
                            $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$company'");
													}else{
														$searchme=$_GET['searchme'];
														$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno INNER JOIN employee_referral er ON er.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$company' AND (ep.lastname LIKE '%$searchme%' OR ep.firstname LIKE '%$searchme%' OR er.effectivity LIKE '%$searchme%')");
													}
                            if(mysqli_num_rows($sqlEmployee)>0){
                              while($company=mysqli_fetch_array($sqlEmployee)){
                                if($company['status']=="REGULAR"){
                                  $status="<span class='label label-success label-mini'>$company[status]</span>";
                                }else{
                                  $status="<span class='label label-warning label-mini'>$company[status]</span>";
                                }
                                $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                                if($shift=="12:00 AM - 12:00 AM"){
                                  $shift="";
                                }
                                $sqlJobTitle=mysqli_query($con,"SELECT jobtitle FROM jobtitle WHERE id='$company[designation]'");
                                if(mysqli_num_rows($sqlJobTitle)>0){
                                  $job=mysqli_fetch_array($sqlJobTitle);
                                  $jobtitle=$job['jobtitle'];
                                }else{
                                  $jobtitle="";
                                }
                                $sqlJobTitle=mysqli_query($con,"SELECT department FROM department WHERE id='$company[department]'");
                                if(mysqli_num_rows($sqlJobTitle)>0){
                                  $job=mysqli_fetch_array($sqlJobTitle);
                                  $department=$job['department'];
                                }else{
                                  $department="";
                                }
                                $sqlBenefits=mysqli_query($con,"SELECT * FROM employee_benefits WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlBenefits)>0){
                                  $benefits=mysqli_fetch_array($sqlBenefits);
                                  $insurance=$benefits['insurance'];
                                  $hmo=$benefits['hmo'];
                                  $sss=$benefits['sss'];
                                  $tin=$benefits['tin'];
                                  $phic=$benefits['phic'];
                                  $hdmf=$benefits['hdmf'];
                                }else{
                                  $hmo="";
                                  $sss="";
                                  $tin="";
                                  $phic="";
                                  $hdmf="";
                                }
                                if($company['status']=="PROBATIONARY"){
                                  $colorprob="style='background-color:yellow;'";
                                }else{
                                  $colorprob="";
                                }
                                $sqlChecklist=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlChecklist)>0){
                                  $checklist=mysqli_fetch_array($sqlChecklist);
                                  $dateoriented=$checklist['dateoriented'];
                                  $releasedtempid=$checklist['releasedtempid'];
                                  $releasedpermanentid=$checklist['releasedpermanentid'];
                                  $clearance=$checklist['clearance'];
                                  $healthcard=$checklist['healthcard'];
                                  $birthcertificate=$checklist['birthcertificate'];
                                  $idpicture1=$checklist['idpicture1'];
                                  $idpicture2=$checklist['idpicture2'];
                                  $status=$checklist['status'];
                                  if($status=="Cleared"){
                                    $color="style='background-color:lightgreen;'";
                                  }else{
                                    $color="";
                                  }
                                }else{
                                  $dateoriented="";
                                  $releasedtempid="";
                                  $releasedpermanentid="";
                                  $clearance="";
                                  $healthcard="";
                                  $birthcertificate="";
                                  $idpicture1="";
                                  $idpicture2="";
                                  $status="";
                                  $color="";
                                }
                                $sqlContract=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlContract)>0){
                                  $contract=mysqli_fetch_array($sqlContract);
                                  $probationary=$contract['probationary'];
                                  $probationarydate=$contract['probationarydate'];
                                  $regular=$contract['regular'];
                                  $regulardate=$contract['regulardate'];
                                }else{
                                  $probationary="";
                                  $probationarydate="";
                                  $regular="";
                                  $regulardate="";
                                }
                                $sqlReferral=mysqli_query($con,"SELECT CONCAT(ep.lastname,', ',ep.firstname) AS fullname,er.effectivity FROM employee_profile ep INNER JOIN employee_referral er ON er.referredby=ep.idno WHERE er.idno='$company[idno]'");
                                if(mysqli_num_rows($sqlReferral)>0){
                                  $ref=mysqli_fetch_array($sqlReferral);
                                  $referredby=$ref['fullname'];
                                  if($ref['effectivity']==null){
                                    $referreddate="";
                                  }else{
                                    $referreddate=date('m/d/Y',strtotime($ref['effectivity']));
                                  }

                                }else{
                                  $referredby="";
                                  $referreddate="";
                                }
                                echo "<tr style='font-size:12px;'>";
                                echo "<th >$x. </th>";
                                echo "<td align='center' class='fixed-side'>$company[idno]</td>";
                                echo "<td align='center' class='fixed-side'>$department</td>";
                                echo "<td align='center' class='fixed-side'>$company[lastname]</td>";
                                echo "<td align='center' class='fixed-side'>$company[firstname]</td>";
                                echo "<td align='center' class='fixed-side'>$company[middlename]</td>";
                                echo "<td align='center' class='fixed-side'>$company[nickname]</td>";
                                echo "<td align='center' class='fixed-side'>".date('m/d/Y',strtotime($company['birthdate']))."</td>";
                                echo "<td align='center' class='fixed-side'>$company[civilstatus]</td>";
                                echo "<td align='center' class='fixed-side'>$company[sex]</td>";
                                echo "<td align='center' class='fixed-side'>$company[eligibility]</td>";
                                echo "<td align='center' $colorprob>$company[status]</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($company['dateofhired']))."</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($company['dateofregular']))."</td>";
                                echo "<td align='center'>$jobtitle</td>";
                                echo "<td align='center'>$shift</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($insurance))."</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($hmo))."</td>";
                                echo "<td align='center'>$sss</td>";
                                echo "<td align='center'>$tin</td>";
                                echo "<td align='center'>$phic</td>";
                                echo "<td align='center'>$hdmf</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($dateoriented))."</td>";
                                echo "<td align='center'>$releasedtempid</td>";
                                echo "<td align='center'>$releasedpermanentid</td>";
                                echo "<td align='center'>$clearance</td>";
                                echo "<td align='center'>$healthcard</td>";
                                echo "<td align='center'>$birthcertificate</td>";
                                echo "<td align='center'>$idpicture1</td>";
                                echo "<td align='center'>$idpicture2</td>";
                                echo "<td align='center' $color>$status</td>";
                                echo "<td align='center'>$probationary</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($probationarydate))."</td>";
                                echo "<td align='center'>$regular</td>";
                                echo "<td align='center'>".date('m/d/Y',strtotime($regulardate))."</td>";
                                echo "<td align='center'>$referredby</td>";
                                echo "<td align='center'>$referreddate</td>";
                                echo "</tr>";
                                $x++;
                              }
                            }else{
                              echo "<tr><th colspan='37' align='center'>No record found!</th></tr>";
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                    <!-- <div style="overflow-x:auto;margin-left:50.3%; width:150%;">
                    <?php
                    $company=$_GET['company'];
                    $sqlName=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$company'");
                    $companyname=mysqli_fetch_array($sqlName);
                    ?>
                    <table border="1" cellpadding="1" cellspacing="1">
                        <thead>
                          <tr style="font-weight:bold;font-size:12px;">
                            <th colspan="5" align="center" style='background-color:#ffe598;'>
                              Employment Information
                            </th>
                            <th colspan="6" align="center" style='background-color:#ffd965;'>
                              Employee Benefits Information
                            </th>
                            <th colspan="9" align="center" style='background-color:#ffc000;'>
                              Probationary Employee/s Initial Checklist
                            </th>
                            <th colspan="4" align="center" style='background-color:#ffc000;'>
                              Contract Status
                            </th>
                            <th colspan="2" align="center" style='background-color:yellow;'>
                              Referral Information
                            </th>
                          </tr>
                          <tr style="font-weight:bold; font-size:12px;">
                            <th align="center" style='background-color:#ffe598;'>Employment Status</th>
                            <th align="center" style='background-color:#ffe598;'>Date of Hired</th>
                            <th align="center" style='background-color:#ffe598;'>Date of Reg</th>
                            <th align="center" style='background-color:#ffe598;'>Job Title/s</th>
                            <th align="center" style='background-color:#ffe598;'>Work Shift</th>
                            <th align="center" style='background-color:#ffd965;'>Life Insurance Effectivity</th>
                            <th align="center" style='background-color:#ffd965;'>HMO Effectivity</th>
                            <th align="center" style='background-color:#ffd965;'>SSS No.</th>
                            <th align="center" style='background-color:#ffd965;'>TIN No.</th>
                            <th align="center" style='background-color:#ffd965;'>Philhealth No.</th>
                            <th align="center" style='background-color:#ffd965;'>Pag-ibig No.</th>
                            <th align="center" style='background-color:#ffc000;'>Date Oriented</th>
                            <th align="center" style='background-color:#ffc000;'>Released Temp ID</th>
                            <th align="center" style='background-color:#ffc000;'>Released Permanent ID</th>
                            <th align="center" style='background-color:#ffc000;'>NBI/Police Clearance</th>
                            <th align="center" style='background-color:#ffc000;'>City Health Card <?=date('Y');?></th>
                            <th align="center" style='background-color:#ffc000;'>Birth Certificate</th>
                            <th align="center" style='background-color:#ffc000;'>1x1 Picture</th>
                            <th align="center" style='background-color:#ffc000;'>2x2 Picture</th>
                            <th align="center" style='background-color:#ffc000;'>Status</th>
                            <th align="center" style='background-color:#ffc000;'>Probationary</th>
                            <th align="center" style='background-color:#ffc000;'>Date</th>
                            <th align="center" style='background-color:#ffc000;'>Regular</th>
                            <th align="center" style='background-color:#ffc000;'>Date</th>
                            <th align="center" style='background-color:yellow;'>Referred By</th>
                            <th align="center" style='background-color:yellow;'>Referral Fee Effectivity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $x=1;
                          mysqli_query($con,"SET NAMES 'utf8'");
                            $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$company'");
                            if(mysqli_num_rows($sqlEmployee)>0){
                              while($company=mysqli_fetch_array($sqlEmployee)){
                                if($company['status']=="REGULAR"){
                                  $status="<span class='label label-success label-mini'>$company[status]</span>";
                                }else{
                                  $status="<span class='label label-warning label-mini'>$company[status]</span>";
                                }
                                $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                                if($shift=="12:00 AM - 12:00 AM"){
                                  $shift="";
                                }
                                $sqlJobTitle=mysqli_query($con,"SELECT jobtitle FROM jobtitle WHERE id='$company[designation]'");
                                if(mysqli_num_rows($sqlJobTitle)>0){
                                  $job=mysqli_fetch_array($sqlJobTitle);
                                  $jobtitle=$job['jobtitle'];
                                }else{
                                  $jobtitle="";
                                }
                                $sqlJobTitle=mysqli_query($con,"SELECT department FROM department WHERE id='$company[department]'");
                                if(mysqli_num_rows($sqlJobTitle)>0){
                                  $job=mysqli_fetch_array($sqlJobTitle);
                                  $department=$job['department'];
                                }else{
                                  $department="";
                                }
                                $sqlBenefits=mysqli_query($con,"SELECT * FROM employee_benefits WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlBenefits)>0){
                                  $benefits=mysqli_fetch_array($sqlBenefits);
                                  $insurance=$benefits['insurance'];
                                  $hmo=$benefits['hmo'];
                                  $sss=$benefits['sss'];
                                  $tin=$benefits['tin'];
                                  $phic=$benefits['phic'];
                                  $hdmf=$benefits['hdmf'];
                                }else{
                                  $hmo="";
                                  $sss="";
                                  $tin="";
                                  $phic="";
                                  $hdmf="";
                                }
                                if($company['status']=="PROBATIONARY"){
                                  $colorprob="style='background-color:yellow;'";
                                }else{
                                  $colorprob="";
                                }
                                $sqlChecklist=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlChecklist)>0){
                                  $checklist=mysqli_fetch_array($sqlChecklist);
                                  $dateoriented=$checklist['dateoriented'];
                                  $releasedtempid=$checklist['releasedtempid'];
                                  $releasedpermanentid=$checklist['releasedpermanentid'];
                                  $clearance=$checklist['clearance'];
                                  $healthcard=$checklist['healthcard'];
                                  $birthcertificate=$checklist['birthcertificate'];
                                  $idpicture1=$checklist['idpicture1'];
                                  $idpicture2=$checklist['idpicture2'];
                                  $status=$checklist['status'];
                                  if($status=="Cleared"){
                                    $color="style='background-color:lightgreen;'";
                                  }else{
                                    $color="";
                                  }
                                }else{
                                  $dateoriented="";
                                  $releasedtempid="";
                                  $releasedpermanentid="";
                                  $clearance="";
                                  $healthcard="";
                                  $birthcertificate="";
                                  $idpicture1="";
                                  $idpicture2="";
                                  $status="";
                                  $color="";
                                }
                                $sqlContract=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$company[idno]'");
                                if(mysqli_num_rows($sqlContract)>0){
                                  $contract=mysqli_fetch_array($sqlContract);
                                  $probationary=$contract['probationary'];
                                  $probationarydate=$contract['probationarydate'];
                                  $regular=$contract['regular'];
                                  $regulardate=$contract['regulardate'];
                                }else{
                                  $probationary="";
                                  $probationarydate="";
                                  $regular="";
                                  $regulardate="";
                                }
                                $sqlReferral=mysqli_query($con,"SELECT CONCAT(ep.lastname,', ',ep.firstname) AS fullname,er.effectivity FROM employee_profile ep INNER JOIN employee_referral er ON er.referredby=ep.idno WHERE er.idno='$company[idno]'");
                                if(mysqli_num_rows($sqlReferral)>0){
                                  $ref=mysqli_fetch_array($sqlReferral);
                                  $referredby=$ref['fullname'];
                                  if($ref['effectivity']==null){
                                    $referreddate="";
                                  }else{
                                    $referreddate=date('m/d/Y',strtotime($ref['effectivity']));
                                  }

                                }else{
                                  $referredby="";
                                  $referreddate="";
                                }
                                echo "<tr style='font-size:12px;'>";
                                echo "<th align='center' $colorprob>$company[status]</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($company['dateofhired']))."</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($company['dateofregular']))."</th>";
                                echo "<th align='center'>$jobtitle</th>";
                                echo "<th align='center'>$shift</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($insurance))."</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($hmo))."</th>";
                                echo "<th align='center'>$sss</th>";
                                echo "<th align='center'>$tin</th>";
                                echo "<th align='center'>$phic</th>";
                                echo "<th align='center'>$hdmf</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($dateoriented))."</th>";
                                echo "<th align='center'>$releasedtempid</th>";
                                echo "<th align='center'>$releasedpermanentid</th>";
                                echo "<th align='center'>$clearance</th>";
                                echo "<th align='center'>$healthcard</th>";
                                echo "<th align='center'>$birthcertificate</th>";
                                echo "<th align='center'>$idpicture1</th>";
                                echo "<th align='center'>$idpicture2</th>";
                                echo "<th align='center' $color>$status</th>";
                                echo "<th align='center'>$probationary</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($probationarydate))."</th>";
                                echo "<th align='center'>$regular</th>";
                                echo "<th align='center'>".date('m/d/Y',strtotime($regulardate))."</th>";
                                echo "<th align='center'>$referredby</th>";
                                echo "<th align='center'>$referreddate</th>";

                                echo "</tr>";
                                $x++;
                              }
                            }else{
                              echo "<tr><th colspan='37' align='center'>No record found!</th></tr>";
                            }
                          ?>
                        </tbody>
                      </table>
                    </div> -->
                <!-- </div> -->
            <!-- </div> -->
            </div>
