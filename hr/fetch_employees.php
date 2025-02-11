<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<?php
include '../config.php'; // Include your database connection file

if (isset($_POST['jobTitleId'])) {
    $jobTitleId = mysqli_real_escape_string($con, $_POST['jobTitleId']);
    
    // Query to fetch employees based on the selected job title
    $query = "SELECT ed.idno, ep.firstname, ep.lastname, ed.designation
              FROM employee_profile ep 
              INNER JOIN employee_details ed ON ed.idno = ep.idno  
              INNER JOIN jobtitle jt ON jt.id = ed.designation
              WHERE ed.designation = '$jobTitleId'
              AND ed.status NOT LIKE 'RESIGNED%'
              ORDER BY ep.lastname ASC";
    
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Loop through the results and generate valid <option> elements
        while ($employee = mysqli_fetch_assoc($result)) {
            $fullName = htmlspecialchars($employee['lastname'] . ', ' . $employee['firstname']);
            $idno = htmlspecialchars($employee['idno']);
            echo "<option value='$idno'>$fullName</option>";
        }
    } else {
        // No results found
        echo "<option value=''>No Approving Officers Found</option>";
    }
} else {
    // Handle cases where jobTitleId is not provided
    echo "<option value=''>Invalid request</option>";
}
?>

