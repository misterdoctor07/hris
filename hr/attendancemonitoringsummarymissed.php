<style>
  .table-scroll {
    position:relative;
    width:100%;
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
    margin-top:auto;
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
  .btn {
    padding: 10px 15px;
    background-color: #13A14D;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #06753a;
}
</style>

<script src="lib/jquery/jquery.min.js"></script>
<script>
  jQuery(document).ready(function() {
    jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
  });

  function tableToExcel(tableID, filename = '') {
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML;

            // Create a download link
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            // Set the file name
            filename = filename ? filename + '.xls' : 'attendance_summary.xls';

            // Create a Blob with the table HTML
            var blob = new Blob([tableHTML], {
                type: dataType
            });

            // Create a URL for the Blob
            var url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.download = filename;

            // Trigger the download
            downloadLink.click();

            // Clean up
            document.body.removeChild(downloadLink);
        }
</script>

<?php
include('../config.php');
$dept=$_GET["dept"];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$sqlCompany=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$dept'");
$comp=mysqli_fetch_array($sqlCompany);
?>
        <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
                <p style="float:left;font-size:24px; text-align:center; font-weight:bold; margin-top:-1px; text-transform:uppercase; border:2px solid; width:20%; background:#ffcccc;"><?=$comp['companyname'];?><br /><font style="font-size:20px; font-weight:bold;"><?=date('F Y',strtotime($startdate));?></font></p>
                <table border="1" cellspacing="1" cellpadding="1" style="float:left; margin-left:100px; font-size:12px; font-weight:bold;" width="60%">
                    <tr style="background-color:#ff7c80;">
                      <td align="center">INCIDENT</td>
                      <td align="center" width="5%">POINTS</td>
                      <td align="center" width="5%">CODE</td>
                      <td align="center">INCIDENT</td>
                      <td align="center" width="5%">POINTS</td>
                      <td align="center" width="5%">CODE</td>
                      <td align="center">INCIDENT</td>
                      <td align="center" width="5%">POINTS</td>
                      <td align="center" width="5%">CODE</td>
                    </tr>
                    <tr>
                      <td>2 mins over-break (lunch)</td>
                      <td align="center">0.2</td>
                      <td align="center">L</td>
                      <td>Forgot to clock in (first shift) and failed to submit form and Over-break</td>
                      <td align="center">0.4</td>
                      <td align="center">B-</td>
                      <td>Forgot to clock in (first shift) and failed to submit form</td>
                      <td align="center">0.2</td>
                      <td align="center">I-</td>
                    </tr>
                    <tr>
                      <td>Forgot to clock in/out (Lunch) w/ non-work related reason</td>
                      <td align="center">0.2</td>
                      <td align="center">L-</td>
                      <td>Forgot to clock in (first shift) and failed to submit form and Missed Out/In (Lunch)</td>
                      <td align="center">0.4</td>
                      <td align="center">M</td>
                      <td colspan="3"></td>
                    </tr>
                  </table>
            </div>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <div style="float:left;">
								<form action="attendancemonitoringsummarymissed.php" method="GET">
									<input type="hidden" name="dept" value="<?=$dept;?>">
									<input type="hidden" name="startdate" value="<?=$startdate;?>">
									<input type="hidden" name="enddate" value="<?=$enddate;?>">
									<input type="text" name="searchme"> <input type="submit" name="submit" value="Search"> <a href="attendancemonitoringsummarymissed.php?dept=<?=$dept;?>&startdate=<?=$startdate;?>&enddate=<?=$enddate;?>"><button type="button">Refresh</button></a>
								</form>
						</div>
            <div style="float:right; margin-bottom: 20px;">
                <form>
                    <input type="hidden" name="dept" value="<?=$dept;?>">
                    <input type="hidden" name="startdate" value="<?=$startdate;?>">
                    <input type="hidden" name="enddate" value="<?=$enddate;?>">
                    <button onclick="tableToExcel('attendanceTable', 'Attendance_Summary_Missed_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                </form>
            </div>
              <br>
              <br>
            <div id="table-scroll" class="table-scroll">
            <div class="table-wrap">
                <table class="main-table">
                  <thead>
                    <tr>
                      <th colspan="3" align="center" class="fixed-side">
                        Employee Information
                      </th>
                      <?php
                      $month=date('m',strtotime($startdate));
                      $year=date('Y',strtotime($startdate));

                      $datearray=date('d',strtotime($enddate));
                      for($i=1;$i<=$datearray;$i++){
                        ?>
                        <th align="center" width="2%"><?=$i;?></th>
                        <?php
                      }
                      ?>
                       <th rowspan="2" width="1.5%" style="border-top:0; border-bottom:0;">
                        &nbsp;
                      </th>
                      <th width="1.5%" rowspan="2">
                        M
                      </th>
                      <th width="1.5%" rowspan="2">
                        L
                      </th>
                      <th width="1.5%" rowspan="2">
                        B-
                      </th>
                      <th width="1.5%" rowspan="2">
                        Total
                      </th>
                      <th width="1.5%" rowspan="2">
                        Freq(-)
                      </th>
                      <th width="1.5%" rowspan="2">
                        I-
                      </th>
                    </tr>
                    <tr>
                      <th width="1%" style="vertical-align:middle;" class="fixed-side">No.</th>
                      <th  style="vertical-align:middle;" class="fixed-side">Employee Name</th>
                      <th  style="vertical-align:middle;" class="fixed-side">Department</th>
                      <?php
                      $month=date('m',strtotime($startdate));
                      $year=date('Y',strtotime($startdate));

                      $datearray=date('d',strtotime($enddate));
                      for($i=1;$i<=$datearray;$i++){
                        $rundate=$year."-".$month."-".$i;
                        $day=date('D',strtotime($rundate));
                        ?>
                        <th align="center" width="1%"><?=$day;?></th>
                        <?php
                      }
                      ?>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                    if(isset($_GET['submit'])){
											$dept=$_GET['dept'];
											$startdate=$_GET['startdate'];
											$enddate=$_GET['enddate'];
											$searchme=$_GET['searchme'];
											$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep 
                      LEFT JOIN employee_details ed ON ed.idno=ep.idno 
                      WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$dept' AND (ep.lastname LIKE '%$searchme%' OR ep.firstname LIKE '%$searchme%') 
                      ORDER BY ed.department ASC, ep.lastname ASC");
										}else{
											$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep 
                      LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$dept' 
                      ORDER BY ed.department ASC, ep.lastname ASC");
										}
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                          $datehired=date('m/d/Y',strtotime($company['dateofhired']));
                          $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$company[department]'");
                          if(mysqli_num_rows($sqlDepartment)>0){
                            $d=mysqli_fetch_array($sqlDepartment);
                            $department=$d['department'];
                          }else{
                            $department="";
                          }
                          echo "<tr>";
                                echo "<td class='fixed-side'>$x.</td>";
                                echo "<td class='fixed-side'>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                                echo "<td align='center' class='fixed-side'>$department</td>";
                                $month=date('m',strtotime($startdate));
                                $year=date('Y',strtotime($startdate));

                                $datearray=date('d',strtotime($enddate));
                                $m="";$l="";$b="";$il="";$li="";
                                for($i=1;$i<=$datearray;$i++){
                              $rundate=$year."-".$month."-".$i;
                              $day=date('D',strtotime($rundate));
                              if($day=="Sun"){
                                $nowork="background-color:gray;";
                              }else{
                                $nowork="";
                              }
                              $sqlAttendance = mysqli_query($con, "SELECT * FROM points a LEFT JOIN attendance o ON a.idno = o.idno WHERE a.logindate='$rundate' AND a.idno='$company[idno]'");
                                  if (mysqli_num_rows($sqlAttendance) > 0) {
                                      while ($rem = mysqli_fetch_array($sqlAttendance)) {
                                          $offense = $rem['offense'];
                                          if ($offense == 19) {
                                              $l++;
                                              $remarks = str_replace('Code ', '', $remarks) . "L";
                                              $color = "background-color:#f4c7c3;";
                                              break; // Exit the loop if you only need to find the first occurrence
                                          }
                                          elseif ($offense == 22) {
                                            $il++;
                                            $remarks = str_replace('Code ', '', $remarks) . "I-";
                                            $color = "background-color:#f4c7c3;";
                                            break; // Exit the loop if you only need to find the first occurrence
                                        }
                                        elseif ($offense == 66) {
                                          $m++;
                                          $remarks = str_replace('Code ', '', $remarks) . "M";
                                          $color = "background-color:#f4c7c3;";
                                          break; // Exit the loop if you only need to find the first occurrence
                                      }
                                      elseif ($offense == 65) {
                                        $b++;
                                        $remarks = str_replace('Code ', '', $remarks) . "B-";
                                        $color = "background-color:#f4c7c3;";
                                        break; // Exit the loop if you only need to find the first occurrence
                                    }
                                      
                                  
                             // elseif($remarks=="Code I-"){
                              //     $il++;
                              //     $remarks=str_replace('Code ','',$remarks)."";
                              //     $color="background-color:#f4c7c3;";
                              //   }elseif($offense == 19){
                              //       $l++;
                              //       $remarks=str_replace('Code ','',$remarks)."L";
                              //       $color="background-color:#f4c7c3;";
                              //   }elseif($remarks=="Code M"){
                              //     $l++;
                              //     $remarks=str_replace('Code ','',$remarks)."";
                              //     $color="background-color:#f4c7c3;";
                              // }
                              //   elseif($remarks=="Code B-"){
                              //     $m++;
                              //     $remarks=str_replace('Code ','',$remarks)."";
                              //     $color="background-color:#f4c7c3;";
                              // }
                                else{
                                
                                  $color="";
                                }
                              }
                              }else{
                                $remarks="";
                                $color="";
                              }
                              ?>
                              <td align="center" style="font-size:12px;<?=$color;?><?=$nowork;?>"><?=$remarks;?></td>
                              <?php
                            }
                            echo "<td style='border-top:0; border-bottom:0;'></td>";
                            echo "<td align='center'>$m</td>";
                            echo "<td align='center'>$l</td>";
                            echo "<td align='center'>$b</td>";
                            echo "<td align='center'></td>";
                            echo "<td align='center'></td>";
                            echo "<td align='center'>$il</td>";
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
                </div>
            </div>
