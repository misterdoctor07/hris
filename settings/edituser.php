<?php
  $id = $_GET['id'];
  $sqlDepartment = mysqli_query($con, "SELECT * FROM employee_profile WHERE id='$id'");
  $department = mysqli_fetch_array($sqlDepartment);
  $sqlAccess = mysqli_query($con, "SELECT * FROM users WHERE idno='{$department['idno']}'");
  $hr = "";
  $payroll = "";
  $accounting = "";
  $admin = "";
  $portal = "";
  
  if (mysqli_num_rows($sqlAccess) > 0) {                          
    while ($access = mysqli_fetch_array($sqlAccess)) {   
      $uname = $access['username'];
      $pword = $access['password'];       
      if ($access['access'] == "HR") {
        $hr = "checked";
      }          
      if ($access['access'] == "ACCOUNTING") {
        $accounting = "checked";
      }
      if ($access['access'] == "IT ADMIN") {
        $admin = "checked";
      } else {
        $admin = "";
      }
      if ($access['access'] == "EMPLOYEE PORTAL") {
        $portal = "checked";
      }
    }
  } else {
    $uname = "";
    $pword = "";
    $hr = "";
    $payroll = "";
    $accounting = "";
    $admin = "";
    $portal = "";
  }
?>
<script type="text/javascript">
  function SubmitDetails() {
    var code = $('#companycode').val();        
    if (code == '') {
      alert('Please enter department!');   
      return false;       
    } else {
      return confirm('Do you wish to submit details?');
    }
  }

  function toggleUsernameWithID(checkbox) {
    if (checkbox.checked) {
      document.querySelector('input[name="user_name"]').value = "<?=$department['idno'];?>";
    }
  }
</script>
<div class="col-lg-6">
  <div class="content-panel">
    <div class="panel-heading">
      <h4><a href="?manageuser"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-building-o"></i> MANAGE USER PROFILE</h4>              
    </div>
    <div class="panel-body">              
      <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
        <input type="hidden" name="edituser">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="idno" value="<?=$department['idno'];?>">
        <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">Name</label>
          <label class="control-label"><?=$department['lastname'];?>, <?=$department['firstname'];?> <?=$department['middlename'];?> <?=$department['suffix'];?></label>                  
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">Username</label>
          <div class="col-sm-6">
            <input type="text" name="user_name" class="form-control" value="<?=$uname;?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">Password</label>
          <div class="col-sm-6">
            <input type="password" name="user_password" class="form-control" value="<?=$pword;?>">
          </div>
        </div>                   
        <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">User Access</label> 
          <div class="col-sm-6">
            <input type="checkbox" name="user_access[]" value="HR" <?=$hr;?>> HR<br>                    
            <input type="checkbox" name="user_access[]" value="ACCOUNTING" <?=$accounting;?>> ACCOUNTING<br>
            <input type="checkbox" name="user_access[]" value="IT ADMIN" <?=$admin;?>> IT ADMIN<br>
            <input type="checkbox" name="user_access[]" value="EMPLOYEE PORTAL" <?=$portal;?> onchange="toggleUsernameWithID(this)"> EMPLOYEE PORTAL
          </div>                 
        </div>                             
        <div class="form-group">
          <label class="col-sm-2 col-sm-2 control-label">&nbsp;</label>
          <div class="col-sm-10">
            <input type="submit" class="btn btn-primary" name="submit" value="Submit Details">
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- col-lg-12-->
</div>      
<?php
  if (isset($_GET['submit'])) {
    $id = $_GET['id'];
    $idno = $_GET['idno'];
    $uname = $_GET['user_name'];
    $pword = $_GET['user_password'];
    $user_access = $_GET['user_access'];
    if (sizeof($user_access) > 0) {
      $sqlDelete = mysqli_query($con, "DELETE FROM users WHERE idno='$idno'");
      foreach ($user_access as $uaccess) {
        $sqlInsert = mysqli_query($con, "INSERT INTO users(username,password,idno,access) VALUES('$uname','$pword','$idno','$uaccess')");
      }      
      if ($sqlInsert) {
        echo "<script>";
        echo "alert('Details successfully updated!');window.location='?edituser&id=$id';";
        echo "</script>";
      } else {
        echo "<script>";
        echo "alert('Unable to update details!');window.location='?edituser&id=$id';";
        echo "</script>";
      }
    } else {
      echo "<script>";
      echo "alert('Details successfully updated!');window.location='?edituser&id=$id';";
      echo "</script>";
    }      
  }
?>
