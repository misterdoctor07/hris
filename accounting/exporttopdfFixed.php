<?php
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Enable remote resources (if you're using external images like your logo)
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultMediaType', 'print');
$options->set('defaultPaperSize', 'legal');
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Load your HTML into a variable (you can output your payslip HTML as a string here)
ob_start();
include 'export_payslip.php'; // This should be the file with your HTML (with PHP variables)
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('Legal', 'landscape');
$dompdf->render();

// Output to browser or save to file
$dompdf->stream("export_payslip.pdf", ["Attachment" => false]); // false = open in browser, true = download
?>