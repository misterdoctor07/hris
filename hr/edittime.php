<?php
    include '../config.php';

   
    $idno = $_GET['idno'];
    $id = $_GET['id'];
    $comp = $_GET['company'];
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];
    
    $sqlCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno='$idno'");
    $credits = []; 
    if (mysqli_num_rows($sqlCredits) > 0) {
        $credit = mysqli_fetch_array($sqlCredits);
        $credits['VL'] = $credit['vacationleave'] - $credit['vlused'];
        $credits['SL'] = $credit['sickleave'] - $credit['slused'];
        $credits['PTO'] = $credit['pto'] - $credit['ptoused'];
        $credits['BLP'] = $credit['bdayleave'] - $credit['blp_used'];
        $credits['EO'] = $credit['earlyout'] - $credit['eo_used'];
        $credits['SPL'] = $credit['spl'] - $credit['spl_used'];
    }
    $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE id='$id'");
    if (mysqli_num_rows($sqlAttendance) > 0) {
        $attend = mysqli_fetch_array($sqlAttendance);
        $loginam = $attend['loginam'] ? date('H:i', strtotime($attend['loginam'])) : '';
        $logoutam = $attend['logoutam'] ? date('H:i', strtotime($attend['logoutam'])) : '';
        $loginpm = $attend['loginpm'] ? date('H:i', strtotime($attend['loginpm'])) : '';
        $logoutpm = $attend['logoutpm'] ? date('H:i', strtotime($attend['logoutpm'])) : '';
        $logindate = $attend['logindate'];
        $status = $attend['status'];
        $remarks = $attend['remarks'];
    } else {
        $loginam = "";
        $logoutam = "";
        $loginpm = "";
        $logoutpm = "";
        $logindate = $_GET['logindate'];
        $status = "";
        $remarks = "";
    }
            $work="";
            $rh="";
            $snwh="";
            $nd="";
            $leave="";
            $ot="";
            $pt="";
            $ab="";
            $sus="";
    //if(sizeof($status)>0){
        $stat=explode('/',$status);
        for($i=0;$i<sizeof($stat);$i++){
            if($stat[$i]=="work"){
                $work="checked";
            }
            if($stat[$i]=="rh"){
                $rh="checked";
            }
            if($stat[$i]=="snwh"){
                $snwh="checked";
            }
            if($stat[$i]=="nd"){
                $nd="checked";
            }
            if($stat[$i]=="leave"){
                $leave="checked";
            }
            if($stat[$i]=="ot"){
                $ot="checked";
            }
            if($stat[$i]=="pt"){
                $pt="checked";
            }
            if($stat[$i]=="ab"){
                $ab="checked";
            }
            if($stat[$i]=="sus"){
                $sus="checked";
            }
        }
    // }else{
    //         $work="";
    //         $rh="";
    //         $snwh="";
    //         $nd="";
    //         $leave="";
    //         $ot="";
    //         $pt="";
    // }
    ?>
    <script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;">
    <a href="javascript:history.back();"><i class="fa fa-arrow-left"></i> BACK</a> | 
    <i class="fa fa-money"></i> MANAGE TIME
</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="edittime">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="company" value="<?=$comp;?>">
      <input type="hidden" name="startdate" value="<?=$startdate;?>">
      <input type="hidden" name="enddate" value="<?=$enddate;?>">
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
                    <input type="text" class="form-control" name="loginam"  value="<?=$loginam;?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-sm-4 control-label">Logout</label>
    <div class="col-sm-5">
                    <input type="text" class="form-control" name="logoutam"  value="<?=$logoutam;?>">
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
                    <input type="text" class="form-control" name="loginpm"  value="<?=$loginpm;?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-sm-4 control-label">Logout</label>
    <div class="col-sm-5">
                    <input type="text" class="form-control" name="logoutpm"  value="<?=$logoutpm;?>">
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
                    <input type="checkbox" name="status[]" value="pt" <?=$pt;?>> OT after 8 hours worked<br>
                    <input type="checkbox" name="status[]" value="ab" <?=$ab;?>> Absent<br>
                    <input type="checkbox" name="status[]" value="sus" <?=$sus;?>> Infraction<br>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Type</label>
                  <div class="col-sm-6">
                    <select name="leavetype" id="leavetypeDropdown" class="form-control" required>
                    <option value="<?=$remarks;?>"><?=$remarks;?></option>
                      <option value="P">Present (P)</option>
                      <option value="P-EO"> Present - Early Out (P-EO)</option>
                      <option value="VL"> Vacation Leave (VL)</option>
                      <option value="SL" required> Sick Leave - with Med Cert (SL)</option>
                      <option value="SL-NC" required> Sick Leave - Natural Calamity (SL-NC)</option>
                      <option value="SL-IO" required> Sick Leave - Internet Outage (SL-IO)</option>
                      <option value="SL-PO" required> Sick Leave - Power Outage (SL-PO)</option>
                      <option value="SL-GS" required> Sick Leave - Government Sanctioned (SL-GS)</option>
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
                      <option value="AWOL" required> Absent Without Leave (AWOL)</option>
                      <option value="AWOL2" required> Absent Without Leave | 2hrs after shift (AWOL2)</option>
                      <option value="CI" required> Absent </option>
                      <option value="AA" required> Authorized Absence</option>
                      <option value="OC" required> Office Close </option>
                      <option value="SUS" required> Suspended</option>



                    </select>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>
       <?php
if (isset($_GET['submit'])) {
  $addedby = $_GET['addedby'];
  $datenow = date('Y-m-d H:i:s');
  $logindate = $_GET['logindate'];
  $idno = $_GET['idno'];
  $newStatus = $_GET['status'];
  $newLeaveType = $_GET['leavetype'];

  // Set time fields to NULL if the leave type is selected
  if (!empty($newLeaveType)) {
      $loginam = !empty($_GET['loginam']) ? $_GET['loginam'] : '0';
      $logoutam = !empty($_GET['logoutam']) ? $_GET['logoutam'] : '0';
      $loginpm = !empty($_GET['loginpm']) ? $_GET['loginpm'] : '0';
      $logoutpm = !empty($_GET['logoutpm']) ? $_GET['logoutpm'] : '0';
  } else {
      $loginam = isset($_GET['loginam']) ? $_GET['loginam'] : '0';
      $logoutam = isset($_GET['logoutam']) ? $_GET['logoutam'] : '0';
      $loginpm = isset($_GET['loginpm']) ? $_GET['loginpm'] : '0';
      $logoutpm = isset($_GET['logoutpm']) ? $_GET['logoutpm'] : '0';
  }

  // Ensure that status is properly formatted
  if (!empty($newStatus)) {
      $status = implode("/", $newStatus);

      // Check the previous status and remarks from the attendance table
      $sqlCheck = mysqli_query($con, "SELECT status, remarks FROM attendance WHERE id = '$id'");
      if (mysqli_num_rows($sqlCheck) > 0) {
          $attendanceData = mysqli_fetch_assoc($sqlCheck);
          $previousStatus = $attendanceData['status'];
          $previousRemarks = $attendanceData['remarks']; // Fetch previous remarks

          // If EEO is selected, append it to the previous remarks
          if ($newLeaveType == 'EEO') {
            $newLeaveType = "$previousRemarks/EEO"; // Combine EEO with previous remarks
        }
        
        if ($newLeaveType == 'P-EO') {
            $newLeaveType = "$previousRemarks/P-EO"; // Combine P-EO with previous remarks
            updateLeaveCredits($con, $idno, 'P-EO', '+'); // Increment eo_used by 1
        }

          // Update credits based on previous and new status
          if ($previousStatus != 'leave' && $status == 'leave') {
              updateLeaveCredits($con, $idno, $newLeaveType, '+');
          } elseif ($previousStatus == 'leave' && $status == 'leave') {
              if (!areEquivalentLeaveTypes($previousRemarks, $newLeaveType)) {
                  updateLeaveCredits($con, $idno, $previousRemarks, '-');
                  updateLeaveCredits($con, $idno, $newLeaveType, '+');
              }
          }

          // Update attendance details
          $sqlInsert = mysqli_query($con, 
              "UPDATE attendance SET loginam='$loginam', logoutam='$logoutam', loginpm='$loginpm', logoutpm='$logoutpm', logindate='$logindate', status='$status', remarks='$newLeaveType' WHERE id = '$id'"
          );

      } else {
          // If the record doesn't exist, insert a new attendance record
          $sqlInsert = mysqli_query($con, 
          "INSERT INTO attendance(idno, loginam, logoutam, loginpm, logoutpm, logindate, status, remarks) 
              VALUES('$idno', '$loginam', '$logoutam', '$loginpm', '$logoutpm', '$logindate', '$status', '$newLeaveType')"
          );

          if ($status == 'leave') {
              updateLeaveCredits($con, $idno, $newLeaveType, '+');
          }
      }

      if ($sqlInsert) {
          echo "<script>alert('Attendance successfully saved!'); window.location='?edittime&idno=$idno&logindate=$logindate&id=$id&company=$comp&startdate=$startdate&enddate=$enddate';</script>";
      } else {
          echo "<script>alert('Unable to save details!'); window.location='?edittime&idno=$idno&logindate=$logindate&id=$id&company=$comp&startdate=$startdate&enddate=$enddate';</script>";
      }
  } else {
      echo "<script>alert('Please select at least 1 status!'); window.location='?edittime&idno=$idno&logindate=$logindate&id=$id&company=$comp&startdate=$startdate&enddate=$enddate';</script>";
  }
}

function updateLeaveCredits($con, $idno, $leaveType, $operation) {
  switch ($leaveType) {
      case 'VL':
          mysqli_query($con, "UPDATE leave_credits SET vlused = vlused $operation 1 WHERE idno = '$idno'");
          break;
      case 'SL':
      case 'SL-NC':
      case 'SL-IO':
      case 'SL-PO':
      case 'SL-GS':
          mysqli_query($con, "UPDATE leave_credits SET slused = slused $operation 1 WHERE idno = '$idno'");
          break;
      case 'PTO':
          mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused $operation 1 WHERE idno = '$idno'");
          break;
      case 'EO':
      case 'P-EO':
          mysqli_query($con, "UPDATE leave_credits SET eo_used = eo_used $operation 1 WHERE idno = '$idno'");
          break;
      case 'BLP':
          mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used $operation 1 WHERE idno = '$idno'");
          break;
      case 'SPL':
          mysqli_query($con, "UPDATE leave_credits SET spl_used = spl_used $operation 1 WHERE idno = '$idno'");
          break;
      default:
          echo "<script>alert('Invalid leave type.');</script>";
          break;
  }
}

// Helper function to check if two leave types are equivalent for credit purposes
function areEquivalentLeaveTypes($leaveType1, $leaveType2) {
  $equivalentLeaveTypes = [
      ['SL', 'SL-NC', 'SL-IO', 'SL-PO', 'SL-GS'],['EO', 'P-EO']
  ];

  foreach ($equivalentLeaveTypes as $equivalentGroup) {
      if (in_array($leaveType1, $equivalentGroup) && in_array($leaveType2, $equivalentGroup)) {
          return true;
      }
  }
  return false;
}
?>
<script>
    document.querySelectorAll('input[name="status[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const leaveTypeDropdown = document.getElementById('leavetypeDropdown');
            const leaveOptions = [
                { value: "SL", text: "Sick Leave - with Med Cert (SL)" },
                { value: "SL-NC", text: "Sick Leave - Natural Calamity (SL-NC)" },
                { value: "SL-IO", text: "Sick Leave - Internet Outage (SL-IO)" },
                { value: "SL-PO", text: "Sick Leave - Power Outage (SL-PO)" },
                { value: "SL-GS", text: "Sick Leave - Government Sanctioned (SL-GS)" },
                { value: "VL", text: "Vacation Leave (VL)" },
                { value: "PTO", text: "Unpaid Leave (PTO)" },
                { value: "MTL", text: "Maternity Leave (MTL)" },
                { value: "PTL", text: "Paternity Leave (PTL)" },
                { value: "SPL", text: "Solo Parent Leave (SPL)" },
                { value: "BL", text: "Bereavement Leave (BL)" },
                { value: "MDL", text: "Medical Leave (MDL)" },
                { value: "LTL", text: "Long Term Leave (LTL)" },
                { value: "BLP", text: "Birthday Leave (BLP)" },
                { value: "EO", text: "Early Out (EO)" },
                { value: "P-EO", text: "Present - Early Out (P-EO)" },
                { value: "EEO", text: "Emergency Early Out Leave (EEO)" },
            ];
            const absentOptions = [
                { value: "CI", text: "Absent" },
                { value: "AWOL", text: "Absent Without Leave (AWOL)" },
                { value: "AWOL2", text: "Absent Without Leave | 2hrs after shift (AWOL2)" },
                { value: "OC", text: "Office Close" },
                { value: "AA", text: "Authorized Absence " },
            ];
            const suspendedOptions = [
                { value: "SUS", text: "Suspended" },
            ];

            if (checkbox.value === 'leave' && checkbox.checked) {
                // Clear the dropdown and add only leave options
                leaveTypeDropdown.innerHTML = '';
                leaveOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    leaveTypeDropdown.appendChild(opt);
                });
            } else if (checkbox.value === 'ab' && checkbox.checked) {
                // Clear the dropdown and add only absent options
                leaveTypeDropdown.innerHTML = '';
                absentOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    leaveTypeDropdown.appendChild(opt);
                });
            } else if (checkbox.value === 'sus' && checkbox.checked) {
                // Clear the dropdown and add only absent options
                leaveTypeDropdown.innerHTML = '';
                suspendedOptions.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    leaveTypeDropdown.appendChild(opt);
                });
            } else {
                // Restore original options if "Leave" is unchecked
                leaveTypeDropdown.innerHTML = `
                    <option value="<?=$remarks;?>"><?=$remarks;?></option>
                    <option value="P">Present</option>
                    <option value="P-EO">Present - Early Out (P-EO)</option>
                    <option value="VL">Vacation Leave (VL)</option>
                    <option value="SL">Sick Leave - with Med Cert (SL)</option>
                    <option value="SL-NC" required> Sick Leave - Natural Calamity (SL-NC)</option>
                    <option value="SL-IO" required> Sick Leave - Internet Outage (SL-IO)</option>
                    <option value="SL-PO" required> Sick Leave - Power Outage (SL-PO)</option>
                    <option value="SL-GS" required> Sick Leave - Government Sanctioned (SL-GS)</option>
                    <option value="PTO">Unpaid Leave (PTO)</option>
                    <option value="MTL">Maternity Leave (MTL)</option>
                    <option value="PTL">Paternity Leave (PTL)</option>
                    <option value="SPL">Solo Parent Leave (SPL)</option>
                    <option value="BL">Bereavement Leave (BL)</option>
                    <option value="MDL">Medical Leave (MDL)</option>
                    <option value="LTL">Long Term Leave (LTL)</option>
                    <option value="BLP">Birthday Leave (BLP)</option>
                    <option value="EO">Early Out (EO)</option>
                    <option value="EEO">Emergency Early Out Leave (EEO)</option>
                    <option value="AWOL">Absent Without Leave (AWOL)</option>
                    <option value="AWOL2">Absent Without Leave | 2hrs after shift(AWOL2)</option>
                    <option value="CI">Absent</option>
                     <option value="AA">Authorized Absence </option>
                    <option value="OC">Office Close  </option> 
                    <option value="SUS">Suspended</option>
                `;
            }
        });
    });
</script>