<?php
$period=$_GET['period'];
$idno=$_GET['idno'];
$company=$_GET['company'];
$sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
$employee=mysqli_fetch_array($sqlEmployee);

$sqlEmployeeDetails=mysqli_query($con,"SELECT * FROM employee_details WHERE idno='$idno'");
$employeedetails=mysqli_fetch_array($sqlEmployeeDetails);

$sqlPayroll=mysqli_query($con,"SELECT * FROM payroll WHERE id='$period'");
if(mysqli_num_rows($sqlPayroll)>0){
  $resPayroll=mysqli_fetch_array($sqlPayroll);
  $periodstart=$resPayroll['periodfrom'];
  $periodend=$resPayroll['periodto'];
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
    ?>
    <script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?managepayroll.php"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> EDIT PAYROLL</h4>
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
    <div class="col-lg-12 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submitPayroll" class="btn btn-primary" value="Save Details" style="float:right;"><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&deduction&company=<?=$company;?>" class="btn btn-warning" style="float:right; margin-right:10px">Deductions</a><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&addons&company=<?=$company;?>" class="btn btn-info" style="float:right; margin-right:10px">Addons</a><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&benefits&company=<?=$company;?>" class="btn btn-success" style="float:right; margin-right:10px">Company Benefits</a> <a href='?edittime&idno=<?=$idno;?>&period=<?=$period;?>&logindate=&id=&company=<?=$company;?>' class='btn btn-default' title='Add Time'  style='float:right;  margin-right:10px'><i class='fa fa-plus'></i> Add Time</a>
                <?php
                if($okay==1){
                  ?>
                  <a href="payslip.php?id=<?=$payroll_id;?>" class="btn btn-warning" title="Print Payslip" target="_blank" style="float:right; margin-right:10px;"><i class='fa fa-print'></i></a>
                  <?php
                }
                ?>
              <h4><i class="fa fa-user"></i> <?=$employee['lastname'];?>, <?=$employee['firstname'];?> <?=$employee['suffix'];?></h4>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <div class="col-sm-12">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Total Hrs</th>
                      <th>Reg Hrs</th>
                      <th width="5%">Hrs Not Work</th>
                      <th>OT</th>
                      <th>ND</th>
                      <th>PTO</th>
                      <th>Rate/Day</th>
                      <th width="5%">Reg Days OT Rate</th>
                      <th>ND Rate</th>
                      <th>Tax</th>
                      <th width="5%">Special Non Working Holiday</th>
                      <th width="5%">OT Special Holiday</th>
                      <th width="5%">OT Regular Holiday</th>
                      <th width="6%">Reg Holidays</th>
                      <th>Total Pay</th>
                      <th width="4%"></th>
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
                    $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE logindate BETWEEN '$periodstart' AND '$periodend' AND idno='$idno' GROUP BY id ORDER BY logindate ASC");
                    if (mysqli_num_rows($sqlAttendance) > 0) {
                        while ($attendance = mysqli_fetch_array($sqlAttendance)) {
                            $attendid = $attendance['id'];
                            $sqlEmployeePayroll = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno='$idno'");
                            $employeepayroll = mysqli_fetch_array($sqlEmployeePayroll);
                    
                            $empsalary = $employeepayroll['salary'];
                            $logindate = $attendance['logindate'];
                            $loginam = $attendance['loginam'];
                            $logoutam = $attendance['logoutam'];
                            $loginpm = $attendance['loginpm'];
                            $logoutpm = $attendance['logoutpm'];
                            $status = $attendance['status'];
                            $remarks = $attendance['remarks'];
                    
                            // Adjust logindate for nightshift
                          
                    
                            $time1am = strtotime($loginam);
                            $time2am = strtotime($logoutam);
                            $time1pm = strtotime($loginpm);
                            $time2pm = strtotime($logoutpm);
                    
                            $difference_am = round(abs($time2am - $time1am) / 3600, 2);
                            $difference_pm = round(abs($time2pm - $time1pm) / 3600, 2);
                    
                            $nd = 0;
                            $work = 0;
                            $rh = 0;
                            $snwh = 0;
                            $leave = 0;
                            $ndhrs = 0;
                            $ot = 0;
                            $pot = 0;
                            $totalhrs = 0;
                           
                            // Reset salary logic or other processing
                           

                        if($employeedetails['startshift']=="23:00:00"){
                          $reghrs=7;
                        }else{
                          $reghrs=8;
                        }

                        $overtime=0;
                        $hrsnotworked=0;
                        $regdaysot=0;
                        $snwh=0;
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
                        }

                        if($nd > 0 && $work >0){//Regular worked with Night Differential
                          if($employeedetails['startshift']=="04:00:00"){
                            $ndhrs1=round(abs(strtotime('06:00:00') - $time1am) / 3600,2);
                            $ndhrs2=round(abs($time2am - strtotime('06:00:00')) / 3600,2);
                            $ndhrs=$ndhrs1;
                            $totalhrs=$ndhrs1+$ndhrs2+$difference_pm;
                          }else{
                            $totalhrs=$difference_am+$difference_pm;
                            $ndhrs=$totalhrs;
                          }
                          if($totalhrs>$reghrs){
                            $reghrs=$totalhrs-($totalhrs-$reghrs);
                            $ndhrs=$reghrs;
                          }else{
                            $reghrs=$totalhrs;
                          }


                        }
                        if($work > 0){ //Regular worked without Night Differential
                            $totalhrs=$difference_am+$difference_pm;
                            $ndhrs=0;
                          if($totalhrs>8){
                            $reghrs=$totalhrs-($totalhrs-8);
                          }
                        }
                        if($leave > 0){//On Leave
                          $totalhrs=$difference_am+$difference_pm;
                          $ndhrs=0;
                        if($totalhrs>8){
                          $reghrs=$totalhrs-($totalhrs-8);
                        }else{
                          $reghrs=$totalhrs;
                        }
                        }

                        if($nd>0 && $pot>0 && $employeedetails['location']=="OS"){ //Night differential with Overtime before 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          if($totalhrs>8.17){
                            $totalhrs1=8.17;
                          }else{
                            if($employeedetails['startshift']=="23:00:00"){
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
                          $totalhrs=$difference_am+$difference_pm;
                            if($employeedetails['startshift']=="23:00:00"){
                              $totalhrs1=$totalhrs;
                              $totalhrs=$totalhrs1;
                            }
                            $totalhrs1=$totalhrs;

                          $totalhrs=$totalhrs1;
                          $overtime=$totalhrs-$reghrs;
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
                        if($nd>0 && $ot>0){ //$Night Differential with Overtime after 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=$totalhrs-$reghrs;
                        }
                        if($work>0 && $ot>0){ //Regular with Overtime after 8 hrs worked
                          $totalhrs=$difference_am+$difference_pm;
                          $overtime=$totalhrs-$reghrs;
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
                            $spholiday=(($empsalary/8)*1.3)*$thours;
                            $spholidayhours2 +=$thours;
                            $spholidayamount2 +=$spholiday;
                            $spholidayhours1 +=0;
                            $spholidayamount1 +=0;
                          $empsalary=0;
                          $ndhrs=0;
                        }
                        if(($work>0 || $leave>0) && $rh>0){//Regular holiday without Night Differential overtime rate
                            $thours=$difference_am+$difference_pm;
                            $regholiday=(($empsalary/8)*2)*$thours;
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
                        if($employeedetails['startshift']=="23:00:00"){
                          $empsalary=($empsalary/8)*$reghrs;
                        }
                        $totalpay=$empsalary+$regdaysot+$ndrate+$spholiday+$spholidayot+$regholiday+$regholidayot;
                        echo "<tr>";
                        echo "<td>".date('m/d/Y',strtotime($logindate))."</td>";
                        echo "<td>".date('h:i A',strtotime($loginam))."</td>";
                        echo "<td>".date('h:i A',strtotime($logoutam))."</td>";
                        echo "<td>".date('h:i A',strtotime($loginpm))."</td>";
                        echo "<td>".date('h:i A',strtotime($logoutpm))."</td>";
                        echo "<td align='center'>$totalhrs</td>";
                        echo "<td align='center'>$reghrs</td>";
                        echo "<td align='center'>$hrsnotworked</td>";
                        echo "<td align='center'>$overtime</td>";
                        echo "<td align='center'>$ndhrs</td>";
                        echo "<td align='center'>0</td>";
                        echo "<td align='right'>".number_format($empsalary,2)."</td>";
                        echo "<td align='right'>".number_format($regdaysot,2)."</td>";
                        echo "<td align='right'>".number_format($ndrate,2)."</td>";
                        echo "<td></td>";
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
                        $totalhours +=$totalhrs;
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
                        $grandtotal +=$totalpay;
                      }
                    }
                    ?>
                    <tr>
                      <td colspan="5" align='right'>TOTAL</td>
                      <td align='center'><?=number_format($totalhours,2);?></td>
                      <td align='center'><?=number_format($regularhours,2);?></td>
                      <td align='center'><?=number_format($totalhoursnotworked,2);?></td>
                      <td align='center'><?=number_format($totalovertime,2);?></td>
                      <td align='center'><?=number_format($totalndhrs,2);?></td>
                      <td align='center'>0</td>
                      <td align='right'><?=number_format($totalbasesalary,2);?></td>
                      <td align='right'><?=number_format($totalregdaysot,2);?></td>
                      <td align='right'><?=number_format($totalndrate,2);?></td>
                      <td align='right'></td>
                      <td align='right'><?=number_format($totalspholiday,2);?></td>
                      <td align='right'><?=number_format($totalspholidayot,2);?></td>
                      <td align='right'><?=number_format($totalregholidayot,2);?></td>
                      <td align='right'><?=number_format($totalregholiday,2);?></td>
                      <td align='right'><?=number_format($grandtotal,2);?></td>
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

        $sqlCheck=mysqli_query($con,"SELECT * FROM payroll_details WHERE payrollperiod='$period' AND idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $payroll=mysqli_fetch_array($sqlCheck);
            $table="payroll_details";
            $values="SET reghours='$reghours',reghoursot='$reghoursot',reghoursotamount='$reghoursotamount',regholidayhrsnotwork='$reghoursnw',regholidayamountnotwork='$reghoursnwamount',regholidayhrswork1='$regholidaywork1',regholidayamountwork1='$regholidayworkamount1',regholidayhrswork2='$regholidaywork2',regholidayamountwork2='$regholidayworkamount2',regholidayothrs='$regholidayothrs',regholidayotamount='$regholidayotamount',spholidayhrs1='$spholidayhours1',spholidayamount1='$spholidayamount1',spholidayhrs2='$spholidayhours2',spholidayamount2='$spholidayamount2',spholidayothrs='$spholidayothrs',spholidayotamount='$spholidayotamount',ndhrs='$ndhrs',ndamount='$ndamount',paidslhrs='$paidSLhrs',paidslamount='$paidSLamount',paidvlhrs='$paidVLhrs',paidvlamount='$paidVLamount',paidblhrs='$paidBLhrs',paidblamount='$paidBLamount',bdayleavehrs='$bdayleavehrs',bdayleaveamount='$bdayleaveamount',totalpay='$totalpay',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno' AND payrollperiod='$period'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="payroll_details(idno,payrollperiod,reghours,reghoursot,reghoursotamount,regholidayhrsnotwork,regholidayamountnotwork,regholidayhrswork1,regholidayamountwork1,regholidayhrswork2,regholidayamountwork2,regholidayothrs,regholidayotamount,spholidayhrs1,spholidayamount1,spholidayhrs2,spholidayamount2,spholidayothrs,spholidayotamount,ndhrs,ndamount,paidslhrs,paidslamount,paidvlhrs,paidvlamount,paidblhrs,paidblamount,bdayleavehrs,bdayleaveamount,totalpay,addedby,addeddatetime)";
            $values="VALUES('$idno','$period','$reghours','$reghoursot','$reghoursotamount','$reghoursnw','$reghoursnwamount','$regholidaywork1','$regholidayworkamount1','$regholidaywork2','$regholidayworkamount2','$regholidayothrs','$regholidayotamount','$spholidayhours1','$spholidayamount1','$spholidayhours2','$spholidayamount2','$spholidayothrs','$spholidayotamount','$ndhrs','$ndamount','$paidSLhrs','$paidSLamount','$paidVLhrs','$paidVLamount','$paidBLhrs','$paidBLamount','$bdayleavehrs','$bdayleaveamount','$totalpay','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
          echo "<script>";
          echo "alert('Payroll successfully saved!'); window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!'); window.location='?editpayroll&idno=$idno&period=$period&company=$company';";
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
