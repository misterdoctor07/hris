
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
    content: '↑'; /* Ascending arrow icon */
    color: #000;
}

th.sortable.desc::after {
    content: '↓'; /* Descending arrow icon */
    color: #000;
}
</style>
<script>
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
</script>
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
                    <label for="fromDate">From:</label>
                    <input type="date" id="fromDate" class="form-control" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>" style="width: 150px;">
                    <label for="toDate">To:</label>
                    <input type="date" id="toDate" class="form-control" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>" style="width: 150px;">
                    <button type="button" onclick="filterByDate()" class="btn btn-primary">Filter</button>
                    <button type="button" onclick="resetFilter()" class="btn btn-default">Reset</button>
                </div>

                <!-- Export to Excel Button -->
                <div class="export-btn" style="display: flex; align-items: center; margin-left: auto">
                    <form>
                        <button type="button" onclick="tablesToExcel('Infraction_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
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
                        AND la.remarks NOT LIKE 'POSTED%'");
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
                // Reset the result pointer to reuse it
                mysqli_data_seek($sqlCompanies, 0);
                $active = 'in active'; // Set the first tab content as active
                while ($company = mysqli_fetch_array($sqlCompanies)) {
                    $companyCode = $company['company'];
                    echo "<div id='tab-$companyCode' class='tab-pane fade $active'>";

                    // Fetch unique departments for the company
                    $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.department FROM employee_details ed
                        INNER JOIN department d ON d.id = ed.department
                        WHERE ed.company = '$companyCode' 
                        AND ed.status != 'RESIGNED'
                        ORDER BY d.department");

                    echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                    $deptActive = 'active';
                    
                    while ($department = mysqli_fetch_array($sqlDepartments)) {
                        $departmentName = $department['department'];
                        
                        // Fetch count of pending leave applications for the department
                        $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                        $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                        $sqlDeptCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM leave_application la
                            INNER JOIN employee_details ed ON la.idno = ed.idno
                            INNER JOIN department d ON d.id = ed.department
                            WHERE ed.company = '$companyCode' 
                            AND d.department = '$departmentName'
                            AND (la.dayfrom BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                            AND la.appstatus NOT IN ('Pending', 'Cancelled') 
                            AND la.appstatus NOT LIKE '*Approved%'
                            AND la.appstatus NOT LIKE '*Disapproved%'
                            AND la.appstatus NOT LIKE 'Disapproved%'
                            AND la.remarks NOT LIKE 'POSTED%'");
                        $deptCount = mysqli_fetch_assoc($sqlDeptCount)['total'];

                        // Assign unique ID using company and department names
                        $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Remove special characters

                        // Add department tab with badge in top-right corner
                        echo "<li class='$deptActive' style='position: relative;'><a data-toggle='pill' href='#dept-$companyCode-$deptId'>$departmentName";
                        if ($deptCount > 0) {
                            echo "<span class='badge badge-right'>$deptCount</span>";
                        }
                        echo "</a></li>";
                        $deptActive = ''; // Remove active class from subsequent department tabs
                    }
                    echo "</ul>";

                    echo "<div class='tab-content' style='margin-top: 10px;'>";
                    mysqli_data_seek($sqlDepartments, 0);
                    $deptActive = 'in active';

                    // Department Content
                    while ($department = mysqli_fetch_array($sqlDepartments)) {
                        $departmentName = $department['department'];
                        $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Remove special characters
                        // Use unique ID for department tabs
                        echo "<div id='dept-$companyCode-$deptId' class='tab-pane fade $deptActive'>";

                        // Fetch employees based on company and department
                        $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                        $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                        $sqlEmployee = mysqli_query($con, "SELECT la.*, la.id as laid, ep.*, ed.*, d.department 
                        FROM leave_application la
                        INNER JOIN employee_profile ep ON ep.idno = la.idno 
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department 
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName' 
                        AND (la.dayfrom BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                        ORDER BY 
                            CASE 
                                WHEN la.appstatus LIKE 'Approved%' AND la.remarks NOT LIKE 'POSTED%' THEN 1
                                WHEN la.appstatus LIKE 'Disapproved%' THEN 2
                                WHEN la.appstatus = 'Pending' THEN 3
                                WHEN la.appstatus LIKE 'Approved%' AND la.remarks LIKE 'POSTED%' THEN 4
                                WHEN la.appstatus = 'Cancelled' THEN 5
                                ELSE 6
                            END,
                            la.datearray DESC");

            ?>
                <!-- Search Bar -->
                <div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
                    </div>
                </div>

                    <table class="table table-bordered table-striped table-condensed" id="attendanceTable">
                        <thead>
                            <tr>
                                <th class="sortable" data-column="0" width="2%" style="text-align: center;">No.</th>
                                <th class="sortable" data-column="1" width="6%" style="text-align: center;">Employee ID</th>
                                <th class="sortable" data-column="2" width="10%" style="text-align: center;">Employee Name</th>
                                <th class="sortable" data-column="3" width="6%" style="text-align: center;">Leave Type</th>
                                <th class="sortable" data-column="4" width="6%" style="text-align: center;">No. of Days</th>
                                <th class="sortable" data-column="5" width="6%" style="text-align: center;">From</th>
                                <th class="sortable" data-column="6" width="6%" style="text-align: center;">To</th>
                                <th class="sortable" data-column="7" style="text-align: center;">Reason</th>
                                <th class="sortable" data-column="8" width="7%" style="text-align: center;">Date Applied</th>
                                <th class="sortable" data-column="9" width="7%" style="text-align: center;">Status</th>
                                <th class="sortable" data-column="10" style="text-align: center;">HR Remarks</th>
                                <th class="sortable" data-column="11" style="text-align: center;">Approver Remarks</th>
                                <th width="6%" style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $x = 1;

                        if (!$sqlEmployee) {
                            echo "Error: " . mysqli_error($con);
                        } elseif (mysqli_num_rows($sqlEmployee) > 0) {
                            while ($emp = mysqli_fetch_array($sqlEmployee)) {
                                $status = $emp['appstatus'];
                                $remarks = $emp['remarks'];

                                // Determine the row class based on the applic_status
                                if (strpos($status, '*Disapproved') !== false || strpos($status, 'Disapproved') !== false) {
                                    $rowClass = "danger"; // Red
                                } elseif (strpos($status, 'Approved') !== false && strpos($remarks, 'POSTED') !== false) {
                                    $rowClass = "success"; // Green
                                } elseif ($status == "Pending") {
                                    $rowClass = "warning"; // Yellow
                                } else {
                                    $rowClass = "primary"; // No class for other cases
                                }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                <td style="text-align: center; vertical-align: middle;"><?= $x++; ?>.</td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $emp['idno']; ?></td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span style="font-weight: bold; font-size: 1.1em;"><?=$emp['lastname'];?></span>, <?=$emp['firstname'];?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $emp['leavetype']?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $emp['numberofdays']?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= date('M j, Y', strtotime($emp['dayfrom'])); ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= date('M j, Y', strtotime($emp['dayto'])); ?></td>
                                    <td style="text-align: justify; vertical-align: middle;"><?= $emp['reason'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= date('M j, Y', strtotime($emp['datearray'])) ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $emp['appstatus'] ?></td>
                                    <td style="text-align: <?= ($emp['remarks'] == 'POSTED') ? 'center' : 'justify'; ?>; vertical-align: middle;">
                                        <?=$emp['remarks'];?>
                                    </td>
                                    <td style="text-align: justify; vertical-align: middle;"><?=$emp['approver_remarks'];?></td>
                                    <td style="text-align: center; vertical-align: middle;">
                                    <?php if (strpos($emp['remarks'], 'POSTED') === false && strpos($emp['appstatus'], '*Approved') === false && strpos($emp['appstatus'], '*Disapproved') === false): ?>
                                        <?php 
                                        // Check if the application status is neither 'Cancelled' nor 'Pending'
                                            if ($emp['appstatus'] != 'Cancelled' && $emp['appstatus'] != 'Pending') { 
                                                // Check if the status contains "Disapproved - [Surname]"
                                                if (strpos($emp['appstatus'], 'Disapproved') === false) {
                                                    // Show the Post button only if "Disapproved" is NOT in the appstatus
                                                    ?>
                                                    <a href="?leaveapplication&post&id=<?=$emp['laid'];?>&remarks=<?=$emp['remarks'];?>" 
                                                        class="btn btn-success btn-xs confirm-post" 
                                                        title="Post">
                                                        <i class='fa fa-upload'></i>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                                    <a href="?leaveapplication&done&id=<?= $emp['laid']; ?>&remarks=<?= $emp['remarks']; ?>" 
                                                        class="btn btn-success btn-xs confirm-done" 
                                                        title="Done">
                                                        <i class='fa fa-check-square-o'></i>
                                                    </a>
                                                <?php
                                            } 
                                        ?>
                                    <?php endif; ?>
                                        <!-- Button for adding remarks -->
                                        <a href="?leaveapplication&addremarks&id=<?=$emp['laid'];?>&remarks=<?=$emp['remarks'];?>" 
                                        class="btn btn-primary btn-xs"
                                        title="Remarks">
                                            <i class='fa fa-comment'></i>
                                        </a>
                                </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='12' align='center'>No leave applications found.</td></tr>";
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
    </div>
</div>

<?php
if (isset($_GET['done'])) {
    $id = $_GET['id'];
    $sqlDone = "UPDATE leave_application 
        SET appstatus = CONCAT('*', appstatus) 
        WHERE id = '$id' AND appstatus NOT LIKE '*%'";

    if (mysqli_query($con, $sqlDone)) {
        echo "<script>alert('Application marked as Done.');</script>";
        echo "<script>window.location.href='?leaveapplication';</script>";
    } else {
        echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
    }
}
if (isset($_GET['post'])) {
    $id = $_GET['id'];

    // Sanitize the input
    $id = mysqli_real_escape_string($con, $id);
    
    // Initialize $startshift with a default value
    $startshift = null;
    
    // Retrieve shift
    $sqlShift = mysqli_query($con, "SELECT startshift FROM employee_details ed INNER JOIN leave_application la ON ed.idno = la.idno WHERE la.id = '$id'");
    if ($sqlShift && mysqli_num_rows($sqlShift) > 0) {
        $startshift = mysqli_fetch_assoc($sqlShift)['startshift'];
    } else {
        echo "Error: Could not retrieve start shift for employee with ID $id.";
    }
    

    // Retrieve leave credits
    $sqlCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno='$idno'");
    if ($sqlCredits && mysqli_num_rows($sqlCredits) > 0) {
        $credits = mysqli_fetch_assoc($sqlCredits)['credits'];
    }

    // Retrieve leave application details
    $sqlRetrieve = mysqli_query($con, "SELECT * FROM leave_application WHERE id='$id'");
    if ($sqlRetrieve && mysqli_num_rows($sqlRetrieve) > 0) {
        $leaveData = mysqli_fetch_array($sqlRetrieve);
        $leaveType = $leaveData['leavetype'];
        $numberOfDays = $leaveData['numberofdays'];
        $idno = $leaveData['idno']; 
        $startdate = $leaveData['dayfrom'];
        $enddate = $leaveData['dayto'];

        $start = new DateTime($startdate);
        $end = new DateTime($enddate);

        // Add 1 day to the end date to make it inclusive
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');  // 1-day interval
        $dateRange = new DatePeriod($start, $interval, $end);

        $dateArray = [];
        $daysAdded = 0;

        // // Define if not night shift
        // $isNotNightShift = ($startshift && ($startshift != '23:00:00' || $startshift != '00:00:00'));
        $isNightShift = ($startshift && ($startshift == '23:00:00' || $startshift == '00:00:00'));
        $isNotNightShift = ($startshift && ($startshift != '23:00:00' && $startshift != '00:00:00'));
        
        foreach ($dateRange as $date) {
            if ($daysAdded >= $numberOfDays) {
                break;
            }
        
            $dayOfWeek = $date->format('N'); // Get the numeric day of the week (1 = Monday, 7 = Sunday)
        
            // Skip Sundays for all shifts
            if ($dayOfWeek == 7) {
                continue;
            }
        
            // Skip Mondays only if it's a day shift
            if ($isNotNightShift && $dayOfWeek == 1) {
                continue;
            }
        
            // Skip Saturdays only if it's a night shift
            if ($isNightShift && $dayOfWeek == 6) {
                continue;
            }
        
            // Add valid date to array
            $dateArray[] = $date->format('Y-m-d');
            $daysAdded++;
        }
        
        // Retrieve current remarks
        $sqlCurrentRemarks = mysqli_query($con, "SELECT remarks FROM leave_application WHERE id='$id'");
        if ($sqlCurrentRemarks && mysqli_num_rows($sqlCurrentRemarks) > 0) {
            $currentRemarks = mysqli_fetch_assoc($sqlCurrentRemarks)['remarks'];
            $newRemarks = "POSTED";

            // If there are existing remarks, append them
            if (!empty($currentRemarks)) {
                $newRemarks .= " - Note: " . $currentRemarks;
            }

        // Update leave application status
        $sqlUpdate = mysqli_query($con, "UPDATE leave_application SET remarks='$newRemarks' WHERE id='$id'");

            if ($sqlUpdate) {
                foreach ($dateArray as $leaveDate) {
                    // Check if the date exists in attendance
                    $sqlCheckAttendance = mysqli_query($con, 
                        "SELECT * FROM attendance WHERE idno = '$idno' AND logindate = '$leaveDate'");
                    
                    if (mysqli_num_rows($sqlCheckAttendance) == 0) {
                        // Insert new attendance row if date doesn't exist
                        $sqlInsertAttendance = mysqli_query($con, 
                            "INSERT INTO attendance (idno, logindate, loginam, logoutam, loginpm, logoutpm, remarks) 
                            VALUES ('$idno', '$leaveDate', '0', '0', '0', '0', '$leaveType')");
                        
                        if (!$sqlInsertAttendance) {
                            echo "<script>alert('Error inserting new attendance record for date: $leaveDate');</script>";
                        }
                    } else {
                        // Update existing attendance row
                        $sqlUpdateAttendRem = mysqli_query($con, 
                            "UPDATE attendance SET remarks = '$leaveType' WHERE idno = '$idno' AND logindate = '$leaveDate'");
                        
                        if (!$sqlUpdateAttendRem) {
                            echo "<script>alert('Error updating attendance for date: $leaveDate');</script>";
                        }
                    }

                    // Update leave credits based on leave type
                    switch ($leaveType) {
                        case 'VL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = vlused + 1 WHERE idno = '$idno'");
                            break;
                        case 'SL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET slused = slused + 1 WHERE idno = '$idno'");
                            break;
                        case 'PTO':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused + 1 WHERE idno = '$idno'");
                            break;
                        case 'BLP':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used + 1 WHERE idno = '$idno'");
                            break;
                        case 'EO':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET eo_used = eo_used + 1 WHERE idno = '$idno'");
                            break;
                        case 'SPL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET spl_used = spl_used + 1 WHERE idno = '$idno'");
                            break;
                        case 'MTL':
                            
                        break;
                        case 'LTL':
                                    
                        break;
                        case 'MDL':
                                    
                        break;
                        case 'PTL':
                                    
                        break;
                        case 'BL':
                                    
                        break;
                        default:
                            echo "<script>alert('Leave type not recognized. No credits updated.');</script>";
                            break;
                    }

                    // Update attendance status to 'leave'
                    $sqlupdateStatus = mysqli_query($con, "UPDATE attendance SET status='leave' WHERE idno='$idno'");
                }

                // Final success message
                echo "<script>alert('Leave application successfully posted!'); window.location='?leaveapplication';</script>";
            } else {
                echo "<script>alert('Unable to post leave application!'); window.location='?leaveapplication';</script>";
            }
        }else {
            echo "<script>alert('Error retrieving current remarks for leave application.'); window.location='?leaveapplication';</script>";
        }
    }
}
?>


<?php 
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
    $sqlUpdateRemarks = "UPDATE leave_application SET remarks = '$remarks' WHERE id = '$id'";
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
//Filter Button for Date Filter
function filterByDate() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;

if (fromDate && toDate) {
        window.location.href = `?leaveapplication&fromDate=${fromDate}&toDate=${toDate}`;
    } else {
        alert('Please select both "From" and "To" dates.');
}
}
//Reset button for Date Filter
function resetFilter() {
    window.location.href = '?leaveapplication';
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

                // Handle numeric vs. string comparison
                return isAscending
                    ? compareValues(bText, aText)
                    : compareValues(aText, bText);
            });

            // Append sorted rows back to the table body
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    function compareValues(a, b) {
        if (!isNaN(a) && !isNaN(b)) {
            return parseFloat(a) - parseFloat(b); // Numeric comparison
        }
        return a.localeCompare(b); // String comparison
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
</script>
