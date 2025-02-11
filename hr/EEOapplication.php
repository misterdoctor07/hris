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

/* Badge styling for top-right corner positioning */
.badge-right {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(50%, -50%);
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 4px 8px;
    font-size: 12px;
}
</style>

<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
}

date_default_timezone_set('Asia/Manila'); // Set your timezone
$currentTime = date('Y-m-d H:i:s');

// // Check for pending EEO applications older than 24 hours
// $sqlCheckTime = mysqli_query($con, "SELECT id, datemissed, mttime 
//                                     FROM emergencyearlyout 
//                                     WHERE eeo_status = 'Pending'");

// if (!$sqlCheckTime) {
//     die("Query failed: " . mysqli_error($con));
// }

// while ($application = mysqli_fetch_assoc($sqlCheckTime)) {
//     $dateApplied = $application['datemissed'];
//     $timeApplied = $application['mttime'];
//     $applicationId = $application['id'];

//     // Combine date and time to create a full datetime
//     $datetimeApplied = $dateApplied . ' ' . $timeApplied;

//     // Calculate the time difference in hours
//     $timeDifference = (strtotime($currentTime) - strtotime($datetimeApplied)) / 3600;

//     // If more than 24 hours have passed, update the status to "Disapproved"
//     if ($timeDifference > 24) {
//         $sqlUpdateStatus = mysqli_query($con, "UPDATE emergencyearlyout 
//                                                SET eeo_status = 'Disapproved (24 hours passed)', 
//                                                    updated_at = '$currentTime' 
//                                                WHERE id = '$applicationId'");

//         if (!$sqlUpdateStatus) {
//             error_log("Failed to update application ID $applicationId: " . mysqli_error($con));
//         }
//     }
// }

// Handle approval action for EEO
if (isset($_GET['approved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='HR Approved - [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('EEO application successfully approved!'); window.location='?EEOapplication';</script>";
    } else {
        echo "<script>alert('Unable to approve EEO application!'); window.location='?EEOapplication';</script>";
    }
}

// Handle disapproval action for EEO
if (isset($_GET['disapproved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID
    $datetime = date('M j, Y - g:i A');
    $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='Disapproved - [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('EEO application successfully disapproved!'); window.location='?EEOapplication';</script>";
    } else {
        echo "<script>alert('Unable to disapprove EEO application!'); window.location='?EEOapplication';</script>";
    }
}

// Handle undo action for leave application
if (isset($_GET['undo']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='Pending' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Action successfully undone!'); window.location='?EEOapplication';</script>";
    } else {
        echo "<script>alert('Action taken was not successful!'); window.location='?EEOapplication';</script>";
    }
}
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-file-text"></i> EMERGENCY EARLY OUT APPLICATION
                <div style="float:right; margin-bottom: 20px;">
                    <form>
                        <button type="button" onclick="tablesToExcel('EEO_Application_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
                </div>
            </h4>
            <!-- Date Filter -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-3">
                    <label for="fromDate">From:</label>
                    <input type="date" id="fromDate" class="form-control" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="toDate">To:</label>
                    <input type="date" id="toDate" class="form-control" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="filterByDate()" class="btn btn-primary" style="margin-top: 25px;">Filter</button>
                    <button type="button" onclick="resetFilter()" class="btn btn-default" style="margin-top: 25px;">Reset</button>
                </div>
            </div>
        </div>

        <!-- Company Tab Navigation -->
        <ul class="nav nav-tabs">
            <?php
                $active = 'active'; // Set the first company tab as active
                while ($company = mysqli_fetch_array($sqlCompanies)) {
                    $companyCode = $company['company'];

                    // Count pending EEO applications for the company
                    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                    $sqlCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM emergencyearlyout eeo
                        INNER JOIN employee_details ed ON eeo.idno = ed.idno
                        WHERE ed.company = '$companyCode'
                        AND (eeo.dateEEO BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '') 
                        AND eeo.eeo_status != 'Cancelled' 
                        AND eeo.eeo_status NOT LIKE '%Approved%'
                        AND eeo.eeo_status NOT LIKE '%Disapproved%'");  
                    $count = mysqli_fetch_assoc($sqlCount)['total'];
                    
                    // Display company name with badge
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
                        WHERE ed.company = '$companyCode' ORDER BY d.department");

                    echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                    $deptActive = 'active';
                    
                    while ($department = mysqli_fetch_array($sqlDepartments)) {
                        $departmentName = $department['department'];
                        
                        // Fetch count of pending EEO applications for the department
                        $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                        $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                        $sqlDeptCount = mysqli_query($con, "SELECT COUNT(*) AS total 
                        FROM emergencyearlyout eeo
                        INNER JOIN employee_details ed ON eeo.idno = ed.idno
                        INNER JOIN department d ON d.id = ed.department
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName'
                        AND eeo.dateEEO IS NOT NULL
                        AND ('$fromDate' = '' OR '$toDate' = '' OR DATE(eeo.dateEEO) BETWEEN '$fromDate' AND '$toDate')
                        AND eeo.eeo_status NOT LIKE '%Approved%' 
                        AND eeo.eeo_status NOT LIKE '%Disapproved%' 
                        AND eeo.eeo_status NOT LIKE 'Cancelled%'");
                        $deptCount = mysqli_fetch_assoc($sqlDeptCount)['total'];

                        // Assign unique ID using company and department names
                        $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Remove special characters

                        // Add department tab with badge in top-right corner
                        echo "<li class='$deptActive' style='position: relative;'><a data-toggle='pill' href='#dept-$companyCode-$deptId'>$departmentName";
                        if ($deptCount > 0) {
                            echo "<span class='badge badge-right'>" . intval($deptCount) . "</span>";
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
                        $sqlEmployee = mysqli_query($con, "SELECT eeo.*, eeo.id as eeoid, ep.*, ed.*, d.department 
                        FROM emergencyearlyout eeo
                        INNER JOIN employee_profile ep ON ep.idno = eeo.idno 
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department 
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName'
                        AND (eeo.dateEEO BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '') 
                        ORDER BY 
                            CASE 
                                WHEN eeo.eeo_status = 'Pending' THEN 1
                                WHEN eeo.eeo_status LIKE '%Approved%' THEN 2
                                WHEN eeo.eeo_status LIKE '%Disapproved%' THEN 3
                                ELSE 4
                            END,
                            eeo.date_applied,
                            eeo.time_applied DESC");
                    

            ?>
                <!-- Search Bar -->
                <div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
                    </div>
                </div>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="2%" style="text-align: center;">No.</th>
                                <th width="6%" style="text-align: center;">Employee ID</th>
                                <th width="7%" style="text-align: center;">Employee Name</th>
                                <th width="5%" style="text-align: center;">Type of EEO</th>
                                <th width="7%" style="text-align: center;">Date of EEO</th>
                                <th width="5%" style="text-align: center;">Time of EEO</th>
                                <th style="text-align: center;">Reason</th>
                                <th width="10%" style="text-align: center;">Date and Time Applied</th>
                                <th width="10%" style="text-align: center;">Status</th>
                                <th style="text-align: center;">Approver's Remarks</th>
                                <th style="text-align: center;">Acknowledged by:</th>
                                <th width="6%" style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $x = 1;

                        if (!$sqlEmployee) {
                            echo "Error: " . mysqli_error($con);
                            exit;
                        }

                        if (mysqli_num_rows($sqlEmployee) > 0) {
                            while ($emp = mysqli_fetch_array($sqlEmployee)) {
                                $status = $emp['eeo_status'];
    
                                $style = "class='primary'"; // Default style
                                
                                if (strpos($status, 'Approved') !== false) {
                                    $style = "class='success'";
                                } elseif (strpos($status, 'Disapproved') !== false) {
                                    $style = "class='danger'";
                                } elseif (strpos($status, 'Pending') !== false) {
                                    $style = "class='warning'";
                                }
                                ?>
                                <tr <?= $style; ?>>
                                    <td align='center'><?= $x++; ?>.</td>
                                    <td align='center'><?= $emp['idno']; ?></td>
                                    <td align='center'><?= $emp['lastname'] . ', ' . $emp['firstname']; ?></td>
                                    <td align='center'><?= $emp['type_EEO']?></td>
                                    <td align='center'><?= date('m/d/Y', strtotime($emp['dateEEO'])); ?></td>
                                    <td align='center'><?= date("g:i A", strtotime($emp['timeEEO'])); ?></td>
                                    <td align='left'><?= $emp['reason'] ?></td>
                                    <td align='center'><?= date('m/d/Y', strtotime($emp['date_applied'])) . "<br>" . date('g:i:s A', strtotime($emp['time_applied'])); ?></td>
                                    <td align='center'><?= $emp['eeo_status'] ?></td>
                                    <td align='left'><?= $emp['approvers_remarks'] ?></td>
                                    <td align='left'><?= $emp['acknowledged'] ?></td>
                                    <td align="center">
                                        <?php if (strpos($emp['eeo_status'], 'Approved') === false && strpos($emp['eeo_status'], 'Disapproved') === false): ?>
                                            <a href="?EEOapplication&addremarks&id=<?= $emp['eeoid']; ?>&remarks=<?= $emp['approvers_remarks']; ?>" 
                                            class="btn btn-primary btn-xs" title="Remarks">
                                            <i class='fa fa-edit'></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php 
                                        $appStatus = $emp['eeo_status'];
                                        if ($appStatus == "Pending"): 
                                        ?>
                                            <a href="?EEOapplication&id=<?= $emp['eeoid']; ?>&approved" 
                                                class="btn btn-success btn-xs" title="Approve" 
                                                onclick="return confirm('Do you wish to approve this EEO application?');">
                                                <i class='fa fa-thumbs-up'></i>
                                            </a>
                                            <a href="?EEOapplication&id=<?= $emp['eeoid']; ?>&disapproved" 
                                                class="btn btn-danger btn-xs" title="Disapprove" 
                                                onclick="return confirm('Do you wish to disapprove this EEO application?');">
                                                <i class='fa fa-thumbs-down'></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?EEOapplication&id=<?= $emp['eeoid']; ?>&undo" 
                                            class="btn btn-warning btn-xs" title="Undo Action" 
                                            onclick="return confirm('Do you wish to undo the action taken?');">
                                            <i class='fa fa-undo'></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='14' align='center'>No records found!</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    echo "</div>"; // End of department tab content
                    $deptActive = ''; // Remove active class from subsequent department tabs
                }
                echo "</div>"; // End of department tabs for a company
                echo "</div>"; // End of company tab content
                $active = ''; // Remove active class from subsequent company contents
            }
            ?>
        </div>
    </div>
</div>

<?php 
//Done Logic
if (isset($_GET['done'])) {
    $id = $_GET['id'];
    $sqlDone = "UPDATE emergencyearlyout 
        SET eeo_status = CONCAT('*', eeo_status) 
        WHERE id = '$id' AND eeo_status NOT LIKE '*%'";

    if (mysqli_query($con, $sqlDone)) {
        echo "<script>alert('Application marked as Done.');</script>";
        echo "<script>window.location.href='?EEOapplication';</script>";
    } else {
        echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
    }
}
// //Post Logic
// if (isset($_GET['post'])) {
//     $id = mysqli_real_escape_string($con, $_GET['id']);
    
//     // Update remarks in emergencyearlyout to "POSTED"
//     $sqlUpdateMissedLog = mysqli_query($con, "UPDATE emergencyearlyout SET remarks='POSTED' WHERE id='$id'");

//     if ($sqlUpdateMissedLog) {
//         // Retrieve the idno and datemissed values
//         $sqlRetrieve = mysqli_query($con, "SELECT idno, datemissed FROM emergencyearlyout WHERE id='$id'");
        
//         if ($sqlRetrieve && mysqli_num_rows($sqlRetrieve) > 0) {
//             $missedlogData = mysqli_fetch_array($sqlRetrieve);
//             $idno = $missedlogData['idno'];
//             $datemissed = $missedlogData['datemissed'];

//             // Check if the attendance record exists for the missed date
//             $sqlCheckAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$idno' AND logindate='$datemissed'");
            
//             if (mysqli_num_rows($sqlCheckAttendance) == 0) {
//                 // Insert a new attendance row if the date doesn't exist
//                 $sqlInsertAttendance = mysqli_query($con, 
//                     "INSERT INTO attendance (idno, logindate, loginam, logoutam, loginpm, logoutpm, remarks) 
//                     VALUES ('$idno', '$datemissed', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'eeo')");
                
//                 if (!$sqlInsertAttendance) {
//                     echo "<script>alert('Error inserting new attendance record for missed date: $datemissed');</script>";
//                 }
//             } else {
//                 // Update the existing attendance row with "eeo" in the remarks column
//                 $sqlUpdateAttendance = mysqli_query($con, "UPDATE attendance SET remarks = 'Code eeo' WHERE idno='$idno' AND logindate='$datemissed'");
                
//                 if (!$sqlUpdateAttendance) {
//                     echo "<script>alert('Error updating attendance for missed date: $datemissed');</script>";
//                 }
//             }
            
//             echo "<script>alert('EEO application successfully posted!'); window.location='?EEOapplication';</script>";
//         }
//     } else {
//         echo "<script>alert('Unable to post EEO application!'); window.location='?EEOapplication';</script>";
//     }
// }

// //Null/Void Logic
// if (isset($_GET['null'])) {
//     $id = mysqli_real_escape_string($con, $_GET['id']);
//     $sqlUpdateMissedLog = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='NULL/VOID' WHERE id='$id'");
// }

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
                    <a href="?EEOapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
    $sqlUpdateRemarks = "UPDATE emergencyearlyout SET remarks = '$remarks' WHERE id = '$id'";
    if (mysqli_query($con, $sqlUpdateRemarks)) {
        echo "<script>alert('Remarks updated successfully.');</script>";
        echo "<script>window.location.href='?EEOapplication';</script>";
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
        const confirmButtons = document.querySelectorAll('.confirm-done');

        // Loop through each button and add a click event listener
        confirmButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                // Display the confirmation dialog
                const confirmAction = confirm("Are you sure this EEO is DONE?");
                
                // If the user clicks "Cancel", prevent the link's default action
                if (!confirmAction) {
                    event.preventDefault();
                }
            });
        });

        // // Select all buttons with the "confirm-null" class
        // const confirmNullButtons = document.querySelectorAll('.confirm-null');

        // // Loop through each button and add a click event listener
        // confirmNullButtons.forEach(button => {
        //     button.addEventListener('click', function(event) {
        //         // Display the confirmation dialog
        //         const confirmAction = confirm("Are you sure you want to VOID/NULL this EEO?");
                
        //         // If the user clicks "Cancel", prevent the link's default action
        //         if (!confirmAction) {
        //             event.preventDefault();
        //         }
        //     });
        // });
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

    function tablesToExcel() {
        const dataType = 'application/vnd.ms-excel';
        let tableHTeeo = '';

        // Define filenames based on the outer tab index
        const filenames = ['NESI1_EEO_Application_Report.xls', 'NESI2_EEO_Application_Report.xls', 'NEWIND_EEO_Application_Report.xls'];

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
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'EEO_Application_Report.xls';

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
                    tableHTeeo += `<h3>${innerTabName}</h3>`; // Add header for the table

                    // Clone the table to modify it
                    const clonedTable = table.cloneNode(true);
                    
                    // Add inline styles for borders
                    clonedTable.style.borderCollapse = 'collapse'; // Collapse borders
                    clonedTable.querySelectorAll('th, td').forEach(cell => {
                        cell.style.border = '1px solid black'; // Add border to each cell
                        cell.style.padding = '5px'; // Optional: Add padding for better spacing
                    });

                    tableHTeeo += clonedTable.outerHTeeo + '<br>'; // Append each table's HTeeo
                }
            });

            // Create a download link
            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            // Create a Blob with the combined table HTeeo
            const blob = new Blob([tableHTeeo], {
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
function filterByDate() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;

    if (fromDate && toDate) {
            window.location.href = `?EEOapplication&fromDate=${fromDate}&toDate=${toDate}`;
        } else {
            alert('Please select both "From" and "To" dates.');
    }
}
function resetFilter() {
    window.location.href = '?EEOapplication';
}
</script>