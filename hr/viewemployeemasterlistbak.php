<?php
$company=$_GET['company'];
$sqlName=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$company'");
$companyname=mysqli_fetch_array($sqlName);
?>
<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-user"></i> EMPLOYEE LIST (<?=$companyname['companyname'];?>)</h4>
            </div>
              <div class="panel-body">
              <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Emp ID</th>
                      <th>Employee Name</th>                      
                      <th>Date of Birth</th>
                      <th>Job Title</th>
                      <th>Department</th>
                      <th>Status</th>
                      <th>Date Hired</th>
                      <th>Shift</th>
                      <th>Work Area</th>                                            
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
                          $datehired=date('m/d/Y',strtotime($company['dateofhired']));
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
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                            
                            echo "<td>".date('M-d-Y',strtotime($company['birthdate']))."</td>";
                            echo "<td>$jobtitle</td>";
                            echo "<td>$department</td>";
                            echo "<td>$status</td>";
                            echo "<td>$datehired</td>";
                            echo "<td>$shift</td>";
                            echo "<td align='center'>$company[location]</td>";                                                        
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
