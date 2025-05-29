<?php
// Include database configuration
ini_set('memory_limit', '256M');
include('../config.php');

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    echo "<script>alert('Session expired. Please log in again.');window.location='/index.php';</script>";
    exit();
}

$userId = $_SESSION['idno'];

// Initialize all variables that might be used later
$attendanceLogsQuery = false;
$translatedLogs = [];
$result = false;
$fromDate = '';
$toDate = '';
$selectedLogType = '';

// Log type options
$logsMapping = [
    'loginam' => "LOG IN",
    'logoutam' => "LUNCH OUT",
    'loginpm' => "LUNCH IN",
    'logoutpm' => "LOG OUT",
    '' => "All Log Types" // Default option
];

// Fetch user details
$userDetailsQuery = mysqli_query($con, "SELECT department, designation, company FROM employee_details WHERE idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userDetailsQuery);
$designation = $userDetails['designation'] ?? null;
$userDept = $userDetails['department'] ?? null;

// Get attendance logs for the user (initialize this for all users)
$attendanceLogsQuery = mysqli_query($con, "SELECT log_type, error_time FROM error_logs WHERE empid = '$userId'");

// Process attendance logs
$translatedLogs = [];
if ($attendanceLogsQuery && mysqli_num_rows($attendanceLogsQuery) > 0) {
    while ($row = mysqli_fetch_assoc($attendanceLogsQuery)) {
        $logType = $row['log_type'];
        $translatedLogType = $logsMapping[$logType] ?? "Unknown Log Type";
        $translatedLogs[] = [
            'log_type' => $translatedLogType,
            'error_time' => $row['error_time']
        ];
    }
}

function translateLogType($logType, $logsMapping) {
    return $logsMapping[$logType] ?? "Unknown Log Type";
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

// Only proceed with main query for specific designations and if dates are selected
$showData = false;
if (in_array($designation, [97, 77, 93, 33, 105])) {
    if (isset($_GET['fromDate']) && isset($_GET['toDate'])) {
        $showData = true;
        $fromDate = mysqli_real_escape_string($con, $_GET['fromDate']);
        $toDate = mysqli_real_escape_string($con, $_GET['toDate']);
        $selectedLogType = isset($_GET['logType']) ? mysqli_real_escape_string($con, $_GET['logType']) : '';

        // Build the WHERE clause
        $whereClause = "DATE(error_logs.error_time) BETWEEN ? AND ?";
        $params = [$fromDate, $toDate];
        $paramTypes = 'ss';
        
        if (!empty($selectedLogType)) {
            $whereClause .= " AND error_logs.log_type = ?";
            $params[] = $selectedLogType;
            $paramTypes .= 's';
        }

        // Main query without pagination
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
                       error_logs.ip_address,
                       error_logs.device_type,
                       error_logs.shift
                FROM error_logs 
                LEFT JOIN employee_profile ON error_logs.empid = employee_profile.idno 
                LEFT JOIN employee_details ON employee_profile.idno = employee_details.idno 
                WHERE $whereClause
                ORDER BY error_logs.error_time DESC";
                
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }
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
        
        .date-selection {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 50px auto;
            text-align: center;
        }
        
        .date-selection h3 {
            margin-top: 0;
            color: #007BFF;
        }
        
        .date-selection input, .date-selection select {
            padding: 8px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        
        .date-selection button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px;
        }
        
        .date-selection button:hover {
            background: #0056b3;
        }
        
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-group label {
            font-weight: bold;
            min-width: 80px;
        }
    </style>
</head>
<body>
    <div class="panel-heading">
        <h4>
            <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
            <i class="fa fa-eye"></i> ERROR CATCHING
        </h4>
        
        <?php if (!$showData): ?>
        <!-- Date Selection Form -->
        <div class="date-selection">
            <h3>Please select filters to view error logs</h3>
            <form method="get" action="">
                <input type="hidden" name="errorcatching" value="1">
                <div class="filter-group">
                    <label for="fromDate">From:</label>
                    <input type="date" id="fromDate" name="fromDate" required>
                </div>
                <div class="filter-group">
                    <label for="toDate">To:</label>
                    <input type="date" id="toDate" name="toDate" required>
                </div>
                <div class="filter-group">
                    <label for="logType">Log Type:</label>
                    <select id="logType" name="logType">
                        <?php foreach ($logsMapping as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">View Logs</button>
            </form>
        </div>
        <?php else: ?>
        <!-- Filter Container -->
        <div class="filter-container">
            <div class="filter-group">
                <label for="fromDate">From:</label>
                <input type="date" id="fromDate" class="form-control" value="<?php echo $fromDate; ?>">
            </div>
            <div class="filter-group">
                <label for="toDate">To:</label>
                <input type="date" id="toDate" class="form-control" value="<?php echo $toDate; ?>">
            </div>
            <div class="filter-group">
                <label for="logType">Log Type:</label>
                <select id="logType" class="form-control">
                    <?php foreach ($logsMapping as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $selectedLogType == $value ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <button type="button" onclick="filterData()" class="btn btn-primary">Filter</button>
                <button type="button" onclick="resetFilter()" class="btn btn-default">Reset</button>
            </div>
            <div style="margin-left: auto;">
                <button type="button" onclick="tablesToExcel('HRIS_Monitoring_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div style="margin-bottom: 10px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterTable(this)" style="width: 300px; padding: 8px;">
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
                    <th style="text-align: center;">Device Type</th>
                    <th style="text-align: center;">Message Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $departmentName = $departments[$row['department']] ?? "Unknown Department";
                        $translatedLogType = translateLogType($row['log_type'], $logsMapping);

                        echo "<tr>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['empid'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['fullname'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['shift'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['company'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($departmentName) . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['error_message'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($translatedLogType) . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['ip_address'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['device_type'] ?? '') . "</td>";
                        echo "<td style='text-align: center;'>" . htmlspecialchars($row['error_time'] ?? '') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='no-records'>No records found for the selected filters</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <script>
        function filterData() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const logType = document.getElementById('logType').value;

            if (fromDate && toDate) {
                let url = `?errorcatching&fromDate=${fromDate}&toDate=${toDate}`;
                if (logType) {
                    url += `&logType=${logType}`;
                }
                window.location.href = url;
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
        
        // Set default dates (today and 7 days ago)
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(today.getDate() - 7);
            
            const formatDate = (date) => {
                return date.toISOString().split('T')[0];
            };
            
            // Only set defaults if we're on the initial form (not showing data)
            if (!<?php echo $showData ? 'true' : 'false'; ?>) {
                document.getElementById('fromDate').value = formatDate(sevenDaysAgo);
                document.getElementById('toDate').value = formatDate(today);
            }
        });
    </script>
</body>
</html>