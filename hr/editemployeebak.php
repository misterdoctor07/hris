<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT ep.*,ed.*,eb.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno LEFT JOIN employee_benefits eb ON eb.idno=ep.idno WHERE ep.id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$middlename=$profile['middlename'];
$suffix=$profile['suffix'];
$nickname=$profile['nickname'];
$birthdate=$profile['birthdate'];
$civilstatus=$profile['civilstatus'];
$gender=$profile['sex'];
$eligibility=$profile['eligibility'];
$address=$profile['address'];
$companyid=$profile['company'];
$deptid=$profile['department'];
$jobid=$profile['designation'];
$status=$profile['status'];
$datehired=$profile['dateofhired'];
$location=$profile['location'];
$insurance=$profile['insurance'];
$hmo=$profile['hmo'];
$sss=$profile['sss'];
$tin=$profile['tin'];
$phic=$profile['phic'];
$hdmf=$profile['hdmf'];

$sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$deptid'");
$dept=mysqli_fetch_array($sqlDepartment);
$department=$dept['department'];

$sqlJobTitle=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobid'");
$job=mysqli_fetch_array($sqlJobTitle);
$jobtitle=$job['jobtitle'];


$sqlChecklist=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $dateoriented=$checklist['dateoriented'];
    $tempid=$checklist['releasedtempid'];
    $permanentid=$checklist['releasedpermanentid'];
    $clearance=$checklist['clearance'];
    $status1=$checklist['status'];
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
    $status1="";
}

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
      <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> UPDATE EMPLOYEE PROFILE</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editemployee">
      <input type="hidden" name="updatedby" value="<?=$fullname;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="oldidno" value="<?=$idno;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> EMPLOYEE PROFILE</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Employee No.</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="idno" required value="<?=$idno;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Last Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="lastname" required value="<?=$lastname;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">First Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstname" required value="<?=$firstname;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Middle Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="middlename" required value="<?=$middlename;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Suffix</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="suffix" value="<?=$suffix;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Nickname</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="nickname" required value="<?=$nickname;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date of Birth</label>
                  <div class="col-sm-5">
                    <input type="date" name="birthdate" class="form-control" value="<?=$birthdate;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Civil Status</label>
                  <div class="col-sm-5">
                    <select name="civilstatus" class="form-control" required>
                      <option  value="<?=$civilstatus;?>">
                      <?php
                      if($civilstatus=="S"){
                          echo "Single";
                      }elseif($civilstatus=="M"){
                          echo "Married";
                      }else{
                          echo "Widowed";
                      }
                      ?>
                    </option>
                      <option value="S">Single</option>
                      <option value="M">Married</option>
                      <option value="W">Widowed</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Gender</label>
                  <div class="col-sm-3">
                    <select name="gender" class="form-control" required>
                      <option  value="<?=$gender;?>">
                      <?php
                      if($gender=="M"){
                          echo "Male";
                      }else{
                          echo "Female";
                      }
                      ?>
                    </option>
                      <option value="M">Male</option>
                      <option value="F">Female</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Eligibility</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="eligibility" value="<?=$eligibility;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Address</label>
                  <div class="col-sm-9">
                    <textarea name="address" class="form-control" required rows="5"><?=$address;?></textarea>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> EMPLOYEE DETAILS</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Status</label>
                  <div class="col-sm-4">
                    <select name="status" class="form-control" required>
                      <option value="<?=$status;?>"><?=$status;?></option>
                      <option value="PROBATIONARY">PROBATIONARY</option>
                      <option value="REGULAR">REGULAR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date Hired</label>
                  <div class="col-sm-5">
                    <input type="date" name="datehired" class="form-control" required value="<?=$datehired;?>">
                  </div>
                </div>
                  <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Work Area</label>
                  <div class="col-sm-5">
                    <select name="location" class="form-control" required>
                      <option value="<?=$location;?>">
                      <?php
                      if($location=="WFH"){
                          echo "Work From Home";
                      }else{
                          echo "On-site";
                      }
                      ?>
                        </option>
                      <option value="OS">On-site</option>
                      <option value="WFH">Work From Home</option>
                    </select>
                  </div>
                </div>

            </div>
          </div>
          <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-stack-overflow"></i> EMPLOYEE BENEFITS</h4>
            </div>
            <div class="panel-body">
                <input type="hidden" name="addcompany">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Life Insurance Effectivity</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="insurance" required value="<?=$insurance;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">HMO Effectivity</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="hmo" required value="<?=$hmo;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">SSS No.</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="sss" required value="<?=$sss;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">TIN</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="tin" required value="<?=$tin;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">PHIC</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="phic" required value="<?=$phic;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Pag-ibig</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="hdmf" required value="<?=$hdmf;?>">
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> PROBATIONARY CHECKLIST</h4>
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
                    <font color="green"><?=$status1;?></font>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
          <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> CONTRACT STATUS</h4>
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
            </div>
          </div>
        </div>
        </form>
  <?php
    if(isset($_GET['submit'])){
      $id=$_GET['id'];
      $oldidno=$_GET['oldidno'];
      $idno=$_GET['idno'];
      $updatedby=$_GET['updatedby'];
      $datenow=date('Y-m-d H:i:s');
      $lastname=$_GET['lastname'];
      $firstname=$_GET['firstname'];
      $middlename=$_GET['middlename'];
      $suffix=$_GET['suffix'];
      $nickname=$_GET['nickname'];
      $address=$_GET['address'];
      $birthdate=$_GET['birthdate'];
      $gender=$_GET['gender'];
      $civilstatus=$_GET['civilstatus'];
      $eligible=$_GET['eligibility'];

      $table="employee_profile";
      $values="SET idno='$idno',lastname='$lastname',firstname='$firstname',middlename='$middlename',suffix='$suffix',nickname='$nickname',birthdate='$birthdate',civilstatus='$civilstatus',sex='$gender',eligibility='$eligible',address='$address',updatedby='$updatedby',updateddatetime='$datenow' WHERE id='$id'";
      $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");

      $status=$_GET['status'];
      $datehired=$_GET['datehired'];
      $location=$_GET['location'];
      $regular=date('Y-m-d',strtotime('3 months',strtotime($datehired)));
      $fulltime=date('Y-m-d',strtotime('1 years',strtotime($regular)));

      $table="employee_details";
      $values="SET idno='$idno',status='$status',dateofhired='$datehired',dateofregular='$regular',dateoffulltime='$fulltime',location='$location',updatedby='$updatedby',updateddatetime='$datenow' WHERE idno='$oldidno'";
      $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");

      $insurance=$_GET['insurance'];
      $hmo=$_GET['hmo'];
      $sss=$_GET['sss'];
      $tin=$_GET['tin'];
      $phic=$_GET['phic'];
      $hdmf=$_GET['hdmf'];
      $table="employee_benefits";
      $values="SET idno='$idno',insurance='$insurance',hmo='$hmo',sss='$sss',tin='$tin',phic='$phic',hdmf='$hdmf',updatedby='$updatedby',updateddatetime='$datenow' WHERE idno='$oldidno'";
      $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");

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

            $probationary=$_GET['probationary'];
        $probationarydate=$_GET['probationarydate'];
        $sqlCheck=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="employee_contract";
            $values="SET probationary='$probationary',probationarydate='$probationarydate',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="employee_contract(idno,probationary,probationarydate,addedby,addeddatetime)";
            $values="VALUES('$idno','$probationary','$probationarydate','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?editemployee&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?editemployee&id=$id';";
        echo "</script>";
      }
    }
  ?>
