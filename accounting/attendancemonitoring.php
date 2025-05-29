<?php
// Define the isNightShift function
function isNightShift($startShift, $endShift) {
    // Check if the shift starts at midnight and ends in the morning
    return ($startShift == '00:00:00' && $endShift == '09:00:00') || 
           ($startShift == '23:00:00' && $endShift == '08:00:00');
}

// Retrieve and sanitize GET parameters
$comp = mysqli_real_escape_string($con, $_GET['company']);
$startdate = mysqli_real_escape_string($con, $_GET['startdate']);
$enddate = mysqli_real_escape_string($con, $_GET['enddate']);
// If you need department filtering, you should get it from GET/POST
$dept = isset($_GET['dept']) ? mysqli_real_escape_string($con, $_GET['dept']) : '';
?>


<!-- Rest of your HTML and PHP code -->
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?monitorattendance"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-user"></i> EMPLOYEE LIST (<?= $comp ?>)
                <div style="float:right; margin-bottom: 20px;">
                    <form>
                        <button type="button" onclick="tablesToExcel('Attendance_monitoring')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
                </div>
            </h4>
        </div>
        <div class="panel-body" id="printThis">
            <b>Company: <?= $comp; ?><br />
            Date Range: <?= date('m/d/Y', strtotime($startdate)); ?> - <?= date('m/d/Y', strtotime($enddate)); ?></b>
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="3%" rowspan="2" style="vertical-align:middle;">No.</th>
                        <th rowspan="2" style="vertical-align:middle;">Emp ID</th>
                        <th rowspan="2" style="vertical-align:middle;">Employee Name</th>
                        <th rowspan="2" style="vertical-align:middle;">Status</th>
                        <th rowspan="2" style="vertical-align:middle;">Department</th>
                        <th rowspan="2" style="vertical-align:middle;">Shift</th>
                        <th rowspan="2" style="vertical-align:middle;">Work Area</th>
                        <th rowspan="2" style="vertical-align:middle;">Date</th>
                        <th colspan="2" align="center">Shift 1</th>
                        <th colspan="2" align="center">Shift 2</th>
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
                    // Modified SQL query with proper department handling
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, i.department AS department_name 
                                                      FROM employee_profile ep 
                                                      LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                                      LEFT JOIN department i ON i.id = ed.department
                                                      WHERE ed.status NOT LIKE '%RESIGNED%' 
                                                      AND company = '$comp' 
                                                      ".($dept ? " AND ed.department = '$dept'" : "")."
                                                      ORDER BY ep.lastname ASC");

                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            $shiftStart = $company['startshift'];
                            $shiftEnd = $company['endshift'];

                            // Check if the shift is a night shift
                            if (isNightShift($shiftStart, $shiftEnd)) {
                                // For night shifts, adjust the logindate to the previous day
                                $adjustedStartDate = date('Y-m-d', strtotime($startdate . ' -1 day'));
                                $adjustedEndDate = date('Y-m-d', strtotime($enddate . ' -1 day'));
                            } else {
                                // For regular shifts, use the original dates
                                $adjustedStartDate = $startdate;
                                $adjustedEndDate = $enddate;
                            }

                            // Query attendance with adjusted dates for night shifts
                            $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance 
                                                                WHERE logindate BETWEEN '$adjustedStartDate' AND '$adjustedEndDate' 
                                                                AND idno = '$company[idno]' 
                                                                ORDER BY logindate ASC");

                            // Process attendance data
                            $login1 = "";
                            $logout1 = "";
                            $login2 = "";
                            $logout2 = "";
                            $datearray = "";

                            if (mysqli_num_rows($sqlAttendance) > 0) {
                                while ($attend = mysqli_fetch_array($sqlAttendance)) {
                                    // Adjust the displayed date for night shifts
                                    $displayDate = date('m/d/Y', strtotime($attend['logindate']));
                                    if (isNightShift($shiftStart, $shiftEnd)) {
                                        $displayDate = date('m/d/Y', strtotime($attend['logindate'] . ' +1 day'));
                                    }

                                    $datearray .= $displayDate . "<br>";
                                    $login1 .= $attend['loginam'] . "<br>";
                                    $logout1 .= $attend['logoutam'] . "<br>";
                                    $login2 .= $attend['loginpm'] . "<br>";
                                    $logout2 .= $attend['logoutpm'] . "<br>";
                                }
                            }

                            // Display the data in the table
                            echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                            echo "<td>$company[status]</td>";
                            echo "<td>$company[department_name]</td>";
                            echo "<td>$company[startshift], $company[endshift]</td>";
                            echo "<td align='center'>$company[location]</td>";
                            echo "<td align='center'>$datearray</td>";
                            echo "<td align='center'>$login1</td>";
                            echo "<td align='center'>$logout1</td>";
                            echo "<td align='center'>$login2</td>";
                            echo "<td align='center'>$logout2</td>";
                            echo "</tr>";
                            $x++;
                        }
                    } else {
                        echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
</script>