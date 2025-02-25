<?php
    include('../config.php');
    $id=$_GET['id'];
    $sqlPayroll=mysqli_query($con,"SELECT * FROM payroll_details WHERE id='$id'");
    $payroll=mysqli_fetch_array($sqlPayroll);
    $idno=$payroll['idno'];
    $period=$payroll['payrollperiod'];
    $grosspay=$payroll['totalpay'];

    //Fetching Employee Details
    $sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile ep
    INNER JOIN employee_details ed ON ep.idno=ed.idno
    INNER JOIN department d ON d.id=ed.department
    WHERE ep.idno='$idno'");
    $employee=mysqli_fetch_array($sqlEmployee);
    $department=$employee['department'];
    $fullname = $employee['firstname'] . ' ' . $employee['lastname'] . ' ' . $employee['suffix'];


    $sqlPayrollDetails=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
    $payrolldetails=mysqli_fetch_array($sqlPayrollDetails);
    $sss=$payrolldetails['sss'];
    $phic=$payrolldetails['phic'];
    $hdmf=$payrolldetails['hdmf'];
    $baserate=$payrolldetails['salary'];

    $sqlPeriod=mysqli_query($con,"SELECT * FROM payroll WHERE id='$period'");
    $payrollperiod=mysqli_fetch_array($sqlPeriod);
    $loc=$payrollperiod['period'];
    if($loc=="mid"){
        $sss=0;
        $phic=0;
        $hdmf=0;
        $month=date('F',strtotime($payrollperiod['periodto']));
        $midPayroll=0;
    }else{
        $month=date('F',strtotime($payrollperiod['periodfrom']));
        $sqlPeriodAdditional=mysqli_query($con,"SELECT * FROM payroll WHERE MONTH(periodto)='".date('m',strtotime($payrollperiod['periodto']))."' AND YEAR(periodto)='".date('Y',strtotime($payrollperiod['periodto']))."' AND period='mid'");
        if(mysqli_num_rows($sqlPeriodAdditional)>0){
            $pp=mysqli_fetch_array($sqlPeriodAdditional);
            $sqlPeriodDetails=mysqli_query($con,"SELECT * FROM payroll_details WHERE payrollperiod='$pp[id]'");
            if(mysqli_num_rows($sqlPeriodDetails)>0){
                $ppd=mysqli_fetch_array($sqlPeriodDetails);
                $midPayroll=$ppd['totalpay'];
            }else{
                $midPayroll=0;
            }
        }else{
            $midPayroll=0;
        }
    }

    $sqlSetting=mysqli_query($con,"SELECT * FROM settings WHERE companycode='NEWIND'");
    $manager=mysqli_fetch_array($sqlSetting);
    $totalpay=0;

    $benList="";
    $benAmount="";
    $totalbenefits=0;
    $sqlBenefits=mysqli_query($con,"SELECT * FROM payroll_benefits WHERE idno='$idno' AND payrollperiod='$period'");
    if(mysqli_num_rows($sqlBenefits)>0){
        while($benefits=mysqli_fetch_array($sqlBenefits)){
            if($loc=="end"){
            $benList .=$benefits['description']."<br>";
            $benAmount .=number_format($benefits['amount'],2)."<br>";
            $totalbenefits +=$benefits['amount'];
            }else{
                $benList="";
                $benAmount="";
                $totalbenefits=0;
            }
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .header-table td {
            border: none;
        }
        .highlight {
            background-color: #d9ead3;
            font-weight: bold;
        }
        .total {
            background-color: #c6efce;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div style="border: 2px solid black; padding: 20px;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>EMPLOYEE NAME:</strong></td>
                        <td style="padding-left: 50px;"><?=$fullname?></td>
                    </tr>
                    <tr>
                        <td><strong>TEAM:</strong></td>
                        <td style="padding-left: 50px;"><?=$department?></td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%; text-align: right;">
                <table class="header-table">
                    <tr>
                        <td><strong>FIXED SEMI-MONTHLY SALARY:</strong></td>
                        <td style="padding-left: 50px;">28,000.00</td>
                    </tr>
                    <tr>
                        <td><strong>PAY PER DAY:</strong></td>
                        <td style="padding-left: 50px;">0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Pay Period:</strong></td>
                        <td style="padding-left: 50px;"><?=date('M j, Y',strtotime($payrollperiod['periodfrom']));?> to <?=date('M j, Y',strtotime($payrollperiod['periodto']));?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--  -->
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 49%;">
                <h4 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">GROSS EARNINGS AND BENEFITS FOR THIS PAY PERIOD</h4>
                <table class="header-table">
                    <tr>
                        <td>Fixed Rate Salary</td>
                        <td>28,000.00</td>
                    </tr>
                    <tr>
                        <td>Allowances</td>
                        <td>14,000.00</td>
                    </tr>
                    <tr>
                        <td>Incentives</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>Reimbursements</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>Company Paid Government Benefits</td>
                        <td>2,790.00</td>
                    </tr>
                    <tr>
                        <td>Other Company Paid Benefits</td>
                        <td>1,521.54</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL GROSS EARNINGS & BENEFITS FOR THIS PAY PERIOD</td>
                        <td>46,311.54</td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%;">
                <h4 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">TAKE HOME PAY COMPUTATION</h4>
                <table class="header-table">
                    <tr>
                        <td>Fixed Rate Salary</td>
                        <td>28,000.00</td>
                    </tr>
                    <tr>
                        <td>Add On Pay</td>
                        <td>14,000.00</td>
                    </tr>
                    <tr>
                        <td>Deductions</td>
                        <td>-4,818.20</td>
                    </tr>
                    <tr>
                        <td>Deductions</td>
                        <td>-4,818.20</td>
                    </tr>
                    <tr>
                        <td>Deductions</td>
                        <td>-4,818.20</td>
                    </tr>
                    <tr>
                        <td>Deductions</td>
                        <td>-4,818.20</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL TAKE HOME PAY FOR THIS PAY PERIOD</td>
                        <td>37,181.80</td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <h3 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">BREAKDOWN OF SALARY AND BENEFITS</h3>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 49%;">
                    <table class="header-table">
                        <tr>
                            <td><strong>Deductions Breakdown</td>
                        </tr>
                        <tr>
                            <td>SSS Loan</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Pag-ibig Loan</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Benefit Contribution</td>
                            <td>1,080.00</td>
                        </tr>
                        <tr>
                            <td>Withholding Tax</td>
                            <td>2,404.70</td>
                        </tr>
                        <tr class="highlight">
                            <td>TOTAL DEDUCTIONS</td>
                            <td>4,818.20</td>
                        </tr>
                    </table>
                </div>
                
                <div style="width: 49%;">
                    <table class="header-table">
                        <tr>
                            <td><strong>Add On Pay Breakdown</td>
                        </tr>
                        <tr>
                            <td>Overtime Pay</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Holiday Pay</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Night Differential</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>Other Incentives</td>
                            <td>0.00</td>
                        </tr>
                        <tr class="highlight">
                            <td>TOTAL ADD ON PAY</td>
                            <td>0.00</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>Government Benefits Breakdown</td>
                    </tr>
                    <tr>
                        <td>SSS Contribution</td>
                        <td>1,440.00</td>
                    </tr>
                    <tr>
                        <td>Philhealth Contribution</td>
                        <td>1,040.00</td>
                    </tr>
                    <tr>
                        <td>Pag-ibig Contribution</td>
                        <td>310.00</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL COMPANY PAID GOVERNMENT BENEFITS</td>
                        <td>2,790.00</td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>Company Paid Benefits</td>
                    </tr>
                    <tr>
                        <td>Life Insurance</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>Philcare (HMO)</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>FWD Retirement Benefit</td>
                        <td>0.00</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL OTHER COMPANY PAID BENEFITS</td>
                        <td>0.00</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
