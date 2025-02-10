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

// Handle acknowledge action for EEO
if (isset($_GET['acknowledged']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']}) [$datetime]";
    
    // Retrieve current acknowledgement
    $sqlCurrentAck = mysqli_query($con, "SELECT acknowledged FROM emergencyearlyout WHERE id='$id'");
    
    if ($sqlCurrentAck && mysqli_num_rows($sqlCurrentAck) > 0) {
        $row = mysqli_fetch_assoc($sqlCurrentAck);
        $currentAck = $row['acknowledged'];
        
        // Append new acknowledgment, separated by commas
        $newAck = empty($currentAck) ? $approval : $currentAck . ", <br>" . $approval;
        
        // Update the acknowledged column
        $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET acknowledged='$newAck' WHERE id='$id'");
        
        if ($sqlUpdate) {
            echo "<script>alert('EEO application acknowledged!'); window.location='?manageEEOapplication';</script>";
        } else {
            echo "<script>alert('Unable to acknowledge EEO application!'); window.location='?manageEEOapplication';</script>";
        }
    }
}

// Handle approval action for EEO
if (isset($_GET['approved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='Approved - $approval [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('EEO application successfully approved!'); window.location='?manageEEOapplication';</script>";
    } else {
        echo "<script>alert('Unable to approve EEO application!'); window.location='?manageEEOapplication';</script>";
    }
}

// Handle disapproval action for EEO
if (isset($_GET['disapproved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $datetime = date('M j, Y - g:i A');
    $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='Disapproved - $approval [$datetime]' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('EEO application successfully disapproved!'); window.location='?manageEEOapplication';</script>";
    } else {
        echo "<script>alert('Unable to disapprove EEO application!'); window.location='?manageEEOapplication';</script>";
    }
}

// // Handle undo action for EEO
// if (isset($_GET['undo']) && isset($_GET['id'])) {
//     $id = intval($_GET['id']); 

//     $sqlUpdate = mysqli_query($con, "UPDATE emergencyearlyout SET eeo_status='Pending' WHERE id='$id'");

//     if ($sqlUpdate) {
//         echo "<script>alert('Action successfully undone!'); window.location='?manageEEOapplication';</script>";
//     } else {
//         echo "<script>alert('Action taken was not successful!'); window.location='?manageEEOapplication';</script>";
//     }
// }
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> MANAGE EEO APPLICATION</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                <thead>
                    <tr>
                        <th width="2%" style="text-align: center;">No.</th>
                        <th width="6%" style="text-align: center;">Employee ID</th>
                        <th width="7%" style="text-align: center;">Employee Name</th>
                        <th width="5%" style="text-align: center;">Department</th>
                        <th width="7%" style="text-align: center;">Type of EEO</th>
                        <th width="7%" style="text-align: center;">Date of EEO</th>
                        <th width="7%" style="text-align: center;">Time of EEO</th>
                        <th style="text-align: center;">Reason</th>
                        <th width="10%" style="text-align: center;">Date and Time Applied</th>
                        <th width="10%" style="text-align: center;">Status</th>
                        <th style="text-align: center;">Approver's Remarks</th>
                        <th width="9%" style="text-align: center;">Acknowledged by:</th>
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

                    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
                   // Build the final query
                   $query = "SELECT eeo.*, eeo.id as eeoid, ep.*, ed.* 
                    FROM emergencyearlyout eeo 
                    INNER JOIN employee_profile ep ON ep.idno = eeo.idno 
                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                    WHERE eeo.idno != '$userId' 
                    AND ($whereClause)
                    ORDER BY 
                        IF(COALESCE(eeo.acknowledged, '') LIKE '%$approval%', 1, 0) ASC,
                        CASE 
                            WHEN eeo.eeo_status LIKE 'Approved%' THEN 1 
                            WHEN eeo.eeo_status LIKE 'Disapproved%' THEN 2
                            WHEN eeo.eeo_status = 'Pending' THEN 3 
                            ELSE 4 
                        END, 
                        eeo.date_applied DESC,
                        eeo.time_applied DESC";         

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
                            $status = $company['eeo_status'];
                            $acknowledge = $company['acknowledged'];
                            $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
                            $idno = $company['idno'];

                            $style = "class='primary'"; // Default style
                            
                            if (strpos($status, 'Approved') !== false) {
                                $style = "class='success'";
                            } elseif (strpos($status, 'Disapproved') !== false) {
                                $style = "class='danger'";
                            } elseif (strpos($status, 'Pending') !== false) {
                                $style = "class='warning'";
                            }
                            $statusText = $status;

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
                                echo "<td align='center'>$idno</td>";
                                echo "<td align='center'>{$company['lastname']}, {$company['firstname']}</td>";
                                echo "<td align='center'>$department</td>"; 
                                echo "<td align='left'>{$company['type_EEO']}</td>";
                                echo "<td align='center'>" . date('m/d/Y', strtotime($company['dateEEO'])) . "</td>";
                                echo "<td align='center'>" . date("g:i A", strtotime($company['timeEEO'])) . "</td>";
                                echo "<td align='left'>{$company['reason']}</td>";
                                echo "<td align='center'>" . date('m/d/Y', strtotime($company['date_applied'])) . "<br>" . date('g:i:s A', strtotime($company['time_applied'])) . "</td>";
                                echo "<td align='center'>$statusText</td>";
                                echo "<td align='left'>{$company['approvers_remarks']}</td>";
                                echo "<td align='left'>{$company['acknowledged']}</td>";
                                echo "<td align='center'>";
                                if ((strpos($status, 'Approved') !== false || strpos($status, 'Disapproved') !== false) && strpos($acknowledge, $approval) === false) {
                                    echo "<a href='?manageEEOapplication&id={$company['eeoid']}&acknowledged' class='btn btn-success btn-xs' title='Acknowledge' onclick=\"return confirm('Do you wish to acknowledge this EEO application?');\"><i class='fa fa-thumbs-up'></i></a>&nbsp;";
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
    $remarks = urldecode($_GET['supe_remarks']); // Use urldecode to handle special characters
    
?>
    <!-- Remarks Form -->
    <div class="modal-overlay">
    <div class="modal-container">
        <div class="content-panel">
            <div class="panel-heading-">
                <h4>
                    <a href="?manageEEOapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
    $sqlUpdateRemarks = "UPDATE emergencyearlyout SET supe_remarks = '$remarks' WHERE id = '$id'";
    if (mysqli_query($con, $sqlUpdateRemarks)) {
        echo "<script>alert('Remarks updated successfully.');</script>";
        echo "<script>window.location.href='?manageEEOapplication';</script>"; // Redirect after update
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