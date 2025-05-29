<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <link rel="icon" type="image/x-icon" href="img/iconhris_2.png">
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
    <?php
require_once('tcpdf/tcpdf.php');

// Create a new PDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company Name');
$pdf->SetTitle('Employee Payslip');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$html = '
    <div style="border: 2px solid black; padding: 10px; width: 47%; justify-content: center; align-items: center; font-size: 70%; position: absolute; top: 50%; left: 50%; 
            transform: translate(-50%, -50%);" class="background">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between;">
            <div style="width: 49%;">
                <table class="header-table">
                    <tr>
                        <td><strong>EMPLOYEE NAME:</strong></td>
                        <td style="padding-left: 70px;">Marc Ian Misa </td>
                    </tr>
                    <tr>
                        <td><strong>TEAM:</strong></td>
                        <td style="padding-left: 70px;">Admin
</td>
                    </tr>
                </table>
            </div>
            
            <div style="width: 49%; text-align: right;">
                <table class="header-table">
                    <tr>
                        <td><strong>FIXED SEMI-MONTHLY SALARY:</strong></td>
                        <td style="text-align: right; white-space: nowrap;">10,000.00</td>
                    </tr>
                    <tr>
                        <td><strong>PAY PER DAY:</strong></td>
                        <td style="text-align: right; white-space: nowrap;">1,000.00</td>
                    </tr>
                    <tr>
                        <td><strong>Pay Period:</strong></td>
                        <td style="text-align: right; white-space: nowrap;">Feb 13, 2025 to Feb 27, 2025</td>
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
                        <tr>
                                        <td style='padding-left: 20px;'>Fixed Rate Salary</td>
                                        <td style='text-align: right; white-space: nowrap;'>10,000.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Allowances</td>
                                        <td style='text-align: right; white-space: nowrap;'>0.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Incentives</td>
                                        <td style='text-align: right; white-space: nowrap;'>0.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Reimbursements</td>
                                        <td style='text-align: right; white-space: nowrap;'>0.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Company Paid Government Benefits</td>
                                        <td style='text-align: right; white-space: nowrap;'>3,600.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Other Company Paid Benefits</td>
                                        <td style='text-align: right; white-space: nowrap;'>2,400.00</td>
                                    </tr>                        <tr class="highlight">
                            <td>TOTAL GROSS EARNINGS & BENEFITS FOR THIS PAY PERIOD</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small">16,000.00</td>
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
                        <tr>
                                        <td style='padding-left: 20px;'>Fixed Rate Salary</td>
                                        <td style='text-align: right; white-space: nowrap;'>10,000.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Add On Pay</td>
                                        <td style='text-align: right; white-space: nowrap;'>0.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>Deductions</td>
                                        <td style='text-align: right; white-space: nowrap;'>- 0.00</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>&nbsp;</td>
                                        <td style='text-align: right; white-space: nowrap;'>&nbsp;</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>&nbsp;</td>
                                        <td style='text-align: right; white-space: nowrap;'>&nbsp;</td>
                                    </tr><tr>
                                        <td style='padding-left: 20px;'>&nbsp;</td>
                                        <td style='text-align: right; white-space: nowrap;'>&nbsp;</td>
                                    </tr>                        <tr class="highlight">
                            <td>TOTAL TAKE HOME PAY FOR THIS PAY PERIOD</td>
                            <td style="text-align: right; white-space: nowrap; font-size:small">10,000.00</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div>
    <h3 style="text-align: center; background-color: #1d2437; color: white; padding: 5px">
        BREAKDOWN OF SALARY AND BENEFITS
    </h3>
    <div style="display: flex; justify-content: space-between;">

        <!-- Deductions Breakdown -->
        <div style="width: 49%;">
            <table class="header-table" style="width: 100%;">
                <tr>
                    <td><strong>Deductions Breakdown</strong></td>
                    <td style="text-align: right;"><strong>Amount</strong></td>
                </tr>
                <tr><td style='padding-left: 20px;'></td><td style='text-align: right; white-space: nowrap;'></td></tr>                <tr class="highlight">
                    <td style="padding-left: 20px;"><strong>TOTAL DEDUCTIONS</strong></td>
                    <td style="text-align: right; white-space: nowrap; font-size:small">
                        <strong>0.00</strong>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Add On Pay Breakdown -->
        <div style="width: 49%;">
            <table class="header-table" style="width: 100%;">
                <tr>
                    <td><strong>Add On Pay Breakdown</strong></td>
                    <td style="text-align: right;"><strong>Amount</strong></td>
                </tr>
                <tr><td style='padding-left: 20px;'></td><td style='text-align: right; white-space: nowrap;'></td></tr>                <tr class="highlight">
                    <td style="padding-left: 20px;"><strong>TOTAL ADD ON PAY</strong></td>
                    <td style="text-align: right; white-space: nowrap; font-size:small">
                        <strong>0.00</strong>
                    </td>
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
                        <td style="text-align: right; white-space: nowrap;">1,200.00<br></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Philhealth Contribution</td>
                        <td style="text-align: right; white-space: nowrap;">1,200.00<br></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Pag-ibig Contribution</td>
                        <td style="text-align: right; white-space: nowrap;">1,200.00<br></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Withholding Tax</td>
                        <td style="text-align: right; white-space: nowrap;">0.00<br></td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL COMPANY PAID GOVERNMENT BENEFITS</td>
                        <td style="text-align: right; white-space: nowrap; font-size:small">3,600.00</td>
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
                        <td style="text-align: right; white-space: nowrap;">1,200.00<br></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">Philcare (HMO)</td>
                        <td style="text-align: right; white-space: nowrap;">1,200.00<br></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;">FWD Retirement Benefit</td>
                        <td style="text-align: right; white-space: nowrap;">0.00<br></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="highlight">
                        <td>TOTAL OTHER COMPANY PAID BENEFITS</td>
                        <td style="text-align: right; white-space: nowrap; font-size:small">2,400.00</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
';
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF (Change 'D' to 'I' to display in the browser)
$pdf->Output('payslip.pdf', 'D');
?>
