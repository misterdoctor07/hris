<?php
$comp=$_GET['company'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$sqlName=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$comp'");
$companyname=mysqli_fetch_array($sqlName);
?>
        <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?monitorattendance"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-user"></i> EMPLOYEE LIST (<?=$companyname['companyname'];?>)</h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Emp ID</th>
                      <th>Employee Name</th>                      
                      <th>Status</th>                      
                      <th>Shift</th>
                      <th>Work Area</th>  
                      <th>Action</th>                    
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$comp'");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          if($company['status']=="REGULAR"){
                            $status="<span class='label label-success label-mini'>$company[status]</span>";
                          }else{
                            $status="<span class='label label-warning label-mini'>$company[status]</span>";
                          }
                          $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                          $datehired=date('m/d/Y',strtotime($company['dateofhired']));
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                            
                            echo "<td>$status</td>";                            
                            echo "<td>$shift</td>";
                            echo "<td align='center'>$company[location]</td>";
                            echo "<td align='center'><a href='?attendancemonitoring&view&company=$comp&startdate=$startdate&enddate=$enddate&idno=$company[idno]' title='View Attendance'><i class='fa fa-eye fa-2x'></i></a></td>";
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
            <?php
            if(isset($_GET['view'])){
                $comp=$_GET['company'];
                $startdate=$_GET['startdate'];
                $enddate=$_GET['enddate'];
                $idno=$_GET['idno'];
                $sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
                $profile=mysqli_fetch_array($sqlProfile);
            ?>
            <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?attendancemonitoring&company=<?=$comp;?>&startdate=<?=$startdate;?>&enddate=<?=$enddate;?>"><i class="fa fa-times"></i></a> | <i class="fa fa-user"></i> <?=$profile['lastname'];?>, <?=$profile['firstname'];?></h4>
            </div>
            <div class="panel-body">                                                            
                <table width="100%" class="table table-bordered">
                    <tr>
                        <td rowspan="2" align="center" style="vertical-align:middle;">DATE</td>
                        <td colspan="2" align="center">1ST SHIFT</td>
                        <td colspan="2" align="center">2ND SHIFT</td>
                    </tr>
                    <tr>                        
                        <td align="center">LOGIN</td>
                        <td align="center">LOGOUT</td>
                        <td align="center">LOGIN</td>
                        <td align="center">LOGOUT</td>
                    </tr>
                    <?php
                    $sqlAttendance=mysqli_query($con,"SELECT * FROM attendance WHERE logindate BETWEEN '$startdate' AND '$enddate' AND idno='$idno'");
                    if(mysqli_num_rows($sqlAttendance)>0){
                        while($attend=mysqli_fetch_array($sqlAttendance)){
                            echo "<tr>";
                                echo "<td align='center'>".date('m-d-Y',strtotime($attend['logindate']))."</td>";
                                echo "<td align='center'>$attend[loginam]</td>";
                                echo "<td align='center'>$attend[logoutam]</td>";
                                echo "<td align='center'>$attend[loginpm]</td>";
                                echo "<td align='center'>$attend[logoutpm]</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>
            </div>
            </div>
            <?php
            }
            ?>
