<?php
$comp=$_GET["company"];
$year=$_GET['year'];
?>
        <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?movement"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-user"></i> MOVEMENT TRACKER (<?=$comp;?>) as of <?=$year;?></h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%" rowspan="2" style="vertical-align:middle;">No.</th>                      
                      <th  rowspan="2" style="vertical-align:middle;">Employee Name</th>                      
                      <th  colspan="2" align="center">Company</th>                      
                      <th  colspan="2" align="center">Department</th>
                      <th  colspan="2" align="center">Job Position</th>                        
                      <th colspan="2" align="center">Shift</th>                      
                      <th  rowspan="2" style="vertical-align:middle;">Effectivity</th>
                    </tr>
                    <tr>
                        <th align="center">From</th>
                        <th align="center">To</th>
                        <th align="center">From</th>
                        <th align="center">To</th>
                        <th align="center">From</th>
                        <th align="center">To</th>
                        <th align="center">From</th>
                        <th align="center">To</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' ORDER BY ep.lastname ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                            $idno=$company['idno'];
                            $display=1;
                                $companyfrom="-";
                                $companyto="-";
                                $departmentfrom="-";
                                $departmentto="-";
                                $jobfrom="-";
                                $jobto="-";
                                $shiftfrom="";
                                $shiftto="";
                                $effectivity="";
                            $sqlMovement=mysqli_query($con,"SELECT * FROM movement_tracker WHERE idno='$idno' AND YEAR(addeddatetime)='$year' AND effectivitydate <> '0000-00-00'");
                            if(mysqli_num_rows($sqlMovement)>0){
                                $row=mysqli_fetch_array($sqlMovement);
                                $companyfrom=$row['companyfrom'];
                                $companyto=$row['companyto'];
                                $departmentfrom=$row['departmentfrom'];
                                $departmentto=$row['departmentto'];
                                $jobfrom=$row['jobfrom'];
                                $jobto=$row['jobto'];
                                $shiftfrom=$row['shiftfrom'];
                                $shiftto=$row['shiftto'];
                                $effectivity=$row['effectivitydate'];
                                if($companyfrom==$comp || $companyfrom==''){
                                    if($company['company']==$comp){
                                        $display=1;
                                    }else if($company['company']!=$comp && $companyfrom==$comp){
                                        $display=1;
                                    }else{
                                        $display=0;
                                    }
                                }else{
                                    $display=0;
                                }
                            }else{
                                $display=0;
                            }
                            $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$departmentfrom'");
                            if(mysqli_num_rows($sqlDepartment)>0){
                                $dept=mysqli_fetch_array($sqlDepartment);
                                $departmentfrom=$dept['department'];
                            }else{
                                $departmentfrom="-";
                            }
                            $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$departmentto'");
                            if(mysqli_num_rows($sqlDepartment)>0){
                                $dept=mysqli_fetch_array($sqlDepartment);
                                $departmentto=$dept['department'];
                            }else{
                                $departmentto="-";
                            }
                            $sqlDepartment=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobfrom'");
                            if(mysqli_num_rows($sqlDepartment)>0){
                                $dept=mysqli_fetch_array($sqlDepartment);
                                $jobfrom=$dept['jobtitle'];
                            }else{
                                $jobfrom="-";
                            }
                            $sqlDepartment=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobto'");
                            if(mysqli_num_rows($sqlDepartment)>0){
                                $dept=mysqli_fetch_array($sqlDepartment);
                                $jobto=$dept['jobtitle'];
                            }else{
                                $jobto="-";
                            }
                            if($display==1){                                
                                if($shiftfrom != ""){
                                    $sfrom=explode('-',$shiftfrom);
                                    $shift11=date('h:i A',strtotime($sfrom[0]));
                                    $shift12=date('h:i A',strtotime($sfrom[1]));
                                    $shift1=$shift11." - ".$shift12;
                                }else{
                                    $shift1="-";
                                }                                
                                if($shiftto != ""){
                                    $sto=explode('-',$shiftto);
                                    $shift21=date('h:i A',strtotime($sto[0]));
                                    $shift22=date('h:i A',strtotime($sto[1]));
                                    $shift2=$shift21." - ".$shift22;
                                }else{
                                    $shift2="-";
                                }

                          echo "<tr>";
                            echo "<td>$x.</td>";                            
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                            
                            echo "<td align='center'>$companyfrom</td>";                            
                            echo "<td align='center'>$companyto</td>";
                            echo "<td align='center'>$departmentfrom</td>";
                            echo "<td align='center'>$departmentto</td>";
                            echo "<td align='center'>$jobfrom</td>";
                            echo "<td align='center'>$jobto</td>";
                            echo "<td align='center'>$shift1</td>";
                            echo "<td align='center'>$shift2</td>";
                            echo "<td align='center'>".date('m/d/y',strtotime($effectivity))."</td>";
                          echo "</tr>";
                          $x++;
                            }
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
