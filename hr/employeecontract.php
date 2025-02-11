<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $probationary=$checklist['probationary'];
    $probationarydate=$checklist['probationarydate'];
    $regular=$checklist['regular'];
    $regulardate=$checklist['regulardate'];
    $fulltime=$checklist['fulltime'];
    $fulltimedate=$checklist['fulltimedate'];
}else{
    $probationary="";
    $probationarydate="";
    $regular="";
    $regulardate="";
    $fulltime="";
    $fulltimedate="";
}
?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> EMPLOYEE CONTRACT STATUS</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="employeecontract">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> <?=$lastname;?>, <?=$firstname;?> <?=$suffix;?></h4>            
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Probationary Status</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="probationary" value="<?=$probationary;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Probationary Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="probationarydate" value="<?=$probationarydate;?>">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Regular Status</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="regular" value="<?=$regular;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Regular Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="regulardate" value="<?=$regulardate;?>">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Full-Time Status</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="fulltime" value="<?=$fulltime;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Full-Time Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="fulltimedate" value="<?=$fulltimedate;?>">
                  </div>
                </div>                
                
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
  <?php
    if(isset($_GET['submit'])){
        $id=$_GET['id'];
        $idno=$_GET['idno'];
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $probationary=$_GET['probationary'];
        $probationarydate=$_GET['probationarydate'];
        $regular=$_GET['regular'];
        $regulardate=$_GET['regulardate'];
        $fulltime=$_GET['fulltime'];
        $fulltimedate=$_GET['fulltimedate'];        
        $sqlCheck=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="employee_contract";
            $values="SET probationary='$probationary',probationarydate='$probationarydate',regular='$regular',regulardate='$regulardate',fulltime='$fulltime',fulltimedate='$fulltimedate',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="employee_contract(idno,probationary,probationarydate,regular,regulardate,fulltime,fulltimedate,addedby,addeddatetime)";
            $values="VALUES('$idno','$probationary','$probationarydate','$regular','$regulardate','$fulltime','$fulltimedate','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?employeecontract&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?employeecontract&id=$id';";
        echo "</script>";
      }
    }
  ?>