<?php
    $comp=$_GET['company'];
    ?>
    <script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?viewresignedemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> RESIGNATION</h4>      
    </div>
    </div>
    <div class="col-lg-8">
            <div class="content-panel">
              <div class="panel-heading">
              <h4>EMPLOYEE LIST (<?=$comp;?>)</h4>
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
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$comp' ORDER BY ep.lastname ASC");
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
                          $empname=$company['lastname'].", ".$company['firstname'];
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                            
                            echo "<td>".date('M-d-Y',strtotime($company['birthdate']))."</td>";
                            echo "<td>$jobtitle</td>";
                            echo "<td>$department</td>";
                            echo "<td>$status</td>";
                            echo "<td>$datehired</td>";                                                      
                            ?>
                            <td align="center">
                              <a href="?addresign&resign&idno=<?=$company['idno'];?>&company=<?=$comp;?>&empname=<?=$empname;?>" class="btn btn-success btn-xs" title="Resignation"><i class='fa fa-sign-out'></i></a>                              
                            </td>
                            <?php
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
    if(isset($_GET['resign'])){
        $idno=$_GET['idno'];   
        $empname=$_GET['empname'];     
    ?>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="addresign">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">          
      <input type="hidden" name="company" value="<?=$comp;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
    <div class="col-lg-4">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> <?=$empname;?></h4>            
            </div>
            <div class="panel-body">                                            
            <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Resignation Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateresigned">
                  </div>
                </div>
                                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Reasons</label>
                  <div class="col-sm-7">
                    <textarea name="reason" class="form-control" rows="5"></textarea>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
        <?php
    }
        ?>
  <?php
    if(isset($_GET['submit'])){        
        $idno=$_GET['idno'];
        $addedby=$_GET['addedby'];        
        $dateresigned=$_GET['dateresigned'];
        $reasons=$_GET['reason'];             
            $table="resignation(idno,dateresigned,reason)";
            $values="VALUES('$idno','$dateresigned','$reasons')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");        
      if($sqlAddEmployee){
          $sqlResign=mysqli_query($con,"UPDATE employee_details SET `status`='RESIGNED' WHERE idno='$idno'");
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?addresign&company=$comp';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?addresign&company=$comp';";
        echo "</script>";
      }
    }
  ?>