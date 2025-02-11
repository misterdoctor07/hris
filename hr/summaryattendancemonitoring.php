<?php
$comp = isset($_GET['company']) ? $_GET['company'] : '';
$startdate = $_GET['startdate'];
$enddate = $_GET['enddate'];

$dept = isset($_GET['departments']) ? $_GET['departments'] : []; 
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?monitorattendance"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-user"></i> EMPLOYEE LIST 
                <button onclick="tableToExcel('printThis','Detailed_Report')" class="btn btn-success" style="float:right;">
                    <i class="fa fa-download"> </i> EXPORT
                </button>
            </h4>
        </div>

        <!-- Tabs for Departments -->
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $active = "active";
            if (!empty($dept)) {
                foreach ($dept as $dpt) {
                    $deptName = htmlspecialchars($dpt); // Sanitize output
                    echo "<li role='presentation' class='$active'><a href='#$deptName' aria-controls='$deptName' role='tab' data-toggle='tab'>$deptName</a></li>";
                    $active = ""; // Reset active after first tab
                }
            } else {
                echo "<li role='presentation' class='active'><a href='#allDepartments' aria-controls='allDepartments' role='tab' data-toggle='tab'>All Employees</a></li>";
            }
            ?>
        </ul>

        <div class="panel-body tab-content" id="printThis">
            <b>Company: <?=!empty($comp) ? $comp : 'All';?><br />
            Date Range: <?=date('m/d/Y',strtotime($startdate));?> - <?=date('m/d/Y',strtotime($enddate));?></b>
            <ul class="nav nav-tabs">
            <?php
            
            $active = "active";

            if (!empty($dept)) {
                foreach ($dept as $dpt) {
                    $deptName = htmlspecialchars($dpt); // Sanitize output
                    ?>
                    </ul>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="<?=$deptName?>">
                        <h4>Department: <?=$deptName;?></h4>
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

                                // Condition 1: If both company and department are selected
                                if (!empty($comp) && !empty($dept)) {
                                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                                        LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                        WHERE ed.status NOT LIKE '%RESIGNED%' 
                                        AND company = '$comp' AND department = '$deptName'
                                        ORDER BY ep.lastname ASC");
                                }
                                // Condition 2: If only the department is selected
                                elseif (!empty($dept) && empty($comp)) {
                                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                                        LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                        WHERE ed.status NOT LIKE '%RESIGNED%' 
                                        AND department = '$deptName' 
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
                                                    $login1 .= "<font $color>" . (($attend['loginam'] === "00:00:00" || $attend['loginam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginam']))) . "</font><br>";
                                                    $logout1 .= "<font $colorLogoutAM>" . (($attend['logoutam'] === "00:00:00" || $attend['logoutam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutam']))) . "</font><br>";
                                                    $login2 .= "<font $colorLoginPM>" . (($attend['loginpm'] === "00:00:00" || $attend['loginpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginpm']))) . "</font><br>";
                                                    $logout2 .= "<font $colorLogoutPM>" . (($attend['logoutpm'] === "00:00:00" || $attend['logoutpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutpm']))) . "</font><br>";
                                                                                                    
                                                    
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
                                        echo "<td align='left'><a href='?edittime&idno=$idno&id=&company=$comp&startdate=$startdate&enddate=$enddate&logindate' title='Add Time'><i class='fa fa-edit'></i> Add Time</a></td>";
                                        echo "</tr>";
                                        $x++;
                                    }
                                } else {
                                    echo "<tr><td colspan='12' align='center'>No record found for $deptName!</td></tr>";
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
                                    $login1 .= "<font $color>" . (($attend['loginam'] === "00:00:00" || $attend['loginam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginam']))) . "</font><br>";
                                    $logout1 .= "<font $colorLogoutAM>" . (($attend['logoutam'] === "00:00:00" || $attend['logoutam'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutam']))) . "</font><br>";
                                    $login2 .= "<font $colorLoginPM>" . (($attend['loginpm'] === "00:00:00" || $attend['loginpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['loginpm']))) . "</font><br>";
                                    $logout2 .= "<font $colorLogoutPM>" . (($attend['logoutpm'] === "00:00:00" || $attend['logoutpm'] === "0") ? "00:00:00" : date('h:i:s A', strtotime($attend['logoutpm']))) . "</font><br>";
                                                                                                        
                    
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
                                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET eo_used = eo_used - 1 WHERE idno = '$idno'");
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

                if(isset($_GET['deleteinfraction'])){
                    // Get parameters from URL
                    $idno = $_GET['idno'];
                    $id = $_GET['id'];
                    $company = $_GET['company'];
                    $startdate = $_GET['startdate'];
                    $enddate = $_GET['enddate'];
                    $logindate = $_GET['logindate'];
                
                    // Fetch the current remarks from the database
                    $sqlRemarks = mysqli_query($con, "SELECT remarks FROM attendance WHERE logindate='$logindate' AND idno='$idno'");
                    $existingRemarks = mysqli_fetch_array($sqlRemarks);
                
                    // Initialize the new remarks
                    $newRemarks = 'P'; // Default value if remarks is not SL-A
                
                    // Check if the current remarks contains 'SL-A'
                    if (strpos($existingRemarks['remarks'], 'SL-A') !== false) {
                        // If it contains 'SL-A', set the new remarks to 'SL'
                        $newRemarks = str_replace('SL-A', 'SL', $existingRemarks['remarks']);
                    }
                    if (strpos($existingRemarks['remarks'], 'SL-B') !== false) {
                        // If it contains 'SL-A', set the new remarks to 'SL'
                        $newRemarks = str_replace('SL-B', 'SL', $existingRemarks['remarks']);
                    }
                    if (strpos($existingRemarks['remarks'], 'SL-C') !== false) {
                        // If it contains 'SL-A', set the new remarks to 'SL'
                        $newRemarks = str_replace('SL-C', 'SL', $existingRemarks['remarks']);
                    }
                    if (strpos($existingRemarks['remarks'], 'CI-A') !== false) {
                        // If it contains 'SL-A', set the new remarks to 'SL'
                        $newRemarks = str_replace('CI-A', 'CI', $existingRemarks['remarks']);
                    }
                
                    // Update the remarks in the attendance table
                    $sqlUpdate = mysqli_query($con, "UPDATE attendance SET remarks='$newRemarks' WHERE logindate='$logindate' AND idno='$idno'");
                    if ($sqlUpdate) {
                        echo "Remarks updated successfully.<br>";
                    } else {
                        echo "Error updating remarks: " . mysqli_error($con) . "<br>";
                    }
                
                    // Proceed to delete the infraction after checking remarks
                    $sqlDelete = mysqli_query($con, "DELETE FROM points WHERE id='$id'");
                
                    if ($sqlDelete) {
                        echo "<script>";
                        echo "alert('Infraction successfully removed!');window.history.back();</script>";
                    } else {
                        echo "<script>";
                        echo "alert('Unable to remove infraction!');window.history.back();</script>";
                    }
                }
                
           ?>
       </div>
   </div>
</div>

<script>
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

