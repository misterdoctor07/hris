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
// Convert requesting officers array into a string for SQL query
$requestingOfficersStr = implode("','", $requestingOfficers);

// Handle approval action for overtime
if (isset($_GET['approved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Update query to approve only the specific overtime application
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE overtime_application SET app_status='Approved - $approval' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Overtime application successfully approved!'); window.location='?manageovertimeapplication';</script>";
    } else {
        echo "<script>alert('Unable to approve overtime application!'); window.location='?manageovertimeapplication';</script>";
    }
}

// Handle disapproval action for overtime
if (isset($_GET['disapproved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update query to disapprove only the specific overtime application
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE overtime_application SET app_status='Disapproved - $approval' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Overtime application successfully disapproved!'); window.location='?manageovertimeapplication';</script>";
    } else {
        echo "<script>alert('Unable to disapprove overtime application!'); window.location='?manageovertimeapplication';</script>";
    }
}
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> MANAGE OT APPLICATION</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                <thead>
                    <tr>
                        <th width="2%" style="text-align: center;">No.</th>
                        <th width="6%" style="text-align: center;">Employee ID</th>
                        <th width="8%" style="text-align: center;">Employee Name</th>
                        <th width="5%" style="text-align: center;">OT Date</th>
                        <th width="5%" style="text-align: center;">OT Time</th>
                        <th style="text-align: center;">Reason</th>
                        <th width="9%" style="text-align: center;">Date/Time Applied</th>
                        <th width="9%" style="text-align: center;">Status</th>
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
                    $query = "SELECT ot.*, ot.id as otid, ep.*, ed.* 
                    FROM overtime_application ot 
                    INNER JOIN employee_profile ep ON ep.idno = ot.idno 
                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                    WHERE ot.idno != '$userId' 
                    AND ($whereClause)
                    ORDER BY 
                        CASE WHEN ot.app_status='Pending' THEN 1 ELSE 2 END, 
                        ot.datearray DESC,
                        ot.timearray DESC";

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
                    $appStatus = $company['app_status'];
                    $idno = $company['idno'];
                    $style = "class='primary'"; // Default style

                    if (strpos($appStatus, 'Approved') !== false) {
                        $style = "class='success'";
                    } elseif (strpos($appStatus, 'Disapproved') !== false) {
                        $style = "class='danger'";
                    } elseif (strpos($appStatus, 'Pending') !== false) {
                        $style = "class='warning'";
                    }
                    $statusText = $appStatus;

                    $sqlDepartment = mysqli_query($con, "SELECT ed.department, d.department, ed.*, d.*
                                    FROM employee_details ed
                                    INNER JOIN department d ON d.id = ed.department
                                    WHERE idno = '$idno'");

                    if(mysqli_num_rows ($sqlDepartment) > 0) {
                        $row = mysqli_fetch_assoc($sqlDepartment);
                        $department = $row['department'];
                    }

                                echo "<tr $style>";
                                echo "<td align='center'>$x.</td>";
                                echo "<td align='center'>{$company['idno']}</td>";
                                echo "<td align='center'>{$company['lastname']}, {$company['firstname']}</td>";
                                echo "<td align='center'>" . date('m/d/Y', strtotime($company['otdate'])) . "</td>";
                                echo "<td align='center'>{$company['ottime']}</td>";
                                echo "<td align='left'>{$company['reasons']}</td>";
                                echo "<td align='center'>{$company['datearray']} {$company['timearray']}</td>";
                                echo "<td align='center'>$statusText</td>";
                                echo "<td align='left'>{$company['remarks']}</td>";
                                ?>
                                <td align="center">
                                    <a href="?manageovertimeapplication&id=<?= $company['otid']; ?>&approved" class="btn btn-success btn-xs" title="Approve" 
                                       onclick="return confirm('Do you wish to approve this overtime application?');">
                                       <i class='fa fa-thumbs-up'></i>
                                    </a>

                                    <a href="?manageovertimeapplication&id=<?= $company['otid']; ?>&disapproved" class="btn btn-danger btn-xs" title="Disapprove" 
                                       onclick="return confirm('Do you wish to disapprove this overtime application?');">
                                       <i class='fa fa-thumbs-down'></i>
                                    </a>
                                    <a href="?manageovertimeapplication&addremarks&id=<?=$company['otid'];?>&remarks=<?=$company['remarks'];?>" class="btn btn-primary btn-xs" title="Remarks">
                                        <i class='fa fa-edit'></i></a>
                                </td>

                                <?php
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
    $remarks = urldecode($_GET['remarks']); // Use urldecode to handle special characters
?>
    <!-- Remarks Form -->
    <div class="modal-overlay">
    <div class="modal-container">
        <div class="content-panel">
            <div class="panel-heading-">
                <h4>
                    <a href="?manageovertimeapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
    $sqlUpdateRemarks = "UPDATE overtime_application SET remarks = '$remarks' WHERE id = '$id'";
    if (mysqli_query($con, $sqlUpdateRemarks)) {
        echo "<script>alert('Remarks updated successfully.');</script>";
        echo "<script>window.location.href='?manageovertimeapplication';</script>"; // Redirect after update
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