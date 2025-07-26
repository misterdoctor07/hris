<?php
    // Fetch unique companies from the employee_details table
    $sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

    if (!$sqlCompanies) {
        echo "Query error: " . mysqli_error($con);
    }
?>
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <div class="flex-container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <!-- Left Section -->
                <div class="flex-item-left" style="display: flex; align-items: center; gap: 10px;">
                    <h4>
                        <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
                        <i class="fa fa-suitcase"></i> LEAVE APPLICATION
                    </h4>
                </div>
                <!-- Date Filter Section -->
                <div class="date-filter" style="display: flex; align-items: center; gap: 10px;">
                    <h5 style="font-weight: bold; margin-left: 30px;">Filter Start Date</h5>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <label for="fromDate" style="margin-bottom: 0;">From:</label>
                        <input type="date" id="fromDate" class="form-control" 
                            value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>" 
                            style="width: 150px; height: 35px;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <label for="toDate" style="margin-bottom: 0;">To:</label>
                        <input type="date" id="toDate" class="form-control" 
                            value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>" 
                            style="width: 150px; height: 35px;">
                    </div>
                    <button id="filterButton"   type="button" onclick="filterByDate()" class="filter-btn">Filter</button>
                    <button type="button" onclick="resetFilter()" class="btn btn-default">Reset</button>
                </div>
                <!-- Export to Excel Button -->
                <div class="export-btn" style="display: flex; align-items: center; margin-left: auto">
                    <form>
                        <button type="button" onclick="tablesToExcel('Leave_Application_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Company Tabs -->
        <ul class="nav nav-tabs">
            <?php
                $active = 'active'; // Set the first tab as active
                while ($company = mysqli_fetch_array($sqlCompanies)) {
                    $companyCode = $company['company'];
                    
                    // Fetch count of pending leave applications for the company
                    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                    $sqlCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM leave_application la
                        INNER JOIN employee_details ed ON la.idno = ed.idno
                        WHERE ed.company = '$companyCode' 
                        AND (la.dayfrom BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                        AND la.appstatus NOT IN ('Pending', 'Cancelled')  
                        AND la.appstatus NOT LIKE '*Approved%'
                        AND la.appstatus NOT LIKE '*Disapproved%'
                        AND la.appstatus NOT LIKE 'Disapproved%'
                        AND la.remarks NOT LIKE '%POSTED%'");
                    $count = mysqli_fetch_assoc($sqlCount)['total'];
                    
                    echo "<li class='$active' style='position: relative;'>
                            <a data-toggle='tab' href='#tab-$companyCode'>$companyCode";
                    if ($count > 0) {
                        echo "<span class='badge badge-right'>$count</span>";
                    }
                    echo "</a></li>";
                    $active = ''; // Remove active class from subsequent tabs
                }
            ?>
        </ul>
        <div class="tab-content">
<?php
            // Reset the result pointer for reuse
            mysqli_data_seek($sqlCompanies, 0);
            $active = 'in active'; // Set the first tab content as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']); // Sanitize output
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode);
                echo "<div id='tab-$sanitizedId' class='tab-pane fade $active'>";
        
                // Fetch unique departments for the current company
                $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.department FROM employee_details ed
                    INNER JOIN department d ON d.id = ed.department
                    WHERE ed.company = '$companyCode' 
                    AND ed.status != 'RESIGNED'
                    ORDER BY d.department");
        
                if (!$sqlDepartments) {
                    echo "Error fetching departments: " . mysqli_error($con);
                    continue;
                }
                echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                $deptActive = 'active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    
                    // Fetch count of pending leave applications for the department
                    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                    $sqlDeptCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM leave_application la
                        INNER JOIN employee_details ed ON la.idno = ed.idno
                        INNER JOIN department d ON d.id = ed.department
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName'
                        AND (
                                ('$fromDate' = '' AND '$toDate' = '')
                                OR (
                                    la.dayto >= '$fromDate' AND la.dayfrom <= '$toDate'
                                )
                            )
                        AND la.appstatus NOT IN ('Pending', 'Cancelled')  
                        AND la.appstatus NOT LIKE '*Approved%'
                        AND la.appstatus NOT LIKE '*Disapproved%'
                        AND la.appstatus NOT LIKE 'Disapproved%'
                        AND la.remarks NOT LIKE '%POSTED%'");
                    $deptCount = mysqli_fetch_assoc($sqlDeptCount)['total'];
        
                    echo "<li class='$deptActive' style='position: relative;'>
                            <a data-toggle='pill' href='#dept-$sanitizedId-$deptId'>$departmentName";
                    if ($deptCount > 0) {
                        echo "<span class='badge badge-right'>$deptCount</span>";
                    }
                    echo "</a></li>";
                    $deptActive = ''; // Remove active class for subsequent department tabs
                }
                echo "</ul>";
        
                echo "<div class='tab-content' style='margin-top: 10px;'>";
                mysqli_data_seek($sqlDepartments, 0); // Reset department pointer
                $deptActive = 'in active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    echo "<div id='dept-$sanitizedId-$deptId' class='tab-pane fade $deptActive'>";
        
                    // Fetch employees for the company and department
                    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                    $sqlEmployee = mysqli_query($con, "SELECT la.*, la.id as laid, ep.*, ed.*, d.department 
                        FROM leave_application la
                        INNER JOIN employee_profile ep ON ep.idno = la.idno 
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department 
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName' 
                        AND (
                                ('$fromDate' = '' AND '$toDate' = '')
                                OR (
                                    la.dayto >= '$fromDate' AND la.dayfrom <= '$toDate'
                                )
                            )
                        ORDER BY 
                            CASE 
                                WHEN la.appstatus LIKE 'Approved%' AND la.remarks NOT LIKE '%POSTED%' THEN 1
                                WHEN la.appstatus = 'Pending' THEN 2
                                WHEN la.appstatus LIKE 'Approved%' AND la.remarks LIKE '%POSTED%' THEN 3
                                WHEN la.appstatus LIKE 'Disapproved%' THEN 4
                                WHEN la.appstatus = 'Cancelled' THEN 5
                                ELSE 6
                            END,
                            la.dayfrom DESC");
        
                    if (!$sqlEmployee) {
                        echo "Error fetching employees: " . mysqli_error($con);
                        continue;
                    }
?>    
            <!-- Search Bar -->
            <div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
                </div>
            </div>
            
            <!-- Bulk Actions Section -->
            <div class="bulk-actions" id="bulkActions-<?= $sanitizedId ?>-<?= $deptId ?>">
                <button class="btn btn-success btn-sm" onclick="bulkAction('post', '<?= $sanitizedId ?>-<?= $deptId ?>')">
                    <i class="fa fa-upload"></i> Post Selected
                </button>
                <button class="btn btn-warning btn-sm" onclick="bulkAction('unpost', '<?= $sanitizedId ?>-<?= $deptId ?>')">
                    <i class="fa fa-undo"></i> Unpost Selected
                </button>
                <button class="btn btn-info btn-sm" onclick="bulkAction('done', '<?= $sanitizedId ?>-<?= $deptId ?>')">
                    <i class="fa fa-check-square-o"></i> Mark as Done
                </button>
                <span class="badge" id="selectedCount-<?= $sanitizedId ?>-<?= $deptId ?>">0 selected</span>
            </div>

            <table class='table table-bordered table-condensed' id="attendanceTable">
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll-<?= $sanitizedId ?>-<?= $deptId ?>" 
                                onclick="toggleSelectAll('<?= $sanitizedId ?>-<?= $deptId ?>')">
                        </th>
                        <th class='sortable' data-column='0' style='text-align: center;'>No.</th>
                        <th class='sortable' data-column='1' style='text-align: center;'>Employee ID</th>
                        <th class='sortable' data-column='2' style='text-align: center;'>Employee Name</th>
                        <th class='sortable' data-column='3' style='text-align: center;'>Leave Type</th>
                        <th class='sortable' data-column='4' style='text-align: center;'>No. of Days</th>
                        <th class='sortable' data-column='5' style='text-align: center;'>Start Date</th>
                        <th class='sortable' data-column='6' style='text-align: center;'>End Date</th>
                        <th class='sortable' data-column='7' style='text-align: center;'>Reason</th>
                        <th class='sortable' data-column='8' style='text-align: center;'>Date and Time Applied</th>
                        <th class='sortable' data-column='9' style='text-align: center;'>Status</th>
                        <th class='sortable' data-column='10' style='text-align: center;'>HR Remarks</th>
                        <th class='sortable' data-column='11' style='text-align: center;'>Approver Remarks</th>
                        <th style='text-align: center;'>Action</th>
                    </tr>
                </thead>
                <tbody>
<?php
                    $x = 1;
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($emp = mysqli_fetch_array($sqlEmployee)) {
                            $status = $emp['appstatus'];
                            $remarks = $emp['remarks'];
                            // Determine the row class based on the applic_status
                            if (strpos($status, '*Approved') !== false || strpos($status, '*Disapproved') !== false) {
                                $rowClass = "info"; // No class for other cases
                            } elseif (strpos($status, 'Disapproved') !== false) {
                                $rowClass = "danger"; // Red
                            } elseif (strpos($status, 'Approved') !== false && strpos($remarks, 'POSTED') !== false) {
                                $rowClass = "success"; // Green
                            } elseif ($status == "Pending") {
                                $rowClass = "warning"; // Yellow
                            } else{
                                $rowClass = "";
                            }
                            
                            $dispStatus = "";
                            if (strpos($status, 'Approved') !== false){
                                $dispStatus = "Approved";
                            } elseif (strpos($status, 'Disapproved') !== false){
                                $dispStatus = "Disapproved";
                            } elseif ($status == 'Pending'){
                                $dispStatus = "Pending";
                            } elseif ($status == 'Cancelled'){
                                $dispStatus = "Cancelled";
                            } else{
                                $dispStatus = "";
                            }

                            $pattern = '/^\*?(.*?)\s*-\s*(.*?)\s*\[(.*?)\]$/';
                            $is_void = false;
                            
                            if (preg_match($pattern, $status, $matches)) {
                                $Status_text = htmlspecialchars($matches[1]); // Approved or Disapproved
                                $approver = htmlspecialchars($matches[2]); // Gallego (Admin Executive)
                                $date = htmlspecialchars($matches[3]); // Jun 7, 2025 - 6:25 AM
                                $is_void = substr($status, 0, 1) === '*';
                            } else {
                                // fallback
                                $Status_text = htmlspecialchars($status);
                                $approver = $date = '';
                            }
?>          
                        <tr class="<?= $rowClass ?>">
                            <td style="text-align: center; vertical-align: middle;">
                                <input type="checkbox" class="rowCheckbox" 
                                    data-tab="<?= $sanitizedId ?>-<?= $deptId ?>" 
                                    value="<?= $emp['laid'] ?>">
                            </td>
                            <td style="text-align: center; vertical-align: middle;"><?= $x++; ?>.</td>
                            <td style="text-align: center; vertical-align: middle;"><?= htmlspecialchars($emp['idno']); ?></td>
                            <td style="text-align: center; vertical-align: middle;">
                                <span style="font-weight: bold; font-size: 1.1em;"><?= htmlspecialchars($emp['lastname']); ?></span>, 
                                <?= htmlspecialchars($emp['firstname']); ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle;"><?= htmlspecialchars($emp['leavetype']); ?></td>
                            <td style="text-align: center; vertical-align: middle;"><?= htmlspecialchars($emp['numberofdays']); ?></td>
                            <td style="text-align: center; vertical-align: middle;"><?= date('M j, Y', strtotime($emp['dayfrom'])); ?></td>
                            <td style="text-align: center; vertical-align: middle;"><?= date('M j, Y', strtotime($emp['dayto'])); ?></td>
                            <td style="text-align: justify; vertical-align: middle;"><?= htmlspecialchars($emp['reason']); ?></td>
                            <td style="text-align: center; vertical-align: middle;">
                                <?= date('M d, Y', strtotime($emp['datearray'])) . "<br>" . 
                                (!empty($emp['timearray']) ? date('g:i A', strtotime($emp['timearray'])) : ""); ?>
                                
                                <?php if (!empty($emp['edited_datetime'])): ?>
                                    <br><strong>Latest Edit:</strong><br><?= date('M d, Y', strtotime($emp['edited_datetime'])) . "<br>" . 
                                                date('g:i A', strtotime($emp['edited_datetime'])); ?>
                                <?php endif; ?>
                            </td>
                            <?php
                                $tooltip = ($approver && $date)
                                    ? "Approved by:\n    $approver\nOn:\n    $date"
                                    : ($is_void ? 'Voided/Overwritten' : '');

                                $tooltipAttr = $tooltip
                                    ? 'data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($tooltip) . '"'
                                    : '';
                            ?>
                            <td style="text-align: center; vertical-align: middle;" <?= $tooltipAttr ?>>
                                <span <?= $is_void ? 'style="color:red; text-decoration: line-through;"' : '' ?>>
                                    <?= $dispStatus; ?>
                                </span>
                            </td>
                            <td style="text-align: <?= ($emp['remarks'] == 'POSTED') ? 'center' : 'justify'; ?>; vertical-align: middle;">
                                <?= htmlspecialchars($emp['remarks']); ?>
                            </td>
                            <td style="text-align: justify; vertical-align: middle;"><?= htmlspecialchars($emp['approver_remarks']); ?></td>
                            <td style="text-align: center; vertical-align: middle;">

                                <a href="?leaveapplication&addremarks&id=<?= $emp['laid']; ?>&remarks=<?= urlencode($emp['remarks']); ?>" 
                                    class="btn btn-primary btn-xs" 
                                    title="Remarks">
                                    <i class='fa fa-comment'></i>
                                </a>
                            </td>
                        </tr>
<?php
                    }
                } else {
                    echo "<tr><td colspan='14' align='center'>No leave applications found.</td></tr>";
                }
?>
                </tbody>
            </table>
<?php
            echo "</div>"; // End of department tab content
            $deptActive = ''; // Remove active class from subsequent department contents
        }
        echo "</div>"; // End of department tabs content
        echo "</div>"; // End of company tab content
        $active = ''; // Remove active class from subsequent company contents
    }
?>
</div>
<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    // Toggle select all checkboxes in a specific tab
    function toggleSelectAll(tabId) {
        const selectAll = document.getElementById(`selectAll-${tabId}`);
        const checkboxes = document.querySelectorAll(`.rowCheckbox[data-tab="${tabId}"]`);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateSelectedCount(tabId);
    }
    
    // Update the selected count badge
    function updateSelectedCount(tabId) {
        const checkboxes = document.querySelectorAll(`.rowCheckbox[data-tab="${tabId}"]:checked`);
        document.getElementById(`selectedCount-${tabId}`).textContent = `${checkboxes.length} selected`;
    }
    
    // Perform bulk action
    
    // Filter table rows based on search input
    function filterTable(input) {
        const filter = input.value.toUpperCase();
        const table = input.closest('.tab-pane').querySelector('table');
        const tr = table.getElementsByTagName("tr");
    
        for (let i = 0; i < tr.length; i++) {
            let display = "none";
            const td = tr[i].getElementsByTagName("td");
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        display = "";
                        break;
                    }
                }
            }
            tr[i].style.display = display;
        }
    }
    
    function bulkAction(action, tabId) {
        // Get all checked checkboxes in the current department tab
        
        const checkboxes = document.querySelectorAll(`#dept-${tabId} .rowCheckbox:checked`);
        const ids = Array.from(checkboxes).map(checkbox => checkbox.value);
        
        if (ids.length === 0) {
            alert('Please select at least one leave application');
            return;
        }
          let actionText = '';
        switch(action) {
            case 'post': actionText = 'post'; break;
            case 'unpost': actionText = 'unpost'; break;
            case 'done': actionText = 'mark as done'; break;
        }
        // Confirm with user
        const confirmation = confirm(`Are you sure you want to ${actionText} ${ids.length} selected leave application(s)?`);
        if (!confirmation) return;
        
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '';
        
        // Add action type
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'bulk_action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        // Add all selected IDs
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'bulk_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        // Add any additional parameters needed
        const fromDateInput = document.createElement('input');
        fromDateInput.type = 'hidden';
        fromDateInput.name = 'fromDate';
        fromDateInput.value = document.getElementById('fromDate').value;
        form.appendChild(fromDateInput);
        
        const toDateInput = document.createElement('input');
        toDateInput.type = 'hidden';
        toDateInput.name = 'toDate';
        toDateInput.value = document.getElementById('toDate').value;
        form.appendChild(toDateInput);
        
        document.body.appendChild(form);
        form.submit();
    }
</script>

<!--- Bulk Action --->
<?php
    if (isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'post' && !empty($_POST['bulk_ids'])) {
        $postedtime = date('F m, Y H:i:s');
        $bulkIds = $_POST['bulk_ids'];
        $success = true;
        
        foreach ($bulkIds as $id) {
            // Sanitize ID
            $id = mysqli_real_escape_string($con, $id);
    
            // Fetch leave application
            $sqlLeave = mysqli_query($con, "SELECT la.*, ed.startshift, ed.idno 
                FROM leave_application la 
                INNER JOIN employee_details ed ON ed.idno = la.idno 
                WHERE la.id = '$id'");
    
            if (!$sqlLeave || mysqli_num_rows($sqlLeave) == 0) {
                $success = false;
                continue;
            }
    
            $leave = mysqli_fetch_assoc($sqlLeave);
            $idno = $leave['idno'];
            $leaveType = $leave['leavetype'];
            $numberOfDays = $leave['numberofdays'];
            $startdate = $leave['dayfrom'];
            $enddate = $leave['dayto'];
            $startshift = $leave['startshift'];
            $currentRemarks = $leave['remarks'];
    
            // Determine shift type
            $isNightShift = in_array($startshift, ['23:00:00', '00:00:00', '01:00:00']);
    
            // Generate date range
            $start = new DateTime($startdate);
            $end = new DateTime($enddate);
            $end->modify('+1 day');
            $interval = new DateInterval('P1D');
            $dateRange = new DatePeriod($start, $interval, $end);
    
            $dateArray = [];
            $daysAdded = 0;
    
            foreach ($dateRange as $date) {
                if ($daysAdded >= $numberOfDays) break;
    
                $dayOfWeek = $date->format('N'); // 1=Mon, 7=Sun
                if ($dayOfWeek == 7) continue; // skip Sunday
                if (!$isNightShift && $dayOfWeek == 1) continue; // skip Mon for day shift
                if ($isNightShift && $dayOfWeek == 6) continue; // skip Sat for night shift
    
                $dateArray[] = $date->format('Y-m-d');
                $daysAdded++;
            }
     // Check if postedtime column exists
                $columnCheck = mysqli_query($con, "SHOW COLUMNS FROM leave_application LIKE 'postedtime'");
                $hasPostedTime = (mysqli_num_rows($columnCheck) > 0);
            // Update remarks and post time
            $newRemarks = "POSTED" . (!empty($currentRemarks) ? " - Note: $currentRemarks" : "");
            $update = mysqli_query($con, "UPDATE leave_application 
                SET remarks = '$newRemarks', postedtime = '$postedtime' 
                WHERE id='$id'");
    
            if (!$update) {
                $success = false;
                continue;
            }
    
            // Update attendance
            foreach ($dateArray as $leaveDate) {
                $check = mysqli_query($con, "SELECT id FROM attendance WHERE idno='$idno' AND logindate='$leaveDate'");
                if (mysqli_num_rows($check) == 0) {
                    $insert = mysqli_query($con, "INSERT INTO attendance (idno, logindate, loginam, logoutam, loginpm, logoutpm, remarks, status)
                        VALUES ('$idno', '$leaveDate', '0', '0', '0', '0', '$leaveType', 'leave')");
                    if (!$insert) $success = false;
                } else {
                    $updateAttendance = mysqli_query($con, "UPDATE attendance 
                        SET remarks='$leaveType', status='leave' 
                        WHERE idno='$idno' AND logindate='$leaveDate'");
                    if (!$updateAttendance) $success = false;
                }
            }
        }
    
        if ($success) {
            echo "<script>alert('Selected leave applications successfully posted!'); window.location='?leaveapplication';</script>";
        } else {
            echo "<script>alert('Some leave applications could not be posted. Please check logs or database.'); window.location='?leaveapplication';</script>";
        }
    }
    if (isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'done' && !empty($_POST['bulk_ids'])) {
        $bulkIds = $_POST['bulk_ids'];
        $success = true;
    
        foreach ($bulkIds as $id) {
            $id = mysqli_real_escape_string($con, $id);
            $sqlDone = "UPDATE leave_application 
                SET appstatus = CONCAT('*', appstatus) 
                WHERE id = '$id' AND appstatus NOT LIKE '*%'";
            if (!mysqli_query($con, $sqlDone)) {
                $success = false;
            }
        }
    
        if ($success) {
            echo "<script>alert('Selected applications marked as Done.'); window.location='?leaveapplication';</script>";
        } else {
            echo "<script>alert('Some applications could not be marked as Done.'); window.location='?leaveapplication';</script>";
        }
    }
    if (isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'unpost' && !empty($_POST['bulk_ids'])) {
        $bulkIds = $_POST['bulk_ids'];
        $success = true;
    
        foreach ($bulkIds as $id) {
            $id = mysqli_real_escape_string($con, $id);
    
            $sqlRetrieve = mysqli_query($con, "SELECT * FROM leave_application WHERE id='$id'");
            if (!$sqlRetrieve || mysqli_num_rows($sqlRetrieve) == 0) {
                $success = false;
                continue;
            }
    
            $leaveData = mysqli_fetch_array($sqlRetrieve);
            $idno = $leaveData['idno'];
            $leaveType = $leaveData['leavetype'];
            $numberOfDays = $leaveData['numberofdays'];
            $startdate = $leaveData['dayfrom'];
            $enddate = $leaveData['dayto'];
    
            $start = new DateTime($startdate);
            $end = new DateTime($enddate);
            $end->modify('+1 day');
            $interval = new DateInterval('P1D');
            $dateRange = new DatePeriod($start, $interval, $end);
    
            $dateArray = [];
            $daysAdded = 0;
    
            $sqlShift = mysqli_query($con, "SELECT startshift FROM employee_details ed 
                INNER JOIN leave_application la ON ed.idno = la.idno WHERE la.id = '$id'");
            $startshift = ($sqlShift && mysqli_num_rows($sqlShift) > 0) ? mysqli_fetch_assoc($sqlShift)['startshift'] : null;
    
            $isNightShift = ($startshift == '23:00:00' || $startshift == '00:00:00');
            $isNotNightShift = !$isNightShift;
    
            foreach ($dateRange as $date) {
                if ($daysAdded >= $numberOfDays) break;
    
                $dayOfWeek = $date->format('N');
                if ($dayOfWeek == 7) continue;
                if ($isNotNightShift && $dayOfWeek == 1) continue;
                if ($isNightShift && $dayOfWeek == 6) continue;
    
                $dateArray[] = $date->format('Y-m-d');
                $daysAdded++;
            }
    
            // Clean remarks
            $sqlGetRemarks = mysqli_query($con, "SELECT remarks FROM leave_application WHERE id='$id'");
            if ($sqlGetRemarks && mysqli_num_rows($sqlGetRemarks) > 0) {
                $remarks = mysqli_fetch_assoc($sqlGetRemarks)['remarks'];
                $cleanedRemarks = preg_replace('/POSTED\s*-?\s*Note:\s*/', '', $remarks);
                $cleanedRemarks = str_replace('POSTED', '', $cleanedRemarks);
                $cleanedRemarks = trim($cleanedRemarks, " -");
                $sqlUpdateRemarks = mysqli_query($con, "UPDATE leave_application SET remarks='$cleanedRemarks' WHERE id='$id'");
            }
    
            // Delete attendance entries
            foreach ($dateArray as $leaveDate) {
                $sqlDelete = mysqli_query($con, "DELETE FROM attendance WHERE idno='$idno' AND logindate='$leaveDate' AND remarks='$leaveType'");
                if (!$sqlDelete) {
                    $success = false;
                }
            }
        }
    
        if ($success) {
            echo "<script>alert('Selected leave applications successfully unposted!'); window.location='?leaveapplication';</script>";
        } else {
            echo "<script>alert('Some applications could not be unposted completely.'); window.location='?leaveapplication';</script>";
        }
    }

    // Check if the user clicked 'Add Remarks'
    if (isset($_GET['addremarks'])) {
        $id = $_GET['id'];
        $remarks = urldecode($_GET['remarks']); // Use urldecode to handle special characters
?>
    <!-- Remarks Form -->
    <div class="modal-overlay">
        <div class="modal-container">
            <div class="content-panel">
                <div class="panel-heading-">
                    <h4>
                        <a href="?leaveapplication"><i class="fa fa-arrow-left"></i> Close</a> |
                        <i class="fa fa-file-text"></i> REMARKS
                    </h4>
                </div>
                <div class="panel-body">
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $id; ?>">
                        <div class="form-group">
                            <textarea name="remarks" class="form-control" rows="5" placeholder="Add Remarks"><?= htmlspecialchars($remarks); ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submitRemarks" class="btn btn-primary" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    }
    
    // Handle form submission for updating remarks
    if (isset($_POST['submitRemarks'])) {
        $id = $_POST['id'];
        $remarks = mysqli_real_escape_string($con, $_POST['remarks']); // Sanitize input
    
        // Update remarks in the database
        $sqlUpdateRemarks = "UPDATE leave_application SET remarks = '$remarks', remarks_view_status='Unseen' WHERE id = '$id'";
        if (mysqli_query($con, $sqlUpdateRemarks)) {
            echo "<script>alert('Remarks updated successfully.');</script>";
            echo "<script>window.location.href='?leaveapplication';</script>"; // Redirect after update
        } else {
            echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
        }
    }
?>
<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    // Select all buttons with the "confirm-action" class
    const confirmButtons = document.querySelectorAll('.confirm-done');
    
    // Loop through each button and add a click event listener
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            // Display the confirmation dialog
            const confirmAction = confirm("Are you sure this leave application is DONE?");
            
            // If the user clicks "Cancel", prevent the link's default action
            if (!confirmAction) {
                event.preventDefault();
            }
        });
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
            const confirmUnpostButtons = document.querySelectorAll('.confirm-unpost');
    
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
            
            confirmUnpostButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    // Display the confirmation dialog
                    const confirmUnpostAction = confirm("Are you sure you want to UNPOST this posted leave?");
                    
                    // If the user clicks "Cancel", prevent the link's default action
                    if (!confirmUnpostAction) {
                        event.preventDefault();
                    }
                });
            });
    });
    
    function storeSearchAndRedirect(anchor) {
        const searchInput = document.querySelector('.form-control');
        const searchValue = searchInput ? searchInput.value : '';
    
        // Inject search value into the URL
        const url = new URL(anchor.href, window.location.origin);
        url.searchParams.set('search', searchValue);
    
        // Save to sessionStorage for redundancy
        sessionStorage.setItem('searchValue', searchValue);
    
        // Redirect with updated URL
        window.location.href = url.toString();
    
        // Prevent default link behavior
        return false;
    }
    
    function filterTable(input) {
        const searchValue = input.value.toLowerCase();
    
        // Find tab IDs
        const deptPane = input.closest('.tab-pane'); // Department tab pane
        const deptId = deptPane.id;
    
        const companyPane = deptPane.closest('.tab-pane'); // Company tab pane
        const companyId = companyPane.id;
    
        // Save all in sessionStorage
        sessionStorage.setItem('searchValue', searchValue);
        sessionStorage.setItem('companyTabId', companyId);
        sessionStorage.setItem('deptTabId', deptId);
    
        // Perform filtering
        const table = deptPane.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
    
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    }
    
    window.onload = function () {
        const searchValue = sessionStorage.getItem('searchValue');
        const companyTabId = sessionStorage.getItem('companyTabId');
        const deptTabId = sessionStorage.getItem('deptTabId');
    
        if (searchValue && companyTabId && deptTabId) {
            // Activate correct company tab
            const companyTabLink = document.querySelector(`a[href="#${companyTabId}"]`);
            if (companyTabLink) companyTabLink.click();
    
            // Delay to ensure company tab is active
            setTimeout(() => {
                // Activate department tab
                const deptTabLink = document.querySelector(`a[href="#${deptTabId}"]`);
                if (deptTabLink) deptTabLink.click();
    
                // Delay again to ensure department content is loaded
                setTimeout(() => {
                    const deptPane = document.getElementById(deptTabId);
                    const searchInput = deptPane.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.value = searchValue;
                        filterTable(searchInput);
                    }
                }, 200);
            }, 200);
        }
    };
    
    //Filter Button for Date Filter
    function filterByDate() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
    
        if (fromDate && toDate) {
            // Save dates in sessionStorage
            sessionStorage.setItem('fromDate', fromDate);
            sessionStorage.setItem('toDate', toDate);
    
            // Redirect with query params
            window.location.href = `?leaveapplication&fromDate=${fromDate}&toDate=${toDate}`;
        } else {
            alert('Please select both "From" and "To" dates.');
        }
    }
    
    window.onload = function () {
        const searchValue = sessionStorage.getItem('searchValue');
        const companyTabId = sessionStorage.getItem('companyTabId');
        const deptTabId = sessionStorage.getItem('deptTabId');
        const fromDate = sessionStorage.getItem('fromDate');
        const toDate = sessionStorage.getItem('toDate');
    
        // Restore date filters
        if (fromDate && toDate) {
            document.getElementById('fromDate').value = fromDate;
            document.getElementById('toDate').value = toDate;
        }
    
        // Restore tab and search filters
        if (searchValue && companyTabId && deptTabId) {
            const companyTabLink = document.querySelector(`a[href="#${companyTabId}"]`);
            if (companyTabLink) companyTabLink.click();
    
            setTimeout(() => {
                const deptTabLink = document.querySelector(`a[href="#${deptTabId}"]`);
                if (deptTabLink) deptTabLink.click();
    
                setTimeout(() => {
                    const deptPane = document.getElementById(deptTabId);
                    const searchInput = deptPane.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.value = searchValue;
                        filterTable(searchInput);
                    }
                }, 200);
            }, 200);
        }
    };
    
    //Reset button for Date Filter
    function resetFilter() {
        window.location.href = '?leaveapplication';
        sessionStorage.removeItem('fromDate');
        sessionStorage.removeItem('toDate');
    }
    //Sorting Columns
    document.addEventListener("DOMContentLoaded", function () {
        const headers = document.querySelectorAll(".sortable");
        
        headers.forEach(header => {
            header.addEventListener("click", function () {
                const table = header.closest("table");
                const tbody = table.querySelector("tbody");
                const columnIndex = parseInt(header.getAttribute("data-column"));
                const isAscending = header.classList.contains("asc");
    
                // Clear existing sorting classes
                headers.forEach(h => h.classList.remove("asc", "desc"));
    
                // Toggle sorting order
                header.classList.toggle("asc", !isAscending);
                header.classList.toggle("desc", isAscending);
    
                const rows = Array.from(tbody.querySelectorAll("tr"));
                rows.sort((a, b) => {
                    const aText = a.cells[columnIndex].innerText.trim();
                    const bText = b.cells[columnIndex].innerText.trim();
    
                    return isAscending
                        ? compareValues(bText, aText) // Sort descending if currently ascending
                        : compareValues(aText, bText); // Sort ascending if currently descending
                });
    
                // Append sorted rows back to the table body
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    
        function compareValues(a, b) {
            const dateA = parseDateTime(a);
            const dateB = parseDateTime(b);
    
            // Check if both values are valid dates, if so, compare as dates
            if (dateA && dateB) return dateA - dateB;
    
            // Otherwise, compare as case-insensitive strings (for names, text, etc.)
            return a.localeCompare(b, undefined, { sensitivity: 'base' });
        }
    
        function parseDateTime(dateStr) {
            const monthMap = {
                "Jan": 1, "Feb": 2, "Mar": 3, "Apr": 4, "May": 5, "Jun": 6,
                "Jul": 7, "Aug": 8, "Sep": 9, "Oct": 10, "Nov": 11, "Dec": 12
            };
    
            // Regex patterns to detect date and date-time formats
            const dateRegex = /^([A-Za-z]+)\s(\d{1,2}),\s(\d{4})$/;  // Format: Jan 02, 2025
            const dateTimeRegex = /^([A-Za-z]+)\s(\d{1,2}),\s(\d{4})\s(\d{1,2}):(\d{2})\s(AM|PM)$/;  // Format: Jan 02, 2025 6:42 AM
    
            const matchDateTime = dateStr.match(dateTimeRegex);
            if (matchDateTime) {
                const [, month, day, year, hours, minutes, meridian] = matchDateTime;
                let hour24 = convertTo24Hour(parseInt(hours), meridian);
                return new Date(parseInt(year), monthMap[month.substring(0, 3)] - 1, parseInt(day), hour24, parseInt(minutes));
            }
    
            const matchDate = dateStr.match(dateRegex);
            if (matchDate) {
                const [, month, day, year] = matchDate;
                return new Date(parseInt(year), monthMap[month.substring(0, 3)] - 1, parseInt(day), 0, 0);
            }
    
            return null; // If not a date, return null (so it will be sorted alphabetically)
        }
    
        function convertTo24Hour(hours, meridian) {
            if (meridian === "PM" && hours !== 12) return hours + 12; // Convert PM hours
            if (meridian === "AM" && hours === 12) return 0; // Midnight case
            return hours; // Otherwise, return as is
        }
    });
    
    // JavaScript function to update displayed leave credits
    function updateCredits(leaveType) {
        const credits = {
            VL: <?= isset($credits['VL']) ? $credits['VL'] : 0; ?>,
            PTO: <?= isset($credits['PTO']) ? $credits['PTO'] : 0; ?>,
            BLP: <?= isset($credits['BLP']) ? $credits['BLP'] : 0; ?>,
            EO: <?= isset($credits['EO']) ? $credits['EO'] : 0; ?>,
            SPL: <?=isset($credits['SPL']) ? $credits['SPL'] :0; ?>
        };
    
        let creditInfo = document.getElementById('credit-info');
        let nofdays = document.getElementById('nofdays');
        let startDate = document.getElementsByName('startDate')[0];
        let endDate = document.getElementsByName('endDate')[0];
        let reasonField = document.getElementsByName('reasons')[0];
    
        // Define leave types that should not be disabled even with 0 credits
        const excludedLeaveTypes = ['MTL', 'PTL', 'BL', 'MDL', 'EEO', 'LTL'];
    
        // Check if the selected leave type is in the excluded list
        if (excludedLeaveTypes.includes(leaveType)) {
            creditInfo.textContent = ''; 
            creditInfo.style.color = ''; 
            
            nofdays.disabled = false; 
            startDate.disabled = false;
            endDate.disabled = false; 
            reasonField.disabled = false; 
    
            // Reset the attributes and styles
            nofdays.max = ''; 
            nofdays.value = 1; 
            nofdays.style.backgroundColor = '';
            startDate.style.backgroundColor = '';
            endDate.style.backgroundColor = '';
            reasonField.style.backgroundColor = '';
        } else if (credits[leaveType] !== undefined && credits[leaveType] > 0) {
            creditInfo.textContent = `Remaining Credits: ${credits[leaveType]}`; 
            creditInfo.style.color = '';
            nofdays.disabled = false; 
            startDate.disabled = false; 
            endDate.disabled = false; 
            reasonField.disabled = false; 
    
            // Set max attribute of "No. of Days" to remaining credits
            nofdays.max = credits[leaveType];
            nofdays.value = 1;  
            nofdays.style.backgroundColor = ''; 
            startDate.style.backgroundColor = '';
            endDate.style.backgroundColor = '';
            reasonField.style.backgroundColor = '';
        } else {
            // No remaining credits for selected leave type, disable all fields
            creditInfo.textContent = 'No available credits for this leave type.';
            creditInfo.style.color = 'red';
            nofdays.disabled = true; 
            startDate.disabled = true; 
            endDate.disabled = true; 
            reasonField.disabled = true; 
            nofdays.style.backgroundColor = '#f0f0f0';
            startDate.style.backgroundColor = '#f0f0f0';
            endDate.style.backgroundColor = '#f0f0f0';
            reasonField.style.backgroundColor = '#f0f0f0';
            nofdays.max = 0;
            nofdays.value = 0; 
        }
        checkSubmitButton();
    }
    function tablesToExcel() {
        const dataType = 'application/vnd.ms-excel';
        let tableHTML = '';
        // Define filenames based on the outer tab index
        const filenames = ['NESI1_Leave_Application_Report.xls', 'NESI2_Leave_Application_Report.xls', 'NEWIND_Leave_Application_Report.xls'];
        // Get all outer tabs
        const outerTabs = document.querySelectorAll('.nav-tabs li a');
        let activeTabIndex = -1;
        // Find the index of the active outer tab
        outerTabs.forEach((tab, index) => {
            if (tab.parentElement.classList.contains('active')) {
                activeTabIndex = index; // Set the index of the active tab
            }
        });
        // Set the filename based on the active tab index
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Leave_Application_Report.xls';
        // Get the currently active outer tab
        const activeOuterTab = outerTabs[activeTabIndex];
        if (activeOuterTab) {
            const outerTabHref = activeOuterTab.getAttribute('href'); // Get the href of the active outer tab
            const activeOuterTabPane = document.querySelector(outerTabHref); // Get the corresponding tab pane
            // Gather all inner tabs and their corresponding tables from the active outer tab pane
            const innerTabs = activeOuterTabPane.querySelectorAll('.nav-pills li a');
            innerTabs.forEach(innerTab => {
                // Get the inner tab name and remove any trailing numbers
                let innerTabName = innerTab.textContent.trim();
                innerTabName = innerTabName.replace(/\s+\d+$/, ''); // Remove trailing space and number
                const innerTabContent = document.querySelector(innerTab.getAttribute('href')); // Get the corresponding inner tab content
                // Check if the inner tab content has a table
                const table = innerTabContent.querySelector('table');
                if (table) {
                    // Add inner tab name as a header before the table
                    tableHTML += `<h3>${innerTabName}</h3>`; // Add header for the table
                    // Clone the table to modify it
                    const clonedTable = table.cloneNode(true);
                    
                    // Add inline styles for borders
                    clonedTable.style.borderCollapse = 'collapse'; // Collapse borders
                    clonedTable.querySelectorAll('th, td').forEach(cell => {
                        cell.style.border = '1px solid black'; // Add border to each cell
                        cell.style.padding = '5px'; // Optional: Add padding for better spacing
                    });
                    tableHTML += clonedTable.outerHTML + '<br>'; // Append each table's HTML
                }
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
            downloadLink.download = filename; // Set the correct filename
            // Trigger the download
            downloadLink.click();
            // Clean up
            document.body.removeChild(downloadLink);
        }
    }
    // Info button
    document.addEventListener("DOMContentLoaded", function () {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function (popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl);
        });
    });
    document.addEventListener("click", function (e) {
        document.querySelectorAll('.info-btn').forEach(btn => {
            if (!btn.contains(e.target) && btn.getAttribute('aria-describedby')) {
                bootstrap.Popover.getInstance(btn).hide();
            }
        });
    });
</script>
<style>
    /* Modal Overlay to Blur Background */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }
    
    /* Modal Container */
    .modal-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        width: 400px;
        max-width: 90%;
        z-index: 1000;
    }
    
    /* Panel Heading Styling */
    .panel-heading- {
        text-align: left;
        margin-bottom: 20px;
    }
    
    /* Close Button */
    .panel-heading- a {
        color: #333;
        text-decoration: none;
    }
    
    /* Form Input and Button Styling */
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .form-group input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
    }
    
    /* Change button color on hover */
    .form-group input[type="submit"]:hover {
        background-color: #0056b3;
    }
    
    .modal-dialog {
        width: auto; /* adjust the width to fit your content */
        max-width: 500px; /* set a maximum width */
    }
    
    .modal-content {
        width: 100%;
        padding:0;
        overflow-y: auto; /* add a scrollbar if the content is too long */
    }
    
    .modal-body form {
        width: 300%; /* adjust the width to fit your content */
        margin: 0 auto; /* center the form horizontally */
    }
    
    /* Panel Heading Styling */
    .panel-heading- {
        text-align: center;
        margin-bottom: 20px;
    }
    
    /* Close Button */
    .panel-heading- a {
        color: #333;
        text-decoration: none;
    }
    /*Form Group for remarks modal*/
    /* Form Input and Button Styling */
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .form-group input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        background-color: #3f4d6a;
        color: white;
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
    }
    
    /* Change button color on hover */
    .form-group input[type="submit"]:hover {
        background-color: #181e2e;
    }
    
    .badge-right {
        position: absolute;
        top: 0;
        right: 0;
        transform: translate(50%, -50%);
        color: white;
        background-color: red;
        border-radius: 50%;
        padding: 4px 8px;
        font-size: 12px;
    }
    /* Sorting Columns */
    th.sortable {
        cursor: pointer;
        position: relative;
    }
    
    th.sortable.asc::after {
        content: ''; 
        color: #000;
    }
    
    th.sortable.desc::after {
        content: '';
        color: #000;
    }
    /*Date Filter Button*/
    .filter-btn {
        background-color: #3f4d6a;
        color: white;
        border: none;
        padding: 7px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    
    .filter-btn:hover {
        background-color: #181e2e;
    }
    .bulk-actions {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    
    .bulk-actions button {
        white-space: nowrap;
        padding: 5px 10px;
        font-size: 12px;
    }
    
    .rowCheckbox {
        cursor: pointer;
    }
    
    #selectAll {
        cursor: pointer;
    }
</style>