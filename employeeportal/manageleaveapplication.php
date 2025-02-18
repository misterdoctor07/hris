<?php
// Get the logged-in user ID
$userId = $_SESSION['idno'];
// Fetch user details 
$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userQuery);
// Extract user designation
$designation = $userDetails['designation']; 
$department = $userDetails['department'];
// Fetch requesting officers, companies, and departments
$sqlProtocol = mysqli_query($con, "SELECT requestingofficer, company, department FROM leave_protocols WHERE approvingofficer = '$userId'");
$requestingOfficers = [];
$requestingCompany = [];
$requestingDepartment = [];

if (mysqli_num_rows($sqlProtocol) > 0) {
    while ($protocol = mysqli_fetch_assoc($sqlProtocol)) {
        if ($protocol['requestingofficer']) $requestingOfficers[] = $protocol['requestingofficer'];
        if ($protocol['company']) $requestingCompany[] = $protocol['company'];
        if ($protocol['department']) $requestingDepartment[] = $protocol['department'];
    }
}

// Convert to strings for SQL IN clauses
$requestingOfficersStr = !empty($requestingOfficers) ? "'" . implode("','", $requestingOfficers) . "'" : null;
$requestingCompStr = !empty($requestingCompany) ? "'" . implode("','", $requestingCompany) . "'" : null;
$requestingDeptStr = !empty($requestingDepartment) ? "'" . implode("','", $requestingDepartment) . "'" : null;

// Handle approval action for leave application
if (isset($_GET['approved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    // Update query to approve only the specific leave application
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE leave_application SET appstatus='Approved - $approval [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Leave application successfully approved!'); window.location='?manageleaveapplication';</script>";
    } else {
        echo "<script>alert('Unable to approve leave application!'); window.location='?manageleaveapplication';</script>";
    }
}

// Handle disapproval action for leave application
if (isset($_GET['disapproved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    // Update query to approve only the specific missed log application
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE leave_application SET appstatus='Disapproved - $approval [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Leave application successfully disapproved!'); window.location='?manageleaveapplication';</script>";
    } else {
        echo "<script>alert('Unable to disapprove leave application!'); window.location='?manageleaveapplication';</script>";
    }
}

// Handle undo action for leave application
if (isset($_GET['undo']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $sqlUpdate = mysqli_query($con, "UPDATE leave_application SET appstatus='Pending' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Action successfully undone!'); window.location='?manageleaveapplication';</script>";
    } else {
        echo "<script>alert('Action taken was not successful!'); window.location='?manageleaveapplication';</script>";
    }
}
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-suitcase"></i> MANAGE LEAVE APPLICATION</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                <thead>
                    <tr>
                        <th width="2%" style="text-align: center;">No.</th>
                        <th width="6%" style="text-align: center;">Employee ID</th>
                        <th width="10%" style="text-align: center;">Employee Name</th>
                        <th width="6%" style="text-align: center;">Leave Type</th>
                        <th width="6%" style="text-align: center;">No. of Days</th>
                        <th width="6%" style="text-align: center;">From</th>
                        <th width="6%" style="text-align: center;">To</th>
                        <th style="text-align: center;">Reason</th>
                        <th width="7%" style="text-align: center;">Date Applied</th>
                        <th width="6%" style="text-align: center;">Status</th>
                        <th style="text-align: center;">HR's Remarks</th>
                        <th style="text-align: center;">Remarks</th>
                        <th width="6%" style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;       

                    // Initialize an empty array for conditions
                    $conditions = [];

                    // Query to fetch the approver's combinations
                    $approverQuery = "SELECT company, department, requestingofficer, shift 
                                    FROM leave_protocols 
                                    WHERE approvingofficer = '$userId'";
                    $result = mysqli_query($con, $approverQuery);

                    if (!$result) {
                        die("Database query failed: " . mysqli_error($con));
                    }

                    // Loop through each row and build a condition
                    while ($row = mysqli_fetch_assoc($result)) {
                        $clauseParts = [];

                        // Build conditions based on non-null values
                        if (!empty($row['shift'])) {
                            $clauseParts[] = "ed.startshift = '{$row['shift']}'";
                        }
                        if (!empty($row['company'])) {
                            $clauseParts[] = "ed.company = '{$row['company']}'";
                        }
                        if (!empty($row['department'])) {
                            $clauseParts[] = "ed.department = '{$row['department']}'";
                        }
                        if (!empty($row['requestingofficer'])) {
                            $clauseParts[] = "ed.designation = '{$row['requestingofficer']}'";
                        }

                        // Combine the conditions for this specific row
                        if (!empty($clauseParts)) {
                            $conditions[] = '(' . implode(' AND ', $clauseParts) . ')';
                        }
                    }

                    // Join all conditions with OR to match any valid combination
                    $whereClause = !empty($conditions) ? implode(' OR ', $conditions) : '1=1';

                    // Build the final query
                    $query = "SELECT la.*, la.id as laid, ep.*, ed.* 
                            FROM leave_application la 
                            INNER JOIN employee_profile ep ON ep.idno = la.idno 
                            INNER JOIN employee_details ed ON ed.idno = ep.idno 
                            WHERE la.idno != '$userId' 
                            AND ($whereClause)
                            ORDER BY 
                                CASE 
                                    WHEN la.appstatus = 'Pending' THEN 1 
                                    WHEN la.appstatus LIKE '%Approved%' THEN 2 
                                    WHEN la.appstatus LIKE '%Dispproved%' THEN 3
                                    WHEN la.appstatus LIKE '%Cancelled%' THEN 4  
                                    ELSE 5 END, 
                                la.datearray DESC";

                    // Debugging: Print the final query
                    // echo "Final Query: " . $query;

                    // Execute query
                    $sqlEmployee = mysqli_query($con, $query);

                    // Check if query was successful
                    if (!$sqlEmployee) {
                        die("Query failed: " . mysqli_error($con));
                    }

                    // Fetch and display results
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        $x = 1;
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            $appStatus = $company['appstatus'];

                            $style = "class='primary'"; // Default style
                            
                            if (strpos($appStatus, 'Approved') !== false) {
                                $style = "class='success'";
                            } elseif (strpos($appStatus, 'Disapproved') !== false) {
                                $style = "class='danger'";
                            } elseif (strpos($appStatus, 'Pending') !== false) {
                                $style = "class='warning'";
                            }
                            
                            $statusText = $appStatus;

                            echo "<tr $style>";
                            echo "<td align='center'>$x.</td>";
                            echo "<td align='center'>{$company['idno']}</td>";
                            echo "<td align='center'>
                                    <span style='font-weight: bold; font-size: 1.1em;'>{$company['lastname']}</span>, {$company['firstname']}
                                </td>";
                            echo "<td align='center'>{$company['leavetype']}</td>"; 
                            echo "<td align='center'>{$company['numberofdays']}</td>";
                            echo "<td align='center'>" . date('M j, Y', strtotime($company['dayfrom'])) . "</td>";
                            echo "<td align='center'>" . date('M j, Y', strtotime($company['dayto'])) . "</td>";
                            echo "<td align='left'>{$company['reason']}</td>";
                            echo "<td align='center'>" . date('M j, Y', strtotime($company['datearray'])) . "</td>";
                            echo "<td align='center'>$statusText</td>";
                            echo "<td style='text-align: " . (($company['remarks'] == 'POSTED') ? 'center' : 'justify') . "; vertical-align: middle;'>
                                    {$company['remarks']}
                                </td>";
                            echo "<td align='left'>{$company['approver_remarks']}</td>";
                            echo "<td align='center'>";
                            if ($appStatus == "Pending") {
                                echo "<a href='?manageleaveapplication&id={$company['laid']}&approved' class='btn btn-success btn-xs' title='Approve' onclick=\"return confirm('Do you wish to approve this leave application?'); return false;\"><i class='fa fa-thumbs-up'></i></a>";
                                echo "<a href='?manageleaveapplication&id={$company['laid']}&disapproved' class='btn btn-danger btn-xs' title='Disapprove' onclick=\"return confirm('Do you wish to disapprove this leave application?'); return false;\"><i class='fa fa-thumbs-down'></i></a>";
                                echo "<a href='?manageleaveapplication&addremarks&id={$company['laid']}&approver_remarks' class='btn btn-primary btn-xs' title='Remarks');\"><i class='fa fa-comment'></i></a>";
                            } else {
                                echo "<a href='?manageleaveapplication&id={$company['laid']}&undo' class='btn btn-warning btn-xs' title='Undo Action' onclick=\"return confirm('Do you wish to undo the action taken?'); return false;\"><i class='fa fa-exchange'></i></a>";
                            }
                            echo "</td>";
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
    </div>
</div>

<?php
// Check if the user clicked 'Add Remarks'
if (isset($_GET['addremarks'])) {
    $id = $_GET['id'];
    $remarks = urldecode($_GET['approver_remarks']); // Use urldecode to handle special characters
?>
    <!-- Remarks Form -->
    <div class="modal-overlay">
    <div class="modal-container">
        <div class="content-panel">
            <div class="panel-heading-">
                <h4>
                    <a href="?manageleaveapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
    $sqlUpdateRemarks = "UPDATE leave_application SET approver_remarks = '$remarks' WHERE id = '$id'";
    if (mysqli_query($con, $sqlUpdateRemarks)) {
        echo "<script>alert('Remarks updated successfully.');</script>";
        echo "<script>window.location.href='?manageleaveapplication';</script>"; // Redirect after update
    } else {
        echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
    }
}
?>

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
</style>