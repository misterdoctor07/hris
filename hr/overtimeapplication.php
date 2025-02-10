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
</style>

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
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-clock-o"></i> OVERTIME APPLICATION
            </h4>
        </div>

        <!-- Company Tabs Navigation -->
        <ul class="nav nav-tabs">
            <?php
            $active = 'active'; // Set the first tab as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = $company['company'];
                
                // Fetch count of pending overtime applications for the company
                $sqlCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM overtime_application ot
                    INNER JOIN employee_details ed ON ot.idno = ed.idno
                    WHERE ed.company = '$companyCode' 
                    AND ot.app_status NOT IN ('Pending', 'Cancelled', 'Disapproved')
                    AND ot.remarks != 'POSTED'");
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
            WHERE ed.company = '$companyCode' ORDER BY d.department");

            echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
            $deptActive = 'active';
            
            while ($department = mysqli_fetch_array($sqlDepartments)) {
                $departmentName = $department['department'];
                
                // Fetch count of pending leave applications for the department
                $sqlDeptCount = mysqli_query($con, "SELECT COUNT(*) AS total FROM overtime_application ot
                    INNER JOIN employee_details ed ON ot.idno = ed.idno
                    INNER JOIN department d ON d.id = ed.department
                    WHERE ed.company = '$companyCode' 
                    AND d.department = '$departmentName'
                    AND ot.app_status NOT IN ('Pending', 'Cancelled', 'Disapproved') 
                    AND ot.remarks != 'POSTED'");
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

        // Fetch employees based on company
        $sqlEmployee = mysqli_query($con, "SELECT ot.*, ot.id as otid, ep.*, ed.*, d.department 
            FROM overtime_application ot 
            INNER JOIN employee_profile ep ON ep.idno = ot.idno 
            INNER JOIN employee_details ed ON ed.idno = ep.idno 
            INNER JOIN department d ON d.id = ed.department
            WHERE ed.company = '$companyCode' AND d.department = '$departmentName'
            ORDER BY 
                CASE 
                    WHEN ot.app_status NOT IN ('Pending', 'Cancelled', 'Disapproved') AND ot.remarks != 'POSTED' THEN 1 
                    ELSE 2 
                END, 
            ot.datearray DESC");

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

            if (!$sqlEmployee) {
                // Output the SQL error message
                echo "Error: " . mysqli_error($con);
                exit; // Stop the script if the query failed
            }

            if (mysqli_num_rows($sqlEmployee) > 0) {
                while ($emp = mysqli_fetch_array($sqlEmployee)) {
                    ?>
                    <tr>
                        <td align='center'><?=$x++;?>.</td>
                        <td align='center'><?=$emp['idno'];?></td>
                        <td align='center'><?=$emp['lastname']. ','.$emp['firstname'];?></td>
                        <td align='center'><?=date('m/d/Y', strtotime($emp['otdate']));?></td>
                        <td align='center'><?=$emp['ottime'];?></td>
                        <td align='left'><?=$emp['reasons'];?></td>
                        <td align='center'><?=$emp['datearray']."".$emp['timearray'];?></td>
                        <td align='center'><?=$emp['app_status'];?></td>
                        <td align='left'><?=$emp['remarks'];?></td>
                        <td align="center">
                            <?php if ($emp['remarks'] != 'POSTED'): ?>
                                <?php if ($emp['app_status'] != 'Disapproved' && $emp['app_status'] != 'Cancelled' && $emp['app_status'] != 'Pending'): ?>
                                    <a href="?overtimeapplication&post&id=<?=$emp['otid'];?>&remarks=<?=$emp['remarks'];?>" 
                                       class="btn btn-success btn-xs confirm-post" 
                                       title="Post">
                                        <i class='fa fa-upload'></i>
                                    </a>
                                <?php endif; ?>
                                <a href="?overtimeapplication&addremarks&id=<?=$emp['otid'];?>&remarks=<?=$emp['remarks'];?>" 
                                   class="btn btn-primary btn-xs"
                                   title="Remarks">
                                    <i class='fa fa-edit'></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10' align='center'>No records found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
        echo "</div>"; // End of tab content
        $deptActive = ''; // Remove active class from subsequent department contents
    }
    echo "</div>"; //End of department tabs content
    echo "</div>"; //End of company tabs content
    $active = ''; //Remove active class from subsequent company contents
}
    ?>
</div>
</div>
</div>


<?php 
if (isset($_GET['post'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    
    // Update remarks in overtime_application to "POSTED"
    $sqlUpdateOvertime = mysqli_query($con, "UPDATE overtime_application SET remarks='POSTED' WHERE id='$id'");

    if ($sqlUpdateOvertime) {
        // Retrieve the idno and otdate values
        $sqlRetrieve = mysqli_query($con, "SELECT idno, otdate FROM overtime_application WHERE id='$id'");
        
        if ($sqlRetrieve && mysqli_num_rows($sqlRetrieve) > 0) {
            $overtimeData = mysqli_fetch_array($sqlRetrieve);
            $idno = $overtimeData['idno'];
            $otdate = $overtimeData['otdate'];

            // Check if the attendance record exists for the ot date
            $sqlCheckAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$idno' AND logindate='$otdate'");
            
            if (mysqli_num_rows($sqlCheckAttendance) == 0) {
                // Insert a new attendance row if the date doesn't exist
                $sqlInsertAttendance = mysqli_query($con, 
                    "INSERT INTO attendance (idno, logindate, loginam, logoutam, loginpm, logoutpm, remarks) 
                    VALUES ('$idno', '$otdate', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 'OT')");
                
                if (!$sqlInsertAttendance) {
                    echo "<script>alert('Error inserting new attendance record for overtime date: $otdate');</script>";
                }
            } else {
                // Update the existing attendance row with "OT" in the remarks column
                $sqlUpdateAttendance = mysqli_query($con, "UPDATE attendance SET remarks = 'Code OT' WHERE idno='$idno' AND logindate='$otdate'");
                
                if (!$sqlUpdateAttendance) {
                    echo "<script>alert('Error updating attendance for overtime date: $otdate');</script>";
                }
            }
            
            echo "<script>alert('Overtime application successfully posted!'); window.location='?overtimeapplication';</script>";
        }
    } else {
        echo "<script>alert('Unable to post overtime application!'); window.location='?overtimeapplication';</script>";
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
                    <a href="?overtimeapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
        echo "<script>window.location.href='?manageleaveapplication';</script>"; // Redirect after update
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