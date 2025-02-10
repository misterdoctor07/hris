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
          <!-- col-lg-12-->
        </div>
        <div class="col-lg-4 mt">
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
