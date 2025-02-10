<?php
$period=$_GET['period'];
$idno=$_GET['idno'];
$sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
$employee=mysqli_fetch_array($sqlEmployee);

$sqlEmployeeDetails=mysqli_query($con,"SELECT * FROM employee_details WHERE idno='$idno'");
$employeedetails=mysqli_fetch_array($sqlEmployeeDetails);

$sqlEmployeePayroll=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
$employeepayroll=mysqli_fetch_array($sqlEmployeePayroll);
$empsalary=$employeepayroll['salary'];

    ?>
    <script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?managepayroll&period=<?=$period;?>"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> EDIT PAYROLL</h4>
    </div>
    </div>
    <?php
    if(!isset($_GET['deduction']) && !isset($_GET['addons']) && !isset($_GET['benefits'])){
    ?>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editpayroll">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="period" value="<?=$period;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
    <div class="col-lg-12 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submitPayroll" class="btn btn-primary" value="Save Details" style="float:right;"><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&deduction" class="btn btn-warning" style="float:right; margin-right:10px">Deductions</a><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&addons" class="btn btn-info" style="float:right; margin-right:10px">Addons</a><a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&benefits" class="btn btn-success" style="float:right; margin-right:10px">Company Benefits</a>
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
                      <th width="5%">OT Rate After 8 Hours</th>
                      <th width="5%">OT Regular Holiday</th>
                      <th width="6%">Reg Holidays</th>
                      <th>Total Pay</th>
                      <th width="4%"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $totalhrsworked=0;
                    $totalreghours=0;
                    $totalOT=0;
                    $totalPTO=0;
                    $totalRate=0;
                    $totalregholidayot=0;
                    $totalregholiday=0;
                    $totalotholiday=0;
                    $totalotholidayot=0;
                    $totalND=0;
                    $totalNDot=0;
                    $totalSNWH=0;
                    $totalpay=0;
                    $totalreghoursnw=0;
                    $sqlPeriod=mysqli_query($con,"SELECT * FROM payroll WHERE id='$period'");
                    if(mysqli_num_rows($sqlPeriod)>0){
                      $payroll=mysqli_fetch_array($sqlPeriod);
                      $periodstart=($payroll['periodfrom']);
                      $periodend=($payroll['periodto']);
                      while(strtotime($periodstart) <= strtotime($periodend)){
                        $periodstart=date('m/d/Y',strtotime($periodstart));
                        $rundate=date('Y-m-d',strtotime($periodstart));
                        $sqlAttendance=mysqli_query($con,"SELECT * FROM attendance WHERE idno='$idno' AND logindate='$rundate'");
                        $totalhrs=0;
                        if(mysqli_num_rows($sqlAttendance)>0){
                          $attend=mysqli_fetch_array($sqlAttendance);
                          $attendid=$attend['id'];
                          $shift1start=$attend['loginam'];
                          $shift1end=$attend['logoutam'];
                          $shift2start=$attend['loginpm'];
                          $shift2end=$attend['logoutpm'];
                          $s1=date('h:i A',strtotime($shift1start));
                          $s2=date('h:i A',strtotime($shift1end));
                          $s3=date('h:i A',strtotime($shift2start));
                          $s4=date('h:i A',strtotime($shift2end));
                          $status=$attend['status'];
                          $reghrs=8;
                        }else{
                          $s1="";
                          $s2="";
                          $s3="";
                          $s4="";
                          $shift1start=0;
                          $shift1end=0;
                          $shift2start=0;
                          $shift2end=0;
                          $status="";
                          $reghrs=0;
                          $attendid="";
                        }
                        if($shift1start > $employeedetails['startshift']){
                          $rundatestart=date('Y-m-d',strtotime('-1 days',strtotime($rundate)));
                        }else{
                          $rundatestart=$rundate;
                        }
                        $totalhrs=abs((strtotime($rundate." ".$shift1end)-strtotime($rundatestart." ".$shift1start))+(strtotime($rundate." ".$shift2end)-strtotime($rundate." ".$shift2start)))/(60*60);
                        if($totalhrs < $reghrs){
                          $reghrs=ROUND($totalhrs,2);
                        }
                        $totalhrs=number_format($totalhrs,2);
                        $overtime=0;
                        $pto=0;
                        $regot=0;
                        $regholiday=0;
                        $regotafter=0;
                        $regholidayot=0;
                        $nightdiff=0;
                        $snwh=0;
                        $reghrsnw=0;
                        $work=0;
                      //  if(sizeof($status)>0){
                          $nd=0;
                          $pot=0;
                          $rh=0;
                          $leave=0;
                          $spnw=0;
                          $ot=0;
                          $no=0;
                          $nwh=0;
                          $empsalary=$employeepayroll['salary'];
                            if($reghrs==0){
                              $empsalary=0;
                            }
                          $p=explode('/',$status);
                          for($i=0;$i<sizeof($p);$i++){
                            if($p[$i]=="nd"){
                              $nd++;
                            }
                            if($p[$i]=="pt"){
                              $pot++;
                            }
                            if($p[$i]=="rh"){
                              $rh++;
                            }
                            if($p[$i]=="leave"){
                              $leave++;
                            }
                            if($p[$i]=="work"){
                              $work++;
                            }
                            if($p[$i]=="ot"){
                              $ot++;
                            }
                            if($p[$i]=="snwh"){
                              $nwh++;
                            }
                          }
                          if($leave>0 && $rh>0 && $nd>0){
                            $regholiday=($empsalary*2.2)/8*$reghrs;
                            //$empsalary=0;
                          }
                          if(($work>0 || $leave>0) && $rh>0){
                            $regholiday=($empsalary*2)/8*$reghrs;
                          }
                          if($rh > 0 && $totalhrs < 8){
                            $reghrsnw=8-$totalhrs;
                          }

                          if($nd > 0 && $rh==0 && $nwh==0){
                            if($employeedetails['startshift'] < "04:00:00"){
                              $no=$reghrs;
                            }elseif($employeedetails['starthift']=="04:00:00"){
                              $no=2;
                            }else{
                              $no=0;
                            }
                            $nightdiff=$no*($empsalary*.1)/8;
                          }else{
                            if($nd>0){
                              $empsalary=0;
                            }else{
                              if($rh>0 || $nwh>0){
                                $empsalary=0;
                              }
                            }
                          }

                          if($nwh>0 && $nd>0){
                            $snwh=(($employeepayroll['salary']/8)*1.43)*$reghrs;
                          }else if($nwh>0){
                            $snwh=(($employeepayroll['salary']/8)*1.3)*$reghrs;
                          }
                          $empsalary=($empsalary/8)*$reghrs;
                          if($pot>0 || $ot>0){
                            $overtime=$totalhrs-$reghrs;
                            $regot=(($empsalary*1.25)/8)*$overtime;
                            $regotafter=0;
                          }
                          if($ot>0){
                            $regotafter = ((($employeepayroll['salary']*1.3)/8)*1.3)*$overtime;
                          }
                          if($regot>0){
                            $regotafter=0;
                          }
                          if($rh>0 && ($ot>0 || $pot>0)){
                            $regholidayot = ((($employeepayroll['salary']/8)*2)*1.3)*$overtime;
                          }
                        // }else{
                        //   $overtime=0;
                        //   $regot=0;
                        // }
                        if($reghrsnw>0){
                          $empsalary=$reghrsnw*($employeepayroll['salary']/8);
                        }
                        $tpay=$empsalary+$regot+$nightdiff+$snwh+$regotafter+$regholiday+$regholidayot;
                        echo "<tr>";
                          echo "<td>".$periodstart."</td>";
                          echo "<td>".$s1."</td>";
                          echo "<td>".$s2."</td>";
                          echo "<td>".$s3."</td>";
                          echo "<td>".$s4."</td>";
                          echo "<td align='center'>$totalhrs</td>";
                          echo "<td align='center'>".number_format($reghrs,2)."</td>";
                          echo "<td align='center'>".number_format($reghrsnw,2)."</td>";
                          echo "<td align='center'>".number_format($overtime,2)."</td>";
                          echo "<td align='center'>".number_format($no,2)."</td>";
                          echo "<td align='center'>".number_format($pto,2)."</td>";
                          echo "<td align='right'>".number_format($empsalary,2)."</td>";
                          echo "<td align='right'>".number_format($regot,2)."</td>";
                          echo "<td align='right'>".number_format($nightdiff,2)."</td>";
                          echo "<td></td>";
                          echo "<td align='right'>".number_format($snwh,2)."</td>";
                          echo "<td align='right'>".number_format($regotafter,2)."</td>";
                          echo "<td align='right'>".number_format($regholidayot,2)."</td>";
                          echo "<td align='right'>".number_format($regholiday,2)."</td>";
                          echo "<td align='right'>".number_format($tpay,2)."</td>";
                          if($attendid==""){
                            echo "<td align='center'><a href='?edittime&idno=$idno&period=$period&logindate=$rundate&id=' title='Add Time'><i class='fa fa-plus'></i></a></td>";
                          }else{
                            echo "<td align='center'><a href='?edittime&idno=$idno&period=$period&id=$attendid' title='Edit Time'><i class='fa fa-edit'></i></a> | ";
                            ?>
                            <a href='?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>&id=<?=$attendid;?>&deletetime' title='Delete Time' onclick="return confirm('Do you wish to remove this attendance?'); return false;"><i class='fa fa-trash'></i></a></td>
                            <?php
                          }

                        echo "</tr>";
                        $totalhrsworked +=$totalhrs;
                        $totalreghours +=$reghrs;
                        $totalOT +=$overtime;
                        $totalPTO +=$pto;
                        $totalRate +=$empsalary;
                        $totalregholidayot +=$regot;
                        $totalregholiday +=$regholiday;
                        $totalND +=$no;
                        $totalNDot +=$nightdiff;
                        $totalSNWH +=$snwh;
                        $totalotholiday +=$regotafter;
                        $totalotholidayot +=$regholidayot;
                        $totalpay +=$tpay;
                        $totalreghoursnw +=$reghrsnw;
                        //$totalpay=$totalRate+$totalregholiday+$totalregholidayot+$totalNDot+$totalSNWH+$totalotholiday+$totalotholidayot;
                        $periodstart=date('m/d/Y',strtotime('1 days',strtotime($periodstart)));
                      }
                    }

                    ?>
                    <tr>
                      <td colspan="5" align="right">TOTAL</td>
                      <td align="center"><?=number_format($totalhrsworked,2);?></td>
                      <td align="center"><?=number_format($totalreghours,2);?></td>
                      <td align="center"><?=number_format($totalreghoursnw,2);?></td>
                      <td align="center"><?=number_format($totalOT,2);?></td>
                      <td align="center"><?=number_format($totalND,2);?></td>
                      <td align="center"><?=number_format($totalPTO,2);?></td>
                      <td align="right"><?=number_format($totalRate,2);?></td>
                      <td align="right"><?=number_format($totalregholidayot,2);?></td>
                      <td align="right"><?=number_format($totalNDot,2);?></td>
                      <td></td>
                      <td align="right"><?=number_format($totalSNWH,2);?></td>
                      <td align="right"><?=number_format($totalotholiday,2);?></td>
                      <td align="right"><?=number_format($totalotholidayot,2);?></td>
                      <td align="right"><?=number_format($totalregholiday,2);?></td>
                      <td align="right"><?=number_format($totalpay,2);?></td>
                      <td></td>
                  </tr>
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <input type="hidden" name="hourswork" value="<?=$totalhrsworked;?>">
        <input type="hidden" name="reghours" value="<?=$totalreghours;?>">
        <input type="hidden" name="reghoursnw" value="<?=$totalreghoursnw;?>">
        <input type="hidden" name="baserate" value="<?=$totalRate;?>">
        <input type="hidden" name="otbefore" value="<?=$totalOT;?>">
        <input type="hidden" name="ndhours" value="<?=$totalND;?>">
        <input type="hidden" name="regdaysot" value="<?=$totalregholidayot;?>">
        <input type="hidden" name="ndrate" value="<?=$totalNDot;?>">
        <input type="hidden" name="specialholiday" value="<?=$totalSNWH;?>">
        <input type="hidden" name="otafter" value="<?=$totalotholiday;?>">
        <input type="hidden" name="otregular" value="<?=$totalotholidayot;?>">
        <input type="hidden" name="regholiday" value="<?=$totalregholiday;?>">
        <input type="hidden" name="totalpay" value="<?=$totalpay;?>">
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
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
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
                  <input type="number" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitDeduction" class="btn btn-primary" value="Add Deduction">
                  </div>
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
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&deduction&remove'>Remove</a></td>";
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
        </form>
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
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
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
                  <input type="number" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitAddons" class="btn btn-primary" value="Save Addons">
                  </div>
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
                                        echo "<td>$deduc[amount]</td>";
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&addons&removeAddons'>Remove</a></td>";
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
        </form>
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
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <a href="?editpayroll&idno=<?=$idno;?>&period=<?=$period;?>" class="btn btn-primary" style="float:right;"><i class="fa fa-times"></i></a>
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
                  <input type="number" class="form-control" name="amount" style='text-align:right' required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label"></label>
                  <div class="col-sm-3">
                  <input type="submit" name="submitBenefits" class="btn btn-primary" value="Save Benefits">
                  </div>
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
                                        echo "<td><a href='?editpayroll&idno=$idno&period=$period&id=$deduc[id]&benefits&removeBenefits'>Remove</a></td>";
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
        </form>
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
          echo "window.location='?editpayroll&idno=$idno&period=$period&deduction';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&deduction';";
        echo "</script>";
      }
    }

    if(isset($_GET['remove'])){
        //$addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $period=$_GET['period'];
        $idno=$_GET['idno'];
        $id=$_GET['id'];
            $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_deductions WHERE id='$id'");
      if($sqlAddEmployee){
          echo "<script>";
          echo "window.location='?editpayroll&idno=$idno&period=$period&deduction';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to remove deduction!');window.location='?editpayroll&idno=$idno&period=$period&deduction';";
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
        echo "window.location='?editpayroll&idno=$idno&period=$period&addons';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&addons';";
      echo "</script>";
    }
  }

  if(isset($_GET['removeAddons'])){
      //$addedby=$_GET['addedby'];
      $datenow=date('Y-m-d H:i:s');
      $period=$_GET['period'];
      $idno=$_GET['idno'];
      $id=$_GET['id'];
          $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_addons WHERE id='$id'");
    if($sqlAddEmployee){
        echo "<script>";
        echo "window.location='?editpayroll&idno=$idno&period=$period&addons';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to remove addons!');window.location='?editpayroll&idno=$idno&period=$period&addons';";
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
      echo "window.location='?editpayroll&idno=$idno&period=$period&benefits';";
    echo "</script>";
  }else{
    echo "<script>";
      echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period&benefits';";
    echo "</script>";
  }
}

if(isset($_GET['removeBenefits'])){
    //$addedby=$_GET['addedby'];
    $datenow=date('Y-m-d H:i:s');
    $period=$_GET['period'];
    $idno=$_GET['idno'];
    $id=$_GET['id'];
        $sqlAddEmployee=mysqli_query($con,"DELETE FROM payroll_benefits WHERE id='$id'");
  if($sqlAddEmployee){
      echo "<script>";
      echo "window.location='?editpayroll&idno=$idno&period=$period&benefits';";
    echo "</script>";
  }else{
    echo "<script>";
      echo "alert('Unable to remove addons!');window.location='?editpayroll&idno=$idno&period=$period&benefits';";
    echo "</script>";
  }
}

    if(isset($_GET['submitPayroll'])){
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $period=$_GET['period'];
        $idno=$_GET['idno'];
        $hourswork=$_GET['hourswork'];
        $baserate=$_GET['baserate'];
        $reghours=$_GET['reghours'];
        $reghoursnw=$_GET['reghoursnw'];
        $otbefore=$_GET['otbefore'];
        $ndhours=$_GET['ndhours'];
        $regdaysot=$_GET['regdaysot'];
        $ndrate=$_GET['ndrate'];
        $specialholiday=$_GET['specialholiday'];
        $otafter=$_GET['otafter'];
        $otregular=$_GET['otregular'];
        $regholiday=$_GET['regholiday'];
        $totalpay=$_GET['totalpay'];
        $sqlCheck=mysqli_query($con,"SELECT * FROM payroll_details WHERE payrollperiod='$period' AND idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $payroll=mysqli_fetch_array($sqlCheck);
            $table="payroll_details";
            $values="SET hourswork='$hourswork',baserate='$baserate',reghours='$reghours',reghoursnw='$reghoursnw',otbefore='$otbefore',ndhours='$ndhours',regdaysot='$regdaysot',ndrate='$ndrate',specialholiday='$specialholiday',otafter='$otafter',otregular='$otregular',regholiday='$regholiday',totalpay='$totalpay',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno' AND payrollperiod='$period'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="payroll_details(idno,payrollperiod,hourswork,baserate,reghours,reghoursnw,otbefore,ndhours,regdaysot,ndrate,specialholiday,otafter,otregular,regholiday,totalpay,addedby,addeddatetime)";
            $values="VALUES('$idno','$period','$hourswork','$baserate','$reghours','$reghoursnw','$otbefore','$ndhours','$regdaysot','$ndrate','$specialholiday','$otafter','$otregular','$regholiday','$totalpay','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
          echo "<script>";
          echo "alert('Payroll successfully saved!');window.location='?editpayroll&idno=$idno&period=$period';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period';";
        echo "</script>";
      }
    }
    if(isset($_GET['deletetime'])){
      $idno=$_GET['idno'];
      $period=$_GET['period'];
      $id=$_GET['id'];
      $sqlDelete=mysqli_query($con,"DELETE FROM attendance WHERE id='$id'");
      if($sqlDelete){
        echo "<script>";
        echo "alert('Item successfully removed!');window.location='?editpayroll&idno=$idno&period=$period';";
      echo "</script>";
    }else{
      echo "<script>";
        echo "alert('Unable to saved details!');window.location='?editpayroll&idno=$idno&period=$period';";
      echo "</script>";
      }
    }
  ?>
