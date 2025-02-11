<?php
    include('../config.php');
    $id=$_GET['id'];
    $sqlPayroll=mysqli_query($con,"SELECT * FROM payroll_details WHERE id='$id'");
    $payroll=mysqli_fetch_array($sqlPayroll);
    $idno=$payroll['idno'];
    $period=$payroll['payrollperiod'];
    $grosspay=$payroll['totalpay'];



    $sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
    $employee=mysqli_fetch_array($sqlEmployee);

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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>PAYSLIP</title>

  <!-- Favicons -->
  <!-- <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link rel="icon" type="image/x-icon" href="img/nesi.jpg">


  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />

  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>

</head>

<body>
<div class="container">
    <div class="row">
      <div class="col-lg-8 col-sm-9 col-lg-offset-2">
        <br>
        <div class="row">
          <div class="col-md-8 col-md-offset-2" style="border:1px solid black">
            <div class="row mt">
                <table width="100%" style="margin:5px 5px 5px 15px;">
                    <tr>
                        <td style="color:black;"><b>Employee Name:</b></td>
                        <td width="10%">&nbsp;</td>
                        <td align="center" style="border-bottom:1px solid; color:black;"><?=$employee['firstname'];?> <?=$employee['lastname'];?> <?=$employee['suffix'];?></td>
                        <td width="10%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="color:black;"><b>Manager Name:</b></td>
                        <td width="10%">&nbsp;</td>
                        <td align="center" style="border-bottom:1px solid; color:black;"><?=$manager['companyceo'];?></td>
                        <td width="10%">&nbsp;</td>
                    </tr>
                </table>
                <br>
                <table width="100%" style="margin:5px 5px 5px 15px;">
                    <tr>
                        <td colspan="2" width="40%">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                        <td colspan="2" style="color:black;"><b>Other Monthly Company Benefits</b></td>
                        <td width="10%"></td>
                    </tr>
                    <tr>
                        <td width="30%" style="color:black;"><b>Basic Salary</b></td>
                        <td width="10%" style="color:black;" align="right"><?=number_format($grosspay,2);?></td>
                        <td width="8">&nbsp;</td>
                        <td rowspan="5" align="left" width="30%" style="vertical-align:top; color:black;"><?=$benList;?></td>
                        <td rowspan="5" align="right" style="vertical-align:top; color:black"><?=$benAmount;?></td>
                    </tr>
                    <?php
                    if($loc=="mid"){
                        ?>
                    <tr>
                        <td width="30%" style="color:black;">&nbsp;</td>
                        <td width="10%" style="color:black;" align="right">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" style="color:black;">&nbsp;</td>
                        <td width="10%" style="color:black;" align="right">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" style="color:black;">&nbsp;</td>
                        <td width="10%" style="color:black;" align="right">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" align="center" style="color:black;">&nbsp;</td>
                        <td width="10%" style="color:black;" align="right">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                    </tr>
                        <?php
                    }else{
                    ?>
                    <tr>
                        <td width="30%" style="color:black;"><b>SSS</b></td>
                        <td width="10%" style="color:black;" align="right"><?=number_format($sss,2);?></td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" style="color:black;"><b>Philhealth</b></td>
                        <td width="10%" style="color:black;" align="right"><?=number_format($phic,2);?></td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" style="color:black;"><b>Pag-ibig</b></td>
                        <td width="10%" style="color:black;" align="right"><?=number_format($hdmf,2);?></td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="30%" align="center" style="color:black;">&nbsp;</td>
                        <td width="10%" style="color:black;" align="right">&nbsp;</td>
                        <td width="10">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    $totalpay=$grosspay+$sss+$phic+$hdmf;
                    ?>
                    <tr>
                        <td width="30%" align="center" style="color:black;"><b>Total</b></td>
                        <td width="10%" style="color:black;" align="right"><b><?=number_format($totalpay,2);?></b></td>
                        <td width="10">&nbsp;</td>
                        <td style="color:black;" align="center"><b>Total</b></td>
                        <td style="color:black;" align="right"><b><?=number_format($totalbenefits,2);?></b></td>
                    </tr>
                </table>
                <br>
                <table width="100%" style="margin:5px 5px 5px 15px;">
                    <tr>
                        <td colspan="3" style="color:black;"><b>Basic Salary</b></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['totalpay'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    $additional=0;
                    $sqlAddons=mysqli_query($con,"SELECT * FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$period'");
                    if(mysqli_num_rows($sqlAddons)>0){
                        while($addons=mysqli_fetch_array($sqlAddons)){
                            ?>
                    <tr>
                        <td colspan="3" style="color:black;"><b><?=$addons['description'];?></b></td>
                        <td align="right" style="color:black;"><?=number_format($addons['amount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                            <?php
                            $additional +=$addons['amount'];
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="color:black;"><b>Less:</b></td>
                    </tr>
                    <?php
                    $totaldeductions=0;
                    $sqlDeductions=mysqli_query($con,"SELECT * FROM payroll_deductions WHERE idno='$idno' AND payrollperiod='$period'");
                    if(mysqli_num_rows($sqlDeductions)>0){
                        while($deduction=mysqli_fetch_array($sqlDeductions)){
                            $totaldeductions +=$deduction['amount'];
                            ?>
                            <tr>
                                <td colspan="3" style="color:black; text-indent:10px;"><b><?=$deduction['description'];?></b></td>
                                <td align="right" style="color:black;"><?=number_format($deduction['amount'],2);?></td>
                                <td width="15%">&nbsp;</td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:black;"><b>Take Home Pay (<?=ucwords($loc);?> of the Month)</b></td>
                        <td align="right" style="color:black;"><?=number_format($additional+$payroll['totalpay']-$totaldeductions,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="color:black;"><b>Week Starting: <?=date('m/d/Y',strtotime($payrollperiod['periodfrom']));?> to <?=date('m/d/Y',strtotime($payrollperiod['periodto']));?></b></td>
                    </tr>
                    <tr>
                        <td colspan="2" width="35%" style="color:black;"><b>Basic Salary Breakdown</b></td>
                        <td align="center" style="color:black;">Total No. of Hours</td>
                        <td align="center" style="color:black;">Base Rate <?=$baserate;?>/day</td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Regular Hours</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['reghours'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['reghours']/8*$baserate,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Reg Hours / Overtime</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['reghoursot'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['reghoursotamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Reg Holiday (Not Worked)</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['regholidayhrsnotwork'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['regholidayamountnotwork'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Reg Holiday (Worked) 220%</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['regholidayhrswork1'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['regholidayamountwork1'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Reg Holiday (Worked) 200%</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['regholidayhrswork2'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['regholidayamountwork2'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>OT for Reg Holiday (Worked)</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['regholidayothrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['regholidayotamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Sp. Non-Work. Holiday 143%</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['spholidayhrs1'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['spholidayamount1'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Sp. Non-Work. Holiday 130%</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['spholidayhrs2'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['spholidayamount2'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>OT for Sp. Non-Work. Holiday</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['spholidayothrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['spholidayotamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Night Differential</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['ndhrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['ndamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    if($payroll['paidslhrs']>0){
                    ?>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Paid SL</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['paidslhrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['paidslamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if($payroll['paidvlhrs']>0){
                    ?>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Paid SL</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['paidvlhrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['paidvlamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if($payroll['paidblhrs']>0){
                    ?>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Paid SL</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['paidblhrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['paidblamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if($payroll['bdayleavehrs']>0){
                    ?>
                    <tr>
                        <td colspan="2" style="color:black;"><b>Paid SL</b></td>
                        <td align="center" style="color:black;"><?=number_format($payroll['bdayleavehrs'],2);?></td>
                        <td align="right" style="color:black;"><?=number_format($payroll['bdayleaveamount'],2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;" align="center"><b>Total</b></td>
                        <td align="center" style="color:black;"></td>
                        <td align="right" style="color:black;"><?=number_format($grosspay,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    if($loc=="end"){
                    ?>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black"><b>Mid <?=$month;?> Salary</b></td>
                        <td align="center" style="color:black;"></td>
                        <td align="right" style="color:black;"><?=number_format($midPayroll,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="color:black;"><b>End <?=$month;?> Salary</b></td>
                        <td align="center" style="color:black;"></td>
                        <td align="right" style="color:black;"><?=number_format($totalpay,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="color:black; font-size:16px;"><b>Total <?=$month;?> Salary & Benefits</b></td>
                        <td align="right" style="color:black; font-size:16px;"><?=number_format($totalpay+$midPayroll,2);?></td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
