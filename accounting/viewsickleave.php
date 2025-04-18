<?php
$cmpy = $_GET['company'];

// Fetch Unique Departments for the Company
$sqlDepartments = mysqli_query($con, 
    "SELECT DISTINCT d.id, d.department 
     FROM employee_details ed
     INNER JOIN department d ON d.id = ed.department
     WHERE ed.company = '$cmpy' 
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
            <h4><a href="?sickleave"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE BENEFITS</h4>
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
                                    <th style="text-align: center;" width="3%">No.</th>
                                    <th style="text-align: center;">Emp ID</th>
                                    <th style="text-align: center;">Employee Name</th>
                                    <th style="text-align: center;">Department</th>
                                    <th style="text-align: center;">Eligibility</th>
                                    <th style="text-align: center;">Unused Sick Leave</th>
                                    <th style="text-align: center;">Unused Vacation Leave</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $x = 1;
                                $sqlEmployee = mysqli_query($con,
                                    "SELECT ep.*, ed.*
                                     FROM employee_profile ep
                                     LEFT JOIN employee_details ed ON ed.idno = ep.idno
                                     WHERE ed.status NOT LIKE '%RESIGNED%'
                                     AND ed.company = '$cmpy'
                                     AND ed.department = '{$dept['id']}'
                                     ORDER BY ep.lastname ASC"
                                );

                                if (mysqli_num_rows($sqlEmployee) > 0) {
                                    while ($company = mysqli_fetch_array($sqlEmployee)) {
                                        $dateleaveeffective = date('m/d/Y', strtotime($company['dateleaveeffective']));
                                        $slremain = 0;
                                        $vlremain = 0;

                                        $sqlBenefits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno = '{$company['idno']}'");
                                        if (mysqli_num_rows($sqlBenefits) > 0) {
                                            $benefits = mysqli_fetch_array($sqlBenefits);
                                            $slremain = $benefits['sickleave'] - $benefits['slused'];
                                            $vlremain = $benefits['vacationleave'] - $benefits['vlused'];
                                        }

                                        if ($slremain > 0) {
                                            echo "<tr>";
                                            echo "<td align='center'>$x.</td>";
                                            echo "<td align='center'>{$company['idno']}</td>";
                                            echo "<td><strong>{$company['lastname']}</strong>, {$company['firstname']} {$company['middlename']} {$company['suffix']}</td>";
                                            echo "<td align='center'>{$dept['department']}</td>";
                                            echo "<td align='center'>$dateleaveeffective</td>";
                                            echo "<td align='center'>$slremain</td>";
                                            echo "<td align='center'>$vlremain</td>";
                                            echo "</tr>";
                                            $x++;
                                        }
                                    }
                                } else {
                                    echo "<tr><td colspan='7' align='center'>No record found!</td></tr>";
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