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

    $sssAmount=$phicAmount=$hdmfAmount=$philcareAmount=$philcareAmount=$generaliAmount=$fwdAmount=$taxAmount=$CompanyGovBenAmount=$CompanyPaidGovBenAmount="";
    $CompanyGovBen=$CompanyPaidGovBen=0;
    $sqlPayrollDetails=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
    $payrolldetails=mysqli_fetch_array($sqlPayrollDetails);
    $sss=$payrolldetails['sss'];
    $sssAmount .=number_format($sss,2)."<br>";
    $phic=$payrolldetails['phic'];
    $phicAmount .=number_format($phic,2)."<br>";
    $hdmf=$payrolldetails['hdmf'];
    $hdmfAmount .=number_format($hdmf,2)."<br>";
    $philcare=$payrolldetails['philcare'];
    $philcareAmount .=number_format($philcare,2)."<br>";
    $generali=$payrolldetails['generali'];
    $generaliAmount .=number_format($generali,2)."<br>";
    $fwd=$payrolldetails['fwd'];
    $fwdAmount .=number_format($fwd,2)."<br>";
    $tax=$payrolldetails['tax'];
    $taxAmount .=number_format($tax,2)."<br>";
    $baserate=$payrolldetails['salary'];
    $baseFixed = number_format($baserate, 2);
    $salaryFixed = $baserate * 20;
    $fixedSalary = number_format($salaryFixed, 2);
    $CompanyPaidGovBen = $sss + $phic + $hdmf + $tax;
    $CompanyPaidGovBenAmount = number_format($CompanyPaidGovBen, 2);
    $CompanyGovBen = $philcare + $generali + $fwd;
    $CompanyGovBenAmount = number_format($CompanyGovBen, 2);

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

    $benList = $benAmount="";
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

    //Fetch employee add ons
    $addonList = $addonAmount =  $totaladdAmount = "";
    $totaladdons=0;
    $sqlAddons=mysqli_query($con, "SELECT * FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$period'");
    if(mysqli_num_rows($sqlAddons)>0){
        while($addons=mysqli_fetch_array($sqlAddons)){
            // if($loc=="end"){
                $addonList .=$addons['description']."<br>"."<br>";
                $addonAmount .=number_format($addons['amount'],2)."<br>"."<br>";
                $totaladdons +=$addons['amount'];
                $totaladdAmount = number_format($totaladdons,2)."<br>";
            // }
        }
    }
    // Categorize addons into Allowances, Incentives, or Reimbursements
    $sqlAddons = mysqli_query($con, "SELECT * FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$period'");

    $allowanceList = $incentiveList = $reimbursementList = ""; // Initialize lists as empty strings
    $allowanceAmount = $incentiveAmount = $reimbursementAmount = 0; // Initialize amounts as 0

    if (mysqli_num_rows($sqlAddons) > 0) {
        while ($addons = mysqli_fetch_array($sqlAddons)) {
            $description = strtolower($addons['description']); // Convert to lowercase for case-insensitive comparison
            $amount = floatval($addons['amount']); // Ensure it's a numeric value
            
            if (stripos($description, 'allowance') !== false) {
                $allowanceList .= $addons['description'] . "<br><br>";
                $allowanceAmount += $amount; // Keep it numeric
            } elseif (stripos($description, 'incentive') !== false) {
                $incentiveList .= $addons['description'] . "<br><br>";
                $incentiveAmount += $amount;
            } elseif (stripos($description, 'reimbursement') !== false) {
                $reimbursementList .= $addons['description'] . "<br><br>";
                $reimbursementAmount += $amount;
            }
        }
    }
    // Format for display
    $allowanceTotal = number_format($allowanceAmount, 2);
    $incentiveTotal = number_format($incentiveAmount, 2);
    $reimbursementTotal = number_format($reimbursementAmount, 2);

    //Fetch employee deductions
    $deductionList = $deductionAmount = $totaldeductAmount = "";
    $totaldeductions=0;
    $sqlDeductions=mysqli_query($con, "SELECT * FROM payroll_deductions WHERE idno='$idno' AND payrollperiod='$period'");
    if(mysqli_num_rows($sqlDeductions)>0){
        while($deductions=mysqli_fetch_array($sqlDeductions)){
            // if($loc=="end"){
                $deductionList .=$deductions['description']."<br>"."<br>";
                $deductionAmount .=number_format($deductions['amount'],2)."<br>"."<br>";
                $totaldeductions +=$deductions['amount'];
                $totaldeductAmount = number_format($totaldeductions,2)."<br>";
                // }
        }
    }

    // Fetch Basic Salary Details
    $sqlBasicSalary = mysqli_query($con, "SELECT * FROM payroll_details WHERE idno='$idno' AND payrollperiod='$period'");
    if (mysqli_num_rows($sqlBasicSalary) > 0) {
        $salaryData = mysqli_fetch_assoc($sqlBasicSalary); // Fetch once and store in an array
        
        $regHrs = $salaryData['reghours'] ?? 0;
        $regHrsOT = $salaryData['reghoursot'] ?? 0;
        $regHrsOTPay = $salaryData['reghoursotamount'] ?? 0;
        $regHolWork = $salaryData['regholidayhrswork2'] ?? 0;
        $regHolWorkPay = $salaryData['regholidayamountwork2'] ?? 0;
        $regHolNotWork = $salaryData['regholidayhrsnotwork'] ?? 0;
        $regHolNotWorkPay = $salaryData['regholidayamountnotwork'] ?? 0;
        $regHolOT = $salaryData['regholidayothrs'] ?? 0;
        $regHolOTPay = $salaryData['regholidayotamount'] ?? 0;
        $specialNonWorkHol = $salaryData['spholidayhrs2'] ?? 0;
        $specialNonWorkHolPay = $salaryData['spholidayamount2'] ?? 0;
        $specialNonWorkHolOT = $salaryData['spholidayothrs'] ?? 0;
        $specialNonWorkHolOTPay = $salaryData['spholidayotamount'] ?? 0;
        $spholiday = $salaryData['spholidayhrs'] ?? 0;
        $spholidayPay = $salaryData['spholidayamount2'] ?? 0;
        $paidVacation = $salaryData['paidvlhrs'] ?? 0;
        $paidVacationPay = $salaryData['paidvlamount'] ?? 0;
        $paidSick = $salaryData['paidslhrs'] ?? 0;
        $paidSickPay = $salaryData['paidslamount'] ?? 0;
        $nightDiff = $salaryData['ndhrs'] ?? 0;
        $nightDiffPay = $salaryData['ndamount'] ?? 0;
    }

    // Format values for display
    $regHrsOTAmount = number_format($regHrsOTPay, 2);
    $regHolWorkAmount = number_format($regHolWorkPay, 2);
    $regHolNotWorkAmount = number_format($regHolNotWorkPay, 2);
    $regHolOTAmount = number_format($regHolOTPay, 2);
    $specialNonWorkHolAmount = number_format($specialNonWorkHolPay, 2);
    $specialNonWorkHolOTAmount = number_format($specialNonWorkHolOTPay, 2);
    $spholidayAmount = number_format($spholidayPay, 2);
    $paidVacationAmount = number_format($paidVacationPay, 2);
    $paidSickAmount = number_format($paidSickPay, 2);
    $nightDiffAmount = number_format($nightDiffPay, 2);

    // Calculate and format basic salary details
    $regHrsPay = (($regHrs) / 8) * $baserate;
    $regHrsAmount = number_format($regHrsPay, 2);
    $regHrsForm = number_format($regHrs, 2);
    $regHrsOTForm = number_format($regHrsOT, 2);
    $regHolNotWorkForm = number_format($regHolNotWork, 2);
    $regHolWorkForm = number_format($regHolWork, 2);
    $regHolOTForm = number_format($regHolOT, 2);
    $specialNonWorkHolForm = number_format($specialNonWorkHol, 2);
    $specialNonWorkHolOTForm = number_format($specialNonWorkHolOT, 2);
    $paidVacationForm = number_format($paidVacation, 2);
    $paidSickForm = number_format($paidSick, 2);
    $nightDiffForm = number_format($nightDiff, 2);
    $totalBasic = $regHrsPay + $regHrsOTPay + $regHolNotWorkPay + $regHolWorkPay + $regHolOTPay + $specialNonWorkHolPay + $specialNonWorkHolOTPay + $paidVacationPay + $paidSickPay + $nightDiffPay;
    $totalBasicSalary = number_format($totalBasic, 2);
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
            background-color: #b0bfc5;
            font-weight: bold;
        }
        .total {
            background-color: #c6efce;
            font-weight: bold;
        }
        .background {
            position: relative;
        }

        .background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/hris/accounting/img/3.png') center/cover no-repeat;
            opacity: 0.1; /* Adjust transparency */
            z-index: -1;
        }
    </style>
</head>
<body>
    <div style="border: 2px solid black; padding: 30px; width: 50%; justify-content: center; align-items: center; font-size: 70%; position: absolute; top: 50%; left: 50%; 
            transform: translate(-50%, -50%);" class="background">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>EMPLOYEE NAME:</strong></td>
                        <td style="padding-left: 70px;"><?=$fullname?></td>
                    </tr>
                    <tr>
                        <td><strong>TEAM:</strong></td>
                        <td style="padding-left: 70px;"><?=$department?></td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%; text-align: right;">
                <table class="header-table">
                    <tr>
                        <td><strong>BASIC MONTHLY SALARY:</strong></td>
                        <td style="text-align: right; white-space: nowrap;"><?=$fixedSalary?></td>
                    </tr>
                    <tr>
                        <td><strong>PAY PER DAY:</strong></td>
                        <td style="text-align: right; white-space: nowrap;"><?=$baseFixed?></td>
                    </tr>
                    <tr>
                        <td><strong>Pay Period:</strong></td>
                        <td style="text-align: right; white-space: nowrap;"><?=date('M j, Y',strtotime($payrollperiod['periodfrom']));?> to <?=date('M j, Y',strtotime($payrollperiod['periodto']));?></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--  -->
                <style>
            .payroll-container {
                display: flex;
                justify-content: space-between;
            }

            .payroll-section {
                width: 49%;
                display: flex;
                flex-direction: column;
            }

            .header-table {
                width: 100%;
                border-collapse: collapse;
            }

            .header-table td {
                padding: 8px;
                vertical-align: middle;
            }

            /* Ensure both tables have equal height */
            .table-wrapper {
                display: table;
                width: 100%;
            }
        </style>
        <div class="payroll-container">
            <!-- Gross Earnings Table -->
            <div class="payroll-section">
                <h4 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">
                    GROSS EARNINGS AND BENEFITS FOR THIS PAY PERIOD
                </h4>
                <div class="table-wrapper">
                    <table class="header-table">
                        <?php
                            // Collecting all the rows dynamically to count and match both tables
                            $grossEarningsRows = [
                                ["Basic Salary", $totalBasicSalary],
                                ["Allowances", $allowanceTotal],
                                ["Incentives", $incentiveTotal],
                                ["Reimbursements", $reimbursementTotal],
                                ["Company Paid Government Benefits", $CompanyPaidGovBenAmount],
                                ["Other Company Paid Benefits", $CompanyGovBenAmount]
                            ];

                            $gross = $totalBasic + $allowanceAmount + $incentiveAmount + $reimbursementAmount + $CompanyPaidGovBen + $CompanyGovBen;
                            $grossEarnings = number_format($gross, 2);
                            
                            foreach ($grossEarningsRows as $row) {
                                echo "<tr>
                                        <td style='padding-left: 20px;'>{$row[0]}</td>
                                        <td style='text-align: right; white-space: nowrap;'>{$row[1]}</td>
                                    </tr>";
                            }
                        ?>
                        <tr class="highlight">
                            <td>TOTAL GROSS EARNINGS & BENEFITS FOR THIS PAY PERIOD</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small"><?=$grossEarnings?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Take Home Pay Table -->
            <div class="payroll-section">
                <h4 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">
                    TAKE HOME PAY COMPUTATION
                </h4>
                <div class="table-wrapper">
                    <table class="header-table">
                        <?php
                            $takeHomePayRows = [
                                ["Basic Salary", $totalBasicSalary],
                                ["Add On Pay", $totaladdAmount],
                                ["Deductions", "- " . $totaldeductAmount]
                            ];

                            $takehomepay = ($totalBasic + $totaladdons) - $totaldeductions;
                            $takehome = number_format($takehomepay, 2);
                            
                            // Match row count for alignment
                            $maxRows = max(count($grossEarningsRows), count($takeHomePayRows));
                            for ($i = 0; $i < $maxRows; $i++) {
                                $leftRow = $grossEarningsRows[$i] ?? ["&nbsp;", "&nbsp;"];
                                $rightRow = $takeHomePayRows[$i] ?? ["&nbsp;", "&nbsp;"];
                                echo "<tr>
                                        <td style='padding-left: 20px;'>{$rightRow[0]}</td>
                                        <td style='text-align: right; white-space: nowrap;'>{$rightRow[1]}</td>
                                    </tr>";
                            }
                        ?>
                        <tr class="highlight">
                            <td>TOTAL TAKE HOME PAY FOR THIS PAY PERIOD</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small"><?=$takehome?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <h3 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">BREAKDOWN OF SALARY AND BENEFITS</h3>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 49%;">
                    <table class="header-table">
                        <tr>
                            <td><strong>Basic Salary Breakdown</td>
                            <td style="text-align: center;"><strong>Total No. of Hours</td>
                            <td style="text-align: right;"><strong>Base Rate <?=$baseFixed?>/day</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Regular Hours</td>
                            <td style="text-align: center;"><?=$regHrsForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$regHrsAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">RegHours / Overtime</td>
                            <td style="text-align: center;"><?=$regHrsOTForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$regHrsOTAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Regular Holiday (Not Worked)</td>
                            <td style="text-align: center;"><?=$regHolNotWorkForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$regHolNotWorkAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Regular Holiday (Worked)</td>
                            <td style="text-align: center;"><?=$regHolWorkForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$regHolWorkAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">OT for Regular Holiday</td>
                            <td style="text-align: center;"><?=$regHolOTForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$regHolOTAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Special Non Working Holiday</td>
                            <td style="text-align: center;"><?=$specialNonWorkHolForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$specialNonWorkHolAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">OT for Special Non Working Holiday</td>
                            <td style="text-align: center;"><?=$specialNonWorkHolOTForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$specialNonWorkHolOTAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Paid Vacation</td>
                            <td style="text-align: center;"><?=$paidVacationForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$paidVacationAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Paid Sick</td>
                            <td style="text-align: center;"><?=$paidSickForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$paidSickAmount?></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;">Night Differential</td>
                            <td style="text-align: center;"><?=$nightDiffForm?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$nightDiffAmount?></td>
                        </tr>
                        <tr class="highlight">
                            <td>TOTAL BASIC SALARY</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: right; white-space: nowrap; font-size:small"><?=$totalBasicSalary?></td>
                        </tr>
                    </table>
                </div>
                
                <div style="width: 49%;">
                    <table class="header-table">
                        <tr>
                            <td><strong>Add On Pay Breakdown</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;"><?=$addonList?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$addonAmount?></td>
                        </tr>
                        <tr class="highlight">
                            <td>TOTAL ADD ON PAY</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small"><?=$totaladdAmount?></td>
                        </tr>
                        <tr>
                            <td><strong>Deductions Breakdown</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px;"><?=$deductionList?></td>
                            <td style="text-align: right; white-space: nowrap;"><?=$deductionAmount?></td>
                        </tr>
                        <tr class="highlight">
                            <td>TOTAL DEDUCTIONS</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small"><?=$totaldeductAmount?></td>
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
                        <td style="padding-left: 20px;">SSS Contribution</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$sssAmount?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Philhealth Contribution</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$phicAmount?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Pag-ibig Contribution</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$hdmfAmount?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Withholding Tax</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$taxAmount?></td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL COMPANY PAID GOVERNMENT BENEFITS</td>
                        <td style="text-align: right; white-space: nowrap; font-size:small"><?=$CompanyPaidGovBenAmount?></td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>Company Paid Benefits</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Life Insurance</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$generaliAmount?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Philcare (HMO)</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$philcareAmount?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">FWD Retirement Benefit</td>
                        <td style="text-align: right; white-space: nowrap;"><?=$fwdAmount?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL OTHER COMPANY PAID BENEFITS</td>
                        <td style="text-align: right; white-space: nowrap; font-size:small"><?=$CompanyGovBenAmount?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
