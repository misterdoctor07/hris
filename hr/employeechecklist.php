<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $dateoriented=$checklist['dateoriented'];
    $tempid=$checklist['releasedtempid'];
    $permanentid=$checklist['releasedpermanentid'];
    $clearance=$checklist['clearance'];
    $status=$checklist['status'];
    if($clearance=="Police Clearance"){
        $police="checked";
        $nbi="";
        $tofollowclearance="";
    }elseif($clearance=="NBI Clearance"){
        $police="";
        $nbi="checked";
        $tofollowclearance="";
    }else{
        $police="";
        $nbi="";
        $tofollowclearance="checked";
    }
    $healthcard=$checklist['healthcard'];
    if($healthcard=="Yes"){
        $yeshealth="checked";
        $nohealth="";
        $tofollowhealth="";
    }elseif($healthcard=="No"){
        $yeshealth="";
        $nohealth="checked";
        $tofollowhealth="";
    }else{
        $yeshealth="";
        $nohealth="";
        $tofollowhealth="checked";
    }
    $birthcertificate=$checklist['birthcertificate'];
    if($birthcertificate=="Yes"){
        $yesbirth="checked";
        $nobirth="";
        $tofollowbirth="";
    }elseif($birthcertificate=="No"){
        $yesbirth="";
        $nobirth="checked";
        $tofollowbirth="";
    }else{
        $yesbirth="";
        $nobirth="";
        $tofollowbirth="checked";
    }
    $idpicture1=$checklist['idpicture1'];
    if($idpicture1=="Yes"){
        $yespicture1="checked";
        $nopicture1="";
        $tofollowpicture1="";
    }elseif($idpicture1=="No"){
        $yespicture1="";
        $nopicture1="checked";
        $tofollowpicture1="";
    }else{
        $yespicture1="";
        $nopicture1="";
        $tofollowpicture1="checked";
    }
    $idpicture2=$checklist['idpicture2'];
    if($idpicture2=="Yes"){
        $yespicture2="checked";
        $nopicture2="";
        $tofollowpicture2="";
    }elseif($idpicture2=="No"){
        $yespicture2="";
        $nopicture2="checked";
        $tofollowpicture2="";
    }else{
        $yespicture2="";
        $nopicture2="";
        $tofollowpicture2="checked";
    }
}else{
    $dateoriented="";
    $tempid="";
    $permanentid="";
    $clearance="";
    $healthcard="";
    $birthcertificate="";
    $idpicture1="";
    $idpicture2="";
    $police="";
    $nbi="";
    $tofollowclearance="";
    $yeshealth="";
    $nohealth="";
    $tofollowhealth="";
    $yesbirth="";
    $nobirth="";
    $tofollowbirth="";
    $yespicture1="";
    $nopicture1="";
    $tofollowpicture1="";
    $yespicture2="";
    $nopicture2="";
    $tofollowpicture2="";
    $status="";
}
?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> PROBATIONARY EMPLOYEE INITIAL CHECKLIST</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="employeechecklist">            
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
                  <label class="col-sm-3 col-sm-3 control-label">Date Oriented</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateoriented" required value="<?=$dateoriented;?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Released Temporary ID</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="tempid" required value="<?=$tempid;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Released Permanent ID</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="permanentid" required value="<?=$permanentid;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">NBI/Police Clearance</label>
                  <div class="col-sm-8">
                    <input type="radio" name="clearance" required value="Police Clearance" <?=$police;?>> Police Clearance &nbsp;&nbsp;&nbsp;<input type="radio" name="clearance" value="NBI Clearance" <?=$nbi;?>> NBI Clearance &nbsp;&nbsp;&nbsp;<input type="radio" name="clearance" value="To Follow" <?=$tofollowclearance;?>> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">City Health Card</label>
                  <div class="col-sm-8">
                    <input type="radio" name="healthcard" required value="Yes" <?=$yeshealth;?>> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="healthcard" value="No" <?=$nohealth;?>> No &nbsp;&nbsp;&nbsp;<input type="radio" name="healthcard" value="To Follow" <?=$tofollowhealth;?>> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Birth Certificate</label>
                  <div class="col-sm-8">
                    <input type="radio" name="birthcertificate" required value="Yes" <?=$yesbirth;?>> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="birthcertificate" value="No" <?=$nobirth;?>> No &nbsp;&nbsp;&nbsp;<input type="radio" name="birthcertificate" value="To Follow" <?=$tofollowbirth;?>> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">1x1 Picture</label>
                  <div class="col-sm-8">
                    <input type="radio" name="idpicture1" required value="Yes" <?=$yespicture1;?>> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture1" value="No"<?=$nopicture1;?>> No &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture1" value="To Follow"<?=$tofollowpicture1;?>> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">2x2 Picture</label>
                  <div class="col-sm-8">
                    <input type="radio" name="idpicture2" required value="Yes"<?=$yespicture2;?>> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture2" value="No"<?=$nopicture2;?>> No &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture2" value="To Follow"<?=$tofollowpicture2;?>> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Status</label>
                  <div class="col-sm-8">
                    <font color="green"><?=$status;?></font>
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
        $dateoriented=$_GET['dateoriented'];
        $tempid=$_GET['tempid'];
        $permanentid=$_GET['permanentid'];
        $clearance=$_GET['clearance'];
        $healthcard=$_GET['healthcard'];
        $birthcertificate=$_GET['birthcertificate'];
        $idpicture1=$_GET['idpicture1'];
        $idpicture2=$_GET['idpicture2'];
        if(($clearance=="Police Clearance" || $clearance=="NBI Clearance") && $healthcard=="Yes" && $birthcertificate=="Yes" && $idpicture1=="Yes" && $idpicture2=="Yes"){
            $status="Cleared";
        }else{
            $status="";
        }
        $sqlCheck=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="employee_checklist";
            $values="SET dateoriented='$dateoriented',releasedtempid='$tempid',releasedpermanentid='$permanentid',clearance='$clearance',healthcard='$healthcard',birthcertificate='$birthcertificate',idpicture1='$idpicture1',idpicture2='$idpicture1',status='$status',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="employee_checklist(idno,dateoriented,releasedtempid,releasedpermanentid,clearance,healthcard,birthcertificate,idpicture1,idpicture2,status,addedby,addeddatetime)";
            $values="VALUES('$idno','$dateoriented','$tempid','$permanentid','$clearance','$healthcard','$birthcertificate','$idpicture1','$idpicture1','$status','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?employeechecklist&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?employeechecklist&id=$id';";
        echo "</script>";
      }
    }
  ?>