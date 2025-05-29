<?php
    $id = $_GET['period'];
    $comp = $_GET['company'];

    $sqlPayroll = mysqli_query($con, "SELECT * FROM payroll WHERE id='$id'");
    $payroll = mysqli_fetch_array($sqlPayroll);

    // Fetch Payroll Status Counts and Salary Type
    $posted = 0;
    $notposted = 0;
    $salary_type = 'Rated'; // Default to 'Rated' if no employees are found

    $sqlDetails = mysqli_query($con, "SELECT pd.status, ep.salary_type 
        FROM payroll_details pd 
        INNER JOIN employee_details ed ON ed.idno = pd.idno
        INNER JOIN employee_payroll ep ON ep.idno = ed.idno 
        WHERE pd.payrollperiod='$id' AND ed.company='$comp'");

$salary_type = null; // Default to null to detect changes

while ($details = mysqli_fetch_array($sqlDetails)) {
    // Count posted vs. not posted
    if ($details['status'] == "posted") {
        $posted++;
    } else {
        $notposted++;
    }

    // Determine salary type
    if ($details['salary_type'] == 'Fixed') {
        $salary_type = 'Fixed'; // Prioritize Fixed if found
    } elseif ($salary_type === null) {
        $salary_type = 'Rated'; // Only assign Rated if Fixed has not been found
    }
}

      
    // Fetch Unique Departments for the Company
    $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.id, d.department FROM employee_details ed
        INNER JOIN department d ON d.id = ed.department
        WHERE ed.company = '$comp' 
        AND ed.status != 'RESIGNED'
        ORDER BY d.department");

    $departments = [];
    while ($row = mysqli_fetch_assoc($sqlDepartments)) {
        $departments[] = $row;
    }
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?viewpayroll"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-calendar"></i> PAYROLL PERIOD (<?=date('F d, Y',strtotime($payroll['periodfrom']));?> - <?=date('F d, Y',strtotime($payroll['periodto']));?>)
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

        <!-- Tabs for Departments -->
        <ul class="nav nav-pills" style="margin-top: 10px;">
            <?php $activeClass = 'active'; ?>
            <?php foreach ($departments as $dept) { 
                $deptId = preg_replace('/[^A-Za-z0-9]/', '', $dept['department']);
            ?>
                <li class="<?= $activeClass; ?>">
                    <a data-toggle="pill" href="#dept-<?= $deptId; ?>"><?= $dept['department']; ?></a>
                </li>
            <?php $activeClass = ''; } ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" style="margin-top: 10px;">
            <?php $activeClass = 'active'; ?>
            <?php foreach ($departments as $dept) { 
                $deptId = preg_replace('/[^A-Za-z0-9]/', '', $dept['department']);
            ?>
                <div id="dept-<?= $deptId; ?>" class="tab-pane fade in <?= $activeClass; ?>">
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No.</th>
                                    <th style="text-align: center;">Emp ID</th>
                                    <th style="text-align: center;">Employee Name</th>
                                    <th style="text-align: center;">Company</th>
                                    <th style="text-align: center;">Addons</th>
                                    <th style="text-align: center;">Total Gross</th>
                                    <th style="text-align: center;">Total Deductions</th>
                                    <th style="text-align: center;">Net Pay</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, d.department 
                                    FROM employee_profile ep
                                    INNER JOIN employee_details ed ON ed.idno = ep.idno
                                    INNER JOIN department d ON d.id = ed.department 
                                    WHERE ed.company = '$comp' 
                                    AND ed.department = '{$dept['id']}' 
                                    AND ed.status NOT LIKE '%RESIGNED%'
                                    ORDER BY ep.lastname ASC");

                                $x = 1;
                                if (mysqli_num_rows($sqlEmployee) > 0) {
                                    while ($employee = mysqli_fetch_array($sqlEmployee)) {
                                        $idno = $employee['idno'];
                                        $lastname = $employee['lastname'];
                                        $firstname = $employee['firstname'];
                                        $middlename = $employee['middlename'];
                                        $suffix = $employee['suffix'];

                                        // Fetch Payroll Details
                                        $sqlGross = mysqli_query($con, "SELECT * FROM payroll_details WHERE idno='$idno' AND payrollperiod='$id'");
                                        $payrollData = mysqli_fetch_array($sqlGross);
                                        $totalpay = $payrollData['totalpay'] ?? 0;
                                        $payroll_id = $payrollData['id'] ?? "";

                                        // Fetch Payroll Deductions
                                        $sqlDeduction = mysqli_query($con, "SELECT SUM(amount) as amount FROM payroll_deductions WHERE idno='$idno' AND payrollperiod='$id' GROUP BY idno");
                                        $deductions = mysqli_fetch_array($sqlDeduction)['amount'] ?? 0;

                                        // Fetch Payroll Addons
                                        $sqlAddons = mysqli_query($con, "SELECT SUM(amount) as amount FROM payroll_addons WHERE idno='$idno' AND payrollperiod='$id' GROUP BY idno");
                                        $addons = mysqli_fetch_array($sqlAddons)['amount'] ?? 0;
                                        
                                        //Identify salary type
                                        $salary = 0;
                                        $sqlSalaryType = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno='$idno'");
                                         if (mysqli_num_rows($sqlSalaryType) > 0) {
                                             $type = mysqli_fetch_array($sqlSalaryType);
                                             $salary = $type['salary'];
                                             $salary_type = $type['salary_type'];
                                         }
                                        
                                        // Compute Net Pay
                                        $netpay = number_format(($totalpay + $addons) - $deductions, 2);
                                        ?>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
                                        <script>
                                        function exportToExcel() {
                                            var wb = XLSX.utils.book_new();
                                        
                                            // Create virtual tables for each logical section
                                            var sections = [
                                                { id: "payslip-employee-info", name: "Employee Info" },
                                                { id: "payslip-gross-earnings", name: "Gross Earnings" },
                                                { id: "payslip-take-home", name: "Take Home Pay" },
                                                { id: "payslip-breakdown", name: "Breakdown" },
                                                { id: "payslip-gov-benefits", name: "Gov Benefits" },
                                                { id: "payslip-company-benefits", name: "Company Benefits" }
                                            ];
                                        
                                            sections.forEach(section => {
                                                var table = document.getElementById(section.id);
                                                if (table) {
                                                    var ws = XLSX.utils.table_to_sheet(table, {raw:true});
                                                    XLSX.utils.book_append_sheet(wb, ws, section.name);
                                                }
                                            });
                                        
                                            XLSX.writeFile(wb, "Payslip.xlsx");
                                        }
                                        </script>

                                        <tr>
                                            <td align="center"><?= $x; ?>.</td>
                                            <td align="center"><?= $idno; ?></td>
                                            <td><?= "<strong>$lastname</strong>, $firstname $middlename $suffix"; ?></td>
                                            <td align="center"><?= $comp; ?></td>
                                            <td align="right"><?= number_format($addons, 2); ?></td>
                                            <td align="right"><?= number_format($totalpay, 2); ?></td>
                                            <td align="right"><?= number_format($deductions, 2); ?></td>
                                            <td align="right"><?= $netpay; ?></td>
                                            <td align="center">
                                                <a href="?editpayroll&idno=<?= $idno ?>&period=<?= $id; ?>&company=<?= $comp; ?>" 
                                                   class="btn btn-primary btn-xs"   title="Edit Payroll">
                                                    <i class='fa fa-pencil'></i>
                                                </a>
                                                <?php 
                                                // Fetch employee salary type
                                                $sqlSalaryType = mysqli_query($con, "SELECT salary_type FROM employee_payroll WHERE idno='$idno'");
                                                $salaryData = mysqli_fetch_array($sqlSalaryType);
                                                $empSalaryType = $salaryData['salary_type'] ?? 'Rated'; // Default to 'Rated' if not found

                                                if ($payroll_id) { ?>
                                                    <?php if ($empSalaryType == 'Rated') { ?>
                                                        <a href="payslipRated.php?id=<?=$payroll_id;?>" class="btn btn-warning btn-xs" title="Print Payslip" target="_blank"><i class='fa fa-print'></i></a>
                                                        <a href="exporttopdfRated.php?id=<?=$payroll_id?>&idno=<?=$idno?>&period=<?=$id?>&company=<?=$comp?>" 
                                                           class="btn btn-success btn-xs"   title="Export Payslip">
                                                            <i class='fa fa-download'></i>
                                                        </a>
                                                    <?php } else if ($empSalaryType == 'Fixed') { ?>  
                                                        <a href="payslip.php?id=<?=$payroll_id;?>" class="btn btn-warning btn-xs" title="Print Payslip" target="_blank"><i class='fa fa-print'></i></a>
                                                        <a href="export_payslip.php?id=<?=$payroll_id?>&idno=<?=$idno?>&period=<?=$id?>&company=<?=$comp?>" 
                                                           class="btn btn-success btn-xs"   title="Export Payslip">
                                                            <i class='fa fa-download'></i>
                                                        </a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $x++;
                                    }
                                } else {
                                    echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php $activeClass = ''; } ?>
        </div>
    </div>
</div>
<?php
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
