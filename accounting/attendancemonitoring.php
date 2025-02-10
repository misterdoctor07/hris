<?php
$comp=$_GET['company'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
?>
        <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?monitorattendance"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-user"></i> EMPLOYEE LIST (<?=$comp?>) <button onclick="tableToExcel('printThis','Detailed_Report')" class="btn btn-success" style="float:right;"><i class="fa fa-download"> </i> EXPORT</button></h4>
            </div>
              <div class="panel-body" id="printThis">
                <b>Company: <?=$comp;?><br />
                Date Range: <?=date('m/d/Y',strtotime($startdate));?> - <?=date('m/d/Y',strtotime($enddate));?></b>
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th width="3%" rowspan="2" style="vertical-align:middle;">No.</th>
                      <th  rowspan="2" style="vertical-align:middle;">Emp ID</th>
                      <th  rowspan="2" style="vertical-align:middle;">Employee Name</th>
                      <th  rowspan="2" style="vertical-align:middle;">Status</th>
                      <th  rowspan="2" style="vertical-align:middle;">Department</th>
                      <th  rowspan="2" style="vertical-align:middle;">Shift</th>
                      <th  rowspan="2" style="vertical-align:middle;">Work Area</th>
                      <th  rowspan="2" style="vertical-align:middle;">Date</th>
                      <th colspan="2" align="center">Shift 1</th>
                      <th colspan="2" align="center">Shift 2</th>
                    </tr>
                    <tr>
                        <th align="center">Login</th>
                        <th align="center">Lunch out</th>
                        <th align="center">Lunch in</th>
                        <th align="center">Logout</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND company='$comp' ORDER BY ep.lastname ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          if($company['status']=="REGULAR"){
                            $status="<span class='label label-success label-mini'>$company[status]</span>";
                          }else{
                            $status="<span class='label label-warning label-mini'>$company[status]</span>";
                          }
                          $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$company[department]'");
                          if(mysqli_num_rows($sqlDepartment)>0){
                            $dept=mysqli_fetch_array($sqlDepartment);
                            $department=$dept['department'];
                          }else{
                            $department="";
                          }
                          $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                          $datehired=date('m/d/Y',strtotime($company['dateofhired']));
                          $sqlAttendance=mysqli_query($con,"SELECT * FROM attendance WHERE logindate BETWEEN '$startdate' AND '$enddate' AND idno='$company[idno]'ORDER BY logindate ASC");
                                $login1="";
                                $logout1="";
                                $login2="";
                                $logout2="";
                                $datearray="";
                            if(mysqli_num_rows($sqlAttendance)>0){
                                while($attend=mysqli_fetch_array($sqlAttendance)){
                                  $datearray .=date('m/d/Y',strtotime($attend['logindate']))."<br>";
                                  $login1 .=$attend['loginam']."<br>";
                                  $logout1 .=$attend['logoutam']."<br>";
                                  $login2 .=$attend['loginpm']."<br>";
                                  $logout2 .=$attend['logoutpm']."<br>";
                                }
                            }else{
                                $login1="";
                                $logout1="";
                                $login2="";
                                $logout2="";
                                $datearray="";
                            }
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                            echo "<td>$status</td>";
                            echo "<td>$department</td>";
                            echo "<td>$shift</td>";
                            echo "<td align='center'>$company[location]</td>";
                            echo "<td align='center'>$datearray</td>";
                            echo "<td align='center'>$login1</td>";
                            echo "<td align='center'>$logout1</td>";
                            echo "<td align='center'>$login2</td>";
                            echo "<td align='center'>$logout2</td>";
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
            </div>
