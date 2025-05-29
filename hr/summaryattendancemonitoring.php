<?php
$comp = isset($_GET['company']) ? $_GET['company'] : '';
$startdate = $_GET['startdate'];
$enddate = $_GET['enddate'];

$dept = isset($_GET['departments']) ? $_GET['departments'] : []; 

if (!empty($dept)) {
    // Ensure all IDs are integers
    $dept = array_map('intval', $dept);

    // Build the query safely
    $sqlDepartment = "SELECT * FROM department WHERE id IN (" . implode(',', $dept) . ")";
    $result = mysqli_query($con, $sqlDepartment);

    $deptNames = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $deptNames[$row['id']] = $row['department'];  // key = id, value = name
    }
}
?>
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?monitorattendance"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-user"></i> EMPLOYEE LIST 
                 <div style="float:right; margin-bottom: 20px;">
                    <form>
                        <button type="button" onclick="tablesToExcel('Infraction_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
                </div>
            </h4>
        </div>

        <!-- Tabs for Departments -->
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $active = "active";
            if (!empty($deptNames)) {
                foreach ($deptNames as $deptId => $deptName) {
                    $safeDeptName = htmlspecialchars($deptName);
                    $tabId = 'dept_' . $deptId;
                    echo "<li role='presentation' class='$active'>
                            <a href='#$tabId' aria-controls='$tabId' role='tab' data-toggle='tab'>$safeDeptName</a>
                        </li>";
                    $active = "";
                }
            } else {
                echo "<li role='presentation' class='active'><a href='#allDepartments' aria-controls='allDepartments' role='tab' data-toggle='tab'>All Employees</a></li>";
            }
            ?>
        </ul>

        <div class="panel-body tab-content" id="printThis">
            <b>Company: <?=!empty($comp) ? $comp : 'All';?></b><br />
            <b>Date Range: <?=date('m/d/Y',strtotime($startdate));?> - <?=date('m/d/Y',strtotime($enddate));?></b>
            <ul class="nav nav-tabs">
            <?php
            
            $active = "active";

            if (!empty($deptNames)) {
                foreach ($deptNames as $deptId => $deptName) {
                    $safeDeptName = htmlspecialchars($deptName);
                    $tabId = 'dept_' . $deptId;
                    ?>
                    </ul>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="<?=$tabId?>">
                        <h4>Department: <?=$safeDeptName;?></h4>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="3%" rowspan="2" style="vertical-align:middle; text-align: center;">No.</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Emp ID</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Employee Name</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Department</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Shift</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Work Area</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Date</th>
                                    <th colspan="2" style="vertical-align:middle; text-align: center;">Shift 1</th>
                                    <th colspan="2" style="vertical-align:middle; text-align: center;">Shift 2</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Action</th>
                                    <th rowspan="2" style="vertical-align:middle; text-align: center;">Add Time</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align:middle; text-align: center;">Login</th>
                                    <th style="vertical-align:middle; text-align: center;">Lunch out</th>
                                    <th style="vertical-align:middle; text-align: center;">Lunch in</th>
                                    <th style="vertical-align:middle; text-align: center;">Logout</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $x = 1;
                                mysqli_query($con, "SET NAMES 'utf8'");

                                // Assume you set $tabDeptId earlier in your loop or tab logic
                                // Example: $tabDeptId = 'DEPT123'; // Should match the current tab's department ID

                                $sqlEmployee = null;

                                // Condition 1: If both company and department are selected
                                if (!empty($comp) && !empty($dept)) {
                                    $escapedDeptList = array_map(function($d) use ($con) {
                                        return mysqli_real_escape_string($con, $d);
                                    }, $dept);
                                    $deptList = "'" . implode("','", $escapedDeptList) . "'";
                                    
                                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                                        LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                        WHERE ed.status NOT LIKE '%RESIGNED%' 
                                        AND ed.department = '$deptId'
                                        ORDER BY ep.lastname ASC");
                                }
                                // Condition 2: If only the department is selected
                                elseif (!empty($dept) && empty($comp)) {
                                    $escapedDeptList = array_map(function($d) use ($con) {
                                        return mysqli_real_escape_string($con, $d);
                                    }, $dept);
                                    $deptList = "'" . implode("','", $escapedDeptList) . "'";
                                    
                                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                                        LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                        WHERE ed.status NOT LIKE '%RESIGNED%' 
                                        AND ed.department = '$deptId'
                                        ORDER BY ep.lastname ASC");
                                }
                                 // Display Employees
                                 if (mysqli_num_rows($sqlEmployee) > 0) {
                                    while ($company = mysqli_fetch_array($sqlEmployee)) {
                                        $idn = $company['idno'];
                                        $statusLabel = ($company['status'] == "REGULAR") 
                                            ? "<span class='label label-success label-mini'>$company[status]</span>" 
                                            : "<span class='label label-warning label-mini'>$company[status]</span>";
                                            
                                        $shift = date('h:i A', strtotime($company['startshift'])) . " - " . date('h:i A', strtotime($company['endshift']));
                                        $datehired = date('m/d/Y', strtotime($company['dateofhired']));

                                        $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$company[department]'");
                                        if(mysqli_num_rows($sqlDepartment)>0){
                                          $dept=mysqli_fetch_array($sqlDepartment);
                                          $deptName=$dept['department'];
                                        }else{
                                          $deptName="";
                                        }
                                        // Fetch attendance data
                                        $shift = date('h:i A', strtotime($company['startshift'])) . " - " . date('h:i A', strtotime($company['endshift']));
                                        $datehired = date('m/d/Y', strtotime($company['dateofhired']));
                                        
                                        $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance 
                                        WHERE logindate BETWEEN '$startdate' AND '$enddate' 
                                        AND idno = '$idn'             
                                        ORDER BY logindate ASC");
                                    
                                        
                                        $login1 = "";
                                        $logout1 = "";
                                        $login2 = "";
                                        $logout2 = "";
                                        $datearray = "";
                                        $action = "";
                                        $removepoint = "";
                                        
                                        if (mysqli_num_rows($sqlAttendance) > 0) {
                                            while ($attend = mysqli_fetch_array($sqlAttendance)) {
                                                $idno = $company['idno'];
                                                $datearray .= date('m/d/Y', strtotime($attend['logindate'])) . "<br>";
                                                $shiftfrom = $company['startshift'];
                                                $endshift = $company['endshift'];

                                                
                                                $lateThreshold = date('H:i:s', strtotime($shiftfrom) + 59);
                                        $remarks = ($attend['loginam'] > $lateThreshold) ? 'L' : 'P';
                                        $loginTime = $attend['loginam'];
                                        
                                        // Special condition for 12 AM shifts
                                        if ($shiftfrom === '00:00:00') {
                                            // Allow logins from the previous day (e.g., 11 PM) to not be late
                                            $previousDayStart = date('H:i:s', strtotime('-1 day', strtotime('09:00 PM')));
                                            $remarks = ($loginTime >= $previousDayStart || $loginTime <= $lateThreshold) ? 'P' : 'L';
                                        } elseif ($loginTime > $lateThreshold || ($loginTime >= '00:00:00' && $loginTime <= '02:00:00')) {
                                            $remarks = 'L';
                                        } else {
                                            $remarks = 'P';
                                        }
                                
                // If the user is late, assign offense points and remarks automatically
                if ($remarks === 'L') {
                  
                    $color = "style='color:red;'"; // Red color for late
                } else {
                    $color = "";
                }
        
                $colorLogoutAM = "";
                $colorLoginPM = "";
                $colorLogoutPM = "";

                                           

                                                
                                                            // Code for detecting lateness remains unchanged...
                                                    
                                                            // Detect Over Break
                                                            if (isset($attend['logoutam']) && isset($attend['loginpm'])) {
                                                                $interval = strtotime($attend['loginpm']) - strtotime($attend['logoutam']); // Calculate interval between logoutam and loginpm
                                                                if ($interval > 3720) { // Overbreak threshold (1 hour)
                                                                    // Set Over Break remark
                                                                   
                                                                    // Set color specifically for overbreak fields (loginpm and logoutam)
                                                                    $colorLogoutAM = "style='color:Blue;'";
                                                                    $colorLoginPM = "style='color:Blue;'";
                                                                }
                                                            }
                                                    
                                                    

                                                    // Set gray color if no overbreak condition for logoutam, loginpm, and logoutpm
                                                    if ($attend['logoutam'] == "00:00:00") {
                                                        $colorLogoutAM = "style='color:transparent;'";
                                                    } else if (!$colorLogoutAM) { // Only gray if not already orange
                                                        $colorLogoutAM = "style='color:gray;'";
                                                    }

                                                    if ($attend['loginpm'] == "00:00:00") {
                                                        $colorLoginPM = "style='color:transparent;'";
                                                    } else if (!$colorLoginPM) { // Only gray if not already orange
                                                        $colorLoginPM = "style='color:gray;'";
                                                    }

                                                    if ($attend['logoutpm'] == "00:00:00") {
                                                        $colorLogoutPM = "style='color:transparent;'";
                                                    } else {
                                                        $colorLogoutPM = "style='color:gray;'";
                                                    }

                                                    // Build the output strings with the appropriate colors
                                                   // Build the output strings with the appropriate colors
                                                    $login1 .= "<font $color>" . (($attend['loginam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginam']))) . "</font><br>";
                                                    $logout1 .= "<font $colorLogoutAM>" . (($attend['logoutam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutam']))) . "</font><br>";
                                                    $login2 .= "<font $colorLoginPM>" . (($attend['loginpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginpm']))) . "</font><br>";
                                                    $logout2 .= "<font $colorLogoutPM>" . (($attend['logoutpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutpm']))) . "</font><br>";
                                                                                                    
                                                    
                                                $sqlPoints = mysqli_query($con, "SELECT * FROM points WHERE idno='$idno' AND logindate='{$attend['logindate']}'");
                                                if (mysqli_num_rows($sqlPoints) > 0) {
                                                    $point = mysqli_fetch_array($sqlPoints);
                                                    $points = $point['points'];
                                                    $point_id = $point['id'];
                                                } else {
                                                    $points = 0;
                                                    $point_id = "";
                                                }
                                        
                                                if ($point_id <> '') {
                                                    $removepoint = "| <a href='?attendancemonitoring&idno=$idno&id=$point_id&deleteinfraction&company=$comp&startdate=$startdate&enddate=$enddate&logindate={$attend['logindate']}' title='Delete Time'><i class='fa fa-trash'></i> Remove Infraction</a>";
                                                } else {
                                                    $removepoint = "";
                                                }
                                                
                                        
                                                $action .= "<a href='?attendancemonitoringsummary&edit&company=$comp&startdate=$startdate&enddate=$enddate&idno=$idno&logindate={$attend['logindate']}'>
                                                              <i class='fa fa-edit fa-fw'></i> Infraction</a> | 
                                                              <a href='?edittime&idno=$idno&id={$attend['id']}&company=$comp&startdate=$startdate&enddate=$enddate' title='Edit Time'>
                                                              <i class='fa fa-edit'></i> Time</a> | 
                                                              <a href='?attendancemonitoring&idno=$idno&id={$attend['id']}&deletetime&company=$comp&startdate=$startdate&enddate=$enddate&logindate={$attend['logindate']}' title='Delete Time' onClick='return confirm(\"Are you sure to delete this one?\");'>
                                                              <i class='fa fa-trash'></i> Delete Time</a> $removepoint<br>";
                                            }
                                        } else {
                                            $login1 = "-";
                                            $logout1 = "-";
                                            $login2 = "-";
                                            $logout2 = "-";
                                            $datearray = "-";
                                            $action = "";
                                        }
                                        $idno = $company['idno'];

                                        $sqlShift = mysqli_query($con, "SELECT * FROM employee_details WHERE idno = '$idno'");
                                        $Shift = mysqli_fetch_array($sqlShift);
                                        $startTime = date('h:i A', strtotime($Shift['startshift']));
                                        $nightShifts = ['12:00 AM', '01:00 AM', '11:00 PM'];
                                        
                                        $bgColor = in_array($startTime, $nightShifts) ? '#cccccc' : '';
                                        
                                        $sqlSalaryType = mysqli_query($con, "SELECT * FROM employee_payroll WHERE idno = '$idno'");
                                        $Salary = mysqli_fetch_array($sqlSalaryType);
                                        
                                        if (!empty($Salary['salary_type']) && $Salary['salary_type'] == 'Fixed') {
                                            $bgColor = '#d0e1f1';
                                        }
                                       
                                        echo "<tr>";
                                        echo "<td style='background-color: $bgColor;' align='center'>$x.</td>";
                                        echo "<td style='background-color: $bgColor;' align='center'>$idn</td>";
                                        echo "<td style='background-color: $bgColor;'><strong>{$company['lastname']}</strong>, {$company['firstname']}</td>";
                                        echo "<td align='center'>$deptName</td>";
                                        echo "<td align='center'>$shift</td>";
                                        echo "<td align='center'>$company[location]</td>";
                                        echo "<td align='center'>$datearray</td>";
                                        echo "<td align='center'>$login1</td>";
                                        echo "<td align='center'>$logout1</td>";
                                        echo "<td align='center'>$login2</td>";
                                        echo "<td align='center'>$logout2</td>";
                                        echo "<td align='center'>$action</td>";
                                        echo "<td align='center'><a href='?edittime&idno=$idno&id=&company=$comp&startdate=$startdate&enddate=$enddate&logindate' title='Add Time'><i class='fa fa-edit'></i> Add Time</a></td>";
                                        echo "</tr>";
                                        $x++;
                                    }
                                } else {
                                    echo "<tr><td colspan='13' align='center'>No record found for $deptName!</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $active = ""; // Reset active class after the first tab
                }
            }  else {
                // Condition 3: If only the company is selected
                
                ?>
                
                <div role="tabpanel" class="tab-pane active" id="allDepartments">
                    <h4>All Departments</h4>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="3%" rowspan="2" style="vertical-align:middle;">No.</th>
                                <th rowspan="2" style="vertical-align:middle;">Emp ID</th>
                                <th rowspan="2" style="vertical-align:middle;">Employee Name</th>
                                <th rowspan="2" style="vertical-align:middle;">Department</th>
                                <th rowspan="2" style="vertical-align:middle;">Shift</th>
                                <th rowspan="2" style="vertical-align:middle;">Work Area</th>
                                <th rowspan="2" style="vertical-align:middle;">Date</th>
                                <th colspan="2" align="center">Shift 1</th>
                                <th colspan="2" align="center">Shift 2</th>
                                <th rowspan="2" style="vertical-align:middle;">Action</th>
                                <th rowspan="2" style="vertical-align:middle;">Add Time</th>
                            </tr>
                            <tr>
                                <th align="center">Login</th>
                                <th align="center">Lunch out</th>
                                <th align="center">Lunch in</th>
                                <th align="center">Logout</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                        $x = 1;

                        mysqli_query($con, "SET NAMES 'utf8'");

                        // Only the company is selected (no departments)
                        $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                            LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                            WHERE ed.status NOT LIKE '%RESIGNED%' 
                            AND company = '$comp' 
                            ORDER BY ep.lastname ASC");

                        if (mysqli_num_rows($sqlEmployee) > 0) {
                            while ($company = mysqli_fetch_array($sqlEmployee)) {
                                $idn = $company['idno'];
                                $statusLabel = ($company['status'] == "REGULAR") 
                                    ? "<span class='label label-success label-mini'>$company[status]</span>" 
                                    : "<span class='label label-warning label-mini'>$company[status]</span>";

                                $shift = date('h:i A', strtotime($company['startshift'])) . " - " . date('h:i A', strtotime($company['endshift']));
                                $datehired = date('m/d/Y', strtotime($company['dateofhired']));
                                $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$company[department]'");
                                if(mysqli_num_rows($sqlDepartment)>0){
                                $dept=mysqli_fetch_array($sqlDepartment);
                                $deptName=$dept['department'];
                                }else{
                                $deptName="";
                                }

                                // Fetch attendance data
                                $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance 
                                    WHERE logindate BETWEEN '$startdate' AND '$enddate' 
                                    AND idno = '$idn' 
                                    ORDER BY logindate ASC");

                                $login1 = $logout1 = $login2 = $logout2 = $datearray = $action = "";

                                if (mysqli_num_rows($sqlAttendance) > 0) {
                                    while ($attend = mysqli_fetch_array($sqlAttendance)) {
                                        $idno = $company['idno'];
                                        $datearray .= date('m/d/Y', strtotime($attend['logindate'])) . "<br>";
                                        $shiftfrom = $company['startshift'];
                                        $endshift = $company['endshift'];

                                        
                                        $lateThreshold = date('H:i:s', strtotime($shiftfrom) + 59);
                                        $remarks = ($attend['loginam'] > $lateThreshold) ? 'L' : 'P';
                                        $loginTime = $attend['loginam'];
                                        
                                        // Special condition for 12 AM shifts
                                        if ($shiftfrom === '00:00:00') {
                                            // Allow logins from the previous day (e.g., 11 PM) to not be late
                                            $previousDayStart = date('H:i:s', strtotime('-1 day', strtotime('09:00 PM')));
                                            $remarks = ($loginTime >= $previousDayStart || $loginTime <= $lateThreshold) ? 'P' : 'L';
                                        } elseif ($loginTime > $lateThreshold || ($loginTime >= '00:00:00' && $loginTime <= '02:00:00')) {
                                            $remarks = 'L';
                                        } else {
                                            $remarks = 'P';
                                        }
                                
                // If the user is late, assign offense points and remarks automatically
                if ($remarks === 'L') {
                  
                    $color = "style='color:red;'"; // Red color for late
                } else {
                    $color = "";
                }
        
                $colorLogoutAM = "";
                $colorLoginPM = "";
                $colorLogoutPM = "";

           

                
                            // Code for detecting lateness remains unchanged...
                    
                            // Detect Over Break
                            if (isset($attend['logoutam']) && isset($attend['loginpm'])) {
                                $interval = strtotime($attend['loginpm']) - strtotime($attend['logoutam']); // Calculate interval between logoutam and loginpm
                                if ($interval > 3720) { // Overbreak threshold (1 hour)
                                    
                                  
                                    // Set color specifically for overbreak fields (loginpm and logoutam)
                                    $colorLogoutAM = "style='color:Blue;'";
                                    $colorLoginPM = "style='color:Blue;'";
                                }
                            }
                    
                    

                                    // Set gray color if no overbreak condition for logoutam, loginpm, and logoutpm
                                    if ($attend['logoutam'] == "00:00:00") {
                                        $colorLogoutAM = "style='color:transparent;'";
                                    } else if (!$colorLogoutAM) { // Only gray if not already orange
                                        $colorLogoutAM = "style='color:gray;'";
                                    }

                                    if ($attend['loginpm'] == "00:00:00") {
                                        $colorLoginPM = "style='color:transparent;'";
                                    } else if (!$colorLoginPM) { // Only gray if not already orange
                                        $colorLoginPM = "style='color:gray;'";
                                    }

                                    if ($attend['logoutpm'] == "00:00:00") {
                                        $colorLogoutPM = "style='color:transparent;'";
                                    } else {
                                        $colorLogoutPM = "style='color:gray;'";
                                    }
                                    // Check user status and adjust the dates for night shifts



                                    // Build the output strings with the appropriate colors
                                                                    // Build the output strings with the appropriate colors
                                    $login1 .= "<font $color>" . (($attend['loginam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginam']))) . "</font><br>";
                                    $logout1 .= "<font $colorLogoutAM>" . (($attend['logoutam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutam']))) . "</font><br>";
                                    $login2 .= "<font $colorLoginPM>" . (($attend['loginpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginpm']))) . "</font><br>";
                                    $logout2 .= "<font $colorLogoutPM>" . (($attend['logoutpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutpm']))) . "</font><br>";
                                                                                                        
                    
                                                $sqlPoints = mysqli_query($con, "SELECT * FROM points WHERE idno='$idno' AND logindate='{$attend['logindate']}'");
                                                if (mysqli_num_rows($sqlPoints) > 0) {
                                                    $point = mysqli_fetch_array($sqlPoints);
                                                    $points = $point['points'];
                                                    $point_id = $point['id'];
                                                } else {
                                                    $points = 0;
                                                    $point_id = "";
                                                }
                                        
                                                if ($point_id <> '') {
                                                    $removepoint = "| <a href='?attendancemonitoring&idno=$idno&id=$point_id&deleteinfraction&company=$comp&startdate=$startdate&enddate=$enddate&logindate={$attend['logindate']}' title='Delete Time'><i class='fa fa-trash'></i> Remove Infraction</a>";
                                                } else {
                                                    $removepoint = "";
                                                }
                                                
                                        
                                                $action .= "<a href='?attendancemonitoringsummary&edit&company=$comp&startdate=$startdate&enddate=$enddate&idno=$idno&logindate={$attend['logindate']}'>
                                                            <i class='fa fa-edit fa-fw'></i> Infraction</a> | 
                                                            <a href='?edittime&idno=$idno&id={$attend['id']}&company=$comp&startdate=$startdate&enddate=$enddate' title='Edit Time'>
                                                            <i class='fa fa-edit'></i> Time</a> | 
                                                            <a href='?attendancemonitoring&idno=$idno&id={$attend['id']}&deletetime&company=$comp&startdate=$startdate&enddate=$enddate&logindate={$attend['logindate']}' title='Delete Time'>
                                                            <i class='fa fa-trash'></i> Delete Time</a> $removepoint<br>";
                                            }
                                        } else {
                                            $login1 = "-";
                                            $logout1 = "-";
                                            $login2 = "-";
                                            $logout2 = "-";
                                            $datearray = "-";
                                            $action = "";
                                        }

                                        echo "<tr>";
                                        echo "<td>$x.</td>";
                                        echo "<td>$idn</td>";
                                        echo "<td>$company[lastname], $company[firstname]</td>";
                                        echo "<td>$deptName</td>";
                                        echo "<td>$shift</td>";
                                        echo "<td align='center'>$company[location]</td>";
                                        echo "<td align='center'>$datearray</td>";
                                        echo "<td align='center'>$login1</td>";
                                        echo "<td align='center'>$logout1</td>";
                                        echo "<td align='center'>$login2</td>";
                                        echo "<td align='center'>$logout2</td>";
                                        echo "<td align='left'>$action</td>";
                                        echo "<td align='left'><a href='?edittime&idno=$idn&id=&company=$comp&startdate=$startdate&enddate=$enddate&logindate' title='Add Time'><i class='fa fa-edit'></i> Add Time</a></td>";
                                        echo "</tr>";
                                        $x++;
                                    }
                                } else {
                                    echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
                                }
                                ?>

                        </tbody>
                    </table>
                </div>
                <?php
           }
                        if (isset($_GET['deletetime'])) {
                            $idno = $_GET['idno'];
                            $id = $_GET['id'];
                            $company = $_GET['company'];
                            $startdate = $_GET['startdate'];
                            $enddate = $_GET['enddate'];
                            $logindate = $_GET['logindate'];
                            $startMonth = date('n', strtotime($logindate));
                        
                            // Retrieve the remarks from the attendance table
                            $sqlGetRemarks = mysqli_query($con, "SELECT remarks FROM attendance WHERE id = '$id'");
                            if ($sqlGetRemarks && mysqli_num_rows($sqlGetRemarks) > 0) {
                                $row = mysqli_fetch_assoc($sqlGetRemarks);
                                $remarks = $row['remarks']; 
                        
                                // Delete the attendance record
                                $sqlDelete = mysqli_query($con, "DELETE FROM attendance WHERE id = '$id'");
                                if ($sqlDelete) {
                                    // Only update leave credits if the remarks are for a leave type
                                    if (in_array($remarks, ['VL', 'SL', 'SL-NC', 'SL-IO', 'SL-PO', 'SL-GS', 'PTO', 'BLP', 'EO', 'SPL'])) {
                                        // Update the appropriate leave credits based on the remarks (leave type)
                                        switch ($remarks) {
                                            case 'VL':
                                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = vlused - 1 WHERE idno = '$idno'");
                                                break;
                                            case 'SL':
                                            case 'SL-NC':
                                            case 'SL-IO':
                                            case 'SL-PO':
                                            case 'SL-GS':
                                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET slused = slused - 1 WHERE idno = '$idno'");
                                                break;
                                            case 'PTO':
                                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused - 1 WHERE idno = '$idno'");
                                                break;
                                            case 'BLP':
                                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used - 1 WHERE idno = '$idno'");
                                                break;
                                            case 'EO':
                                            case 'P-EO':
                                                // Ensure $startMonth is an integer
                                                $startMonth = (int) $startMonth;
                                            
                                                // Define month column mappings
                                                $monthNames = [
                                                    1 => "jan", 2 => "feb", 3 => "mar", 4 => "apr", 5 => "may", 6 => "jun",
                                                    7 => "jul", 8 => "aug", 9 => "sep", 10 => "oct", 11 => "nov", 12 => "dec"
                                                ];
                                            
                                                // Validate month and operation before executing the query
                                                if (isset($monthNames[$startMonth])) {
                                                    $columnName = $monthNames[$startMonth] . "_eo_used";
                                                    $query = "UPDATE leave_credits SET $columnName = $columnName - 1 WHERE idno = '$idno'";
                                                    
                                                    // Execute query and check for errors
                                                    if (!mysqli_query($con, $query)) {
                                                        die("Error updating leave credits: " . mysqli_error($con));
                                                    }
                                                }
                                                break;
                                            case 'SPL':
                                            $sqlUpdateCredits = mysqli_query($con,  "UPDATE leave_credits SET spl_used = spl_used - 1 WHERE idno = '$idno'");
                                                break;
                                            default:
                                                echo "<script>alert('Leave type not recognized. No credits updated.');</script>";
                                                break;
                                        }
                                    }
                        
                                    // Also delete the associated points log
                                    $deletePoints = mysqli_query($con, "DELETE FROM points WHERE idno='$idno' AND logindate='$logindate'");
                        
                                    echo "<script>alert('Item successfully removed!'); window.history.back();</script>";
                        } else {
                            echo "<script>alert('Unable to delete time!'); window.history.back();</script>";
                        }
                    } else {
                        echo "<script>alert('Error retrieving remarks for the attendance record.'); window.history.back();</script>";
                    }
                }

                if (isset($_GET['deleteinfraction'])) {
                    // Get parameters from URL
                    $idno = $_GET['idno'];
                    $id = $_GET['id'];
                    $company = $_GET['company'];
                    $startdate = $_GET['startdate'];
                    $enddate = $_GET['enddate'];
                    $logindate = $_GET['logindate'];
                
                    // Fetch the current remarks and remarks1 from the database
                    $sqlRemarks = mysqli_query($con, "SELECT remarks, remarks1 FROM attendance WHERE logindate='$logindate' AND idno='$idno'");
                    $existingRemarks = mysqli_fetch_array($sqlRemarks);
                
                    $remarks = $existingRemarks['remarks'];
                    $remarks1 = $existingRemarks['remarks1'];
                
                    // Initialize the new remarks and remarks1
                    $newRemarks = $remarks; // Keep remarks unchanged initially
                    $newRemarks1 = $remarks1; // Keep remarks1 unchanged initially
                
                    // Check if remarks1 is not empty
                    if (!empty($remarks1)) {
                        // Delete remarks1 (set it to empty)
                        $newRemarks1 = '';
                    } else {
                        // If remarks1 is empty, delete remarks and set it to 'P'
                        $newRemarks = 'P';
                
                        // Replace specific codes in remarks (if any)
                        if (strpos($remarks, 'SL-A') !== false) {
                            $newRemarks = str_replace('SL-A', 'SL', $remarks);
                        }
                        if (strpos($remarks, 'SL-B') !== false) {
                            $newRemarks = str_replace('SL-B', 'SL', $remarks);
                        }
                        if (strpos($remarks, 'SL-C') !== false) {
                            $newRemarks = str_replace('SL-C', 'SL', $remarks);
                        }
                        if (strpos($remarks, 'CI-A') !== false) {
                            $newRemarks = str_replace('CI-A', 'CI', $remarks);
                        }
                        if (strpos($remarks, 'CI-B') !== false) {
                            $newRemarks = str_replace('CI-B', 'CI', $remarks);
                        }
                        if (strpos($remarks, 'CI-C') !== false) {
                            $newRemarks = str_replace('CI-C', 'CI', $remarks);
                        }
                    }
                
                    // Update the attendance table with the new remarks and remarks1
                    $sqlUpdate = mysqli_query($con, "UPDATE attendance SET remarks='$newRemarks', remarks1='$newRemarks1' WHERE logindate='$logindate' AND idno='$idno'");
                    if ($sqlUpdate) {
                        echo "<script>alert('Infraction deleted successfully!');</script>";
                    } else {
                        echo "<script>alert('Error deleting infraction!');</script>";
                    }
                
                    // Fetch the last entry for the employee on the given logindate
                    $sqlLastEntry = mysqli_query($con, "SELECT id FROM points WHERE idno='$idno' AND logindate='$logindate' ORDER BY id DESC LIMIT 1");
                    if (mysqli_num_rows($sqlLastEntry) > 0) {
                        $lastEntry = mysqli_fetch_array($sqlLastEntry);
                        $lastEntryId = $lastEntry['id'];
                
                        // Delete the last entry
                        $sqlDelete = mysqli_query($con, "DELETE FROM points WHERE id='$lastEntryId'");
                        if ($sqlDelete) {
                            echo "<script>";
                            echo "alert('Infraction successfully removed!');window.history.back();</script>";
                        } else {
                            echo "<script>";
                            echo "alert('Unable to remove infraction!');window.history.back();</script>";
                        }
                    } else {
                        echo "<script>";
                        echo "alert('No infraction found to delete!');window.history.back();</script>";
                    }
                }
                
           ?>
       </div>
   </div>
</div>

<script>
    function tablesToExcel() {
    const dataType = 'application/vnd.ms-excel';
    let tableHTML = '';

    // Define the filename for the exported file
    const filename = 'Attendance_Report.xls';

    // Select all tables on the page
    const tables = document.querySelectorAll('table');

    // Loop through each table and prepare the HTML content
    tables.forEach((table, index) => {
        // Add a header for each table (optional, if you want to distinguish them)
        tableHTML += `<h3>Table ${index + 1}</h3>`; // Add a title for each table

        // Clone the table to modify it
        const clonedTable = table.cloneNode(true);

        // Add inline styles for borders
        clonedTable.style.borderCollapse = 'collapse'; // Collapse borders
        clonedTable.querySelectorAll('th, td').forEach(cell => {
            cell.style.border = '1px solid black'; // Add border to each cell
            cell.style.padding = '5px'; // Add padding for better spacing
        });

        tableHTML += clonedTable.outerHTML + '<br>'; // Append each table's HTML
    });

    // Create a download link
    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);

    // Create a Blob with the combined table HTML
    const blob = new Blob([tableHTML], {
        type: dataType
    });

    // Create a URL for the Blob
    const url = URL.createObjectURL(blob);
    downloadLink.href = url;
    downloadLink.download = filename; // Set the filename

    // Trigger the download
    downloadLink.click();

    // Clean up
    document.body.removeChild(downloadLink);
}


    $(document).ready(function() {
    // Store active tab on click
    $('.nav-tabs a').on('click', function() {
        localStorage.setItem('activeTab', $(this).attr('href'));
    });

    // Retrieve active tab on page load
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }
});
$(document).ready(function() {
        // Store active main tab on click
        $('.nav-tabs a').on('click', function() {
            localStorage.setItem('activeMainTab', $(this).attr('href'));
        });

        // Retrieve active main tab on page load
        const activeMainTab = localStorage.getItem('activeMainTab');
        if (activeMainTab) {
            $('.nav-tabs a[href="' + activeMainTab + '"]').tab('show');
        }

        // Store active inner tab on click
        $('.nav-pills a').on('click', function() {
            const companyId = $(this).closest('.tab-pane').attr('id'); // Get the company tab ID
            localStorage.setItem('activeInnerTab-' + companyId, $(this).attr('href'));
        });

        // Retrieve active inner tab on page load
        $('.tab-pane').each(function() {
            const companyId = $(this).attr('id');
            const activeInnerTab = localStorage.getItem('activeInnerTab-' + companyId);
            if (activeInnerTab) {
                $('.nav-pills a[href="' + activeInnerTab + '"]').tab('show');
            }
        });

        // Select all buttons with the "confirm-action" class
        const confirmButtons = document.querySelectorAll('.confirm-post');

        // Loop through each button and add a click event listener
        confirmButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                // Display the confirmation dialog
                const confirmAction = confirm("Are you sure you want to POST this leave?");
                
                // If the user clicks "Cancel", prevent the link's default action
                if (!confirmAction) {
                    event.preventDefault();
                }
            });
        });
    });

    function filterTable(input) {
        // Get the input field and table
        const searchValue = input.value.toLowerCase();
        const table = input.closest('.tab-pane').querySelector('table');
        
        // Loop through all table rows and hide those that don't match the search query
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    }
</script>

