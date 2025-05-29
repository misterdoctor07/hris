<?php
$period=$_GET['period'];
$idno=$_GET['idno'];
$company=$_GET['company'];
$sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
$employee=mysqli_fetch_array($sqlEmployee);

function isNightShift($startShift, $endShift) {
  // Check if the shift starts at midnight and ends in the morning
  return (($startShift == '00:00:00' && $endShift == '09:00:00')||( $startShift == '23:00:00' && $endShift == '08:00:00'));
}

$sqlEmployeeDetails=mysqli_query($con,"SELECT * FROM employee_details WHERE idno='$idno'");
$employeedetails=mysqli_fetch_array($sqlEmployeeDetails);

$sqlPayroll=mysqli_query($con,"SELECT * FROM payroll WHERE id='$period'");
if(mysqli_num_rows($sqlPayroll)>0){
  $resPayroll=mysqli_fetch_array($sqlPayroll);
  $periodstart=$resPayroll['periodfrom'];
  $periodend=$resPayroll['periodto'];
  $workdays = $resPayroll['days'];
}else{
  $periodstart="";
  $periodend="";
}
$okay=0;
$payroll_id="";
$sqlPayrollDetails=mysqli_query($con,"SELECT * FROM payroll_details WHERE payrollperiod='$period' AND idno='$idno'");
if(mysqli_num_rows($sqlPayrollDetails)>0){
  $pd=mysqli_fetch_array($sqlPayrollDetails);
  $payroll_id=$pd['id'];
  $okay=1;
}
$sqlPayrollInfo = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno='$idno'");
if (mysqli_num_rows($sqlPayrollInfo) > 0) {
    $payrollInfo = mysqli_fetch_array($sqlPayrollInfo);
    $salary_type = $payrollInfo['salary_type']; // Fetch the salary type
} else {
    $salary_type = 'Rated'; // Default value if not set
}

    ?>
    <script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?managepayroll&period=<?=$period;?>&company=<?=$company;?>"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> EDIT PAYROLL</h4>
    </div>
    </div>
    <?php
    if(!isset($_GET['deduction']) && !isset($_GET['addons']) && !isset($_GET['benefits'])){
    ?>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editpayroll">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
   <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="editpayroll">
    <input type="hidden" name="addedby" value="<?=$fullname;?>">
    <input type="hidden" name="period" value="<?=$period;?>">
    <input type="hidden" name="company" value="<?=$company;?>">
    <input type="hidden" name="idno" value="<?=$idno;?>">
    <div class="col-lg-12 mt">
        <div class="content-panel">
            
         <div class="panel-heading">
    <input type="submit" name="submitPayroll" class="btn btn-primary" value="Save Details" style="float:right;">
    <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&deduction&company=<?=$company;?>" class="btn btn-warning" style="float:right; margin-right:10px">Deductions</a>
    <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&addons&company=<?=$company;?>" class="btn btn-info" style="float:right; margin-right:10px">Addons</a>
    <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&benefits&company=<?=$company;?>" class="btn btn-success" style="float:right; margin-right:10px">Company Benefits</a>
    <a href='?edittime&idno=<?=$idno;?>&period=<?=$period;?>&logindate=&id=&company=<?=$company;?>' class='btn btn-default' title='Add Time' style='float:right; margin-right:10px'><i class='fa fa-plus'></i> Add Time</a>
    <?php if ($okay == 1) { ?>
      <?php if ($salary_type == 'Rated') { ?>
        <a href="payslipRated.php?id=<?=$payroll_id;?>" class="btn btn-warning" title="Print Payslip" target="_blank" style="float:right; margin-right:10px;"><i class='fa fa-print'></i></a>
      <?php } else if ($salary_type == 'Fixed') { ?>  
        <a href="payslip.php?id=<?=$payroll_id;?>" class="btn btn-warning" title="Print Payslip" target="_blank" style="float:right; margin-right:10px;"><i class='fa fa-print'></i></a>
      <?php } ?>
    <?php } ?>
    <div class="form-group" style="display: block; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <label class="col-sm-2 col-sm-2 control-label">Salary Type:</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="salary_type" value="Fixed" <?= ($salary_type === 'Fixed') ? 'checked' : ''; ?>> Fixed
            </label>
            <label class="radio-inline">
                <input type="radio" name="salary_type" value="Rated" <?= ($salary_type === 'Rated') ? 'checked' : ''; ?>> Rated
            </label>
        </div>
    </div>
    <div>
        <span style="display: block; font-family: Arial, sans-serif; font-size: 14px; color: #333; margin-bottom: 5px;">
            <i class="fa fa-user"></i> 
            <strong style="font-size: 18px;"> <?=$employee['lastname'];?>, 
            <?=$employee['firstname'];?> <?=$employee['suffix'];?></strong>
        </span>
        <span style="display: block; font-family: Arial, sans-serif; font-size: 14px; color: #333; margin-bottom: 5px;">
            Shift: <?= date("h:i A", strtotime($employeedetails['startshift'])); ?> - <?= date("h:i A", strtotime($employeedetails['endshift'])); ?>
        </span>
        <span style="display: block; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
            Location: <?=$employeedetails['location'];?>
        </span>
    </div>
</div>
            <div class="panel-body">
              <div class="form-group">
                <div class="col-sm-12">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="text-align: center; vertical-align: middle;">Date</th>
                      <th style="text-align: center; vertical-align: middle;">Time In</th>
                      <th style="text-align: center; vertical-align: middle;">Time Out</th>
                      <th style="text-align: center; vertical-align: middle;">Time In</th>
                      <th style="text-align: center; vertical-align: middle;">Time Out</th>
                      <th style="text-align: center; vertical-align: middle;">Total Hrs</th>
                      <th style="text-align: center; vertical-align: middle;">Reg Hrs</th>
                      <th style="text-align: center; vertical-align: middle;" width="5%">Hrs Not Work</th>
                      <th style="text-align: center; vertical-align: middle;">OT</th>
                      <th style="text-align: center; vertical-align: middle;">ND</th>
                      <th style="text-align: center; vertical-align: middle;">Rate/Day</th>
                      <th style="text-align: center; vertical-align: middle;" width="5%">Reg Days OT Rate</th>
                      <th style="text-align: center; vertical-align: middle;">ND Rate</th>
                      <th style="text-align: center; vertical-align: middle;" width="5%">Special Non Working Holiday</th>
                      <th style="text-align: center; vertical-align: middle;" width="5%">OT Special Holiday</th>
                      <th style="text-align: center; vertical-align: middle;" width="5%">OT Regular Holiday</th>
                      <th style="text-align: center; vertical-align: middle;" width="6%">Reg Holidays</th>
                      <th style="text-align: center; vertical-align: middle;">Total Pay</th>
                      <th style="text-align: center; vertical-align: middle;" width="4%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $totalhours=0;
                    $regularhours=0;
                    $totalovertime=0;
                    $totalhoursnotworked=0;
                    $totalregdaysot=0;
                    $totalndhrs=0;
                    $totalndrate=0;
                    $totalbasesalary=0;
                    $totalspholiday=0;
                    $totalspholidayot=0;
                    $totalregholiday=0;
                    $totalregholidayot=0;
                    $grandtotal=0;
                    $regular_hours=0;
                    $hoursnotworkedamount=0;
                    $regholidaywork1=0;
                    $regholidayworkamount1=0;
                    $regholidaywork2=0;
                    $regholidayworkamount2=0;
                    $regholidayothrs=0;
                    $regholidayotamount=0;
                    $spholidayhours1=0;
                    $spholidayamount1=0;
                    $spholidayhours2=0;
                    $spholidayamount2=0;
                    $spholidayothours=0;
                    $paidSLhrs=0;
                    $paidSLamount=0;
                    $paidVLhrs=0;
                    $paidVLamount=0;
                    $paidBLhrs=0;
                    $paidBLamount=0;
                    $bdayleavehrs=0;
                    $bdayleaveamount=0;
                    $sqlEmployeeShift = mysqli_query($con, "SELECT startshift, endshift FROM employee_details WHERE idno = '$idno'");
                    if (mysqli_num_rows($sqlEmployeeShift) > 0) {
                        $shiftDetails = mysqli_fetch_array($sqlEmployeeShift);
                        $shiftStart = $shiftDetails['startshift'];
                        $shiftEnd = $shiftDetails['endshift'];
                    
                        // Check if the employee is on a night shift
                        $isNightShift = isNightShift($shiftStart, $shiftEnd);
                    
                        // Adjust the period start and end dates for night shifts
                        if ($isNightShift) {
                            $adjustedPeriodStart = date('Y-m-d', strtotime($periodstart . ' -1 day'));
                            $adjustedPeriodEnd = date('Y-m-d', strtotime($periodend . ' -1 day'));
                        } else {
                            $adjustedPeriodStart = $periodstart;
                            $adjustedPeriodEnd = $periodend;
                        }
                    } else {
                        // Default to regular shift if shift details are not found
                        $isNightShift = false;
                        $adjustedPeriodStart = $periodstart;
                        $adjustedPeriodEnd = $periodend;
                    }
                    
                    // Query attendance with adjusted dates for night shifts
                    $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance 
                                                         WHERE logindate BETWEEN '$adjustedPeriodStart' AND '$adjustedPeriodEnd' 
                                                         AND idno = '$idno' 
                                                         GROUP BY id 
                                                         ORDER BY logindate ASC");
                    $ab_count = $ab_total = $pto_count = $pto_total = $mtl_count = $mtl_total = $mdl_count = $mdl_total = $ltl_count = $ltl_total = $suspended_count = $suspended_total = 0;
                    if (mysqli_num_rows($sqlAttendance) > 0) {
                        while ($attendance = mysqli_fetch_array($sqlAttendance)) {
                            $attendid = $attendance['id'];
                            $sqlEmployeePayroll = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno = '$idno'");
                            $employeepayroll = mysqli_fetch_array($sqlEmployeePayroll);
                    
                            $sqlEmployeeDetails = mysqli_query($con, "SELECT * FROM employee_details WHERE idno='$idno'");
                            $EmployeeDetails = mysqli_fetch_array($sqlEmployeeDetails);

                            $startshift = $EmployeeDetails['startshift'];
                            $endshift = $EmployeeDetails['endshift'];
                            $empsalary = $employeepayroll['salary'] ?? 0;
                            $logindate = $attendance['logindate'];
                            $loginam = ( $attendance['loginam'] === "0") ? "0" : date('h:i:s A', strtotime($attendance['loginam']));
                            $logoutam = ( $attendance['logoutam'] === "0") ? "0" : date('h:i:s A', strtotime($attendance['logoutam']));
                            $loginpm = ( $attendance['loginpm'] === "0") ? "0" : date('h:i:s A', strtotime($attendance['loginpm']));
                            $logoutpm = ($attendance['logoutpm'] === "0") ? "0" : date('h:i:s A', strtotime($attendance['logoutpm']));
                            $status = $attendance['status'];
                            $ab_count = substr_count($status, "ab");
                            $ab_total += substr_count($status, "ab");
                            $remarks = $attendance['remarks'];
                            $pto_count = substr_count($remarks, "PTO");
                            $pto_total += substr_count($remarks, "PTO");
                            $mtl_count = substr_count($remarks, "MTL");
                            $mtl_total += substr_count($remarks, "MTL");
                            $mdl_count = substr_count($remarks, "MDL");
                            $mdl_total += substr_count($remarks, "MDL");
                            $ltl_count = substr_count($remarks, "LTL");
                            $ltl_total += substr_count($remarks, "LTL");
                            $suspended_count = substr_count($remarks, "SUS");
                            $suspended_total += substr_count($remarks, "SUS");
                            // Adjust the displayed logindate for night shifts
                            if ($isNightShift) {
                                $displayLogindate = date('m/d/Y', strtotime($logindate . ' +1 day'));
                            } else {
                                $displayLogindate = date('m/d/Y', strtotime($logindate));
                            }
                            $time1am = ( $loginam === "0") ? 0 : strtotime($loginam);
                            $time2am = ($logoutam === "0") ? 0 : strtotime($logoutam);
                            $time1pm = ( $loginpm === "0") ? 0 : strtotime($loginpm);
                            $time2pm = ( $logoutpm === "0") ? 0 : strtotime($logoutpm);
                            $timestart = strtotime($startshift);
                            $timeend = strtotime($endshift);
                    
                            $difference_am = round(abs($time2am - $time1am) / 3600, 2);
                            $difference_pm = round(abs($time2pm - $time1pm) / 3600, 2);
                            $totalstart = round(abs($timestart - $time2am) / 3600, 2);
                            $totalam = round(abs($time2am  - $time2pm) / 3600, 2);
                            $shiftdiff = round(abs($timestart - $timeend) / 3600, 2);
                            // Convert times to Unix timestamps


                            // Check if the shift crosses midnight
                            if ($time2pm < $time1am) {
                              // Add 24 hours to the end time
                              $time2pm += 86400;
                            }
                            
                            // Calculate total working hours
                            $totalwo = (round(abs($time2pm - $time1am) / 3600, 2)-1);
                            
                            // Subtract break time (if applicable)
                            $totalhrs = $totalwo;
                            $totalhrs -= 0.5;
                    
                            $nd = 0;
                            $work = 0;
                            $rh = 0;
                            $snwh = 0;
                            $leave = 0;
                            $ndhrs = 0;
                            $ot = 0;
                            $pot = 0;
                            $ab = 0;
                            $sus =0;
                            $totalhrs = 0;
                           

                        if($employeedetails['startshift']=="00:00:00"){
                          $reghrs=7;
                        }else{
                          $reghrs=8;
                        }

                        $overtime=0;
                        $hrsnotworked=0;
                        $regdaysot=0;
                        $snwh=0;
                        $salary=0;
                        $spholiday=0;
                        $spholidayot=0;
                        $regholiday=0;
                        $regholidayot=0;
                        $totalpay=0;
                        $p=explode('/',$status);
                        
                        for($i=0;$i<sizeof($p);$i++){
                          if($p[$i]=="nd"){
                            $nd++;
                          }
                          if($p[$i]=="work"){
                            $work++;
                          }
                          if($p[$i]=="rh"){
                            $rh++;
                          }
                          if($p[$i]=="snwh"){
                            $snwh++;
                          }
                          if($p[$i]=="leave"){
                            $leave++;
                          }
                          if($p[$i]=="ot"){
                            $ot++;
                          }
                          if($p[$i]=="pt"){
                            $pot++;
                          }
                          if($p[$i]=="ab"){
                                $ab++;
                           }
                          if($p[$i]=="sus"){
                                $sus++;
                          }
                        }

                       
                        if($work > 0){ //Regular worked without Night Differential
                            
                          
                          if($totalwo>8){
                            $reghrs=$totalwo-($totalwo-8);
                          }
                        }

                        if($sus > 0){//On Leave
                          $empsalary = 0;
                          $totalwo=0;
                        }
                        if($ab > 0){//On Leave
                          $empsalary = 0;
                          $totalwo=0;
                        }
                      
                        
                        if ($nd > 0 && $work > 0) { // Regular work with Night Differential
                            $startShift = $employeedetails['startshift']; // Get the employee's shift start time
                            $ndhrs = 0; 
                            $totalhrs = 0;
                        
                            if ($startShift == "00:00:00") {
                                // Shift starting at midnight (Full night shift)
                                $ndhrs1 = round(abs(strtotime('06:00:00') - $time1am) / 3600, 2);
                                $ndhrs2 = round(abs($time2am - strtotime('06:00:00')) / 3600, 2);
                                $ndhrs = $ndhrs1; // ND applies only until 6 AM
                                $totalhrs = $ndhrs1 + $ndhrs2 + $difference_pm;
                        
                            } elseif ($startShift == "03:00:00") {
                                // Shift starting at 3:00 AM (Partial Night Differential)
                                $ndhrs1 = round(abs(strtotime('06:00:00') - $time1am) / 3600, 2);
                                $ndhrs2 = round(abs($time2am - strtotime('06:00:00')) / 3600, 2);
                                $ndhrs = $ndhrs1; // ND applies only until 6 AM
                                $totalhrs = $ndhrs1 + $ndhrs2 + $difference_pm;
                        
                            } elseif ($startShift == "04:00:00") {
                                // Shift starting at 4:00 AM (Limited Night Differential)
                                $ndhrs1 = round(abs(strtotime('06:00:00') - $time1am) / 3600, 2);
                                $ndhrs2 = round(abs($time2am - strtotime('06:00:00')) / 3600, 2);
                                $ndhrs = $ndhrs1; // ND only applies for 2 hours (4 AM - 6 AM)
                                $totalhrs = $ndhrs1 + $ndhrs2 + $difference_pm;
                        
                            } else {
                                // Default calculation for other shifts
                                $totalhrs = $difference_am + $difference_pm;
                                $ndhrs = $totalhrs;
                            }
                        
                            // Adjust regular hours
                            if ($totalhrs > $reghrs) {
                                $reghrs = $totalhrs - ($totalhrs - $reghrs);
                                $ndhrs = $reghrs;
                            } else {
                                $reghrs = $totalhrs;
                            }
                        }

                        
                        
                        
                        if($nd>0 && $pot>0 && $employeedetails['location']=="OS"){ //Night differential with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          if($totalhrs>8.17){
                            $totalhrs1=8.17;
                          }else{
                            if($employeedetails['startshift']=="00:00:00"){
                              $totalhrs1=$totalhrs;
                              $totalhrs=$totalhrs1;
                              $ndhrs=1;
                              $reghrs=$totalhrs-.17;
                            }
                            $totalhrs1=$totalhrs;
                          }
                          $totalhrs=$totalhrs1;
                          $overtime=$totalhrs-$reghrs;
                          if($employeedetails['startshift']=="04:00:00"){
                            $ndhrs=$ndhrs1=round(abs(strtotime('06:00:00') - $time1am) / 3600,2);
                          }
                        }
                        if($nd>0 && $ot>0 && $pot==0){ //Night differential with Overtime afer 8 hrs worked
                        
                            if($employeedetails['startshift']=="00:00:00"){
                              $totalhrs1=$totalwo;
                              $totalwo=$totalhrs1;
                            }
                            $totalhrs1=$totalwo;

                          $totalwo=$totalhrs1;
                          $overtime=$totalwo-$reghrs;
                          if($employeedetails['startshift']=="04:00:00"){
                            $ndhrs=round(abs(strtotime('06:00:00') - $time1am) / 3600,2);
                          }
                        }
                        if($work>0 && $pot>0 && $employeedetails['location']=="OS"){ //Regular Work with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $thrs=round(abs(strtotime($employeedetails['startshift'])-$time1am));
                          if($totalhrs>8.17){
                            $totalhrs=8.17;
                          }
                          $overtime=$totalhrs-$reghrs;
                          //$empsalary = ($empsalary/8) * $totalhrs;
                        }
                        if($work>0 && $pot>0 && $leave > 0 && $employeedetails['location']=="OS"){ //Regular Work with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $thrs=round(abs(strtotime($employeedetails['startshift'])-$time1am));
                          if($totalhrs>8.17){
                            $totalhrs=8.17;
                          }
                          $overtime=$totalhrs-$reghrs;
                          $empsalary = ($empsalary/8) * $totalhrs;
                        }
                        if($work>0 && $pot>0 && $ot>0){//REgular work with OT before and OT After 8 hours of worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=$totalhrs-$reghrs;
                        }
                        if($nd>0 && $pot>0 && $employeedetails['location']=="WFH"){ //Night differential with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=0;
                          $ndhrs=0;
                        }
                        if($nd>0 && $pot>0 && $employeedetails['location']=="WFH"){ //Night differential with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=0;
                          $ndhrs=0;
                        }
                        if($nd>0 && $ot>0 && $work){ //$Night Differential with Overtime after 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=$totalhrs-$reghrs;
                        }
                       if ($work > 0 && $ot > 0) { // Regular with Overtime after 8 hrs worked
                                $endShift = strtotime($employeedetails['endshift']); // Convert end shift time to Unix timestamp
                                $time2pm = strtotime($logoutpm); // Convert PM logout time to Unix timestamp
                            
                                // Calculate overtime hours
                                $overtime = round(abs($endShift - $time2pm) / 3600, 2); // Difference in hours
                            }
                        if($rh>0 && $nd==0 && $work==0){ //Regular Holiday Not worked
                          $totalhrs=0;
                          $reghrs=0;
                          $hrsnotworked=$difference_am+$difference_pm;
                        }

                        if(($ot>0 || $pot>0) && ($nd>0 || $work>0)){ //Regular work with overtime before and after 8 hrs of worked
                          $regdaysot=(($empsalary/8)*1.25)*$overtime;
                        }
                        if($nd>0 && $snwh>0){
                          if($employeedetails['startshift']=="04:00:00"){
                            $ndhrs1=round(abs(strtotime('06:00:00') - $time1am) / 3600,2);
                            $ndhrs2=round(abs($time2am - strtotime('06:00:00')) / 3600,2);
                            $sh1=(($empsalary/8)*1.43)*$ndhrs1;
                            $sh2=(($empsalary/8)*1.3)*($ndhrs2+$difference_pm);
                            $spholiday=$sh1+$sh2;
                            $spholidayhours1 +=$ndhrs1;
                            $spholidayamount1 +=$sh1;
                            $spholidayhours2 +=$ndhrs2+$difference_pm;
                            $spholidayamount2 +=$sh2;
                          }else{
                            $thours=$difference_am+$difference_pm;
                            $spholiday=(($empsalary/8)*1.43)*$thours;
                            $spholidayhours1 +=$thours;
                            $spholidayamount1 +=$spholiday;
                            $spholidayhours2 +=0;
                            $spholidayamount2 +=0;
                          }
                          $empsalary=0;
                          $ndhrs=0;
                        }
                        if($work>0 && $snwh>0){//Special Non Working holiday without ND overtime rate
                            $thours=$difference_am+$difference_pm;
                            $spholiday=(($empsalary/8)*2)*$thours;
                            $spholidayhours2 +=$thours;
                            $spholidayamount2 +=$spholiday;
                            $spholidayhours1 +=0;
                            $spholidayamount1 +=0;
                          $empsalary=0;
                          $ndhrs=0;
                        }
                        if(($work>0 || $leave>0) && $rh>0){//Regular holiday without Night Differential overtime rate
                            $thours=$difference_am+$difference_pm;
                            $regholiday=(($empsalary/8)*1.3)*$thours;
                            $regholidaywork2 +=$thours;
                            $regholidayworkamount2 +=$regholiday;
                            $regholidaywork1 +=0;
                            $regholidayworkamount1 +=0;
                          $empsalary=0;
                          $ndhrs=0;
                        }
                        if($leave>0){
                          $tothours=$difference_am+$difference_pm;
                          $leaveamount=(($empsalary/8))*$tothours;
                          if($remarks=="VL"){
                            $paidVLhrs +=$tothours;
                            $paidVLamount +=$leaveamount;
                          }
                          if($remarks=="SL"){
                            $paidSLhrs +=$tothours;
                            $paidSLamount +=$leaveamount;
                          }
                          if($remarks=="BL"){
                            $paidBLhrs +=$tothours;
                            $paidBLamount +=$leaveamount;
                          }
                          if($remarks=="BLP"){
                            $bdayleavehrs +=$tothours;
                            $bdayleaveamount +=$leaveamount;
                          }
                          if($remarks=="PTL"){
                            $paidVLhrs +=$tothours;
                            $paidVLamount +=$leaveamount;
                          }
                          if($remarks=="SPL"){
                            $paidVLhrs +=$tothours;
                            $paidVLamount +=$leaveamount;
                          }
                        }
                        if($ot > 0 && ($nd>0 || $work>0) && $snwh>0){ // Special Non working holiday overtime rate
                          $spholidayot=((($empsalary/8)*1.3)*1.3)*$overtime;
                          $spholidayothours +=$overtime;
                        }
                        if(($ot>0 || $pot>0) && $rh>0 && ($work>0 || $nd>0)){//Regular Holiday Overtime Rate
                          $regholidayot=((($empsalary/8)*2)*1.3)*$overtime;
                          $regholidayothrs +=$overtime;
                          $regholidayotamount +=$regholidayot;
                        }
                        if($rh > 0 && $nd>0){//Regular holiday with Night Differential Overtime Rate
                          if($employeedetails['startshift']=="04:00:00"){
                            $ndhrs1=round(abs(strtotime('06:00:00') - $time1am) / 3600,2);
                            $ndhrs2=round(abs($time2am - strtotime('06:00:00')) / 3600,2);
                            $sh1=(($empsalary/8)*2.2)*$ndhrs1;
                            $sh2=(($empsalary/8)*2)*($ndhrs2+$difference_pm);
                            $regholiday=$sh1+$sh2;
                            $regholidaywork1 +=$ndhrs1;
                            $regholidayworkamount1 +=$sh1;
                            $regholidaywork2 +=$ndhrs2+$difference_pm;
                            $regholidayworkamount2 +=$sh2;
                          }else{
                            $thours=$difference_am+$difference_pm;
                            $regholiday=(($empsalary/8)*2.2)*$thours;
                            $regholidaywork1 +=$thours;
                            $regholidayworkamount1 +=$regholiday;
                            $regholidaywork2 +=0;
                            $regholidayworkamount2 +=0;
                          }
                          $empsalary=0;
                          $ndhrs=0;
                        }
                        $ndrate=$ndhrs*(($empsalary/8)*.1);
                        if($employeedetails['startshift']=="00:00:00"){
                          $empsalary=($empsalary/8)*$reghrs;
                        }
                        
                            $startshift = strtotime($employeedetails['startshift']); // Example shift start time
                            $endshift = strtotime($employeedetails['endshift']); // Example shift end time
                            
                            // Convert login/logout times to timestamps
                            $loginam = strtotime($loginam);
                            $logoutam = strtotime($logoutam);
                            $loginpm = strtotime($loginpm);
                            $logoutpm = strtotime($logoutpm);
                        
                             $loginam_adjusted = $loginam;
                             $logoutam_adjusted = $logoutam;
                             $loginpm_adjusted = $loginpm;
                             $logoutpm_adjusted = $logoutpm;
                             
                            if ($startshift == strtotime("12:00 AM")) {
                        // Handle night shift (startshift at midnight)
                        if ($loginam >= strtotime("11:50 PM") && $loginam < $startshift) {
                            $loginam_adjusted = $startshift - 600; // Subtract 10 minutes from shift start
                        }
                    } else {
                        // Handle day shift
                        if ($loginam < $startshift && ($startshift - $loginam) >= 600) {
                            $loginam_adjusted = $startshift - 600; // Subtract 10 minutes from shift start
                        }
                    }
                            // Adjust logoutam and loginpm
                            $logoutam_adjusted = $logoutam;
                            $loginpm_adjusted = $loginpm;
                            
                            if (abs($logoutam - $loginpm) <= 3600) { // 3600 seconds = 1 hour
                                if ($logoutam >= strtotime("12:00 PM") && $logoutam < strtotime("06:00 PM")) { // Day shift
                                    $logoutam_adjusted = strtotime("12:00 PM");
                                    $loginpm_adjusted = strtotime("01:00 PM");
                                } else { // Night shift
                                    $logoutam_adjusted = strtotime("06:00 AM");
                                    $loginpm_adjusted = strtotime("07:00 AM");
                                }
                            }
                            
                        $sqlPayrollDetails=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
                        $payrolldetails=mysqli_fetch_array($sqlPayrollDetails);
                        $salary = $payrolldetails['salary'];
                       if($salary_type == "Rated"){
                            $totalpay = $empsalary + $regdaysot + $ndrate + $spholiday + $spholidayot + $regholiday + $regholidayot;
                        }else if ($salary_type == "Fixed") {
                            // Calculate deduction based on number of absences and unpaid leaves
                            $deduction_per_absence = $salary;
                        
                            // Sum up all unpaid leave and absence counts
                            $total_deductions_count = $ab_count + $pto_count + $mtl_count + $mdl_count + $ltl_count + $suspended_count;
                        
                            // Calculate total deduction
                            $total_deduction = $deduction_per_absence * $total_deductions_count;
                        
                            // Compute total pay after deductions
                            $totalpay = $salary - $total_deduction;
                        
                            // For clarity (though it's just $salary)
                            $empsalary = $salary;
                        }
                        
                            
                        echo "<tr>";
                          echo "<td align='center'>" . date('m/d/Y',strtotime($displayLogindate))."</td>";
                          echo "<td align='center'>" . date('h:i A', $loginam_adjusted) . "</td>";
                          echo "<td align='center'>" . date('h:i A', $logoutam_adjusted) . "</td>";
                          echo "<td align='center'>" . date('h:i A', $loginpm_adjusted) . "</td>";
                          echo "<td align='center'>" . date('h:i A', $logoutpm_adjusted) . "</td>";
                          echo "<td align='center'>$totalwo</td>";
                          echo "<td align='center'>$reghrs</td>";
                          echo "<td align='center'>$hrsnotworked</td>";
                          echo "<td align='center' data-id='$attendid'>$overtime</td>";
                          echo "<td align='center'>$ndhrs</td>";
                          echo "<td align='right'>".number_format($empsalary,2)."</td>";
                          echo "<td align='right'>".number_format($regdaysot,2)."</td>";
                          echo "<td align='right'>".number_format($ndrate,2)."</td>";
                          echo "<td align='right'>".number_format($spholiday,2)."</td>";
                          echo "<td align='right'>".number_format($spholidayot,2)."</td>";
                          echo "<td align='right'>".number_format($regholidayot,2)."</td>";
                          echo "<td align='right'>".number_format($regholiday,2)."</td>";
                          echo "<td align='right'>".number_format($totalpay,2)."</td>";
                          echo "<td align='center'><a href='?edittime&idno=$idno&period=$period&id=$attendid&company=$company' title='Edit Time'><i class='fa fa-edit'></i></a> | ";
                        ?>
                        <a href='?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&id=<?=$attendid;?>&deletetime&company=<?=$company;?>' title='Delete Time' onclick="return confirm('Do you wish to remove this attendance?'); return false;"><i class='fa fa-trash'></i></a></td>
                        <?php
                        echo "</tr>";
                        if(($nd>0 || $work>0) && $rh==0 && $snwh==0 && $leave==0){
                          $regular_hours +=$reghrs;
                        }
                        $hoursnotworkedamount +=($empsalary/8)*$hrsnotworked;
                        $totalhours +=$totalwo;
                        $regularhours +=$reghrs;
                        $totalovertime +=$overtime;
                        $totalhoursnotworked +=$hrsnotworked;
                        $totalregdaysot +=$regdaysot;
                        $totalndhrs +=$ndhrs;
                        $totalndrate +=$ndrate;
                        $totalbasesalary +=$empsalary;
                        $totalspholiday +=$spholiday;
                        $totalspholidayot +=$spholidayot;
                        $totalregholiday +=$regholiday;
                        $totalregholidayot +=$regholidayot;
                        
                        if($salary_type == "Rated"){
                            $grandtotal +=$totalpay;
                        }else if ($salary_type == "Fixed") {
                            // Combine all unpaid leave types and absences
                            $total_deductions = $ab_total + $pto_total + $mtl_total + $mdl_total + $ltl_total + $suspended_total;
                        
                            if ($workdays <= 10) {
                                $grandtotal = ($salary * 10) - ($salary * $total_deductions);
                            } else { // $workdays >= 11
                                $working_days_present = $workdays - $total_deductions;
                        
                                if ($working_days_present >= 10) {
                                    $grandtotal = $salary * 10;
                                } else {
                                    $deducted_absences = 10 - $working_days_present;
                                    $grandtotal = ($salary * 10) - ($salary * $deducted_absences);
                                }
                            }
                        }
                      }
                    } else {
                        $sqlPayrollDetails=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
                        $payrolldetails=mysqli_fetch_array($sqlPayrollDetails);
                        $salary = $payrolldetails['salary'];
                        if ($salary_type == "Fixed") {
                            // Combine all unpaid leave types and absences
                            $total_deductions = $ab_total + $pto_total + $mtl_total + $mdl_total + $ltl_total + $suspended_total;
                        
                            if ($workdays <= 10) {
                                $grandtotal = ($salary * 10) - ($salary * $total_deductions);
                            } else { // $workdays >= 11
                                $working_days_present = $workdays - $total_deductions;
                        
                                if ($working_days_present >= 10) {
                                    $grandtotal = $salary * 10;
                                } else {
                                    $deducted_absences = 10 - $working_days_present;
                                    $grandtotal = ($salary * 10) - ($salary * $deducted_absences);
                                }
                            }
                        }
                    }
                    ?>
                    <tr>
                      <td colspan="5" align='right' style="font-size: 1.3rem"><strong>TOTAL</strong></td>
                      <td align='center' style="font-size: 1.3rem"><strong><?=number_format($totalhours,2);?></strong></td>
                      <td align='center' style="font-size: 1.3rem"><strong><?=number_format($regularhours,2);?></strong></td>
                      <td align='center' style="font-size: 1.3rem"><strong><?=number_format($totalhoursnotworked,2);?></strong></td>
                      <td align='center' style="font-size: 1.3rem"><strong><?=number_format($totalovertime,2);?></strong></td>
                      <td align='center' style="font-size: 1.3rem"><strong><?=number_format($totalndhrs,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalbasesalary,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalregdaysot,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalndrate,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalspholiday,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalspholidayot,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalregholidayot,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($totalregholiday,2);?></strong></td>
                      <td align='right' style="font-size: 1.3rem"><strong><?=number_format($grandtotal,2);?></strong></td>
                    </tr>
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <input type="hidden" name="reghours" value="<?=$regular_hours;?>">
        <input type="hidden" name="reghoursot" value="<?=$totalovertime;?>">
        <input type="hidden" name="reghoursotamount" value="<?=$totalregdaysot;?>">
        <input type="hidden" name="reghoursnw" value="<?=$totalhoursnotworked;?>">
        <input type="hidden" name="reghoursnwamount" value="<?=$hoursnotworkedamount;?>">
        <input type="hidden" name="regholidaywork1" value="<?=$regholidaywork1;?>">
        <input type="hidden" name="regholidayworkamount1" value="<?=$regholidayworkamount1;?>">
        <input type="hidden" name="regholidaywork2" value="<?=$regholidaywork2;?>">
        <input type="hidden" name="regholidayworkamount2" value="<?=$regholidayworkamount2;?>">
        <input type="hidden" name="regholidayothrs" value="<?=$regholidayothrs;?>">
        <input type="hidden" name="regholidayotamount" value="<?=$regholidayotamount;?>">
        <input type="hidden" name="spholidayhours1" value="<?=$spholidayhours1;?>">
        <input type="hidden" name="spholidayamount1" value="<?=$spholidayamount1;?>">
        <input type="hidden" name="spholidayhours2" value="<?=$spholidayhours2;?>">
        <input type="hidden" name="spholidayamount2" value="<?=$spholidayamount2;?>">
        <input type="hidden" name="spholidayothrs" value="<?=$spholidayothours;?>">
        <input type="hidden" name="spholidayotamount" value="<?=$totalspholidayot;?>">
        <input type="hidden" name="ndhrs" value="<?=$totalndhrs;?>">
        <input type="hidden" name="ndamount" value="<?=$totalndrate;?>">
        <input type="hidden" name="paidSLhrs" value="<?=$paidSLhrs;?>">
        <input type="hidden" name="paidSLamount" value="<?=$paidSLamount;?>">
        <input type="hidden" name="paidVLhrs" value="<?=$paidVLhrs;?>">
        <input type="hidden" name="paidVLamount" value="<?=$paidVLamount;?>">
        <input type="hidden" name="paidBLhrs" value="<?=$paidBLhrs;?>">
        <input type="hidden" name="paidBLamount" value="<?=$paidBLamount;?>">
        <input type="hidden" name="bdayleavehrs" value="<?=$bdayleavehrs;?>">
        <input type="hidden" name="bdayleaveamount" value="<?=$bdayleaveamount;?>">
        <input type="hidden" name="totalpay" value="<?=$grandtotal;?>">
        </form>
<?php
}
if(isset($_GET['deduction'])){
?>
        <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editpayroll">
      <input type="hidden" name="deduction">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&company=<?=$company;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
              <h4><i class="fa fa-file-text"></i> Deduction Details</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Description</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="description" required >
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Amount</label>
                  <div class="col-sm-3">
                  <input type="text" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitDeduction" class="btn btn-primary" value="Add Deduction">
                  </div>
                </div>
              </form>
              <div class="form-group">
                <label class="col-sm-4 col-sm-4 control-label">Deduction List</label>
                <div class="col-sm-3">
                </div>
              </div>
              <div class="form-group">
              <label class="col-sm-12 col-sm-12 control-label">
                  <table class="table">
                      <tr>
                          <td>Description</td>
                          <td>Amount</td>
                          <td width="5%"></td>
                      </tr>
                      <?php
                          $sqlDeduction=mysqli_query($con,"SELECT * FROM deductions");
                          if(mysqli_num_rows($sqlDeduction)>0){
                              while($deduc=mysqli_fetch_array($sqlDeduction)){
                                echo "<form name='$deduc[id]' method='get'>";
                                ?>
                                <input type="hidden" name="editpayroll">
                                <input type="hidden" name="deduction">
                                <input type="hidden" name="period" value="<?=$period;?>">
                                <input type="hidden" name="idno" value="<?=$idno;?>">
                                <input type="hidden" name="company" value="<?=$company;?>">
                                <?php
                                echo "<input type='hidden' name='description' value='$deduc[deduction]' />";
                                  echo "<tr>";
                                      echo "<td>$deduc[deduction]</td>";
                                      echo "<td align='right' width='20%'><input style='text-align:right;' type='text' name='amount' value='$deduc[amount]' class='form-control' /></td>";
                                      echo "<td><!--a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&addons&submitAddons&company=$company&description=$deduc[deduction]&amount=$deduc[amount]'>Add</a--><input type='submit' name='submitDeduction' value='Add' class='btn btn-success' /></td>";
                                  echo "<tr>";
                                  echo "</form>";
                              }
                          }
                      ?>
                  </table>
              </label>
              </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Deductions</label>
                  <div class="col-sm-3">
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-12 col-sm-12 control-label">
                    <table class="table table-bordered">
                        <tr>
                            <td>Description</td>
                            <td>Amount</td>
                            <td width="5%"></td>
                        </tr>
                        <?php
                            $sqlDeduction=mysqli_query($con,"SELECT * FROM payroll_deductions WHERE idno='$idno' AND payrollperiod='$period'");
                            if(mysqli_num_rows($sqlDeduction)>0){
                                while($deduc=mysqli_fetch_array($sqlDeduction)){
                                    echo "<tr>";
                                        echo "<td>$deduc[description]</td>";
                                        echo "<td>$deduc[amount]</td>";
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&deduction&remove&company=$company'>Remove</a></td>";
                                    echo "<tr>";
                                }
                            }
                        ?>
                    </table>
                </label>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <?php
}
        ?>

<?php
if(isset($_GET['addons'])){
?>
        <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editpayroll">
      <input type="hidden" name="addons">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&company=<?=$company;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
              <h4><i class="fa fa-file-text"></i> Addons Details</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Description</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="description" required >
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Amount</label>
                  <div class="col-sm-3">
                  <input type="text" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitAddons" class="btn btn-primary" value="Save Addons">
                  </div>
                </div>
                </form>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Addons List</label>
                  <div class="col-sm-3">
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-12 col-sm-12 control-label">
                    <table class="table">
                        <tr>
                            <td>Description</td>
                            <td>Amount</td>
                            <td width="5%"></td>
                        </tr>
                        <?php
                            $sqlDeduction=mysqli_query($con,"SELECT * FROM addons");
                            if(mysqli_num_rows($sqlDeduction)>0){
                                while($deduc=mysqli_fetch_array($sqlDeduction)){
                                  echo "<form name='$deduc[id]' method='get'>";
                                  ?>
                                  <input type="hidden" name="editpayroll">
                                  <input type="hidden" name="addons">
                                  <input type="hidden" name="period" value="<?=$period;?>">
                                  <input type="hidden" name="idno" value="<?=$idno;?>">
                                  <input type="hidden" name="company" value="<?=$company;?>">
                                  <?php
                                  echo "<input type='hidden' name='description' value='$deduc[addons]' />";
                                    echo "<tr>";
                                        echo "<td>$deduc[addons]</td>";
                                        echo "<td align='right' width='20%'><input style='text-align:right;' type='text' name='amount' value='$deduc[amount]' class='form-control' /></td>";
                                        echo "<td><!--a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&addons&submitAddons&company=$company&description=$deduc[addons]&amount=$deduc[amount]'>Add</a--><input type='submit' name='submitAddons' value='Add' class='btn btn-success' /></td>";
                                    echo "<tr>";
                                    echo "</form>";
                                }
                            }
                        ?>
                    </table>
                </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Addons Details</label>
                  <div class="col-sm-3">
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-12 col-sm-12 control-label">
                    <table class="table table-bordered">
                        <tr>
                            <td>Description</td>
                            <td>Amount</td>
                            <td width="5%"></td>
                        </tr>
                        <?php
                            $sqlDeduction=mysqli_query($con,"SELECT * FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$period'");
                            if(mysqli_num_rows($sqlDeduction)>0){
                                while($deduc=mysqli_fetch_array($sqlDeduction)){
                                    echo "<tr>";
                                        echo "<td>$deduc[description]</td>";
                                        echo "<td align='right'>".number_format($deduc['amount'],2)."</td>";
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&addons&removeAddons&company=$company'>Remove</a></td>";
                                    echo "<tr>";
                                }
                            }
                        ?>
                    </table>
                </label>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <?php
}
        ?>

<?php
if(isset($_GET['benefits'])){
?>
        <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editpayroll">
      <input type="hidden" name="benefits">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&company=<?=$company;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
              <h4><i class="fa fa-file-text"></i> Company Benefit Details</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Description</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="description" required >
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Amount</label>
                  <div class="col-sm-3">
                  <input type="text" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitBenefits" class="btn btn-primary" value="Save Benefits">
                  </div>
                </div>
              </form>
              <div class="form-group">
                <label class="col-sm-4 col-sm-4 control-label">Company Benefits List</label>
                <div class="col-sm-3">
                </div>
              </div>
              <div class="form-group">
              <label class="col-sm-12 col-sm-12 control-label">
                  <table class="table">
                      <tr>
                          <td>Description</td>
                          <td>Amount</td>
                          <td width="5%"></td>
                      </tr>
                      <?php
                          $sqlDeduction=mysqli_query($con,"SELECT * FROM benefits");
                          if(mysqli_num_rows($sqlDeduction)>0){
                              while($deduc=mysqli_fetch_array($sqlDeduction)){
                                echo "<form name='$deduc[id]' method='get'>";
                                ?>
                                <input type="hidden" name="editpayroll">
                                <input type="hidden" name="benefits">
                                <input type="hidden" name="period" value="<?=$period;?>">
                                <input type="hidden" name="idno" value="<?=$idno;?>">
                                <input type="hidden" name="company" value="<?=$company;?>">
                                <?php
                                echo "<input type='hidden' name='description' value='$deduc[benefits]' />";
                                  echo "<tr>";
                                      echo "<td>$deduc[benefits]</td>";
                                      echo "<td align='right' width='20%'><input style='text-align:right;' type='text' name='amount' value='$deduc[amount]' class='form-control' /></td>";
                                      echo "<td><!--a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&addons&submitAddons&company=$company&description=$deduc[benefits]&amount=$deduc[amount]'>Add</a--><input type='submit' name='submitBenefits' value='Add' class='btn btn-success' /></td>";
                                  echo "<tr>";
                                  echo "</form>";
                              }
                          }
                      ?>
                  </table>
              </label>
              </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Benefit Details</label>
                  <div class="col-sm-3">
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-12 col-sm-12 control-label">
                    <table class="table table-bordered">
                        <tr>
                            <td>Description</td>
                            <td>Amount</td>
                            <td width="5%"></td>
                        </tr>
                        <?php
                            $sqlDeduction=mysqli_query($con,"SELECT * FROM payroll_benefits WHERE idno='$idno' AND payrollperiod='$period'");
                            if(mysqli_num_rows($sqlDeduction)>0){
                                while($deduc=mysqli_fetch_array($sqlDeduction)){
                                    echo "<tr>";
                                        echo "<td>$deduc[description]</td>";
                                        echo "<td>$deduc[amount]</td>";
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&benefits&removeBenefits&company=$company'>Remove</a></td>";
                                    echo "<tr>";
                                }
                            }
                        ?>
                    </table>
                </label>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <?php
}
        ?>

  <?php
    if(isset($_GET['submitDeduction'])){
        //$addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $period=$_GET['period'];
        $idno=$_GET['idno'];
        $description=$_GET['description'];
        $amount=$_GET['amount'];
        $company=$_GET['company'];
        $sqlCheck=mysqli_query($con,"SELECT * FROM payroll_deductions WHERE payrollperiod='$period' AND idno='$idno' AND description='$description'");
        if(mysqli_num_rows($sqlCheck)>0){
            $payroll=mysqli_fetch_array($sqlCheck);
          echo "<script>";
        echo "alert('Deduction already exist!');";
          echo "window.location='?editpayroll&idno=$idno&period=$period&deduction';";
        echo "</script>";
        }else{
            $table="payroll_deductions(idno,payrollperiod,description,amount)";
            $values="VALUES('$idno','$period','$description','$amount')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
          echo "<script>";
          echo "window.location='?editpayroll&idno=$idno&period=$period&deduction&company=$company';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&deduction&company=$company';";
        echo "</script>";
      }
    }

    if(isset($_GET['remove'])){
        //$addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $period=$_GET['period'];
        $idno=$_GET['idno'];
        $id=$_GET['id'];
        $company=$_GET['company'];
            $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_deductions WHERE id='$id'");
      if($sqlAddEmployee){
          echo "<script>";
          echo "window.location='?editpayroll&idno=$idno&period=$period&deduction&company=$company';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to remove deduction!');window.location='?editpayroll&idno=$idno&period=$period&deduction&company=$company';";
        echo "</script>";
      }
    }

    if(isset($_GET['submitAddons'])){
      //$addedby=$_GET['addedby'];
      $datenow=date('Y-m-d H:i:s');
      $period=$_GET['period'];
      $idno=$_GET['idno'];
      $description=$_GET['description'];
      $amount=$_GET['amount'];
      $company=$_GET['company'];
      $sqlCheck=mysqli_query($con,"SELECT * FROM payroll_addons WHERE payrollperiod='$period' AND idno='$idno' AND description='$description'");
      if(mysqli_num_rows($sqlCheck)>0){
          $payroll=mysqli_fetch_array($sqlCheck);
        echo "<script>";
      echo "alert('Addons already exist!');";
        echo "window.location='?editpayroll&idno=$idno&period=$period&addons';";
      echo "</script>";
      }else{
          $table="payroll_addons(idno,payrollperiod,description,amount)";
          $values="VALUES('$idno','$period','$description','$amount')";
          $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
      }
    if($sqlAddEmployee){
        echo "<script>";
        echo "window.location='?editpayroll&idno=$idno&period=$period&addons&company=$company';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&addons&company=$company';";
      echo "</script>";
    }
  }

  if(isset($_GET['removeAddons'])){
      //$addedby=$_GET['addedby'];
      $datenow=date('Y-m-d H:i:s');
      $period=$_GET['period'];
      $idno=$_GET['idno'];
      $id=$_GET['id'];
      $company=$_GET['company'];
          $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_addons WHERE id='$id'");
    if($sqlAddEmployee){
        echo "<script>";
        echo "window.location='?editpayroll&idno=$idno&period=$period&addons&company=$company';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to remove addons!');window.location='?editpayroll&idno=$idno&period=$period&addons&company=$company';";
      echo "</script>";
    }
  }

  if(isset($_GET['submitBenefits'])){
    //$addedby=$_GET['addedby'];
    $datenow=date('Y-m-d H:i:s');
    $period=$_GET['period'];
    $idno=$_GET['idno'];
    $description=$_GET['description'];
    $amount=$_GET['amount'];
    $company=$_GET['company'];
    $sqlCheck=mysqli_query($con,"SELECT * FROM payroll_benefits WHERE payrollperiod='$period' AND idno='$idno' AND description='$description'");
    if(mysqli_num_rows($sqlCheck)>0){
        $payroll=mysqli_fetch_array($sqlCheck);
      echo "<script>";
    echo "alert('Benefits already exist!');";
      echo "window.location='?editpayroll&idno=$idno&period=$period&benefits';";
    echo "</script>";
    }else{
        $table="payroll_benefits(idno,payrollperiod,description,amount)";
        $values="VALUES('$idno','$period','$description','$amount')";
        $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
    }
  if($sqlAddEmployee){
      echo "<script>";
      echo "window.location='?editpayroll&idno=$idno&period=$period&benefits&company=$company';";
    echo "</script>";
  }else{
    echo "<script>";
      echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&benefits&company=$company';";
    echo "</script>";
  }
}

if(isset($_GET['removeBenefits'])){
    //$addedby=$_GET['addedby'];
    $datenow=date('Y-m-d H:i:s');
    $period=$_GET['period'];
    $idno=$_GET['idno'];
    $id=$_GET['id'];
    $company=$_GET['company'];
        $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_benefits WHERE id='$id'");
  if($sqlAddEmployee){
      echo "<script>";
      echo "window.location='?editpayroll&idno=$idno&period=$period&benefits&company=$company';";
    echo "</script>";
  }else{
    echo "<script>";
      echo "alert('Unable to remove addons!');window.location='?editpayroll&idno=$idno&period=$period&benefits&company=$company';";
    echo "</script>";
  }
}

  if(isset($_GET['submitPayroll'])){
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $period=$_GET['period'];
        $idno=$_GET['idno'];
        $reghours=$_GET['reghours'];
        $reghoursot=$_GET['reghoursot'];
        $reghoursotamount=$_GET['reghoursotamount'];
        $reghoursnw=$_GET['reghoursnw'];
        $reghoursnwamount=$_GET['reghoursnwamount'];
        $regholidaywork1=$_GET['regholidaywork1'];
        $regholidayworkamount1=$_GET['regholidayworkamount1'];
        $regholidaywork2=$_GET['regholidaywork2'];
        $regholidayworkamount2=$_GET['regholidayworkamount2'];
        $regholidayothrs=$_GET['regholidayothrs'];
        $regholidayotamount=$_GET['regholidayotamount'];
        $spholidayhours1=$_GET['spholidayhours1'];
        $spholidayamount1=$_GET['spholidayamount1'];
        $spholidayhours2=$_GET['spholidayhours2'];
        $spholidayamount2=$_GET['spholidayamount2'];
        $spholidayothrs=$_GET['spholidayothrs'];
        $spholidayotamount=$_GET['spholidayotamount'];
       	$salary_type = $_GET['salary_type']; 
        $ndhrs=$_GET['ndhrs'];
        $ndamount=$_GET['ndamount'];
        $paidSLhrs=$_GET['paidSLhrs'];
        $paidSLamount=$_GET['paidSLamount'];
        $paidVLhrs=$_GET['paidVLhrs'];
        $paidVLamount=$_GET['paidVLamount'];
        $paidBLhrs=$_GET['paidBLhrs'];
        $paidBLamount=$_GET['paidBLamount'];
        $bdayleavehrs=$_GET['bdayleavehrs'];
        $bdayleaveamount=$_GET['bdayleaveamount'];
        $totalpay=$_GET['totalpay'];

        $sqlCheck = mysqli_query($con, "SELECT * FROM payroll_details WHERE payrollperiod='$period' AND idno='$idno'");
        if (mysqli_num_rows($sqlCheck) > 0) {
            $payroll = mysqli_fetch_array($sqlCheck);
            $table = "payroll_details";
            $values = "SET reghours='$reghours',reghoursot='$reghoursot',reghoursotamount='$reghoursotamount',regholidayhrsnotwork='$reghoursnw',
                      regholidayamountnotwork='$reghoursnwamount',regholidayhrswork1='$regholidaywork1',regholidayamountwork1='$regholidayworkamount1',
                      regholidayhrswork2='$regholidaywork2',regholidayamountwork2='$regholidayworkamount2',regholidayothrs='$regholidayothrs',
                      regholidayotamount='$regholidayotamount',spholidayhrs1='$spholidayhours1',spholidayamount1='$spholidayamount1',
                      spholidayhrs2='$spholidayhours2',spholidayamount2='$spholidayamount2',spholidayothrs='$spholidayothrs',spholidayotamount='$spholidayotamount',
                      ndhrs='$ndhrs',ndamount='$ndamount',paidslhrs='$paidSLhrs',paidslamount='$paidSLamount',paidvlhrs='$paidVLhrs',paidvlamount='$paidVLamount',
                      paidblhrs='$paidBLhrs',paidblamount='$paidBLamount',bdayleavehrs='$bdayleavehrs',bdayleaveamount='$bdayleaveamount',totalpay='$totalpay',
                      updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno' AND payrollperiod='$period'";
            $sqlAddEmployee = mysqli_query($con, "UPDATE $table $values");

            // Update salary_type in employee_payroll
            $sqlUpdateEmployeePayroll = mysqli_query($con, 
                "UPDATE employee_payroll 
                SET salary_type='$salary_type' 
                WHERE idno='$idno'");

        } else {
            $table = "payroll_details(idno,payrollperiod,reghours,reghoursot,reghoursotamount,regholidayhrsnotwork,regholidayamountnotwork,regholidayhrswork1,
                      regholidayamountwork1,regholidayhrswork2,regholidayamountwork2,regholidayothrs,regholidayotamount,spholidayhrs1,spholidayamount1,spholidayhrs2,
                      spholidayamount2,spholidayothrs,spholidayotamount,ndhrs,ndamount,paidslhrs,paidslamount,paidvlhrs,paidvlamount,paidblhrs,paidblamount,bdayleavehrs,
                      bdayleaveamount,totalpay,addedby,addeddatetime)";
            $values = "VALUES('$idno','$period','$reghours','$reghoursot','$reghoursotamount','$reghoursnw','$reghoursnwamount','$regholidaywork1',
                      '$regholidayworkamount1','$regholidaywork2','$regholidayworkamount2','$regholidayothrs','$regholidayotamount','$spholidayhours1',
                      '$spholidayamount1','$spholidayhours2','$spholidayamount2','$spholidayothrs','$spholidayotamount','$ndhrs','$ndamount','$paidSLhrs',
                      '$paidSLamount','$paidVLhrs','$paidVLamount','$paidBLhrs','$paidBLamount','$bdayleavehrs','$bdayleaveamount','$totalpay','$addedby','$datenow')";
            $sqlAddEmployee = mysqli_query($con, "INSERT INTO $table $values");

            // Check if employee_payroll entry exists
            $sqlCheckProfile = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno='$idno'");
            if (mysqli_num_rows($sqlCheckProfile) > 0) {
                // Update salary_type in employee_profile if entry exists
                $sqlUpdateEmployeePayroll = mysqli_query($con, 
                    "UPDATE employee_payroll 
                    SET salary_type='$salary_type' 
                    WHERE idno='$idno'");
            } else {
                // Insert into employee_payroll if no entry exists
                $sqlInsertEmployeePayroll = mysqli_query($con, 
                    "INSERT INTO employee_payroll (idno, salary_type) 
                    VALUES ('$idno', '$salary_type')");
            }
        }

        // Success/Failure Message
        if ($sqlAddEmployee) {
            echo "<script>";
            echo "alert('Payroll successfully saved!'); window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!'); window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
            echo "</script>";
        }
    }
    if(isset($_GET['deletetime'])){
      $idno=$_GET['idno'];
      $period=$_GET['period'];
      $id=$_GET['id'];
      $company=$_GET['company'];
      $sqlDelete=mysqli_query($con,"DELETE FROM attendance WHERE id='$id'");
      if($sqlDelete){
        echo "<script>";
        echo "alert('Item successfully removed!');window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
      echo "</script>";
      }
    }
  ?>
