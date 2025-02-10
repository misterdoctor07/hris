<?php
$idno = $_GET['idno'];
$period = $_GET['period'];
$id = $_GET['id'];
$company = $_GET['company'];

// Get the employee's work area
$employeeWorkArea = "";
$employeeQuery = mysqli_query($con, "SELECT work_area FROM employee_details WHERE idno = '$idno'");
if ($row = mysqli_fetch_assoc($employeeQuery)) {
    $employeeWorkArea = $row['work_area'];
}

// Initialize variables
$holidays = [];
$logindate = "";

// Get attendance details
$sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE id='$id'");
if (mysqli_num_rows($sqlAttendance) > 0) {
    $attend = mysqli_fetch_array($sqlAttendance);
    $loginam = date('H:i', strtotime($attend['loginam']));
    $logoutam = date('H:i', strtotime($attend['logoutam']));
    $loginpm = date('H:i', strtotime($attend['loginpm']));
    $logoutpm = date('H:i', strtotime($attend['logoutpm']));
    $logindate = $attend['logindate'];
    $status = $attend['status'];
    $remarks = $attend['remarks'];
} else {
    $loginam = $logoutam = $loginpm = $logoutpm = "";
    $logindate = $_GET['logindate'];
    $status = "";
    $remarks = "";
}

// Fetch holidays applicable to the employee
$holidayQuery = mysqli_query($con, "
    SELECT `date`, `type`, `location` 
    FROM holidays 
    WHERE (location = 'allbranch' OR location = '$employeeWorkArea') AND `date` = '$logindate'
");
if (!$holidayQuery) {
    echo "<script>alert('Holiday query error: " . mysqli_error($con) . "');</script>";
}

// Map holidays
while ($row = mysqli_fetch_assoc($holidayQuery)) {
    $holidays[$row['date']] = $row['type'];
}

// Determine holiday type for the logindate
$rh = $snwh = "";
if (!empty($logindate) && isset($holidays[$logindate])) {
    if ($holidays[$logindate] === 'rh') {
        $rh = "checked"; // Regular Holiday
    } elseif ($holidays[$logindate] === 'snwh') {
        $snwh = "checked"; // Special Non-Working Holiday
    }
}

// Initialize other status variables
$work = $nd = $leave = $ot = $pt = "";
if ($remarks == "") {
    $remarks = "P";
}

// Parse status and set appropriate checks
$stat = explode('/', $status);
foreach ($stat as $s) {
    switch ($s) {
        case "work":
            $work = "checked";
            break;
        case "nd":
            $nd = "checked";
            break;
        case "leave":
            $leave = "checked";
            break;
        case "ot":
            $ot = "checked";
            break;
        case "pt":
            $pt = "checked";
            break;
    }
}
?>


    <script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&company=<?=$company;?>"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> MANAGE TIME</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="edittime">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-clock-o"></i> ATTENDANCE DETAILS</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Shift 1</label>
                  <div class="col-sm-5">

                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Login</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="loginam" required value="<?=$loginam;?>" placeholder="HH:mm:ss">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Logout</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="logoutam" required value="<?=$logoutam;?>" placeholder="HH:mm:ss">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Shift 2</label>
                  <div class="col-sm-5">

                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Login</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="loginpm" required value="<?=$loginpm;?>" placeholder="HH:mm:ss">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Logout</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="logoutpm" required value="<?=$logoutpm;?>" placeholder="HH:mm:ss">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Log Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="logindate" required value="<?=$logindate;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Status</label>
                  <div class="col-sm-5">
                  <input type="checkbox" name="status[]" value="work" <?=$work;?>> Regular Work<br>
                    <input type="checkbox" name="status[]" value="rh" <?=$rh;?>> Regular Holiday<br>
                    <input type="checkbox" name="status[]" value="snwh" <?=$snwh;?>> Special Non-Working Holiday<br>
                    <input type="checkbox" name="status[]" value="nd" <?=$nd;?>> Night Differential<br>
                    <input type="checkbox" name="status[]" value="leave" <?=$leave;?>> Leave<br>
                    <input type="checkbox" name="status[]" value="ot" <?=$ot;?>> OT after 8 hours worked<br>
                    <input type="checkbox" name="status[]" value="pt" <?=$pt;?>> OT before 8 hours worked
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Leave Type</label>
                  <div class="col-sm-6">
                    <select name="leavetype" class="form-control" required>
                      <option value="<?=$remarks;?>"><?=$remarks;?></option>
                      <option value="P">P</option>
                      <option value="VL"> Vacation Leave (VL)</option>
                      <option value="SL" required> Sick Leave (SL)</option>
                      <option value="PTO" required> Unpaid Leave (PTO) </option>
                      <option value="MTL" required> Maternity Leave (MTL)</option>
                      <option value="PTL" required> Paternity Leave (PTL) </option>
                      <option value="SPL" required> Solo Parent Leave (SPL)</option>
                      <option value="BL" required> Bereavement Leave (BL) </option>
                      <option value="MDL" required> Medical Leave (MDL)</option>
                      <option value="LTL" required> Long Term Leave (LTL) </option>
                      <option value="BLP" required> Birthday Leave (BLP)</option>
                      <option value="EO" required> Early Out (EO) </option>
                      <option value="EEO" required> Emergency Early Out Leave (EEO)</option>
                    </select>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>
  <?php
    if(isset($_GET['submit'])){
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $logindate=$_GET['logindate'];
        $loginam=$_GET['loginam'];
        $logoutam=$_GET['logoutam'];
        $loginpm=$_GET['loginpm'];
        $logoutpm=$_GET['logoutpm'];
        $idno=$_GET['idno'];
        $period=$_GET['period'];
        $stat=$_GET['status'];
        $id=$_GET['id'];
        $leavetype=$_GET['leavetype'];
        if(sizeof($stat)>0){
        $status="";
        foreach($stat AS $s){
            $status .=$s."/";
        }

        $sqlCheck=mysqli_query($con,"SELECT * FROM attendance WHERE id='$id'");
        if(mysqli_num_rows($sqlCheck)>0){
          $table="attendance";
          $values="SET loginam='$loginam',logoutam='$logoutam',loginpm='$loginpm',logoutpm='$logoutpm',status='$status',remarks='$leavetype' WHERE id='$id'";
          $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="attendance(idno,loginam,logoutam,loginpm,logoutpm,logindate,status,remarks)";
            $values="VALUES('$idno','$loginam','$logoutam','$loginpm','$logoutpm','$logindate','$status','$leavetype')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
          echo "<script>";
          echo "alert('Attendance successfully saved!');window.location='?edittime&idno=$idno&period=$period&logindate=$logindate&id=$id&company=$company';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to save details!');window.location='?edittime&idno=$idno&period=$period&logindate=$logindate&id=$id&company=$company';";
        echo "</script>";
      }
        }else{
        echo "<script>";
          echo "alert('Please select at least 1 status!');window.location='?edittime&idno=$idno&period=$period&logindate=$logindate&id=$id&company=$company';";
        echo "</script>";
    }
    }
  ?>
