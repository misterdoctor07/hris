<?php
include('../config.php');
$company=$_GET['company'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$month1=date('F Y',strtotime($startdate));
$month2=date('F Y',strtotime('1 month',strtotime($startdate)));
$month3=date('F Y',strtotime('2 month',strtotime($startdate)));
$month4=date('F Y',strtotime('3 month',strtotime($startdate)));
$month5=date('F Y',strtotime('4 month',strtotime($startdate)));
$month6=date('F Y',strtotime($enddate));

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
	border-collapse:collapse;
	border-spacing:0;
}
.table-scroll th, .table-scroll td {
	padding:5px 10px;
	border:1px solid #000;
	border-collapse: collapse;;
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
th{
	font-size:10px;
}
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
									<td valign="middle">
										<form name="f1" action="viewemployeemasterlist.php" method="get">
											<input type="hidden" name="company" value="<?=$company;?>">
											<input type="text" name="searchme"> <input type="submit" name="submit" value="Search"> <a href="monitorinfractionsummary.php?company=<?=$company;?>&startdate=<?=$startdate;?>&enddate=<?=$enddate;?>&searchme"><button>Refresh</button></a>
										</form>
									</td>
									<!-- <td><button onclick="tableToExcel('printThis','Detailed_Report')" type="button">EXPORT</button></td> -->
								</tr>
							</table>
            <!-- </div> -->
              <!-- <div class="panel-body"> -->
              <div id="table-scroll" class="table-scroll">
								<div id="printThis">
  							<div class="table-wrap">
                    <table class="main-table">
                        <thead>
                          <tr style="font-weight:bold;font-size:12px;">
                            <th colspan="5" rowspan="2" align="center" style='background-color:#fef2cb;' class="fixed-side">
                              Employee Information
                            </th>
                            <th colspan="54" align="center" style='background-color:#ffe598;'>
                              Attendance Summary <?=date('Y',strtotime($startdate));?>
                            </th>
                            <th align="center" style='border-top:0;border-bottom:0;'>
															&nbsp;
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
                            <td align="center" style='background-color:#ffe598;' colspan="9"><?=$month1;?></td>
                            <td align="center" style='background-color:#ffe598;' colspan="9"><?=$month2;?></td>
                            <td align="center" style='background-color:#ffe598;' colspan="9"><?=$month3;?></td>
														<td align="center" style='background-color:#ffe598;' colspan="9"><?=$month4;?></td>
                            <td align="center" style='background-color:#ffe598;' colspan="9"><?=$month5;?></td>
                            <td align="center" style='background-color:#ffe598;' colspan="9"><?=$month6;?></td>
                            <td align="center" style='border-top:0;border-bottom:0;'></td>
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
                          <tr>
                            <th width="1%" align="center" style='background-color:#fef2cb;' class="fixed-side">No.</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Emp No.</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Department</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">Last Name</th>
                            <th align="center" style='background-color:#fef2cb;' class="fixed-side">First Name</th>
                            <th align="center" style='background-color:#ffe598;'>A</td>
                            <th align="center" style='background-color:#ffe598;'>B</td>
                            <th align="center" style='background-color:#ffe598;'>C</td>
														<th align="center" style='background-color:#ffe598;'>D</td>
                            <th align="center" style='background-color:#ffe598;'>E</td>
                            <th align="center" style='background-color:#ffe598;'>F</td>
                            <th align="center" style='background-color:#ffe598;'>OB</td>
                            <th align="center" style='background-color:#ffe598;'>Frq</td>
                            <th align="center" style='background-color:#ffe598;'>I-</td>
                              <th align="center" style='background-color:#ffe598;'>A</td>
                              <th align="center" style='background-color:#ffe598;'>B</td>
                              <th align="center" style='background-color:#ffe598;'>C</td>
  														<th align="center" style='background-color:#ffe598;'>D</td>
                              <th align="center" style='background-color:#ffe598;'>E</td>
                              <th align="center" style='background-color:#ffe598;'>F</td>
                              <th align="center" style='background-color:#ffe598;'>OB</td>
                              <th align="center" style='background-color:#ffe598;'>Frq</td>
                              <th align="center" style='background-color:#ffe598;'>I-</td>
                                <th align="center" style='background-color:#ffe598;'>A</td>
                                <th align="center" style='background-color:#ffe598;'>B</td>
                                <th align="center" style='background-color:#ffe598;'>C</td>
    														<th align="center" style='background-color:#ffe598;'>D</td>
                                <th align="center" style='background-color:#ffe598;'>E</td>
                                <th align="center" style='background-color:#ffe598;'>F</td>
                                <th align="center" style='background-color:#ffe598;'>OB</td>
                                <th align="center" style='background-color:#ffe598;'>Frq</td>
                                <th align="center" style='background-color:#ffe598;'>I-</td>
                                  <th align="center" style='background-color:#ffe598;'>A</td>
                                  <th align="center" style='background-color:#ffe598;'>B</td>
                                  <th align="center" style='background-color:#ffe598;'>C</td>
      														<th align="center" style='background-color:#ffe598;'>D</td>
                                  <th align="center" style='background-color:#ffe598;'>E</td>
                                  <th align="center" style='background-color:#ffe598;'>F</td>
                                  <th align="center" style='background-color:#ffe598;'>OB</td>
                                  <th align="center" style='background-color:#ffe598;'>Frq</td>
                                  <th align="center" style='background-color:#ffe598;'>I-</td>
                                    <th align="center" style='background-color:#ffe598;'>A</td>
                                    <th align="center" style='background-color:#ffe598;'>B</td>
                                    <th align="center" style='background-color:#ffe598;'>C</td>
        														<th align="center" style='background-color:#ffe598;'>D</td>
                                    <th align="center" style='background-color:#ffe598;'>E</td>
                                    <th align="center" style='background-color:#ffe598;'>F</td>
                                    <th align="center" style='background-color:#ffe598;'>OB</td>
                                    <th align="center" style='background-color:#ffe598;'>Frq</td>
                                    <th align="center" style='background-color:#ffe598;'>I-</td>
                                      <th align="center" style='background-color:#ffe598;'>A</td>
                                      <th align="center" style='background-color:#ffe598;'>B</td>
                                      <th align="center" style='background-color:#ffe598;'>C</td>
          														<th align="center" style='background-color:#ffe598;'>D</td>
                                      <th align="center" style='background-color:#ffe598;'>E</td>
                                      <th align="center" style='background-color:#ffe598;'>F</td>
                                      <th align="center" style='background-color:#ffe598;'>OB</td>
                                      <th align="center" style='background-color:#ffe598;'>Frq</td>
                                      <th align="center" style='background-color:#ffe598;'>I-</td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $x=1;
                          mysqli_query($con,"SET NAMES 'utf8'");
													if(!isset($_GET['submit'])){
                            $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$company' ORDER BY ed.department ASC");
													}else{
														$searchme=$_GET['searchme'];
														$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$company' AND (ep.lastname LIKE '%$searchme%' OR ep.firstname LIKE '%$searchme%')");
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

                                echo "<tr style='font-size:12px;'>";
                                echo "<th >$x. </th>";
                                echo "<td align='center' class='fixed-side'>$company[idno]</td>";
                                echo "<td align='center' class='fixed-side'>$department</td>";
                                echo "<td align='center' class='fixed-side'>$company[lastname]</td>";
                                echo "<td align='center' class='fixed-side'>$company[firstname]</td>";
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
                  </div>                    
            </div>

						<script type="text/javascript">
					var tableToExcel = (function() {
					  var uri = 'data:application/vnd.ms-excel;base64,'
					    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
					    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
					    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
					  return function(table, name) {
					    if (!table.nodeType) table = document.getElementById(table)
					    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
					    window.location.href = uri + base64(format(template, ctx))
					  }
					})()
					</script>
