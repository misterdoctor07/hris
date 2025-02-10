<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PhpSpreadsheet autoload file (adjust path if necessary)
require '/PhpSpreadsheet/IOFactory.php'; // Example path

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// The rest of your export_excel.php script


// Include database configuration
include('../config.php');

// Start the session
session_start();

// Ensure session is valid
if (!isset($_SESSION['idno'])) {
    echo "<script>alert('Session expired. Please log in again.');window.location='login.php';</script>";
    exit();
}

$designation = $_SESSION['designation'] ?? null;

// Check if the user has the appropriate designation to export data
if ($designation == 97) {
    // SQL query to fetch the error logs data
    $sql = "SELECT 
            error_logs.empid, 
            CONCAT(employee_profile.firstname, ' ', employee_profile.lastname) AS fullname, 
            employee_details.company, 
            employee_details.department, 
            employee_details.designation, 
            error_logs.error_message, 
            error_logs.log_type, 
            error_logs.error_time 
        FROM error_logs 
        LEFT JOIN employee_profile ON error_logs.empid = employee_profile.idno 
        LEFT JOIN employee_details ON employee_profile.idno = employee_details.idno 
        ORDER BY error_logs.error_time DESC";

    $result = mysqli_query($con, $sql);

    // Debug: Check if query was successful and has data
    if (!$result) {
        echo "Error in query execution: " . mysqli_error($con);
        exit();
    }

    if (mysqli_num_rows($result) == 0) {
        echo "No records found.";
        exit();
    }

    // Create a new spreadsheet instance
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header row
    $headers = ['Employee ID', 'Full Name', 'Company', 'Department', 'Error Message', 'Log Type', 'Error Time'];
    $sheet->fromArray($headers, null, 'A1');

    // Department mappings
    $departments = [
        1 => "Admin", 2 => "HR", 3 => "IT", 9 => "Home Health", 11 => "HH - Medicare",
        12 => "HP - Medicare", 13 => "HP - Managed Care", 14 => "HH - Managed Care",
        15 => "Data Review", 16 => "PFCPD", 19 => "Anaheim Billers", 20 => "TQA",
        22 => "Hospice", 23 => "Miracle", 24 => "HH Digos", 25 => "Hospice Digos",
        36 => "CARE COORDANITOR", 37 => "PAYMENT POSTING", 38 => "INTAKE & SUP",
        39 => "DPD & HR", 40 => "VITUAL ASSISTANT", 42 => "Newind AM", 43 => "Newind GY"
    ];

    // Fetch and write data rows
    $rowIndex = 2; // Starting from row 2 for the data
    while ($row = mysqli_fetch_assoc($result)) {
        $departmentId = $row['department'] ?? null;
        $departmentName = $departments[$departmentId] ?? "Unknown Department";

        // Write the data into spreadsheet
        $sheet->fromArray([
            $row['empid'],
            $row['fullname'],
            $row['company'],
            $departmentName,
            $row['error_message'],
            $row['log_type'],
            $row['error_time']
        ], null, "A$rowIndex");

        $rowIndex++;
    }

    // Clean output buffer to prevent issues with the file download
    ob_clean();
    flush();

    // Set headers for Excel file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="error_logs.xlsx"');

    // Write the file to output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
} 
?>
