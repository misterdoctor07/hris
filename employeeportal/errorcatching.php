<?php
// Include database configuration
include('../config.php');

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    echo "<script>alert('Session expired. Please log in again.');window.location='login.php';</script>";
    exit();
}

$userId = $_SESSION['idno'];

// Fetch user details, including department and designation
$userDetailsQuery = mysqli_query($con, "SELECT department, designation, company FROM employee_details WHERE idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userDetailsQuery);
$designation = $userDetails['designation'];
$userDept = $userDetails['department'];

$attendanceLogsQuery = mysqli_query($con, "SELECT log_type, error_time FROM error_logs WHERE empid = '$userId'");

$logsMapping = [
    'loginam' => "LOG IN",
    'logoutam' => "LUNCH OUT",
    'loginpm' => "LUNCH IN",
    'logoutpm' => "LOG OUT"
];

$translatedLogs = [];
if ($attendanceLogsQuery && mysqli_num_rows($attendanceLogsQuery) > 0) {
    while ($row = mysqli_fetch_assoc($attendanceLogsQuery)) {
        $logType = $row['log_type'];
        $translatedLogType = isset($logsMapping[$logType]) ? $logsMapping[$logType] : "Unknown Log Type";

        // Add the translated log type and original data to an array
        $translatedLogs[] = [
            'log_type' => $translatedLogType,
            'error_time' => $row['error_time']
        ];
    }
}

$departments = [
    1 => "Admin",
    2 => "HR",
    3 => "IT",
    9 => "Home Health",
    11 => "HH - Medicare",
    12 => "HP - Medicare",
    13 => "HP - Managed Care",
    14 => "HH - Managed Care",
    15 => "Data Review",
    16 => "PFCPD",    
    19 => "Anaheim Billers",
    20 => "TQA",
    22 => "Hospice",
    23 => "Miracle",
    24 => "HH Digos",
    25 => "Hospice Digos",
    36 => "CARE COORDANITOR",
    37 => "PAYMENT POSTING",
    38 => "INTAKE & SUP",
    39 => "DPD & HR",
    40 => "VITUAL ASSISTANT",
    42 => "Newind AM",
    43 => "Newind GY"
];

if ($designation == 97||$designation == 77||$designation == 93) {
    // Fetch records from error_logs joined with employee_profile
    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;

    // Safeguard against SQL injection
    $fromDate = mysqli_real_escape_string($con, $fromDate);
    $toDate = mysqli_real_escape_string($con, $toDate);

    $sql = "SELECT error_logs.empid, 
                   CONCAT(employee_profile.firstname, ' ', employee_profile.lastname) AS fullname, 
                   employee_details.company, 
                   employee_details.department, 
                   employee_details.startshift,
                   employee_details.endshift,
                   employee_details.designation, 
                   error_logs.error_message, 
                   error_logs.log_type, 
                   error_logs.error_time, 
                   error_logs.ip_address
            FROM error_logs 
            LEFT JOIN employee_profile ON error_logs.empid = employee_profile.idno 
            LEFT JOIN employee_details ON employee_profile.idno = employee_details.idno 
            WHERE (DATE(error_logs.error_time) BETWEEN '$fromDate' AND '$toDate' 
                   OR '$fromDate' = '' OR '$toDate' = '') 
            ORDER BY error_logs.error_time DESC";

    $result = mysqli_query($con, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Logs</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #444;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        thead {
            background-color: #007BFF;
            color: #fff;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            text-transform: uppercase;
            font-size: 14px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .no-records {
            text-align: center;
            font-style: italic;
            color: #888;
        }

        .download-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="panel-heading">
        <h4>
            <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
            <i class="fa fa-eye"></i> ERROR CATCHING
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
            <div style="float:right; margin-top: 25px;">
                <form>
                    <button type="button" onclick="tablesToExcel('HRIS_Monitoring_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                </form>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="col-md-2" style="margin-bottom: 10px; margin-left: -15px">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
        </div>
    </div>
  
    <!-- Table -->
    <table id="errorLogsTable">
        <thead>
            <tr>
                <th style="text-align: center;">Employee ID</th>
                <th style="text-align: center;">Full Name</th>
                <th style="text-align: center;">Shift</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">HRIS Message</th>
                <th style="text-align: center;">Log Type</th>
                <th style="text-align: center;">Ip Address</th>
                <th style="text-align: center;">Message Time</th>
            </tr>
        </thead>
        <tbody>
        <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $departmentId = $row['department'] ?? null; 
        $departmentName = $departments[$departmentId] ?? "Unknown Department";

        // Translate the log type dynamically for each row
        $logType = $row['log_type'] ?? null; // Fetch the log_type from the row
        $translatedLogType = $logsMapping[$logType] ?? "Unknown Log Type"; // Map log_type to a user-friendly name

        echo "<tr>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['empid']) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['fullname']) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars(date("h:i A", strtotime($row['startshift'])) . " - " . date("h:i A", strtotime($row['endshift']))) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['company']) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($departmentName) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['error_message']) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($translatedLogType) . "</td>"; // Use the dynamically calculated log type
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['ip_address']) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['error_time']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8' class='no-records'>No records found</td></tr>";
}
?>

        </tbody>
    </table>

    <script>
        function filterByDate() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;

            if (fromDate && toDate) {
                window.location.href = `?errorcatching&fromDate=${fromDate}&toDate=${toDate}`;
            } else {
                alert('Please select both "From" and "To" dates.');
            }
        }

        function resetFilter() {
            window.location.href = '?errorcatching';
        }

        function tablesToExcel() {
            const dataType = 'application/vnd.ms-excel';
            let tableHTML = '';
            const filename = 'HRIS_Monitoring_Report.xls';

            const tables = document.querySelectorAll('table');

            tables.forEach((table, index) => {
                tableHTML += `<h3>Table ${index + 1}</h3>`;
                const clonedTable = table.cloneNode(true);
                clonedTable.style.borderCollapse = 'collapse';
                clonedTable.querySelectorAll('th, td').forEach(cell => {
                    cell.style.border = '1px solid black';
                    cell.style.padding = '5px';
                });
                tableHTML += clonedTable.outerHTML + '<br>';
            });

            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            const blob = new Blob([tableHTML], { type: dataType });
            const url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.download = filename;
            downloadLink.click();

            document.body.removeChild(downloadLink);
        }

        function filterTable(input) {
            const searchValue = input.value.toLowerCase();
            const table = document.getElementById('errorLogsTable');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells)
                    .map(cell => cell.textContent.toLowerCase())
                    .join(' ');

                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
