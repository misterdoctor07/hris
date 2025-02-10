<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM employee_referral WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $probationary=$checklist['referredby'];
    $probationarydate=$checklist['effectivity'];
    $sqlRefProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$probationary'");
    $ref=mysqli_fetch_array($sqlRefProfile);
    $referredby=$ref['lastname'].", ".$ref['firstname'];
}else{
    $probationary="";
    $probationarydate="";
    $referredby="";
}
?>
<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> EMPLOYEE REFERRAL</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="employeereferral">
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
                  <label class="col-sm-4 col-sm-4 control-label">Referred By</label>
                  <div class="col-sm-7">
                  <select name="referredby" class="form-control" required>
                        <option value="<?=$probationary;?>"><?=$referredby;?></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ep.idno <> '$idno'");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[idno]'>$employee[lastname], $employee[firstname]</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Effectivity</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="probationarydate" value="<?=$probationarydate;?>" required>
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
        $probationary=$_GET['referredby'];
        $probationarydate=$_GET['probationarydate'];
        $sqlCheck=mysqli_query($con,"SELECT * FROM employee_referral WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="employee_referral";
            $values="SET referredby='$probationary',effectivity='$probationarydate',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="employee_referral(idno,referredby,effectivity,addedby,addeddatetime)";
            $values="VALUES('$idno','$probationary','$probationarydate','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?employeereferral&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?employeereferral&id=$id';";
        echo "</script>";
      }
    }
  ?>
