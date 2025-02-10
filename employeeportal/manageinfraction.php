<?php
include('../config.php'); // Include your database connection file

// Get the logged-in user ID and check if the session exists
if (!isset($_SESSION['idno'])) {
    echo "<script>alert('Session expired. Please log in again.');window.location='login.php';</script>";
    exit();
}

$userId = $_SESSION['idno'];

// Fetch user details, including department and designation
$userDetailsQuery = mysqli_query($con, "SELECT department, designation, company FROM employee_details WHERE idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userDetailsQuery);
$userDetailsQuery = mysqli_query($con, "SELECT d.department FROM department d LEFT JOIN employee_details ed ON ed.department=d.id WHERE ed.idno= d.id");
$dept = mysqli_fetch_array($userDetailsQuery);

if (!$userDetails) {
    echo "<script>alert('User details not found.');window.location='login.php';</script>";
    exit();
}

$userDept = $userDetails['department'];
$designation = $userDetails['designation'];
$userCompany = $userDetails['company'];

//Department mapping
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
    43 => "Newiwnd GY",  // Add more department mappings here
];

// Ensure the logged-in user is an assessor (designation 8)
if ($designation == 8|| $designation == 59 || $designation == 65 || $designation == 94) {  //Assessor || TL || TM || OIC
    // Assessor: View only own department
    $query = "SELECT 
                ep.*, 
                i.id, i.dateserved, i.dateissued, i.typecat, i.typeofoffense, 
                i.dateofincident, i.typeofmemo, i.points, i.memonumber, 
                i.dateofsuspension, i.status,
                ed.department, ed.company 
              FROM employee_profile ep
              INNER JOIN infraction i ON i.idno = ep.idno
              INNER JOIN employee_details ed ON ed.idno = ep.idno
              WHERE ed.department = '$userDept'
              ORDER BY 
                i.dateissued ASC";
} elseif ($designation == 50 || $designation == 89) { //OS || OM
    // View all departments in the same company
    $query = "SELECT 
                ep.*, 
                i.id, i.dateserved, i.dateissued, i.typecat, i.typeofoffense, 
                i.dateofincident, i.typeofmemo, i.points, i.memonumber, 
                i.dateofsuspension, i.status,
                ed.department, ed.company 
              FROM employee_profile ep
              INNER JOIN infraction i ON i.idno = ep.idno
              INNER JOIN employee_details ed ON ed.idno = ep.idno
              WHERE ed.company = '$userCompany'
              ORDER BY 
                i.dateissued ASC";
} else if($designation == 102 || $designation == 3 || $designation == 88|| $designation == 114||$designation == 92){ // Accounting Assistant || Accounting Specialist || Accounting Associate || Admin Executive || Senor Admin Auditor
    $query = "SELECT 
    ep.*, 
    i.id, i.dateserved, i.dateissued, i.typecat, i.typeofoffense, 
    i.dateofincident, i.typeofmemo, i.points, i.memonumber, 
    i.dateofsuspension, i.status,
    ed.department, ed.company 
  FROM 
    employee_profile ep
  INNER JOIN 
    infraction i ON i.idno = ep.idno
  INNER JOIN 
    employee_details ed ON ed.idno = ep.idno

  ORDER BY 
    i.dateissued ASC";
} 

else {
    echo "<script>alert('Access Denied!');window.location='?main';</script>";
    exit();
}

$sqlEmployee = mysqli_query($con, $query);

?>
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
                <i class="fa fa-user"></i> INFRACTION LIST
                <div style="float:right; margin-bottom: 20px;">
                    <form>
                        <button type="button" onclick="tablesToExcel('Infraction_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
                </div>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Emp ID</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Company</th>
                        <th>Date Issued</th>
                        <th>Date Served</th>
                        <th>Type of Memo</th>
                        <th>Type of Category</th>
                        <th>Type of Offense</th>
                        <th>Points</th>
                        <th>Memo No.</th>
                        <th>Date of Incident</th>
                        <th>Suspension Dates</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            $status = $company['status'];
                            $dateissued = date('M-d-Y', strtotime($company['dateissued']));
                            $dateserved = date('M-d-Y', strtotime($company['dateserved']));
                            $dateofincident = date('M-d-Y', strtotime($company['dateofincident']));
                            $style = match ($status) {
                                "Void" => "class='danger'",
                                "pending" => "class='warning'",
                                default => "class='success'",
                            };

                            // Translate department ID to name
                            $departmentName = $departments[$company['department']] ?? "Unknown Department";

                            echo "<tr $style>";
                            echo "<td>$x.</td>";
                            echo "<td>{$company['idno']}</td>";
                            echo "<td>{$company['lastname']}, {$company['firstname']} {$company['middlename']} {$company['suffix']}</td>";
                            echo "<td> $departmentName</td>";
                            echo "<td>{$company['company']}</td>";
                            echo "<td>$dateissued</td>";
                            echo "<td>$dateserved</td>";
                            echo "<td>{$company['typeofmemo']}</td>";
                            echo "<td>{$company['typecat']}</td>";
                            echo "<td>{$company['typeofoffense']}</td>";
                            echo "<td>{$company['points']}</td>";
                            echo "<td align='center'>{$company['memonumber']}</td>";
                            echo "<td>$dateofincident</td>";
                            echo "<td align='center'>{$company['dateofsuspension']}</td>";
                            echo "<td align='center'>$status</td>";
                            echo "</tr>";
                            $x++;
                        }
                    } else {
                        echo "<tr><td colspan='15' align='center'>No record found!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
// Action Handlers (Delete, Undo, Serve)
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $datenow = date('Y-m-d H:i:s');
    $sqlDelete = mysqli_query($con, "UPDATE infraction SET `status`='Void', updatedby='$fullname', updateddatetime='$datenow' WHERE id='$id'");
    echo $sqlDelete ? "<script>alert('Infraction successfully voided!');window.location='?manageinfraction';</script>" : "<script>alert('Unable to void infraction!');window.location='?manageinfraction';</script>";
}

if (isset($_GET['undo'])) {
    $id = $_GET['id'];
    $datenow = date('Y-m-d H:i:s');
    $sqlUndo = mysqli_query($con, "UPDATE infraction SET `status`='pending', updatedby='$fullname', updateddatetime='$datenow' WHERE id='$id'");
    echo $sqlUndo ? "<script>alert('Infraction successfully restored!');window.location='?manageinfraction';</script>" : "<script>alert('Unable to restore infraction!');window.location='?manageinfraction';</script>";
}

if (isset($_GET['serve'])) {
    $id = $_GET['id'];
    $datenow = date('Y-m-d H:i:s');
    $sqlServe = mysqli_query($con, "UPDATE infraction SET `status`='Served', updatedby='$fullname', updateddatetime='$datenow' WHERE id='$id'");
    echo $sqlServe ? "<script>alert('Infraction successfully served!');window.location='?manageinfraction';</script>" : "<script>alert('Unable to serve infraction!');window.location='?manageinfraction';</script>";
}
?>

<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
function tablesToExcel() {
    const dataType = 'application/vnd.ms-excel';
    let tableHTML = '';

    // Define the filename for the exported file
    const filename = 'Infraction_Report.xls';

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
</script>