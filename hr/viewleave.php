<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $vacation=$checklist['vacationleave']??0;
    $vlused=$checklist['vlused']??0;
    $sick=$checklist['sickleave']??0;
    $slused=$checklist['slused']??0;
    $pto=$checklist['pto']??0;
    $ptoused=$checklist['ptoused']??0; 
    $bdleave=$checklist['bdayleave']??0;
    $bdused=$checklist['blp_used']??0;
    $eaout=$checklist['earlyout']??0;
    $eaused=$checklist['eo_used']??0;
   $spl=$checklist['spl']??0;
    $splused=$checklist['spl_used']??0;
} else {
  $vacation="";
  $vlused="";
  $sick="";
  $slused="";
  $pto="";
  $ptoused="";
  $bdleave="";
  $bdused="";
  $eaout="";
  $eaused= "";
 $spl="";
  $splused="";
}

// Re-run the query to retrieve the updated data
$sql = "SELECT * FROM leave_credits WHERE idno = '$idno'";
$result = mysqli_query($con, $sql);
$checklist = mysqli_fetch_array($result);

// $vacation = $checklist['vacationleave'] - $checklist['vlused'];
// $sick = $checklist['sickleave'] - $checklist['slused'];
// $pto = $checklist['pto'] - $checklist['ptoused'];
// $bdleave = $checklist['bdayleave'] - $checklist['blp_used'];
// $eaout = $checklist['earlyout'] - $checklist['eo_used'];
// $spls = $checklist['spl'] - $checklist['spl_used'];

?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="javascript:history.back();"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE LEAVE CREDITS</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="viewleave">            
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
                  <label class="col-sm-4 col-sm-4 control-label">VL Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="vacation" style="text-align:center;" value="<?=$vacation;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">VL Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="vacationused" style="text-align:center;" value="<?=$vlused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SL Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sick" style="text-align:center;" value="<?=$sick;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SL Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sickused" style="text-align:center;" value="<?=$slused;?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PTO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="pto" style="text-align:center;" value="<?=$pto;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PTO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="ptoused" style="text-align:center;" value="<?=$ptoused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">BLP Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="bdayleave" style="text-align:center;" value="<?=$bdleave;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">BLP Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="blp_used" style="text-align:center;" value="<?=$bdused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="earlyout" style="text-align:center;" value="<?=$eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="eo_used" style="text-align:center;" value="<?=$eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SPL Credits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="spl" style="text-align:center;" value="<?=$spl;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SPL Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="spl_used" style="text-align:center;" value="<?=$splused;?>">
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
        $vecay=$_GET['vacation'];
        $sicky=$_GET['sick'];
        $pty=$_GET['pto'];  
        $vlused=$_GET['vacationused'];      
        $slused=$_GET['sickused'];
        $ptoused=$_GET['ptoused'];
        $bdayleave=$_GET['bdayleave'];
        $blp_used=$_GET['blp_used'];
        $earlyout=$_GET['earlyout'];
        $eo_used=$_GET['eo_used'];
        $spl=$_GET['spl'];
        $spl_used=$_GET['spl_used'];

        $sqlCheck=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="leave_credits";
            $values="SET vacationleave='$vecay',vlused='$vlused',sickleave='$sicky',slused='$slused',pto='$pty',ptoused='$ptoused',bdayleave='$bdayleave', blp_used='$blp_used', earlyout='$earlyout', eo_used='$eo_used', spl='$spl',  spl_used='$spl_used'

            ,updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="leave_credits(idno,vacationleave,vlused,sickleave,slused,pto,ptoused,bdayleave,blp_used,earlyout,eo_used,spl,spl_used,addedby,addeddatetime)";
            $values="VALUES('$idno','$vecay','$vlused','$sicky','$slused','$pty','$ptoused','$bdayleave','$blp_used','$earlyout','$eo_used','$spl','$spl_used','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?viewleave&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?viewleave&id=$id';";
        echo "</script>";
      }
    }
  ?>