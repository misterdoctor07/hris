<?php
$comp=$_GET['company'];
?>
        <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?infractionmonitoring"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> INFRACTION MONITORING<div style="float:right;"></h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Emp ID</th>
                      <th>Employee Name</th>
                      <th>Team</th>
                      <th>Company</th>                      
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlEmployee=mysqli_query($con,"SELECT ep.* FROM employee_profile ep INNER JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.company='$comp' ORDER BY ep.lastname ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          $idno=$company['idno'];
                          $lastname=$company['lastname'];
                          $firstname=$company['firstname'];
                          $middlename=$company['middlename'];
                          $suffix=$company['suffix'];                          
                          $sqlDept=mysqli_query($con,"SELECT d.department FROM department d LEFT JOIN employee_details ed ON ed.department=d.id WHERE ed.idno='$idno'");
                          if(mysqli_num_rows($sqlDept)>0){
                            $dept=mysqli_fetch_array($sqlDept);
                            $department=$dept['department'];
                          }else{
                            $department="";
                          }
//                          $sqlDept=mysqli_query($con,"SELECT company FROM employee_details WHERE idno='$idno'");
//                          $comp=mysqli_fetch_array($sqlDept);
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$idno</td>";
                            echo "<td>$lastname, $firstname $middlename $suffix</td>";
                            echo "<td>$department</td>";
                            echo "<td>$comp</td>";
                            ?>
                            <td align="center">                            
                              <a href="?monitorinfraction&idno=<?=$company['idno'];?>&company=<?=$comp;?>&view" class="btn btn-success btn-xs" title="View Infraction Details"><i class='fa fa-eye'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='13' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>              
                </div>  
            </div>
            </div> 
            <?php
            if(isset($_GET['view'])){
                $idno=$_GET['idno'];
                $sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
                $employee=mysqli_fetch_array($sqlEmployee);
            ?>
            <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?monitorinfraction&company=<?=$comp;?>"><i class="fa fa-arrow-left"></i> Hide</a> | <i class="fa fa-user"></i> <?=$employee['lastname'];?>, <?=$employee['firstname'];?> <?=$employee['middlename'];?></h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Date Issued</th>
                      <th>Date Served</th>
                      <th>Type of Memo</th>
                      <th>Type of Offense</th>
                      <th>Points</th>
                      <th>Memo No.</th>
                      <th>Date of Suspension</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlEmployee=mysqli_query($con,"SELECT * FROM infraction WHERE idno='$idno' ORDER BY addeddatetime ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){                                                    
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[dateissued]</td>";
                            echo "<td>$company[dateserved]</td>";
                            echo "<td>$company[typeofmemo]</td>";
                            echo "<td>$company[typeofoffense]</td>";
                            echo "<td>$company[points]</td>";                            
                            echo "<td>$company[memonumber]</td>";
                            echo "<td>$company[dateofsuspension]</td>";
                            echo "<td>$company[status]</td>";                            
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='13' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>              
                </div>  
            </div>
            </div> 
            <?php
            }
            ?>