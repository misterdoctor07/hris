<?php
    $id=$_GET['period'];
    $sqlPayroll=mysqli_query($con,"SELECT * FROM payroll WHERE id='$id'");
    $payroll=mysqli_fetch_array($sqlPayroll);
?>
<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-calendar"></i> PAYROLL PERIOD (<?=date('F d, Y',strtotime($payroll['periodfrom']));?> - <?=date('F d, Y',strtotime($payroll['periodto']));?>)</h4>
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
                      <th>Hours Work</th>
                      <th>Rate per Hour</th>
                      <th>Adjustment</th>
                      <th>Total Gross</th>
                      <th>Total Deductions</th>
                      <th>Net Pay</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlEmployee=mysqli_query($con,"SELECT ep.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%'  ORDER BY ed.department ASC,ep.lastname ASC");
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
                                $hourswork=$empgross['hourswork'];
                          }else{
                              $totalpay=0;
                              $hourswork=0;
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
                          $tdeduct=$deductions;
                          $totaldeductions=number_format($tdeduct,2);
                          $netpay=number_format($totalpay-$tdeduct,2);
                          $adjustment=$totalpay-($perhour*$hourswork);
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$idno</td>";
                            echo "<td>$lastname, $firstname $middlename $suffix</td>";
                            echo "<td>$department</td>";
                            echo "<td>$comp[company]</td>";
                            echo "<td align='center'>$hourswork</td>";
                            echo "<td align='right'>".number_format($perhour,2)."</td>";
                            echo "<td align='right'>".number_format($adjustment,2)."</td>";
                            echo "<td align='right'>".number_format($totalpay,2)."</td>";
                            echo "<td align='right'>$totaldeductions</td>";
                            echo "<td align='right'>$netpay</td>";
                            ?>
                            <td align="center">
                              <a href="?editpayroll&idno=<?=$idno?>&period=<?=$id;?>" class="btn btn-primary btn-xs" title="Edit Payroll"><i class='fa fa-pencil'></i></a>
                              <a href="?manageinfraction&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Payroll" onclick="return confirm('Do you wish to void this infraction?'); return false;"><i class='fa fa-trash'></i></a>
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
            if(isset($_GET['delete'])){
              $id=$_GET['id'];
              $datenow=date('Y-m-d H:i:s');
              $sqlDelete=mysqli_query($con,"UPDATE infraction SET `status`='Void',updatedby='$fullname',updateddatetime='$datenow' WHERE id='$id'");
              if($sqlDelete){
                echo "<script>alert('Infraction successfully void!');window.location='?manageinfraction';</script>";
              }else{
                echo "<script>alert('Unable to void infraction!');window.location='?manageinfraction';</script>";
              }
            }
            ?>
