<?php
require_once '../dompdf/autoload.inc.php';
require_once '../db_connect.php'; // your PDO connection

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultPaperSize', 'legal');
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Get payroll period & company from URL
$payrollId = $_GET['period'] ?? '';
$companyId = $_GET['company'] ?? '';

// Create zip file
$zip = new ZipArchive();
$zipFile = __DIR__ . "/all_payslips_" . date('Ymd_His') . ".zip";

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Cannot create ZIP file");
}

// Fetch all employees in that company
$stmt = $pdo->prepare("SELECT id, firstname, lastname FROM employees WHERE company_id = ?");
$stmt->execute([$companyId]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($employees as $emp) {
    $employeeId = $emp['id'];

    ob_start();
    include 'export_payslip.php'; // must use $employeeId
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('Legal', 'landscape');
    $dompdf->render();

    $pdfData = $dompdf->output();
    $filename = "Payslip_{$emp['lastname']}_{$emp['firstname']}.pdf";
    $zip->addFromString($filename, $pdfData);
}

$zip->close();

// Download the zip
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
header('Content-Length: ' . filesize($zipFile));
readfile($zipFile);

// Optional: remove file after download
unlink($zipFile);
exit;
