<?php
    $id=$_GET['period'];
    $comp=$_GET['company'];
    $sqlPayroll=mysqli_query($con,"SELECT * FROM payroll WHERE id='$id'");
    $payroll=mysqli_fetch_array($sqlPayroll);
    $posted=0;
    $notposted=0;
    $sqlDetails=mysqli_query($con,"SELECT pd.status FROM payroll_details pd INNER JOIN employee_details ed ON ed.idno=pd.idno WHERE pd.payrollperiod='$id' AND ed.company='$comp'");
    if(mysqli_num_rows($sqlDetails)>0){
      while($details=mysqli_fetch_array($sqlDetails)){
        if($details['status']=="posted"){
          $posted++;
        }else{
          $notposted++;
        }
      }
    }
?>
<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?viewpayroll"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-calendar"></i> PAYROLL PERIOD (<?=date('F d, Y',strtotime($payroll['periodfrom']));?> - <?=date('F d, Y',strtotime($payroll['periodto']));?>)
                <?php
                if($posted==0 && $notposted==0){

              }elseif($notposted>0){
                ?>
                <a href="?managepayroll&postpayslip&period=<?=$id;?>&company=<?=$comp;?>" class="btn btn-primary" style="float:right;" onclick="return confirm('Do you wish to post payslip?');return false;">POST PAYSLIP</a>
                <?php
              }else{
                ?>
                <a href="?managepayroll&undopostpayslip&period=<?=$id;?>&company=<?=$comp;?>" class="btn btn-warning" style="float:right;" onclick="return confirm('Do you wish to undo post?');return false;">UNDO POST</a>
                <?php
              }
                ?>
              </h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Emp ID</th>
                      <th>Employee Name</th>
                      <th>Team</th>
                      <th>Company</th>
                      <th>Addons</th>
                      <th>Total Gross</th>
                      <th>Total Deductions</th>
                      <th>Net Pay</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlEmployee=mysqli_query($con,"SELECT ep.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$comp'  ORDER BY ed.department ASC,ep.lastname ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          $idno=$company['idno'];
                          $lastname=$company['lastname'];
                          $firstname=$company['firstname'];
                          $middlename=$company['middlename'];
                          $suffix=$company['suffix'];
                          $sqlGross=mysqli_query($con,"SELECT * FROM payroll_details WHERE idno='$idno' AND payrollperiod='$id'");
                          if(mysqli_num_rows($sqlGross)>0){
                              $empgross=mysqli_fetch_array($sqlGross);
                                $totalpay=$empgross['totalpay'];
                                $reghours=$empgross['reghours'];
                                $payroll_id=$empgross['id'];
                          }else{
                              $totalpay=0;
                              $hourswork=0;
                              $payroll_id="";
                              $reghours=0;
                          }

                          $sqlDept=mysqli_query($con,"SELECT d.department FROM department d LEFT JOIN employee_details ed ON ed.department=d.id WHERE ed.idno='$idno'");
                          if(mysqli_num_rows($sqlDept)>0){
                            $dept=mysqli_fetch_array($sqlDept);
                            $department=$dept['department'];
                          }else{
                            $department="";
                          }
                          $sqlDept=mysqli_query($con,"SELECT company FROM employee_details WHERE idno='$idno'");
                          $comp=mysqli_fetch_array($sqlDept);
                          $emppayroll=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
                          if(mysqli_num_rows($emppayroll)>0){
                            $emp=mysqli_fetch_array($emppayroll);
                            $salary=$emp['salary'];
                          }else{
                            $salary=0;
                          }
                          $perhour=$salary/8;
                          //$grosspay=$hourswork*$hoursrate;
                          //$gross=number_format($grosspay,2);
                          $sqlDeduction=mysqli_query($con,"SELECT SUM(amount) as amount FROM payroll_deductions WHERE idno='$idno' AND payrollperiod='$id' GROUP BY idno");
                          if(mysqli_num_rows($sqlDeduction)>0){
                              $deduct=mysqli_fetch_array($sqlDeduction);
                              $deductions=$deduct['amount'];
                          }else{
                              $deductions=0;
                          }
                          $sqlAddons=mysqli_query($con,"SELECT SUM(amount) as amount FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$id' GROUP BY idno");
                          if(mysqli_num_rows($sqlAddons)>0){
                              $add=mysqli_fetch_array($sqlAddons);
                              $addons=$add['amount'];
                          }else{
                              $addons=0;
                          }
                          $tdeduct=$deductions;
                          $totaldeductions=number_format($tdeduct,2);
                          $netpay=number_format(($totalpay+$addons)-$tdeduct,2);
                          //$adjustment=$totalpay-($perhour*$hourswork);
                          $adjustment=0;
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$idno</td>";
                            echo "<td>$lastname, $firstname $middlename $suffix</td>";
                            echo "<td>$department</td>";
                            echo "<td>$comp[company]</td>";
                            echo "<td align='right'>".number_format($addons,2)."</td>";
                            echo "<td align='right'>".number_format($totalpay,2)."</td>";
                            echo "<td align='right'>$totaldeductions</td>";
                            echo "<td align='right'>$netpay</td>";
                            ?>
                            <td align="center">
                              <a href="?editpayroll&idno=<?=$idno?>&period=<?=$id;?>&company=<?=$comp['company'];?>" class="btn btn-primary btn-xs" title="Edit Payroll" target=""><i class='fa fa-pencil'></i></a>
                              <?php
                              if($payroll_id==""){

                              }else{
                                ?>
                                <a href="payslip.php?id=<?=$payroll_id;?>" class="btn btn-warning btn-xs" title="Print Payslip" target="_blank"><i class='fa fa-print'></i></a>
                                <?php
                              }
                              ?>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='12' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
                </div>
            </div>
            </div>
            <?php
            // if(isset($_GET['delete'])){
            //   $id=$_GET['id'];
            //   $company=$_GET['company'];
            //   $datenow=date('Y-m-d H:i:s');
            //   $sqlDelete=mysqli_query($con,"UPDATE infraction SET `status`='Void',updatedby='$fullname',updateddatetime='$datenow' WHERE id='$id'");
            //   if($sqlDelete){
            //     echo "<script>alert('Infraction successfully void!');window.location='?manageinfraction&company=$company';</script>";
            //   }else{
            //     echo "<script>alert('Unable to void infraction!');window.location='?manageinfraction';</script>";
            //   }
            // }
            if(isset($_GET['postpayslip'])){
              $id=$_GET['period'];
              $company=$_GET['company'];
              $datenow=date('Y-m-d H:i:s');
              $sqlCheck=mysqli_query($con,"SELECT idno FROM employee_details WHERE company='$company' AND status NOT LIKE '%RESIGNED%'");
              if(mysqli_num_rows($sqlCheck)>0){
                while($check=mysqli_fetch_array($sqlCheck)){
                  $idno=$check['idno'];
                  $sqlUpdate=mysqli_query($con,"UPDATE payroll_details SET status='posted',dateposted='$datenow' WHERE idno='$idno' AND payrollperiod='$id' AND status='pending'");
                }
              }
              if($sqlUpdate){
                echo "<script>alert('Payslip successfully posted!');window.location='?managepayroll&period=$id&company=$company';</script>";
              }else{
                echo "<script>alert('Unable to post payslip!');window.location='?managepayroll&period=$id&company=$company';</script>";
              }
            }
            if(isset($_GET['undopostpayslip'])){
              $id=$_GET['period'];
              $company=$_GET['company'];
              $datenow=date('Y-m-d H:i:s');
              $sqlCheck=mysqli_query($con,"SELECT idno FROM employee_details WHERE company='$company' AND status NOT LIKE '%RESIGNED%'");
              if(mysqli_num_rows($sqlCheck)>0){
                while($check=mysqli_fetch_array($sqlCheck)){
                  $idno=$check['idno'];
                  $sqlUpdate=mysqli_query($con,"UPDATE payroll_details SET status='pending',dateposted=null WHERE idno='$idno' AND payrollperiod='$id' AND status='posted'");
                }
              }
              if($sqlUpdate){
                echo "<script>alert('Payslip successfully unposted!');window.location='?managepayroll&period=$id&company=$company';</script>";
              }else{
                echo "<script>alert('Unable to undo post payslip!');window.location='?managepayroll&period=$id&company=$company';</script>";
              }
            }
            ?>
